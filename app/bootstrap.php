<?php

declare(strict_types=1);

require_once __DIR__ . '/support/meeting_status.php';
require_once __DIR__ . '/support/meeting_contract.php';

$contract = validate_meeting_contract(require __DIR__ . '/data/meeting.php');

if (!is_array($contract)) {
    throw new RuntimeException('O contrato da reunião deve retornar um array.');
}

$timezone = (string) $contract['timezone'];
$meeting = $contract['meeting'];
$supportLinks = $contract['support_links'];

$status = resolve_meeting_status($meeting, $timezone);

return [
    'site' => [
        'name' => 'Grupo QuarenteNA de Narcóticos Anônimos',
        'locale' => 'pt-BR',
        'timezone' => $timezone,
    ],
    'meeting' => $meeting,
    'meeting_status' => $status,
    'support_links' => $supportLinks,
];
