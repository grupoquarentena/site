<?php

declare(strict_types=1);

require_once __DIR__ . '/external_links.php';

/** @return list<string> */
function valid_track_events(): array
{
    return ['meeting_cta_click', 'whatsapp_click', 'report_click', 'support_directory_click'];
}

function is_valid_track_event(string $event): bool
{
    return in_array($event, valid_track_events(), true);
}

function analytics_storage_file(): string
{
    return dirname(__DIR__, 2) . '/tmp/analytics.json';
}

/**
 * Incrementa um contador agregado e anonimo (sem identificadores pessoais).
 * Leitura, incremento e escrita acontecem sob o mesmo flock() exclusivo,
 * de modo que requisicoes concorrentes nao percam cliques.
 */
function track_event(string $event): void
{
    if (!is_valid_track_event($event)) {
        return;
    }

    $storageFile = analytics_storage_file();
    $dir = dirname($storageFile);

    if (!is_dir($dir) && !@mkdir($dir, 0755, true) && !is_dir($dir)) {
        return;
    }

    $handle = @fopen($storageFile, 'c+');

    if ($handle === false) {
        return;
    }

    try {
        if (!flock($handle, LOCK_EX)) {
            return;
        }

        rewind($handle);
        $raw = stream_get_contents($handle);
        $data = [];

        if (is_string($raw) && $raw !== '') {
            $decoded = json_decode($raw, true);

            if (is_array($decoded)) {
                $data = $decoded;
            }
        }

        $data[$event] = ((int) ($data[$event] ?? 0)) + 1;

        if (!isset($data['_first_event_at'])) {
            $data['_first_event_at'] = date('c');
        }

        $data['_last_event_at'] = date('c');

        $encoded = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        if (is_string($encoded)) {
            ftruncate($handle, 0);
            rewind($handle);
            fwrite($handle, $encoded . "\n");
            fflush($handle);
        }

        flock($handle, LOCK_UN);
    } finally {
        fclose($handle);
    }
}

/**
 * Monta a URL de medicao por identificador de recurso.
 * O destino real e resolvido em track.php a partir do contrato; nenhuma URL
 * arbitraria trafega pela pagina.
 */
function tracked_url(string $target, string $event): string
{
    return 'track.php?event=' . rawurlencode($event) . '&target=' . rawurlencode($target);
}

/**
 * Valida a requisicao de medicao por completo antes de registrar qualquer
 * evento: evento conhecido, alvo conhecido e destino resolvido pelo contrato.
 */
function resolve_track_request(array $contract, string $event, string $target): ?string
{
    if (!is_valid_track_event($event)) {
        return null;
    }

    $destination = resolve_tracked_target($contract, $target);

    if ($destination === null || filter_var($destination, FILTER_VALIDATE_URL) === false) {
        return null;
    }

    return $destination;
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

/**
 * Avalia o checklist apenas com as evidencias informadas pelo chamador.
 * Sem evidencia explicita, o item fica 'pending' — nenhum estado e aprovado
 * por constante. O MVP historico nunca foi publicado em producao.
 *
 * @param array<string, mixed> $evidence Mapa id do item => true/'ok' | false/'fail'
 */
function validate_launch_readiness(array $evidence = []): array
{
    $checks = launch_checklist()['items'];
    $results = [];

    foreach ($checks as $check) {
        $state = $evidence[$check['id']] ?? null;

        if ($state === true || $state === 'ok') {
            $status = 'ok';
        } elseif ($state === false || $state === 'fail') {
            $status = 'fail';
        } else {
            $status = 'pending';
        }

        $results[] = [
            'id' => $check['id'],
            'item' => $check['item'],
            'category' => $check['category'],
            'status' => $status,
        ];
    }

    $statuses = array_column($results, 'status');
    $okCount = count(array_filter($statuses, static fn (string $status): bool => $status === 'ok'));
    $failCount = count(array_filter($statuses, static fn (string $status): bool => $status === 'fail'));
    $pendingCount = count(array_filter($statuses, static fn (string $status): bool => $status === 'pending'));

    return [
        'ready' => $results !== [] && $failCount === 0 && $pendingCount === 0 && $okCount === count($results),
        'total' => count($results),
        'ok' => $okCount,
        'fail' => $failCount,
        'pending' => $pendingCount,
        'note' => 'O MVP historico nunca foi publicado em producao; o resultado reflete apenas as evidencias informadas nesta chamada.',
        'items' => $results,
    ];
}
