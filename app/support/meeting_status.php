<?php

declare(strict_types=1);

function resolve_meeting_status(array $meeting, string $timezone, ?DateTimeImmutable $now = null): array
{
    $manualOverride = normalize_status_label($meeting['status_override'] ?? $meeting['status'] ?? null);
    if ($manualOverride !== null) {
        return status_payload($manualOverride, 'manual');
    }

    $tz = normalize_contract_timezone($timezone);
    $currentTime = $now instanceof DateTimeImmutable ? $now->setTimezone($tz) : new DateTimeImmutable('now', $tz);
    $startsAt = parse_meeting_datetime($meeting['starts_at'] ?? null, $tz);
    $endsAt = parse_meeting_datetime($meeting['ends_at'] ?? null, $tz);

    if (!($startsAt instanceof DateTimeImmutable) || !($endsAt instanceof DateTimeImmutable)) {
        throw new RuntimeException('Agenda invalida exige status_override manual valido.');
    }

    if ($currentTime >= $startsAt && $currentTime <= $endsAt) {
        return status_payload('agora', 'automatic');
    }

    if ($currentTime < $startsAt && $currentTime->format('Y-m-d') === $startsAt->format('Y-m-d')) {
        return status_payload('mais tarde hoje', 'automatic');
    }

    return status_payload('próxima reunião', 'automatic');
}

function parse_meeting_datetime(mixed $value, DateTimeZone $timezone): ?DateTimeImmutable
{
    if (!is_string($value) || trim($value) === '') {
        return null;
    }

    try {
        return (new DateTimeImmutable($value, $timezone))->setTimezone($timezone);
    } catch (Throwable) {
        return null;
    }
}

function status_payload(string $label, string $source): array
{
    $normalized = trim($label);

    return [
        'label' => $label,
        'source' => $source,
        'slug' => preg_match('/^agora$/iu', $normalized) === 1
            ? 'agora'
            : (preg_match('/^mais tarde hoje$/iu', $normalized) === 1 ? 'mais-tarde-hoje' : 'proxima-reuniao'),
    ];
}

function normalize_status_label(mixed $value): ?string
{
    if (!is_string($value) || trim($value) === '') {
        return null;
    }

    $normalized = trim($value);

    if (preg_match('/^agora$/iu', $normalized) === 1) {
        return 'agora';
    }

    if (preg_match('/^mais tarde hoje$/iu', $normalized) === 1) {
        return 'mais tarde hoje';
    }

    if (preg_match('/^pr[oó]xima reuni[aã]o$/iu', $normalized) === 1) {
        return 'próxima reunião';
    }

    return null;
}
