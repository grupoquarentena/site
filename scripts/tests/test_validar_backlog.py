"""Document-only regressions. No Laravel, generation CLI, network or real Story writes."""

import ast
import hashlib
import importlib.util
import json
from pathlib import Path
import re
import shutil
import sys
import tempfile
import unittest

sys.dont_write_bytecode = True
ROOT = Path(__file__).resolve().parents[2]
spec = importlib.util.spec_from_file_location("backlog", ROOT / "scripts/validar-backlog.py")
backlog = importlib.util.module_from_spec(spec)
spec.loader.exec_module(backlog)


def native_parser():
    """Execute only the installed parser's pure declarations, not its CLI."""
    source = ROOT / ".agents/skills/bmad-sprint-planning/scripts/sprint_plan.py"
    tree = ast.parse(source.read_text(encoding="utf-8"))
    assignments = {"EPIC_RE", "STORY_RE", "SUSPECT_RE", "FENCE_RE"}
    nodes = [node for node in tree.body
             if isinstance(node, ast.FunctionDef) and node.name in {"_slug", "parse_epics"}
             or isinstance(node, ast.Assign) and any(isinstance(t, ast.Name) and t.id in assignments for t in node.targets)]
    namespace = {"re": re, "Path": Path, "hashlib": hashlib}
    exec(compile(ast.Module(body=nodes, type_ignores=[]), str(source), "exec"), namespace)
    return namespace["parse_epics"]


class DistributedBacklogTests(unittest.TestCase):
    def setUp(self):
        approved_temp = Path.home() / "AppData/Local/Temp/opencode"
        self.temp = tempfile.TemporaryDirectory(prefix="backlog-b-test-", dir=approved_temp if approved_temp.exists() else None)
        self.root = Path(self.temp.name)
        self.planning = self.root / "_bmad-output/planning-artifacts"
        self.planning.mkdir(parents=True)
        for path in (ROOT / "_bmad-output/planning-artifacts").glob("*.md"):
            shutil.copy2(path, self.planning / path.name)
        shutil.copytree(ROOT / "_bmad-output/planning-artifacts/epics", self.planning / "epics")
        shutil.copytree(ROOT / "docs", self.root / "docs")
        shutil.copy2(ROOT / "AGENTS.md", self.root / "AGENTS.md")
        self.index = self.planning / "epics.md"

    def tearDown(self):
        self.temp.cleanup()

    def edit(self, path, old, new):
        text = path.read_text(encoding="utf-8")
        self.assertIn(old, text)
        path.write_text(text.replace(old, new, 1), encoding="utf-8")

    def failures(self):
        report = backlog.validate(self.root)
        self.assertEqual(report["status"], "FAIL")
        return "\n".join(report["errors"])

    def test_integrity_and_repeatability(self):
        first = backlog.validate(self.root)
        self.assertEqual(first, backlog.validate(self.root))
        self.assertEqual(first["errors"], [])
        self.assertEqual((first["macro_epics"], first["rf"], first["families"]), (15, 236, 22))
        self.assertEqual(sum(first["per_epic"][n]["stories"] for n in range(1, 6)), 39)
        self.assertEqual(sum(first["per_epic"][n]["acs"] for n in range(1, 6)), 235)
        self.assertEqual(sum(first["per_epic"][n]["principal_rf"] for n in range(1, 6)), 103)
        self.assertFalse(first["implementation_authorized"])

    def test_A_directed_discovery_without_other_details(self):
        for path in (self.planning / "epics").glob("*.md"):
            if path.name != "epic-03.md":
                path.unlink()
        story = backlog.locate(self.root, "3.2")
        self.assertEqual(story["acs"], [1, 2, 3, 4, 5])
        self.assertEqual(len(story["sources"]), 2)
        self.assertIn("3.1", story["metadata"])
        self.assertIn("RF-MIGRACAO-006/012", story["metadata"])

    def test_B_cross_context(self):
        producer = backlog.locate(self.root, "2.5")
        consumer = backlog.locate(self.root, "4.8")
        transport = backlog.locate(self.root, "4.2")
        self.assertIn("durável", producer["title"])
        self.assertIn("2.5", consumer["metadata"])
        self.assertIn("4.2", consumer["metadata"])
        self.assertIn("4.1", transport["metadata"])
        self.assertIn("intenção", (self.planning / "epics/epic-04.md").read_text(encoding="utf-8"))

    def pending_six_fixture(self):
        """Normalize only the disposable copy; keep this regression after Epic 6 exists."""
        state = backlog.frontmatter(self.index.read_text(encoding="utf-8"))
        # A synthetic pending Epic 6 remains reproducible after real future Epics exist.
        state.update(status="draft", currentStep="step-03-create-stories", currentEpic=6)
        state["pendingDetailedEpics"] = sorted(set(state["pendingDetailedEpics"]) | {6})
        state["pendingReviewEpics"] = [n for n in state.get("pendingReviewEpics", []) if n != 6]
        state["approvedPlanningEpicDetails"] = [n for n in state["approvedPlanningEpicDetails"] if n != 6]
        state["approvedStoryIds"] = [sid for sid in state["approvedStoryIds"] if not sid.startswith("6.")]
        self.write_state(state)
        target = backlog.story_path(self.planning, state["currentEpic"])
        self.assertEqual(target.name, "epic-06.md")
        if target.exists():
            target.unlink()  # Only the explicitly synthetic copy, never ROOT.
        return target

    def write_state(self, state):
        """Change only requested top-level fields; never reserialize other YAML/body."""
        original = self.index.read_text(encoding="utf-8")
        _, header, body = original.split("---\n", 2)
        previous = backlog.frontmatter(original)
        for key, value in state.items():
            if key in previous and previous[key] == value:
                continue
            pattern = rf"^{re.escape(key)}:.*(?:\n(?:[ \t]+[^\n]*|\s*-[^\n]*))*"
            replacement = f"{key}: {json.dumps(value, ensure_ascii=False)}"
            if key in previous:
                header, count = re.subn(pattern, lambda _: replacement, header, count=1, flags=re.M)
                self.assertEqual(count, 1)
            else:
                header += replacement + "\n"
        self.index.write_text("---\n" + header + "---\n" + body, encoding="utf-8")
        self.assertEqual(backlog.frontmatter(self.index.read_text(encoding="utf-8")), state)

    def fully_approved_fixture(self):
        state = backlog.frontmatter(self.index.read_text(encoding="utf-8"))
        state.update(pendingDetailedEpics=[], pendingReviewEpics=[],
                     approvedPlanningEpicDetails=list(range(1, 16)), finalValidationStatus="passed")
        state["approvedStoryIds"] = [s["id"] for path in sorted((self.planning / "epics").glob("*.md"))
                                     for s in backlog.stories(path.read_text(encoding="utf-8"), path)]
        self.write_state(state)
        return state

    def test_C_synthetic_continuity_only_temporary(self):
        target = self.pending_six_fixture()
        before = {path: path.read_bytes() for path in self.planning.rglob("*.md")}
        self.assertFalse(target.exists())
        target.write_text('## Epic 6: Ensaio sintético\n\n### Story 6.1: Teste de roteamento\n\n**Acceptance Criteria:**\n\n**AC-01.** **Given** fixture sintética; **When** conferir caminho; **Then** persistir somente no temporário.\n', encoding="utf-8")
        self.assertEqual(backlog.locate(self.root, "6.1")["acs"], [1])
        self.assertFalse(backlog.locate(self.root, "6.1")["planning_approved"])
        self.assertTrue(all(path.read_bytes() == data for path, data in before.items()))

    def test_D_installed_parser_details_only(self):
        parse = native_parser()
        files = sorted((self.planning / "epics").glob("epic-*.md"))
        entries, warnings = parse(files)
        expected = sum(len(backlog.stories(path.read_text(encoding="utf-8"), path)) for path in files)
        self.assertEqual(sum(kind == "story" for _, kind, _ in entries), expected)
        self.assertEqual(sum(kind == "epic" for _, kind, _ in entries), len(files))
        self.assertTrue(all("unparsed Epic/Story-like heading" in warning for warning in warnings))
        self.assertFalse(any(self.root.rglob("sprint-status.yaml")))
        # Index is context, not a parser input: demonstrate the extra macro Epics.
        combined, _ = parse([self.index, *files])
        self.assertEqual(sum(kind == "epic" for _, kind, _ in combined), 15)
        self.assertEqual(sum(kind == "story" for _, kind, _ in combined), expected)

    def test_duplicate_story_id_even_with_different_title(self):
        path = self.planning / "epics/epic-03.md"
        path.write_text(path.read_text(encoding="utf-8") + '\n### Story 3.2: Outro título\n\n**AC-01.** **Given** x; **When** y; **Then** z.\n', encoding="utf-8")
        self.assertIn("duplicate Story IDs", self.failures())

    def test_index_cannot_contain_story(self):
        self.index.write_text(self.index.read_text(encoding="utf-8") + '\n### Story 6.1: Indevida\n', encoding="utf-8")
        self.assertIn("Story definition in global index", self.failures())

    def test_missing_ac_reference(self):
        path = self.planning / "epics/epic-03.md"
        self.edit(path, "3.2 AC-01–05", "3.2 AC-01–99")
        self.assertIn("missing Story/AC reference", self.failures())

    def test_descending_ac_range(self):
        path = self.planning / "epics/epic-03.md"
        self.edit(path, "3.2 AC-01–05", "3.2 AC-01–00")
        self.assertIn("descending AC range", self.failures())

    def test_missing_matrix_has_specific_diagnostic(self):
        path = self.planning / "epics/epic-03.md"
        self.edit(path, "### Rastreabilidade proposta do Epic 3", "### Matriz removida")
        self.assertIn("missing RF matrix", self.failures())

    def test_partial_unapproved_epic_reports_pending_not_corruption(self):
        target = self.pending_six_fixture()
        target.write_text('## Epic 6: Ensaio sintético\n\n### Story 6.1: Incremento parcial\n\n**AC-01.** **Given** x; **When** y; **Then** z.\n', encoding="utf-8")
        # Preserve the target referenced by the global audit while keeping coverage partial.
        with target.open("a", encoding="utf-8") as fixture:
            fixture.write('\n### Story 6.2: Contrato referenciado\n\n')
            for ac in range(1, 5):
                fixture.write(f'**AC-{ac:02d}.** **Given** x; **When** y; **Then** z.\n\n')
        report = backlog.validate(self.root)
        self.assertEqual(report["errors"], [])
        self.assertIn(6, report["pending_epics"])
        self.assertTrue(report["pending_coverage"][6])
        expected_approvals = len(backlog.frontmatter(self.index.read_text(encoding="utf-8"))["approvedStoryIds"])
        self.assertEqual(report["planning_approved_stories"], expected_approvals)

    def test_missing_anchor(self):
        self.edit(self.index, "#story-3-4", "#story-3-99")
        self.assertIn("missing anchor", self.failures())

    def test_duplicate_anchor(self):
        path = self.planning / "epics/epic-03.md"
        self.edit(path, 'id="story-3-2"', 'id="story-3-1"')
        self.assertIn("duplicate explicit anchor", self.failures())

    def test_matrix_without_ac_reference(self):
        path = self.planning / "epics/epic-03.md"
        self.edit(path, "| AC-01/04/05/06 |", "| Sem critério |")
        self.assertIn("RF matrix row without Story/AC reference", self.failures())

    def test_fake_approval(self):
        self.edit(self.index, 'approvedStoryIds: ["1.1",', 'approvedStoryIds: ["99.99", "1.1",')
        self.assertIn("invalid or duplicated approvedStoryIds", self.failures())

    def test_missing_principal_rf(self):
        path = self.planning / "epics/epic-03.md"
        self.edit(path, "| RF-MIGRACAO-006 |", "| RF-MIGRACAO-999 |")
        self.assertIn("RF matrix differs", self.failures())

    def test_wrong_epic_story(self):
        path = self.planning / "epics/epic-03.md"
        self.edit(path, "### Story 3.2:", "### Story 4.99:")
        self.assertIn("wrong Epic Stories", self.failures())

    def test_cannot_finalize_incomplete_backlog(self):
        self.pending_six_fixture()
        self.edit(self.index, "status: draft", "status: final")
        self.assertIn("incomplete backlog must remain draft", self.failures())

    def test_review_pending_blocks_every_completion_marker(self):
        state = self.fully_approved_fixture()
        state["pendingReviewEpics"] = [6]
        state["approvedPlanningEpicDetails"].remove(6)
        state["approvedStoryIds"] = [sid for sid in state["approvedStoryIds"] if not sid.startswith("6.")]
        state.update(status="draft", currentStep="step-03-create-stories")
        self.write_state(state)
        original = self.index.read_text(encoding="utf-8")
        for changes in ({"status": "final"}, {"currentStep": "step-04-final-validation"},
                        {"stepsCompleted": ["step-01-validate-prerequisites", "step-02-design-epics", "step-03-create-stories"]},
                        {"stepsCompleted": ["step-04-final-validation"]}):
            with self.subTest(changes=changes):
                self.index.write_text(original, encoding="utf-8")
                state = backlog.frontmatter(original)
                state.update(changes)
                self.write_state(state)
                self.assertIn("incomplete backlog must remain draft", self.failures())

    def test_missing_approvals_block_even_without_pending_lists(self):
        state = self.fully_approved_fixture()
        state.update(status="final", pendingReviewEpics=[])
        state["approvedStoryIds"].remove("6.1")
        self.write_state(state)
        failures = self.failures()
        self.assertIn("planning-approved Epic has unapproved Stories", failures)
        self.assertIn("incomplete backlog must remain draft", failures)

    def test_complete_approved_fixture_can_finalize_without_authorizing_build(self):
        state = self.fully_approved_fixture()
        state.update(status="final", currentStep="step-04-final-validation",
                     stepsCompleted=["step-03-create-stories", "step-04-final-validation"])
        self.write_state(state)
        report = backlog.validate(self.root)
        self.assertEqual(report["errors"], [])
        self.assertTrue(report["planning_complete"])
        self.assertFalse(report["implementation_authorized"])

    def test_pending_review_validation(self):
        original = self.index.read_text(encoding="utf-8")
        for value in ("6", [6, 6], [16], [True], [1], [6.0]):
            with self.subTest(value=value):
                self.index.write_text(original, encoding="utf-8")
                state = backlog.frontmatter(original)
                state["pendingReviewEpics"] = value
                self.write_state(state)
                self.assertIn("pending", self.failures())

    def test_all_approved_with_open_gates_stays_draft(self):
        state = self.fully_approved_fixture()
        state.update(status="draft", currentStep="step-03-create-stories",
                     finalValidationStatus="concerns-open-gates")
        self.write_state(state)
        report = backlog.validate(self.root)
        self.assertEqual(report["errors"], [])
        self.assertTrue(report["documentary_approval_complete"])
        self.assertFalse(report["planning_complete"])
        for changes in ({"status": "final"}, {"currentStep": "step-04-final-validation"},
                        {"stepsCompleted": ["step-03-create-stories", "step-04-final-validation"]}):
            with self.subTest(changes=changes):
                self.write_state({**state, **changes})
                self.assertIn("incomplete backlog must remain draft", self.failures())

    def test_planning_cannot_authorize_implementation(self):
        state = self.fully_approved_fixture()
        state["implementation_authorized"] = True
        self.write_state(state)
        self.assertIn("planning backlog cannot authorize implementation", self.failures())

    def test_pending_fixture_preserves_other_yaml_and_body(self):
        original = self.index.read_text(encoding="utf-8")
        previous = backlog.frontmatter(original)
        self.pending_six_fixture()
        changed = self.index.read_text(encoding="utf-8")
        state = backlog.frontmatter(changed)
        affected = {"status", "currentStep", "currentEpic", "pendingDetailedEpics",
                    "pendingReviewEpics", "approvedPlanningEpicDetails", "approvedStoryIds"}
        self.assertEqual({k: v for k, v in previous.items() if k not in affected},
                         {k: v for k, v in state.items() if k not in affected})
        self.assertEqual(original.split("---\n", 2)[2], changed.split("---\n", 2)[2])
        self.assertFalse(any(line.rstrip() != line for line in changed.splitlines()))

    def test_nonsequential_ac(self):
        path = self.planning / "epics/epic-03.md"
        self.edit(path, "**AC-01.**", "**AC-09.**")
        self.assertIn("nonsequential ACs", self.failures())

    def test_YAML_duplicate_key_rejected(self):
        with self.assertRaises(backlog.YAMLError):
            backlog.frontmatter("---\nstatus: draft\nstatus: final\n---\n")

    def test_no_future_file_created_by_validation(self):
        before = {p: p.read_bytes() for p in self.planning.rglob("*.md")}
        backlog.validate(self.root)
        self.assertEqual(before, {p: p.read_bytes() for p in self.planning.rglob("*.md")})


if __name__ == "__main__":
    unittest.main()
