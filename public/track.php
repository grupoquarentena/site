<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/app/support/analytics.php';
require_once dirname(__DIR__) . '/app/support/external_links.php';

$event = is_string($_GET['event'] ?? null) ? $_GET['event'] : '';
$target = is_string($_GET['target'] ?? null) ? $_GET['target'] : '';

// O destino nunca vem do visitante: ele e resolvido pelo contrato aprovado
// a partir de um identificador de recurso conhecido (meeting, directory,
// report ou whatsapp). Parametros invalidos sao rejeitados sem medicao.
try {
    $contract = validate_meeting_contract(require dirname(__DIR__) . '/app/data/meeting.php');
} catch (Throwable) {
    http_response_code(500);
    exit;
}

$destination = resolve_track_request($contract, $event, $target);

if ($destination === null) {
    http_response_code(400);
    exit;
}

track_event($event);

header('Location: ' . $destination, true, 302);
exit;
