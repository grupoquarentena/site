<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/app/support/meeting_status.php';
require_once dirname(__DIR__) . '/app/support/meeting_contract.php';
require_once dirname(__DIR__) . '/app/support/meeting_presenter.php';
require_once dirname(__DIR__) . '/app/support/external_links.php';

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
$assertTrue(array_key_exists('status', $contract['meeting']), 'O contrato deve incluir status.');
$assertTrue(array_key_exists('status_override', $contract['meeting']), 'O contrato deve incluir status_override.');
$assertTrue(isset($contract['meeting']['updated_at']), 'O contrato deve incluir updated_at.');
$assertTrue(array_key_exists('directory', $contract['support_links']), 'O contrato deve incluir o link de diretório.');
 $assertTrue(is_file(dirname(__DIR__) . '/public/assets/css/home.css'), 'A home deve expor um CSS dedicado em public/assets/css.');

$validatedContract = validate_meeting_contract($contract);
$assertSame('America/Sao_Paulo', $validatedContract['timezone'], 'O contrato valido deve preservar o timezone oficial.');
$assertSame(null, $validatedContract['meeting']['status'], 'Sem override manual, o contrato validado deve permitir calculo automatico do status.');

$meetingDisplay = present_meeting_details(
    $validatedContract['meeting'],
    $validatedContract['timezone'],
    [
        'label' => 'mais tarde hoje',
        'slug' => 'mais-tarde-hoje',
    ]
);
$assertSame('mais tarde hoje', $meetingDisplay['status_label'], 'A apresentacao da reuniao deve preservar o rotulo canonico do status para exibicao.');
$assertSame('mais-tarde-hoje', $meetingDisplay['status_slug'], 'A apresentacao da reuniao deve propagar o slug do status.');
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

$nextMeetingStatus = resolve_meeting_status(
    [
        'starts_at' => '2026-03-25T19:30:00-03:00',
        'ends_at' => '2026-03-25T20:00:00-03:00',
        'status_override' => null,
    ],
    'America/Sao_Paulo',
    new DateTimeImmutable('2026-03-24T22:00:00-03:00')
);
$assertSame('próxima reunião', $nextMeetingStatus['label'], 'O cálculo automático deve cair para próxima reunião quando o proximo horario for em outro dia.');

$liveStatus = resolve_meeting_status(
    [
        'starts_at' => '2026-03-24T19:30:00-03:00',
        'ends_at' => '2026-03-24T20:00:00-03:00',
        'status_override' => null,
    ],
    'America/Sao_Paulo',
    new DateTimeImmutable('2026-03-24T19:45:00-03:00')
);
$assertSame('agora', $liveStatus['label'], 'O cálculo automático deve identificar quando a reuniao esta acontecendo agora.');

$manualFallbackStatus = resolve_meeting_status(
    [
        'starts_at' => null,
        'ends_at' => null,
        'status_override' => 'próxima reunião',
    ],
    'America/Sao_Paulo',
    new DateTimeImmutable('2026-03-24T09:00:00-03:00')
);
$assertSame('manual', $manualFallbackStatus['source'], 'Quando a agenda nao for suficiente, o fallback manual deve continuar valido.');

$supportSectionWithoutLinks = present_support_links([
    'whatsapp' => null,
    'report' => null,
    'directory' => null,
]);
$assertSame(false, $supportSectionWithoutLinks['has_items'], 'Sem links complementares validos, a secao secundaria deve poder ser ocultada.');
$assertSame([], $supportSectionWithoutLinks['items'], 'Sem links complementares validos, nenhum item deve ser renderizado.');

$supportSectionWithLinks = present_support_links([
    'whatsapp' => null,
    'report' => '',
    'directory' => 'https://www.na.org.br/virtual/',
]);
$assertSame(true, $supportSectionWithLinks['has_items'], 'Com ao menos um link complementar valido, a secao secundaria deve ser preparada.');
$assertSame('directory', $supportSectionWithLinks['items'][0]['key'], 'O helper de apoio deve preservar a chave do recurso complementar disponivel.');

$supportSectionWithInvalidLink = present_support_links([
    'whatsapp' => 'javascript:alert(1)',
    'report' => null,
    'directory' => null,
]);
$assertSame(false, $supportSectionWithInvalidLink['has_items'], 'Links complementares malformados devem ser ignorados para fail-soft.');

$viewData = [
    'support_section' => $supportSectionWithoutLinks,
    'home_content' => [
        'support_links' => [],
    ],
];
$escape = static fn (mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
ob_start();
require dirname(__DIR__) . '/app/views/partials/support_links.php';
$emptySupportHtml = trim((string) ob_get_clean());
$assertSame('', $emptySupportHtml, 'Sem links validos, a partial complementar deve se ocultar sem quebrar o fluxo principal.');

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

putenv('SITE_TEST_NOW=2026-03-24T19:45:00-03:00');
$viewData = require dirname(__DIR__) . '/app/bootstrap.php';
$assertTrue(isset($viewData['meeting_status']['label']), 'O bootstrap deve preparar o status da reunião.');
$assertSame($meetingDisplay['schedule_label'], $viewData['meeting_display']['schedule_label'], 'O bootstrap deve propagar o horario formatado do card principal.');
$assertSame('America/Sao_Paulo', $viewData['site']['timezone'], 'O bootstrap deve propagar o timezone do contrato.');
$assertSame('agora', $viewData['meeting_status']['label'], 'O bootstrap deve resolver o status automaticamente no timezone oficial.');
$assertTrue(isset($viewData['home_content']['hero']['cta_label']), 'O bootstrap deve carregar o conteudo da home.');
$assertSame(true, $viewData['support_section']['has_items'], 'O bootstrap deve preparar a secao complementar quando houver link aprovado.');

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
$assertTrue(str_contains($renderedHtml, $meetingDisplay['type_label']), 'O card deve exibir o tipo da reuniao.');
$assertSame(1, substr_count($renderedHtml, '>' . $meetingDisplay['meeting_id_label'] . '<'), 'O meeting ID nao deve ser duplicado fora do bloco principal.');
$assertSame(1, substr_count($renderedHtml, '>' . $meetingDisplay['password_label'] . '<'), 'A senha nao deve ser duplicada fora do bloco principal.');
$assertTrue(str_contains($renderedHtml, 'rel="noopener noreferrer"'), 'O CTA externo deve proteger a navegacao.');
$assertTrue(!str_contains($renderedHtml, '<script'), 'A home nao deve depender de JavaScript obrigatorio.');

// Story 1.3: Tipo e elegibilidade
$assertTrue(isset($meetingDisplay['type_description']), 'O presenter deve expor type_description.');
$assertTrue($meetingDisplay['type_description'] !== '', 'type_description nao deve ser vazio.');
$assertTrue(str_contains($renderedHtml, 'class="meeting-card__type-note"'), 'O card deve exibir a nota explicativa do tipo.');
$assertTrue(str_contains($renderedHtml, $meetingDisplay['type_description']), 'O card deve exibir o texto de elegibilidade do tipo.');

// Story 1.4: Status da reuniao
$assertTrue(str_contains($renderedHtml, '>Status</dt>'), 'O card deve rotular o status da reuniao.');
$assertTrue(str_contains($renderedHtml, 'class="meeting-status-pill meeting-status-pill--agora"'), 'O card deve exibir o estado atual com pill dedicada.');
$assertTrue(str_contains($renderedHtml, 'agora'), 'A home deve exibir exatamente um estado visivel da reuniao no bloco principal.');

// Story 1.5: Resiliencia a falhas externas
$assertTrue(str_contains($renderedHtml, 'class="support-links"'), 'A home deve renderizar uma secao complementar secundaria quando houver recurso externo aprovado.');
$assertTrue(str_contains($renderedHtml, 'Diretorio e outras reunioes'), 'A secao complementar deve expor o recurso externo disponivel sem competir com o CTA principal.');
$assertTrue(str_contains($renderedHtml, 'data-event="support_directory_click"'), 'A secao complementar deve sinalizar o evento agregado do link de apoio.');

// Story 2.1: Mensagem curta de acolhimento
$assertTrue(isset($viewData['home_content']['hero']['welcome_message']), 'O bootstrap deve expor a mensagem curta de acolhimento.');
$assertTrue(str_contains($renderedHtml, 'class="hero__welcome"'), 'A home deve renderizar um bloco curto de acolhimento perto do CTA principal.');
$assertTrue(str_contains($renderedHtml, $viewData['home_content']['hero']['welcome_message']), 'A copy acolhedora deve vir do arquivo de conteudo da home.');

// Story 2.2: Bloco O que e NA
$assertTrue(isset($viewData['home_content']['about_na']['body']), 'O bootstrap deve expor a copy do bloco O que e NA.');
$assertTrue(strlen($viewData['home_content']['about_na']['body']) < 180, 'A copy do bloco O que e NA deve permanecer curta.');
$assertTrue(!str_contains(strtolower($viewData['home_content']['about_na']['body']), 'garantia'), 'O bloco O que e NA nao deve prometer resultados indevidos.');
$assertTrue(str_contains($renderedHtml, 'class="info-section"'), 'A home deve renderizar um bloco institucional curto abaixo da dobra.');
$assertTrue(str_contains($renderedHtml, $viewData['home_content']['about_na']['title']), 'O bloco O que e NA deve consumir o titulo vindo do conteudo da home.');
$assertTrue(str_contains($renderedHtml, $viewData['home_content']['about_na']['body']), 'O bloco O que e NA deve consumir a copy curta vinda do conteudo da home.');

// Story 2.3: Sobre o Grupo QuarenteNA
$assertTrue(isset($viewData['home_content']['about_group']['body']), 'O bootstrap deve expor a copy do bloco sobre o grupo.');
$assertTrue(str_contains(strtolower($viewData['home_content']['about_group']['body']), 'reunioes'), 'O bloco sobre o grupo deve comunicar frequencia regular.');
$assertTrue(str_contains(strtolower($viewData['home_content']['about_group']['body']), 'narcoticos anonimos'), 'O bloco sobre o grupo deve explicar a relacao com NA.');
$assertTrue(!str_contains($viewData['home_content']['about_group']['body'], 'Ciro'), 'O bloco sobre o grupo nao deve expor identidades pessoais.');
$assertTrue(str_contains($renderedHtml, 'info-section info-section--group'), 'A home deve renderizar o bloco sobre o grupo abaixo do contexto inicial sobre NA.');
$assertTrue(str_contains($renderedHtml, $viewData['home_content']['about_group']['title']), 'O bloco sobre o grupo deve consumir o titulo vindo do conteudo da home.');
$assertTrue(str_contains($renderedHtml, $viewData['home_content']['about_group']['body']), 'O bloco sobre o grupo deve consumir a copy aprovada da home.');

$viewData = array_merge($viewData, [
    'support_section' => $supportSectionWithoutLinks,
]);
ob_start();
require dirname(__DIR__) . '/app/views/home.php';
$renderedHtmlWithoutSupport = (string) ob_get_clean();
$assertTrue(str_contains($renderedHtmlWithoutSupport, 'class="hero__cta"'), 'Mesmo sem links externos, o CTA principal deve continuar renderizado.');
$assertTrue(str_contains($renderedHtmlWithoutSupport, 'class="meeting-card"'), 'Mesmo sem links externos, o card principal deve continuar renderizado.');
$assertTrue(!str_contains($renderedHtmlWithoutSupport, 'class="support-links"'), 'Sem links externos validos, a secao complementar deve desaparecer sem quebrar a home.');

$css = (string) file_get_contents(dirname(__DIR__) . '/public/assets/css/home.css');
$assertTrue(str_contains($css, '--color-bg-deep'), 'O CSS da home deve declarar tokens visuais do MVP.');
$assertTrue(str_contains($css, '--color-accent'), 'O CSS da home deve declarar o acento amarelo do CTA.');
$assertTrue(str_contains($css, '.skip-link:focus-visible'), 'O CSS da home deve estilizar o estado de foco do skip link.');
$assertTrue(str_contains($css, '.meeting-card__item'), 'O CSS da home deve estilizar o card de dados da reuniao.');
$assertTrue(str_contains($css, '.meeting-status-pill'), 'O CSS da home deve estilizar a pill de status da reuniao.');
$assertTrue(str_contains($css, '.support-links__link'), 'O CSS da home deve estilizar a secao complementar de apoio.');
$assertTrue(str_contains($css, '.hero__welcome'), 'O CSS da home deve estilizar o bloco curto de acolhimento.');
$assertTrue(str_contains($css, '.info-section'), 'O CSS da home deve estilizar o bloco O que e NA.');
$assertTrue(str_contains($css, '.info-section--group'), 'O CSS da home deve estilizar o bloco sobre o grupo.');

if ($failures > 0) {
    exit(1);
}

fwrite(STDOUT, "PASS: " . (string) $assertions . " verificacoes\n");
