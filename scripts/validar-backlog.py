# /// script
# requires-python = ">=3.11"
# dependencies = ["ruamel.yaml>=0.18"]
# ///
"""Validate the distributed planning backlog, or locate one Story. Never writes."""

import argparse
from collections import Counter
import hashlib
import json
from pathlib import Path
import re
import sys
from urllib.parse import unquote, urlsplit

from ruamel.yaml import YAML
from ruamel.yaml.error import YAMLError

STORY = re.compile(r"^### Story (\d+)\.(\d+): (.+)$", re.M)
AC = re.compile(r"^\*\*AC-(\d{2})", re.M)
RF = r"RF-[A-Z]+-\d{3}"


def visible(text):
    """Blank fenced examples but retain line positions."""
    result, fence = [], None
    for line in text.splitlines():
        match = re.match(r"^\s{0,3}(`{3,}|~{3,})", line)
        if match:
            marker = match[1]
            if fence is None:
                fence = marker
            elif marker[0] == fence[0] and len(marker) >= len(fence):
                fence = None
            result.append("")
        else:
            result.append(line if fence is None else "")
    if fence:
        raise ValueError("unclosed Markdown fence")
    return "\n".join(result)


def frontmatter(text):
    if not text.startswith("---\n"):
        return {}
    _, header, _ = text.split("---\n", 2)
    value = YAML(typ="safe").load(header)
    if not isinstance(value, dict):
        raise ValueError("frontmatter is not a YAML mapping")
    return value


def stories(text, path):
    text = visible(text)
    found = []
    for match in STORY.finditer(text):
        tail = text[match.end():]
        boundary = re.search(r"^#{1,3} |^<a id=\"story-", tail, re.M)
        body = tail[:boundary.start()] if boundary else tail
        acs = list(AC.finditer(body))
        found.append({
            "id": f"{match[1]}.{match[2]}", "epic": int(match[1]),
            "title": match[3], "path": str(path),
            "line": text[:match.start()].count("\n") + 1,
            "acs": [int(ac[1]) for ac in acs], "body": body,
            "gwt": all(all(token in body[ac.start():acs[i+1].start() if i+1 < len(acs) else len(body)]
                           for token in ("**Given**", "**When**", "**Then**"))
                       for i, ac in enumerate(acs)),
        })
    return found


def story_path(planning, number):
    return planning / "epics" / f"epic-{number:02d}.md"


def locate(root, sid):
    if not re.fullmatch(r"[1-9]\d*\.[1-9]\d*", sid):
        raise ValueError("Story ID must be N.M")
    planning = root / "_bmad-output/planning-artifacts"
    index = planning / "epics.md"
    state = frontmatter(index.read_text(encoding="utf-8-sig"))
    if state.get("layout") != "index-plus-epic-files":
        raise ValueError("index does not declare structure B")
    path = story_path(planning, int(sid.split(".")[0]))
    matches = [s for s in stories(path.read_text(encoding="utf-8-sig"), path) if s["id"] == sid]
    if len(matches) != 1:
        raise ValueError(f"expected one Story {sid}, found {len(matches)}")
    result = matches[0]
    result["metadata"] = result.pop("body").split("**Acceptance Criteria:**", 1)[0].strip()
    result["sources"] = [str(index), str(path)]
    result["planning_approved"] = sid in state.get("approvedStoryIds", [])
    result["implementation_authorized"] = False
    return result


def anchors(text):
    text = visible(text)
    result = set(re.findall(r'<a\s+(?:id|name)="([^"]+)"', text))
    counts = Counter()
    for heading in re.findall(r"^#{1,6}\s+(.+)$", text, re.M):
        slug = re.sub(r"[^\w\- ]", "", heading.lower()).replace(" ", "-")
        suffix = f"-{counts[slug]}" if counts[slug] else ""
        result.add(slug + suffix)
        counts[slug] += 1
    return result


def check_links(path, text, errors):
    for target in re.findall(r"\[[^\]]*\]\(([^)]+)\)", visible(text)):
        parsed = urlsplit(target)
        if parsed.scheme or parsed.netloc:
            continue
        dest = path.parent / unquote(parsed.path) if parsed.path else path
        if not dest.is_file():
            errors.append(f"{path}: missing link {target}")
        elif parsed.fragment and unquote(parsed.fragment) not in anchors(dest.read_text(encoding="utf-8-sig")):
            errors.append(f"{path}: missing anchor {target}")


def validate(root):
    planning = root / "_bmad-output/planning-artifacts"
    index = planning / "epics.md"
    texts = {index: index.read_text(encoding="utf-8-sig")}
    state = frontmatter(texts[index])
    errors = []
    if state.get("layout") != "index-plus-epic-files":
        errors.append("index must declare layout: index-plus-epic-files")
    if STORY.search(visible(texts[index])):
        errors.append("Story definition in global index")
    macro = [int(n) for n in re.findall(r"^### Epic (\d+):", visible(texts[index]), re.M)]
    if macro != list(range(1, 16)):
        errors.append("expected 15 ordered macro Epics")
    inventory = re.findall(r"^- `(" + RF + r")`:", texts[index], re.M)
    mapping = re.findall(r"^\| (" + RF + r") \| Epic (\d+) \|", texts[index], re.M)
    prd = set(re.findall(RF, (planning / "PRD.md").read_text(encoding="utf-8-sig")))
    if len(inventory) != 236 or len(set(inventory)) != 236 or set(inventory) != prd:
        errors.append("inventory differs from 236 unique PRD RFs")
    if len(mapping) != 236 or len(dict(mapping)) != 236 or set(dict(mapping)) != set(inventory):
        errors.append("RF ownership missing or duplicated")
    if any(int(epic) not in macro for _, epic in mapping):
        errors.append("RF owner outside macro catalogue")
    owner = dict(mapping)
    pending = state.get("pendingDetailedEpics", [])
    if not isinstance(pending, list) or any(type(number) is not int or number not in macro for number in pending) or len(pending) != len(set(pending)):
        errors.append("invalid pendingDetailedEpics")
        pending = []
    approved_epics = state.get("approvedPlanningEpicDetails", [])
    if not isinstance(approved_epics, list) or any(type(number) is not int or number not in macro for number in approved_epics) or len(approved_epics) != len(set(approved_epics)):
        errors.append("invalid approvedPlanningEpicDetails")
        approved_epics = []
    if set(pending) & set(approved_epics):
        errors.append("Epic cannot be both pending and planning-approved")
    pending_review = state.get("pendingReviewEpics", [])
    if not isinstance(pending_review, list) or any(type(number) is not int or number not in macro for number in pending_review) or len(pending_review) != len(set(pending_review)):
        errors.append("invalid pendingReviewEpics")
        pending_review = []
    if set(pending_review) & (set(pending) | set(approved_epics)):
        errors.append("review-pending Epic cannot be detail-pending or planning-approved")
    coverage_pending = {}
    all_stories, per_epic = [], {}
    for path in sorted((planning / "epics").glob("*.md")):
        if not re.fullmatch(r"epic-\d{2}\.md", path.name):
            errors.append(f"unexpected detail filename: {path.name}")
            continue
        number = int(path.stem.split("-")[1])
        text = path.read_text(encoding="utf-8-sig")
        texts[path] = text
        meta = frontmatter(text)
        if any(k in meta for k in ("approvedStoryIds", "currentStep", "currentEpic")):
            errors.append(f"{path}: duplicated global state")
        items = stories(text, path)
        if not items or any(item["epic"] != number for item in items):
            errors.append(f"{path}: empty or wrong Epic Stories")
        if re.findall(r"^## Epic (\d+):", visible(text), re.M) != [str(number)]:
            errors.append(f"{path}: missing or conflicting Epic heading")
        if number not in macro:
            errors.append(f"{path}: Epic outside catalogue")
        expected = {rf for rf, epic in owner.items() if epic == str(number)}
        per_epic[number] = {"stories": len(items), "acs": sum(len(item["acs"]) for item in items),
                            "principal_rf": len(expected), "matrix_text": ""}
        all_stories.extend(items)
        matrix = re.search(r"^### Rastreabilidade .*Epic " + str(number) + r"\b", text, re.M)
        if not matrix:
            if number in pending and number not in approved_epics:
                coverage_pending[number] = sorted(expected)
            else:
                errors.append(f"{path}: missing RF matrix")
            continue
        matrix_text = re.split(r"^#{1,3} ", text[matrix.end():], maxsplit=1, flags=re.M)[0]
        rows = re.findall(r"^\| (" + RF + r") \|", matrix_text, re.M)
        if set(rows) - expected or len(rows) != len(set(rows)):
            errors.append(f"{path}: RF matrix differs from principal ownership")
        if expected - set(rows):
            if number in pending and number not in approved_epics:
                coverage_pending[number] = sorted(expected - set(rows))
            else:
                errors.append(f"{path}: RF matrix differs from principal ownership")
        # Expand implicit one-Story AC references in the Epic 3 matrix.
        for row in matrix_text.splitlines():
            cells = [cell.strip() for cell in row.strip("|").split("|")]
            if number == 3 and len(cells) >= 3 and re.fullmatch(RF, cells[0]) and re.fullmatch(r"\d+\.\d+", cells[1]):
                matrix_text = matrix_text.replace(row, row.replace("| " + cells[2] + " |", "| " + cells[1] + " " + cells[2] + " |", 1))
        for row in matrix_text.splitlines():
            if re.match(r"^\| " + RF + r" \|", row) and not re.search(r"\d+\.\d+ AC-\d{2}", row):
                errors.append(f"{path}: RF matrix row without Story/AC reference")
        per_epic[number]["matrix_text"] = matrix_text
    by_id = {item["id"]: item for item in all_stories}
    duplicates = [sid for sid, count in Counter(item["id"] for item in all_stories).items() if count > 1]
    if duplicates:
        errors.append(f"duplicate Story IDs: {duplicates}")
    for item in all_stories:
        if not item["acs"] or item["acs"] != list(range(1, len(item["acs"]) + 1)) or not item["gwt"]:
            errors.append(f"{item['id']}: missing/nonsequential ACs or GWT")
    for number in per_epic:
        numbers = sorted(int(item["id"].split(".")[1]) for item in all_stories if item["epic"] == number)
        if numbers != list(range(1, len(numbers) + 1)):
            errors.append(f"Epic {number}: nonsequential Story IDs")
    # All explicit Story+AC references, including matrix shorthand expanded above.
    reference_texts = [*texts.values(), *(data["matrix_text"] for data in per_epic.values())]
    for text in reference_texts:
        for match in re.finditer(r"(?<![\d.])(\d+\.\d+) AC-(\d{2})((?:[–/\-]\d{2})*)", text):
            numbers, previous = [int(match[2])], int(match[2])
            for separator, value in re.findall(r"([–/\-])(\d{2})", match[3]):
                value = int(value)
                if separator in "–-" and value < previous:
                    errors.append(f"descending AC range: {match[0]}")
                numbers.extend(range(previous + 1, value + 1) if separator in "–-" else [value])
                previous = value
            if match[1] not in by_id or not set(numbers) <= set(by_id.get(match[1], {}).get("acs", [])):
                errors.append(f"missing Story/AC reference: {match[0]}")
    for path, text in texts.items():
        check_links(path, text, errors)
        explicit = re.findall(r'<a\s+(?:id|name)="([^"]+)"', visible(text))
        if len(explicit) != len(set(explicit)):
            errors.append(f"{path}: duplicate explicit anchor")
        if "\ufffd" in text:
            errors.append(f"{path}: Unicode replacement character")
        if any(line.rstrip() != line for line in text.splitlines()):
            errors.append(f"{path}: trailing whitespace")
        for line, following in zip(text.splitlines(), text.splitlines()[1:]):
            if line.startswith("|") and following.startswith("|") and line.count("|") != following.count("|"):
                errors.append(f"{path}: inconsistent table columns")
    approved = state.get("approvedStoryIds", [])
    if not isinstance(approved, list) or any(not isinstance(sid, str) or sid not in by_id for sid in approved) or len(approved) != len(set(approved)):
        errors.append("invalid or duplicated approvedStoryIds")
        approved = []
    approved_ids = set(approved)
    for number in approved_epics:
        if any(item["id"] not in approved_ids for item in all_stories if item["epic"] == number):
            errors.append(f"Epic {number}: planning-approved Epic has unapproved Stories")
    for sid in approved_ids:
        if int(sid.split(".")[0]) in pending:
            errors.append(f"{sid}: approved Story belongs to detail-pending Epic")
    absent = set(macro) - set(per_epic)
    if not absent <= set(pending) or absent & set(approved_epics):
        errors.append("missing detail must be pending and cannot be planning-approved")
    if state.get("currentEpic") not in macro:
        errors.append("invalid currentEpic")
    approval_incomplete = bool(pending or pending_review or set(macro) - set(approved_epics)
                               or set(by_id) - approved_ids or absent or coverage_pending)
    final_validation = state.get("finalValidationStatus")
    if final_validation not in (None, "pending", "concerns-open-gates", "passed"):
        errors.append("invalid finalValidationStatus")
    # Documentary acceptance is not the final readiness gate. Never infer gate closure
    # from the absence of pending approval lists or the presence of every Story ID.
    incomplete = approval_incomplete or final_validation in ("pending", "concerns-open-gates")
    if state.get("implementation_authorized", False) is not False:
        errors.append("planning backlog cannot authorize implementation")
    if incomplete and (state.get("status") != "draft" or state.get("currentStep") != "step-03-create-stories" or any(str(step).startswith(("step-03", "step-04")) for step in state.get("stepsCompleted", []))):
        errors.append("incomplete backlog must remain draft at step 03")
    for data in per_epic.values():
        data.pop("matrix_text")
    return {"status": "FAIL" if errors else "PASS", "errors": sorted(set(errors)),
            "macro_epics": len(macro), "rf": len(set(inventory)),
            "families": len({rf.rsplit("-", 1)[0] for rf in inventory}),
            "stories": len(all_stories), "acs": sum(len(item["acs"]) for item in all_stories),
             "per_epic": per_epic, "pending_epics": pending,
             "pending_review_epics": pending_review,
              "planning_complete": not incomplete,
              "documentary_approval_complete": not approval_incomplete,
              "final_validation_status": final_validation,
            "pending_coverage": coverage_pending,
            "planning_approved_stories": len(approved), "workflow_step": state.get("currentStep"),
            "implementation_authorized": False,
            "hashes": {str(path.relative_to(root)): hashlib.sha256(path.read_bytes()).hexdigest() for path in texts}}


def main():
    if hasattr(sys.stdout, "reconfigure"):
        sys.stdout.reconfigure(encoding="utf-8")
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--root", type=Path, default=Path(__file__).resolve().parents[1])
    parser.add_argument("--story", help="Locate N.M using only index + matching Epic")
    args = parser.parse_args()
    try:
        result = locate(args.root.resolve(), args.story) if args.story else validate(args.root.resolve())
    except (OSError, ValueError, TypeError, KeyError, AttributeError, YAMLError) as error:
        result = {"status": "FAIL", "errors": [str(error)]}
    print(json.dumps(result, ensure_ascii=False, sort_keys=True, indent=2, default=str))
    return 1 if result.get("status") == "FAIL" else 0


if __name__ == "__main__":
    sys.exit(main())
