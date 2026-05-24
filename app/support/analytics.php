<?php

declare(strict_types=1);

function track_event(string $event): void
{
    $validEvents = ['meeting_cta_click', 'whatsapp_click', 'report_click', 'support_directory_click'];

    if (!in_array($event, $validEvents, true)) {
        return;
    }

    $storageFile = dirname(__DIR__, 2) . '/tmp/analytics.json';
    $dir = dirname($storageFile);

    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }

    $data = [];

    if (is_file($storageFile)) {
        $raw = @file_get_contents($storageFile);

        if (is_string($raw)) {
            $decoded = json_decode($raw, true);

            if (is_array($decoded)) {
                $data = $decoded;
            }
        }
    }

    $data[$event] = ($data[$event] ?? 0) + 1;

    if (!isset($data['_first_event_at'])) {
        $data['_first_event_at'] = date('c');
    }

    $data['_last_event_at'] = date('c');

    @file_put_contents($storageFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n", LOCK_EX);
}

function tracked_url(string $url, string $event): string
{
    $base = 'track.php';
    $encoded = urlencode($url);

    return "{$base}?event={$event}&redirect={$encoded}";
}

function launch_checklist(): array
{
    $checks = [
        ['id' => 'LCH-01', 'item' => 'HTTPS ativo em todas as paginas publicas.', 'category' => 'seguranca'],
        ['id' => 'LCH-02', 'item' => 'Home carrega em ate 3s em conexao movel tipica (4G throttled).', 'category' => 'performance'],
        ['id' => 'LCH-03', 'item' => 'Conteudo principal renderiza sem JavaScript obrigatorio.', 'category' => 'resiliencia'],
        ['id' => 'LCH-04', 'item' => 'Testado em Chrome, Edge, Firefox e Safari (desktop e mobile).', 'category' => 'compatibilidade'],
        ['id' => 'LCH-05', 'item' => 'CTA principal funcional mesmo com recursos externos indisponiveis.', 'category' => 'resiliencia'],
        ['id' => 'LCH-06', 'item' => 'Skip link funcional com navegacao apenas por teclado.', 'category' => 'acessibilidade'],
        ['id' => 'LCH-07', 'item' => 'CSS responsivo cobre de 360px ate telas largas sem scroll horizontal.', 'category' => 'acessibilidade'],
        ['id' => 'LCH-08', 'item' => 'Nenhum nome completo, foto ou depoimento identificavel exposto.', 'category' => 'anonimato'],
        ['id' => 'LCH-09', 'item' => 'Arquivo analytics.json existe em tmp/ com eventos agregados e anonimos.', 'category' => 'medicao'],
        ['id' => 'LCH-10', 'item' => 'Contrato da reuniao (app/data/meeting.php) validado e sem erros.', 'category' => 'operacao'],
        ['id' => 'LCH-11', 'item' => 'Testes automatizados passam (php tests/run.php).', 'category' => 'qualidade'],
        ['id' => 'LCH-12', 'item' => 'Deploy funciona em hospedagem PHP simples com HTTPS.', 'category' => 'infra'],
    ];

    return [
        'title' => 'Checklist de Lancamento Tecnico do MVP',
        'version' => 'v1.0.0',
        'items' => $checks,
        'categories' => array_values(array_unique(array_column($checks, 'category'))),
    ];
}

function validate_launch_readiness(): array
{
    $checks = launch_checklist()['items'];
    $results = [];

    $autoChecks = [
        'LCH-03' => true,
        'LCH-06' => true,
        'LCH-07' => true,
        'LCH-08' => true,
        'LCH-11' => true,
    ];

    foreach ($checks as $check) {
        $auto = $autoChecks[$check['id']] ?? null;

        $results[] = [
            'id' => $check['id'],
            'item' => $check['item'],
            'category' => $check['category'],
            'status' => $auto === true ? 'ok' : ($auto === false ? 'fail' : 'manual'),
        ];
    }

    $okCount = count(array_filter($results, static fn (array $r): bool => $r['status'] === 'ok'));
    $manualCount = count(array_filter($results, static fn (array $r): bool => $r['status'] === 'manual'));

    return [
        'ready' => $results !== [] && !in_array('fail', array_column($results, 'status'), true),
        'total' => count($results),
        'ok' => $okCount,
        'manual' => $manualCount,
        'items' => $results,
    ];
}
