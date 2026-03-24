<?php

declare(strict_types=1);

require_once __DIR__ . '/support/meeting_status.php';
require_once __DIR__ . '/support/meeting_contract.php';
require_once __DIR__ . '/support/meeting_presenter.php';

$contract = validate_meeting_contract(require __DIR__ . '/data/meeting.php');
$homeContent = require __DIR__ . '/content/home.php';

if (!is_array($contract)) {
    throw new RuntimeException('O contrato da reunião deve retornar um array.');
}

if (!is_array($homeContent)) {
    throw new RuntimeException('O conteudo da home deve retornar um array.');
}

$timezone = (string) $contract['timezone'];
$meeting = $contract['meeting'];
$supportLinks = $contract['support_links'];

$referenceNow = null;
$testNow = getenv('SITE_TEST_NOW');

if (is_string($testNow) && trim($testNow) !== '') {
    try {
        $referenceNow = new DateTimeImmutable($testNow, new DateTimeZone($timezone));
    } catch (Throwable) {
        $referenceNow = null;
    }
}

$status = resolve_meeting_status($meeting, $timezone, $referenceNow);
$meetingDisplay = present_meeting_details($meeting, $timezone, $status);

return [
    'site' => [
        'name' => 'Grupo QuarenteNA de Narcóticos Anônimos',
        'locale' => 'pt-BR',
        'timezone' => $timezone,
    ],
    'meeting' => $meeting,
    'meeting_display' => $meetingDisplay,
    'meeting_status' => $status,
    'support_links' => $supportLinks,
    'home_content' => $homeContent,
];
