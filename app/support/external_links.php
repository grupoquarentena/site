<?php

declare(strict_types=1);

function present_support_links(array $supportLinks): array
{
    $definitions = [
        'directory' => [
            'label' => 'Diretorio e outras reunioes',
            'description' => 'Abrir recurso complementar de apoio',
            'event' => 'support_directory_click',
            'external_hint' => 'Abre recurso externo do ecossistema NA',
        ],
        'report' => [
            'label' => 'Relatorio publico',
            'description' => 'Abrir relatorio publico aprovado pelo grupo',
            'event' => 'report_click',
            'external_hint' => 'Abre relatorio em ferramenta externa',
        ],
        'whatsapp' => [
            'label' => 'WhatsApp de membros',
            'description' => 'Abrir canal de comunicacao para participantes recorrentes',
            'event' => 'whatsapp_click',
            'external_hint' => 'Abre WhatsApp Web ou aplicativo',
            'member_context' => 'Link disponivel para membros e participantes regulares do grupo.',
        ],
    ];

    $items = [];

    foreach ($definitions as $key => $definition) {
        $value = $supportLinks[$key] ?? null;

        if (!is_string($value) || trim($value) === '') {
            continue;
        }

        $url = trim($value);

        if (!is_valid_support_link($url)) {
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

function is_valid_support_link(string $url): bool
{
    if (filter_var($url, FILTER_VALIDATE_URL) === false) {
        return false;
    }

    $scheme = parse_url($url, PHP_URL_SCHEME);

    return in_array($scheme, ['http', 'https'], true);
}
