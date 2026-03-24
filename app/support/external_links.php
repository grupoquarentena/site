<?php

declare(strict_types=1);

function present_support_links(array $supportLinks): array
{
    $definitions = [
        'directory' => [
            'label' => 'Diretorio e outras reunioes',
            'description' => 'Abrir recurso complementar de apoio',
            'event' => 'support_directory_click',
        ],
        'report' => [
            'label' => 'Relatorio publico',
            'description' => 'Abrir recurso complementar do grupo',
            'event' => 'report_click',
        ],
        'whatsapp' => [
            'label' => 'WhatsApp de membros',
            'description' => 'Abrir recurso complementar para membros',
            'event' => 'whatsapp_click',
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
            'event' => $definition['event'],
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
