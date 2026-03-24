<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/app/support/meeting_status.php';
require_once dirname(__DIR__) . '/app/support/meeting_contract.php';
require_once dirname(__DIR__) . '/app/support/meeting_presenter.php';

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
 $assertTrue(is_file(dirname(__DIR__) . '/public/assets/css/home.css'), 'A home deve expor um CSS dedicado em public/assets/css.');

$validatedContract = validate_meeting_contract($contract);
$assertSame('America/Sao_Paulo', $validatedContract['timezone'], 'O contrato valido deve preservar o timezone oficial.');
$assertSame('próxima reunião', $validatedContract['meeting']['status'], 'O contrato validado deve preservar o status manual seedado.');

$meetingDisplay = present_meeting_details($validatedContract['meeting'], $validatedContract['timezone']);
$assertTrue($meetingDisplay['schedule_label'] !== '', 'A apresentacao da reuniao deve formatar o horario em um unico rotulo legivel.');
$assertSame($validatedContract['meeting']['meeting_id'], $meetingDisplay['meeting_id_label'], 'A apresentacao da reuniao deve expor o ID vindo da fonte unica.');
$assertSame($validatedContract['meeting']['password'], $meetingDisplay['password_label'], 'A apresentacao da reuniao deve expor a senha vinda da fonte unica.');
$assertSame($validatedContract['meeting']['type'] === 'aberta' ? 'Aberta' : 'Fechada', $meetingDisplay['type_label'], 'A apresentacao da reuniao deve normalizar o tipo para exibicao.');

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
$assertSame($meetingDisplay['schedule_label'], $viewData['meeting_display']['schedule_label'], 'O bootstrap deve propagar o horario formatado do card principal.');
$assertSame('America/Sao_Paulo', $viewData['site']['timezone'], 'O bootstrap deve propagar o timezone do contrato.');
$assertSame('próxima reunião', $viewData['meeting_status']['label'], 'O bootstrap deve respeitar o status manual seedado.');
$assertTrue(isset($viewData['home_content']['hero']['cta_label']), 'O bootstrap deve carregar o conteudo da home.');

ob_start();
require dirname(__DIR__) . '/public/index.php';
$renderedHtml = (string) ob_get_clean();
$assertTrue(str_contains($renderedHtml, '<h1 id="hero-title" class="hero__title">' . $contract['meeting']['title'] . '</h1>'), 'O h1 principal da home deve consumir meeting.title da fonte unica.');
$assertTrue(str_contains($renderedHtml, 'class="skip-link"'), 'A home deve incluir skip link visivel ao foco.');
$assertTrue(str_contains($renderedHtml, 'href="assets/css/home.css"'), 'A home deve carregar o CSS dedicado.');
$assertTrue(str_contains($renderedHtml, 'id="main-content"'), 'A home deve expor landmark principal com id navegavel.');
$assertSame(1, substr_count($renderedHtml, 'class="hero__cta"'), 'A home deve renderizar exatamente um CTA primario.');
$assertTrue(str_contains($renderedHtml, 'href="' . $contract['meeting']['join_url'] . '"'), 'O CTA deve consumir o join_url vindo da fonte unica.');
$assertTrue(str_contains($renderedHtml, $contract['meeting']['title']), 'O CTA e a home devem expor o titulo vindo da fonte unica.');
$assertTrue(str_contains($renderedHtml, 'class="meeting-card"'), 'A home deve renderizar um card dedicado para os dados da reuniao.');
$assertTrue(str_contains($renderedHtml, 'aria-labelledby="meeting-card-title"'), 'O card deve expor uma relacao semantica clara com seu titulo.');
$assertTrue(str_contains($renderedHtml, '>Horario</dt>'), 'O card deve rotular o horario da reuniao.');
$assertTrue(str_contains($renderedHtml, '>' . $meetingDisplay['schedule_label'] . '</dd>'), 'O card deve exibir o horario formatado no mesmo bloco visual.');
$assertTrue(str_contains($renderedHtml, '>' . $meetingDisplay['meeting_id_label'] . '</dd>'), 'O card deve exibir o meeting ID vindo da fonte unica.');
$assertTrue(str_contains($renderedHtml, '>' . $meetingDisplay['password_label'] . '</dd>'), 'O card deve exibir a senha vinda da fonte unica.');
$assertTrue(str_contains($renderedHtml, '>' . $meetingDisplay['type_label'] . '</dd>'), 'O card deve exibir o tipo da reuniao.');
$assertSame(1, substr_count($renderedHtml, '>' . $meetingDisplay['meeting_id_label'] . '<'), 'O meeting ID nao deve ser duplicado fora do bloco principal.');
$assertSame(1, substr_count($renderedHtml, '>' . $meetingDisplay['password_label'] . '<'), 'A senha nao deve ser duplicada fora do bloco principal.');
$assertTrue(str_contains($renderedHtml, 'rel="noopener noreferrer"'), 'O CTA externo deve proteger a navegacao.');
$assertTrue(!str_contains($renderedHtml, '<script'), 'A home nao deve depender de JavaScript obrigatorio.');

$css = (string) file_get_contents(dirname(__DIR__) . '/public/assets/css/home.css');
$assertTrue(str_contains($css, '--color-bg-deep'), 'O CSS da home deve declarar tokens visuais do MVP.');
$assertTrue(str_contains($css, '--color-accent'), 'O CSS da home deve declarar o acento amarelo do CTA.');
$assertTrue(str_contains($css, '.skip-link:focus-visible'), 'O CSS da home deve estilizar o estado de foco do skip link.');
$assertTrue(str_contains($css, '.meeting-card__item'), 'O CSS da home deve estilizar o card de dados da reuniao.');

if ($failures > 0) {
    exit(1);
}

fwrite(STDOUT, "PASS: " . (string) $assertions . " verificacoes\n");
