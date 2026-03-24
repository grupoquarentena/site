<?php

declare(strict_types=1);

require_once __DIR__ . '/meeting_status.php';
require_once __DIR__ . '/meeting_contract.php';

function present_meeting_details(array $meeting, string $timezone, ?array $status = null): array
{
    $tz = normalize_contract_timezone($timezone);
    $startsAt = parse_meeting_datetime($meeting['starts_at'] ?? null, $tz);
    $endsAt = parse_meeting_datetime($meeting['ends_at'] ?? null, $tz);
    $type = $meeting['type'] ?? null;
    $meetingStatus = present_meeting_status($status ?? resolve_meeting_status($meeting, $timezone));

    return [
        'status_label' => $meetingStatus['label'],
        'status_slug' => $meetingStatus['slug'],
        'status_a11y_label' => $meetingStatus['a11y_label'],
        'schedule_label' => format_meeting_schedule($startsAt, $endsAt),
        'meeting_id_label' => trim((string) ($meeting['meeting_id'] ?? '')),
        'password_label' => trim((string) ($meeting['password'] ?? '')),
        'type_label' => format_meeting_type_label($type),
        'type_description' => format_meeting_type_description($type),
        'updated_at_label' => trim((string) ($meeting['updated_at'] ?? '')),
    ];
}

function format_meeting_schedule(?DateTimeImmutable $startsAt, ?DateTimeImmutable $endsAt): string
{
    if (!($startsAt instanceof DateTimeImmutable) || !($endsAt instanceof DateTimeImmutable)) {
        return 'Horario a confirmar';
    }

    if ($startsAt->format('Y-m-d') === $endsAt->format('Y-m-d')) {
        return $startsAt->format('d/m, H:i') . ' as ' . $endsAt->format('H:i');
    }

    return $startsAt->format('d/m, H:i') . ' ate ' . $endsAt->format('d/m, H:i');
}

function format_meeting_type_label(mixed $type): string
{
    return $type === 'aberta' ? 'Aberta' : 'Fechada';
}

function format_meeting_type_description(mixed $type): string
{
    if ($type === 'aberta') {
        return 'Aberta ao publico — qualquer pessoa pode participar.';
    }

    return 'Fechada — exclusiva para quem se identifica como dependente.';
}

function present_meeting_status(array $status): array
{
    $label = trim((string) ($status['label'] ?? 'próxima reunião'));
    $slug = trim((string) ($status['slug'] ?? 'proxima-reuniao'));

    return [
        'label' => $label,
        'slug' => $slug,
        'a11y_label' => 'Status da reuniao: ' . $label,
    ];
}
