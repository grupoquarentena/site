<?php

declare(strict_types=1);

require_once __DIR__ . '/meeting_contract.php';

function present_support_links(array $supportLinks): array
{
    $definitions = [
        'directory' => [
            'label' => 'Diretório e outras reuniões',
            'description' => 'Abrir recurso complementar de apoio',
            'event' => 'support_directory_click',
            'external_hint' => 'Abre recurso externo do ecossistema NA',
        ],
        'report' => [
            'label' => 'Relatório público',
            'description' => 'Abrir relatório público aprovado pelo grupo',
            'event' => 'report_click',
            'external_hint' => 'Abre relatório em ferramenta externa',
        ],
        'whatsapp' => [
            'label' => 'WhatsApp de membros',
            'description' => 'Abrir canal de comunicação para participantes recorrentes',
            'event' => 'whatsapp_click',
            'external_hint' => 'Abre WhatsApp Web ou aplicativo',
            'member_context' => 'Link disponível para membros e participantes regulares do grupo.',
        ],
    ];

    $items = [];

    foreach ($definitions as $key => $definition) {
        $value = $supportLinks[$key] ?? null;

        if (!is_string($value) || trim($value) === '') {
            continue;
        }

        $url = trim($value);

        if (!is_valid_support_link($url, $key)) {
            continue;
        }

        $items[] = [
            'key' => $key,
            'url' => $url,
            'label' => $definition['label'],
            'description' => $definition['description'],
            'external_hint' => $definition['external_hint'] ?? 'Abre recurso externo',
            'event' => $definition['event'],
            'member_context' => $definition['member_context'] ?? null,
        ];
    }

    return [
        'has_items' => $items !== [],
        'items' => $items,
    ];
}

/**
 * Valida um link complementar por categoria.
 * - directory: apenas o ecossistema oficial de NA (na.org.br / na.org);
 * - report: apenas o ecossistema oficial de NA ate que um dominio alternativo
 *   seja aprovado pela governanca editorial;
 * - whatsapp: apenas convites oficiais do WhatsApp (chat.whatsapp.com / wa.me).
 * Somente https, sem credenciais embutidas e sem porta alternativa.
 */
function is_valid_support_link(string $url, string $category): bool
{
    if (filter_var($url, FILTER_VALIDATE_URL) === false) {
        return false;
    }

    $parts = parse_url($url);

    if (!is_array($parts)) {
        return false;
    }

    $scheme = strtolower((string) ($parts['scheme'] ?? ''));
    $host = strtolower((string) ($parts['host'] ?? ''));

    if ($scheme !== 'https' || $host === '') {
        return false;
    }

    if (isset($parts['user']) || isset($parts['pass'])) {
        return false;
    }

    if (isset($parts['port']) && (int) $parts['port'] !== 443) {
        return false;
    }

    $allowedHosts = support_link_host_allowlist($category);

    return $allowedHosts !== [] && host_matches_allowlist($host, $allowedHosts);
}

/** @return list<string> */
function support_link_host_allowlist(string $category): array
{
    switch ($category) {
        case 'directory':
        case 'report':
            return ['na.org.br', 'na.org'];
        case 'whatsapp':
            return ['chat.whatsapp.com', 'wa.me'];
        default:
            return [];
    }
}

/**
 * Resolve o destino de um alvo de medicao a partir do contrato aprovado.
 * Nunca aceita URLs arbitrarias: apenas identificadores de recurso conhecidos.
 */
function resolve_tracked_target(array $contract, string $target): ?string
{
    $meeting = $contract['meeting'] ?? [];
    $supportLinks = $contract['support_links'] ?? [];

    if ($target === 'meeting') {
        $joinUrl = is_array($meeting) ? ($meeting['join_url'] ?? null) : null;

        return is_string($joinUrl) && trim($joinUrl) !== '' ? trim($joinUrl) : null;
    }

    if (!array_key_exists($target, ['directory' => true, 'report' => true, 'whatsapp' => true])) {
        return null;
    }

    $url = is_array($supportLinks) ? ($supportLinks[$target] ?? null) : null;

    if (!is_string($url) || trim($url) === '') {
        return null;
    }

    $url = trim($url);

    return is_valid_support_link($url, $target) ? $url : null;
}
