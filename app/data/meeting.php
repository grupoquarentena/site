<?php

declare(strict_types=1);

// Fonte unica de dados do MVP historico (marco-maio/2026).
// Arquivamento (2026-10-18): os dados operacionais da reuniao (link, ID e senha)
// foram removidos. Os campos nulos representam, de forma explicita, a ausencia
// de agenda ativa; este arquivo permanece apenas como referencia historica e
// nao deve ser reutilizado para publicar uma reuniao sem nova revisao de
// contrato, testes e copy publica.
return [
    'timezone' => 'America/Sao_Paulo',
    'meeting' => [
        'title' => 'Reunião Online do Grupo QuarenteNA',
        'join_url' => null,
        'meeting_id' => null,
        'password' => null,
        'type' => 'fechada',
        'starts_at' => null,
        'ends_at' => null,
        'status' => 'sem agenda ativa',
        'status_override' => 'sem agenda ativa',
        'updated_at' => '2026-10-18 BRT',
    ],
    'support_links' => [
        'whatsapp' => null,
        'report' => null,
        'directory' => 'https://www.na.org.br/virtual/',
    ],
];
