<?php

declare(strict_types=1);

require_once __DIR__ . '/meeting_status.php';

function validate_meeting_contract(array $contract): array
{
    assert_required_keys($contract, ['timezone', 'meeting', 'support_links'], 'contrato');

    $timezone = normalize_contract_timezone($contract['timezone']);
    $meeting = $contract['meeting'];
    $supportLinks = $contract['support_links'];

    if (!is_array($meeting) || !is_array($supportLinks)) {
        throw new RuntimeException('Os blocos meeting e support_links devem ser arrays.');
    }

    assert_required_keys(
        $meeting,
        ['title', 'join_url', 'meeting_id', 'password', 'type', 'starts_at', 'ends_at', 'status', 'status_override', 'updated_at'],
        'meeting'
    );
    assert_required_keys($supportLinks, ['whatsapp', 'report', 'directory'], 'support_links');

    foreach (['title', 'updated_at'] as $requiredStringKey) {
        assert_non_empty_string($meeting[$requiredStringKey], 'meeting.' . $requiredStringKey);
    }

    foreach (['join_url', 'meeting_id', 'password'] as $nullableStringKey) {
        assert_nullable_string($meeting[$nullableStringKey], 'meeting.' . $nullableStringKey);
    }

    $joinUrl = $meeting['join_url'];

    if (is_string($joinUrl) && trim($joinUrl) !== '') {
        assert_valid_zoom_join_url(trim($joinUrl));
    }

    if (!in_array($meeting['type'], ['aberta', 'fechada'], true)) {
        throw new RuntimeException('meeting.type deve ser "aberta" ou "fechada".');
    }

    foreach (['whatsapp', 'report', 'directory'] as $supportKey) {
        assert_nullable_string($supportLinks[$supportKey], 'support_links.' . $supportKey);
    }

    $statusField = normalize_status_label($meeting['status']);
    $statusOverride = normalize_status_label($meeting['status_override']);
    $manualStatus = $statusOverride ?? $statusField;
    $startsAt = parse_meeting_datetime($meeting['starts_at'], $timezone);
    $endsAt = parse_meeting_datetime($meeting['ends_at'], $timezone);
    $hasStartsAt = is_string($meeting['starts_at']) && trim($meeting['starts_at']) !== '';
    $hasEndsAt = is_string($meeting['ends_at']) && trim($meeting['ends_at']) !== '';

    if (($hasStartsAt xor $hasEndsAt) && $manualStatus === null) {
        throw new RuntimeException('Agenda parcial exige status_override manual valido.');
    }

    if (($hasStartsAt || $hasEndsAt) && $manualStatus === null && (!($startsAt instanceof DateTimeImmutable) || !($endsAt instanceof DateTimeImmutable))) {
        throw new RuntimeException('Agenda invalida exige status_override manual valido.');
    }

    if ($startsAt instanceof DateTimeImmutable && $endsAt instanceof DateTimeImmutable && $endsAt < $startsAt) {
        throw new RuntimeException('meeting.ends_at deve ser posterior a meeting.starts_at.');
    }

    if ($statusField !== null && $statusOverride !== null && $statusField !== $statusOverride) {
        throw new RuntimeException('meeting.status e meeting.status_override devem permanecer sincronizados quando ambos forem definidos.');
    }

    return [
        'timezone' => $timezone->getName(),
        'meeting' => array_merge($meeting, [
            'status' => $manualStatus,
            'status_override' => $manualStatus,
        ]),
        'support_links' => $supportLinks,
    ];
}

function normalize_contract_timezone(mixed $value): DateTimeZone
{
    if (!is_string($value) || trim($value) === '') {
        throw new RuntimeException('timezone deve ser uma string nao vazia.');
    }

    try {
        return new DateTimeZone($value);
    } catch (Throwable $exception) {
        throw new RuntimeException('timezone invalido no contrato: ' . $value, 0, $exception);
    }
}

/**
 * Quando houver um link real de reuniao configurado, ele precisa ser uma URL
 * https de dominio oficial do Zoom. No estado arquivado (join_url nulo), a
 * ausencia de dado operacional e permitida de forma explicita.
 */
function assert_valid_zoom_join_url(string $url): void
{
    if (filter_var($url, FILTER_VALIDATE_URL) === false) {
        throw new RuntimeException('meeting.join_url deve ser uma URL valida quando configurada.');
    }

    $parts = parse_url($url);

    if (!is_array($parts)) {
        throw new RuntimeException('meeting.join_url nao pode ser interpretada.');
    }

    $scheme = strtolower((string) ($parts['scheme'] ?? ''));
    $host = strtolower((string) ($parts['host'] ?? ''));

    if ($scheme !== 'https') {
        throw new RuntimeException('meeting.join_url deve usar esquema https.');
    }

    if (!host_matches_allowlist($host, zoom_join_url_allowed_hosts())) {
        throw new RuntimeException('meeting.join_url deve apontar para dominio oficial do Zoom.');
    }

    if (isset($parts['user']) || isset($parts['pass'])) {
        throw new RuntimeException('meeting.join_url nao pode conter credenciais embutidas.');
    }

    if (isset($parts['port']) && (int) $parts['port'] !== 443) {
        throw new RuntimeException('meeting.join_url nao pode usar porta alternativa.');
    }
}

/** @return list<string> */
function zoom_join_url_allowed_hosts(): array
{
    return ['zoom.us', 'zoom.com', 'zoom.com.br'];
}

/**
 * Confere se um hostname pertence a um dominio autorizado ou a um subdominio
 * real dele (sufixo com ponto), sem aceitar dominios "parecidos".
 *
 * @param list<string> $allowedHosts
 */
function host_matches_allowlist(string $host, array $allowedHosts): bool
{
    $host = strtolower(trim($host));

    if ($host === '') {
        return false;
    }

    foreach ($allowedHosts as $allowedHost) {
        $allowed = strtolower(trim((string) $allowedHost));

        if ($allowed === '') {
            continue;
        }

        if ($host === $allowed || str_ends_with($host, '.' . $allowed)) {
            return true;
        }
    }

    return false;
}

function assert_required_keys(array $source, array $requiredKeys, string $context): void
{
    foreach ($requiredKeys as $requiredKey) {
        if (!array_key_exists($requiredKey, $source)) {
            throw new RuntimeException('Campo obrigatorio ausente em ' . $context . ': ' . $requiredKey);
        }
    }
}

function assert_non_empty_string(mixed $value, string $field): void
{
    if (!is_string($value) || trim($value) === '') {
        throw new RuntimeException($field . ' deve ser uma string nao vazia.');
    }
}

function assert_nullable_string(mixed $value, string $field): void
{
    if ($value === null) {
        return;
    }

    assert_non_empty_string($value, $field);
}
