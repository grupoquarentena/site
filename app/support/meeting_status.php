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
    $hasStartsAt = is_string($meeting['starts_at'] ?? null) && trim((string) $meeting['starts_at']) !== '';
    $hasEndsAt = is_string($meeting['ends_at'] ?? null) && trim((string) $meeting['ends_at']) !== '';

    if (!$hasStartsAt && !$hasEndsAt) {
        return status_payload('sem agenda ativa', 'automatic');
    }

    if (!($startsAt instanceof DateTimeImmutable) || !($endsAt instanceof DateTimeImmutable)) {
        throw new RuntimeException('Agenda invalida exige status_override manual valido.');
    }

    if ($currentTime >= $startsAt && $currentTime <= $endsAt) {
        return status_payload('agora', 'automatic');
    }

    if ($currentTime < $startsAt && $currentTime->format('Y-m-d') === $startsAt->format('Y-m-d')) {
        return status_payload('mais tarde hoje', 'automatic');
    }

    if ($currentTime < $startsAt) {
        return status_payload('próxima reunião', 'automatic');
    }

    // Ocorrencia encerrada sem recorrencia conhecida: o legado nao inventa
    // uma proxima reuniao e assume explicitamente a ausencia de agenda ativa.
    return status_payload('sem agenda ativa', 'automatic');
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
    $slug = 'proxima-reuniao';

    if (preg_match('/^agora$/iu', $normalized) === 1) {
        $slug = 'agora';
    } elseif (preg_match('/^mais tarde hoje$/iu', $normalized) === 1) {
        $slug = 'mais-tarde-hoje';
    } elseif (preg_match('/^sem agenda ativa$/iu', $normalized) === 1) {
        $slug = 'sem-agenda-ativa';
    }

    return [
        'label' => $label,
        'source' => $source,
        'slug' => $slug,
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

    if (preg_match('/^sem agenda ativa$/iu', $normalized) === 1) {
        return 'sem agenda ativa';
    }

    return null;
}

/**
 * Relogio de referencia para testes deterministicos.
 * SITE_TEST_NOW so tem efeito quando APP_ENV=test; em qualquer outro
 * ambiente o relogio real e usado e a variavel e ignorada.
 */
function site_test_now(DateTimeZone $timezone, ?string $appEnv = null, ?string $testNow = null): ?DateTimeImmutable
{
    if ($appEnv === null) {
        $envValue = getenv('APP_ENV');
        $appEnv = is_string($envValue) ? $envValue : '';
    }

    if (strtolower(trim($appEnv)) !== 'test') {
        return null;
    }

    if ($testNow === null) {
        $nowValue = getenv('SITE_TEST_NOW');
        $testNow = is_string($nowValue) ? $nowValue : '';
    }

    if (trim($testNow) === '') {
        return null;
    }

    try {
        return new DateTimeImmutable($testNow, $timezone);
    } catch (Throwable) {
        return null;
    }
}
