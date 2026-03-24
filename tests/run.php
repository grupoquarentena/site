<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/app/support/meeting_status.php';
require_once dirname(__DIR__) . '/app/support/meeting_contract.php';

$failures = 0;
$assertions = 0;

$assertTrue = static function (bool $condition, string $message) use (&$failures, &$assertions): void {
    $assertions++;

    if ($condition) {
        return;
    }

    $failures++;
    fwrite(STDERR, "FAIL: {$message}\n");
};

$assertSame = static function (mixed $expected, mixed $actual, string $message) use (&$assertTrue): void {
    $assertTrue($expected === $actual, $message . ' (expected ' . var_export($expected, true) . ', got ' . var_export($actual, true) . ')');
};

$contract = require dirname(__DIR__) . '/app/data/meeting.php';
$assertTrue(is_array($contract), 'O contrato deve retornar um array.');
$assertSame(
    ['timezone', 'meeting', 'support_links'],
    array_keys($contract),
    'O contrato deve expor apenas as chaves principais esperadas.'
);

$assertTrue(isset($contract['meeting']['join_url']), 'O contrato deve incluir o link principal da reunião.');
$assertTrue(isset($contract['meeting']['title']), 'O contrato deve incluir o titulo da reuniao.');
$assertTrue(isset($contract['meeting']['meeting_id']), 'O contrato deve incluir o ID da reunião.');
$assertTrue(isset($contract['meeting']['password']), 'O contrato deve incluir a senha da reunião.');
$assertTrue(isset($contract['meeting']['type']), 'O contrato deve incluir o tipo da reunião.');
$assertTrue(isset($contract['meeting']['starts_at']), 'O contrato deve incluir starts_at.');
$assertTrue(isset($contract['meeting']['ends_at']), 'O contrato deve incluir ends_at.');
$assertTrue(isset($contract['meeting']['status']), 'O contrato deve incluir status.');
$assertTrue(array_key_exists('status_override', $contract['meeting']), 'O contrato deve incluir status_override.');
$assertTrue(isset($contract['meeting']['updated_at']), 'O contrato deve incluir updated_at.');
$assertTrue(array_key_exists('directory', $contract['support_links']), 'O contrato deve incluir o link de diretório.');

$validatedContract = validate_meeting_contract($contract);
$assertSame('America/Sao_Paulo', $validatedContract['timezone'], 'O contrato valido deve preservar o timezone oficial.');
$assertSame('próxima reunião', $validatedContract['meeting']['status'], 'O contrato validado deve preservar o status manual seedado.');

$manualStatus = resolve_meeting_status(
    ['status_override' => 'agora'],
    'America/Sao_Paulo',
    new DateTimeImmutable('2026-03-24T09:00:00-03:00')
);
$assertSame('agora', $manualStatus['label'], 'O status manual deve ter precedência.');

$invalidManualStatus = resolve_meeting_status(
    [
        'status_override' => 'amanhã',
        'starts_at' => '2026-03-24T19:30:00-03:00',
        'ends_at' => '2026-03-24T20:00:00-03:00',
    ],
    'America/Sao_Paulo',
    new DateTimeImmutable('2026-03-24T10:00:00-03:00')
);
$assertSame('mais tarde hoje', $invalidManualStatus['label'], 'Status manual inválido deve cair no cálculo automático.');

$automaticStatus = resolve_meeting_status(
    [
        'starts_at' => '2026-03-24T22:30:00+00:00',
        'ends_at' => '2026-03-24T23:00:00+00:00',
        'status_override' => null,
    ],
    'America/Sao_Paulo',
    new DateTimeImmutable('2026-03-24T10:00:00-03:00')
);
$assertSame('mais tarde hoje', $automaticStatus['label'], 'O cálculo automático deve normalizar offsets para o timezone oficial.');

try {
    validate_meeting_contract([
        'timezone' => 'America/Sao_Paoloo',
        'meeting' => $contract['meeting'],
        'support_links' => $contract['support_links'],
    ]);
    $assertTrue(false, 'Timezone invalido deve disparar excecao.');
} catch (RuntimeException) {
    $assertTrue(true, 'Timezone invalido deve disparar excecao.');
}

try {
    validate_meeting_contract([
        'timezone' => 'America/Sao_Paulo',
        'meeting' => array_merge($contract['meeting'], [
            'starts_at' => '2026-03-24T19:30:00-03:00',
            'ends_at' => null,
            'status' => null,
            'status_override' => null,
        ]),
        'support_links' => $contract['support_links'],
    ]);
    $assertTrue(false, 'Agenda parcial sem override deve disparar excecao.');
} catch (RuntimeException) {
    $assertTrue(true, 'Agenda parcial sem override deve disparar excecao.');
}

$viewData = require dirname(__DIR__) . '/app/bootstrap.php';
$assertTrue(isset($viewData['meeting_status']['label']), 'O bootstrap deve preparar o status da reunião.');
$assertSame('America/Sao_Paulo', $viewData['site']['timezone'], 'O bootstrap deve propagar o timezone do contrato.');
$assertSame('próxima reunião', $viewData['meeting_status']['label'], 'O bootstrap deve respeitar o status manual seedado.');

ob_start();
require dirname(__DIR__) . '/public/index.php';
$renderedHtml = (string) ob_get_clean();
$assertTrue(str_contains($renderedHtml, '<h1>Reunião Online do Grupo QuarenteNA</h1>'), 'O smoke test deve renderizar a home pelo entrypoint público.');

if ($failures > 0) {
    exit(1);
}

fwrite(STDOUT, "PASS: " . (string) $assertions . " verificacoes\n");
