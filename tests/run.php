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
$assertTrue(str_contains($renderedHtml, '>Horário</dt>'), 'O card deve rotular o horario da reuniao.');
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
$assertTrue(str_contains($renderedHtml, 'Diretório e outras reuniões'), 'A secao complementar deve expor o recurso externo disponivel sem competir com o CTA principal.');
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
$assertTrue(str_contains(strtolower($viewData['home_content']['about_group']['body']), 'reuniões'), 'O bloco sobre o grupo deve comunicar frequencia regular.');
$assertTrue(str_contains(strtolower($viewData['home_content']['about_group']['body']), 'narcóticos anônimos'), 'O bloco sobre o grupo deve explicar a relacao com NA.');
$assertTrue(!str_contains($viewData['home_content']['about_group']['body'], 'Ciro'), 'O bloco sobre o grupo nao deve expor identidades pessoais.');
$assertTrue(str_contains($renderedHtml, 'info-section info-section--group'), 'A home deve renderizar o bloco sobre o grupo abaixo do contexto inicial sobre NA.');
$assertTrue(str_contains($renderedHtml, $viewData['home_content']['about_group']['title']), 'O bloco sobre o grupo deve consumir o titulo vindo do conteudo da home.');
$assertTrue(str_contains($renderedHtml, $viewData['home_content']['about_group']['body']), 'O bloco sobre o grupo deve consumir a copy aprovada da home.');

// Story 2.4: Explicacao aberta versus fechada
$assertTrue(isset($viewData['home_content']['meeting_types']['open_body']), 'O bootstrap deve expor a copy de reuniao aberta.');
$assertTrue(isset($viewData['home_content']['meeting_types']['closed_body']), 'O bootstrap deve expor a copy de reuniao fechada.');
$assertTrue(str_contains($renderedHtml, 'info-section info-section--types'), 'A home deve renderizar um bloco dedicado para explicar aberta versus fechada.');
$assertTrue(str_contains($renderedHtml, $viewData['home_content']['meeting_types']['open_title']), 'A explicacao de reuniao aberta deve vir do conteudo da home.');
$assertTrue(str_contains($renderedHtml, $viewData['home_content']['meeting_types']['closed_title']), 'A explicacao de reuniao fechada deve vir do conteudo da home.');
$assertTrue(str_contains($renderedHtml, $viewData['home_content']['meeting_types']['open_body']), 'A explicacao de reuniao aberta deve expor a descricao publica aprovada.');
$assertTrue(str_contains($renderedHtml, $viewData['home_content']['meeting_types']['closed_body']), 'A explicacao de reuniao fechada deve expor a descricao publica aprovada.');
$assertTrue(!str_contains(strtolower($viewData['home_content']['meeting_types']['open_body']), 'nome completo'), 'A copy de reuniao aberta deve permanecer compativel com as regras publicas.');
$assertTrue(!str_contains(strtolower($viewData['home_content']['meeting_types']['closed_body']), 'nome completo'), 'A copy de reuniao fechada deve permanecer compativel com as regras publicas.');

// Story 2.5: Historia resumida do grupo
$assertTrue(isset($viewData['home_content']['group_history']['body']), 'O bootstrap deve expor a copy do bloco de historia.');
$assertTrue(str_contains(strtolower($viewData['home_content']['group_history']['body']), 'quarentena'), 'O bloco de historia deve mencionar a origem do nome QuarenteNA.');
$assertTrue(!str_contains($viewData['home_content']['group_history']['body'], 'Ciro'), 'O bloco de historia nao deve expor identidades pessoais.');
$assertTrue(str_contains($renderedHtml, 'info-section info-section--history'), 'A home deve renderizar o bloco de historia.');
$assertTrue(str_contains($renderedHtml, $viewData['home_content']['group_history']['title']), 'O bloco de historia deve consumir o titulo vindo do conteudo da home.');
$assertTrue(str_contains($renderedHtml, $viewData['home_content']['group_history']['body']), 'O bloco de historia deve consumir a copy aprovada da home.');
$assertTrue(str_contains($renderedHtml, 'aria-labelledby="group-history-title"'), 'O bloco de historia deve expor uma relacao semantica clara com seu titulo.');

$heroPosition = strpos($renderedHtml, 'class="hero"');
$meetingTypesPosition = strpos($renderedHtml, 'info-section info-section--types');
$aboutNaPosition = strpos($renderedHtml, 'info-section" aria-labelledby="about-na-title"');
$aboutGroupPosition = strpos($renderedHtml, 'info-section info-section--group');
$assertTrue($heroPosition !== false, 'A home deve renderizar o bloco hero.');
$assertTrue($meetingTypesPosition !== false, 'A home deve renderizar o bloco aberta versus fechada.');
$assertTrue($aboutNaPosition !== false, 'A home deve renderizar o bloco O que e NA.');
$assertTrue($aboutGroupPosition !== false, 'A home deve renderizar o bloco sobre o grupo.');
$assertTrue($heroPosition < $meetingTypesPosition, 'O bloco aberta versus fechada deve aparecer depois do hero principal.');
$assertTrue($meetingTypesPosition < $aboutNaPosition, 'O bloco aberta versus fechada deve aparecer antes do bloco O que e NA.');
$assertTrue($meetingTypesPosition < $aboutGroupPosition, 'O bloco aberta versus fechada deve aparecer antes do bloco sobre o grupo.');

$groupHistoryPosition = strpos($renderedHtml, 'info-section info-section--history');
$assertTrue($groupHistoryPosition !== false, 'A home deve renderizar o bloco de historia.');
$assertTrue($aboutGroupPosition < $groupHistoryPosition, 'O bloco de historia deve aparecer depois do bloco sobre o grupo.');

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
$assertTrue(str_contains($css, '.meeting-types-grid'), 'O CSS da home deve estilizar o bloco aberta versus fechada.');
$assertTrue(str_contains($css, '.info-section--history'), 'O CSS da home deve estilizar o bloco de historia do grupo.');

require_once dirname(__DIR__) . '/app/support/editorial_governance.php';

// Story 4.2: Fluxo Manual de Atualizacao Operacional
$updateGuide = operational_update_guide();
$assertSame('Guia de Atualizacao Operacional', $updateGuide['title'], 'O guia de atualizacao deve expor o titulo esperado.');
$assertSame('app/data/meeting.php', $updateGuide['single_source'], 'O guia deve declarar a unica fonte autorizada.');
$assertSame(5, count($updateGuide['steps']), 'O guia deve listar os passos completos do fluxo manual.');
$assertTrue(str_contains($updateGuide['rule'], 'app/data/meeting.php'), 'O guia deve reforcar que nenhuma edicao externa e permitida.');
$assertTrue(str_contains($updateGuide['warning'], '/admin'), 'O guia deve alertar que o MVP nao possui painel administrativo.');

$flowCheck = verify_update_flow($validatedContract);
$assertTrue($flowCheck['passed'], 'O fluxo de atualizacao deve passar com um contrato valido.');
$assertSame(7, count($flowCheck['checks']), 'O verificador deve inspecionar todos os campos criticos do contrato.');
$assertSame('meeting.join_url', $flowCheck['checks'][0]['field'], 'O verificador deve inspecionar join_url imediatamente.');
$assertTrue($flowCheck['checks'][0]['present'], 'O verificador deve confirmar que join_url esta preenchido.');

$brokenContract = $validatedContract;
$brokenContract['meeting']['join_url'] = '  ';
$flowCheckBroken = verify_update_flow($brokenContract);
$assertTrue(!$flowCheckBroken['checks'][0]['present'], 'Campo em branco deve ser reportado como ausente pelo verificador.');

// Story 4.3: Checklist de Anonimato para Conteudo Publico
$checklist = anonymity_checklist();
$assertSame('Checklist de Anonimato para Publicacao', $checklist['title'], 'O checklist deve expor o titulo esperado.');
$assertSame('MVP v1', $checklist['version'], 'O checklist deve registrar a versao do MVP.');
$assertSame(5, count($checklist['items']), 'O checklist deve conter todos os itens de verificacao.');
$assertSame('blocker', $checklist['items'][0]['severity'], 'Nome completo de membro deve ser um blocker.');
$assertSame('ANON-02', $checklist['items'][1]['id'], 'Foto identificavel deve ter identificador unico.');

$cleanScan = scan_content_block(['title' => 'Sobre o grupo', 'body' => 'Um grupo virtual de apoio mutuo, com reunioes regulares.'], 'about_group');
$assertTrue($cleanScan['passed'], 'Conteudo institucional limpo deve passar no scanner de anonimato.');
$assertSame([], $cleanScan['findings'], 'Conteudo limpo nao deve gerar achados no scanner.');

$piiScan = scan_content_block([
    'title' => 'Depoimento',
    'body' => 'Carlos Eduardo da Silva compartilhou: minha historia de recuperacao comecou quando eu percebi que precisava de ajuda.',
], 'testimonial');
$assertTrue(!$piiScan['passed'], 'Conteudo com nome completo deve ser detectado pelo scanner de anonimato.');
$assertTrue(count($piiScan['findings']) >= 1, 'Scanner deve reportar achados quando houver nome pessoal no conteudo.');

$photoScan = scan_content_block([
    'title' => 'Galeria',
    'body' => 'Veja a foto dos membros da reuniao.',
], 'gallery');
$assertTrue(!$photoScan['passed'], 'Referencia a foto deve ser detectada pelo scanner de anonimato.');

$emptyScan = scan_content_block([], 'empty');
$assertTrue($emptyScan['passed'], 'Bloco vazio deve passar sem violacoes no scanner.');
$assertSame([], $emptyScan['findings'], 'Bloco vazio nao deve produzir achados.');

$fullCheck = run_full_anonymity_check([
    'about_na' => ['title' => 'O que e NA', 'body' => 'Narcoticos Anonimos e uma irmandade de apoio mutuo.'],
    'about_group' => ['title' => 'Sobre', 'body' => 'Grupo virtual com reunioes online regulares.'],
]);
$assertTrue($fullCheck['passed'], 'Blocos institucionais limpos devem passar na checagem completa de anonimato.');
$assertSame(2, count($fullCheck['blocks']), 'A checagem completa deve inspecionar cada bloco individualmente.');
$assertSame(5, count($fullCheck['checklist']), 'O relatorio completo deve incluir a lista de itens do checklist.');

// Story 4.4: Regras Editoriais para Creditos e Responsaveis
$rules = editorial_rules();
$assertSame(5, count($rules), 'O registro de regras editoriais deve conter todas as regras do MVP.');
$assertSame('EDT-01', $rules[0]['id'], 'A primeira regra deve tratar de creditos com primeiro nome ou apelido.');
$assertSame('EDT-05', $rules[4]['id'], 'A ultima regra deve tratar do fluxo de conteudo institucional.');

$validCredit = validate_editorial_credits('Cirino');
$assertTrue($validCredit['valid'], 'Nome unico deve ser aceito como credito editorial valido.');
$assertSame([], $validCredit['issues'], 'Apelido de palavra unica nao deve gerar violacoes.');

$validNickname = validate_editorial_credits('Ciri');
$assertTrue($validNickname['valid'], 'Apelido curto deve ser aceito como credito editorial valido.');

$invalidCredit = validate_editorial_credits('Carlos Eduardo da Silva Santos');
$assertTrue(!$invalidCredit['valid'], 'Nome com multiplas palavras deve ser rejeitado como credito editorial.');
$assertTrue(count($invalidCredit['issues']) >= 1, 'Creditos com nome completo devem listar as violacoes.');

$emailCredit = validate_editorial_credits('Contato: circulo@exemplo.com');
$assertTrue(!$emailCredit['valid'], 'E-mail em credito deve ser rejeitado.');
$assertTrue(count($emailCredit['issues']) >= 1, 'E-mail detectado deve gerar violacao explicita.');

$phoneCredit = validate_editorial_credits('Tel: 71 99999 9999');
$assertTrue(!$phoneCredit['valid'], 'Telefone em credito deve ser rejeitado.');
$assertTrue(count($phoneCredit['issues']) >= 1, 'Credito com telefone deve gerar violacao.');

$emptyCredit = validate_editorial_credits('');
$assertTrue($emptyCredit['valid'], 'Credito vazio deve ser considerado valido.');
$assertSame([], $emptyCredit['issues'], 'Credito vazio nao deve gerar violacoes.');

$surnameCredit = validate_editorial_credits('Joao da Silva');
$assertTrue(!$surnameCredit['valid'], 'Sobrenome comum deve ser detectado em credito editorial.');

$contentValidation = validate_content_against_rules($viewData['home_content']);
$assertTrue($contentValidation['valid'], 'O conteudo atual da home deve passar nas regras editoriais do MVP.');
$assertSame(5, $contentValidation['rules_count'], 'A validacao deve reportar a contagem de regras registradas.');
$assertSame([], $contentValidation['violations'], 'Conteudo aprovado nao deve produzir violacoes editoriais.');

$contentWithCredits = ['footer' => ['credits' => 'Carlos Eduardo da Silva']];
$creditsValidation = validate_content_against_rules($contentWithCredits);
$assertTrue(!$creditsValidation['valid'], 'Campo de creditos com nome completo deve ser rejeitado.');
$assertTrue(count($creditsValidation['violations']) >= 1, 'Credito com nome completo deve listar violacoes.');

$contentWithNickname = ['footer' => ['credits' => 'Ciri']];
$nicknameValidation = validate_content_against_rules($contentWithNickname);
$assertTrue($nicknameValidation['valid'], 'Campo de creditos com apelido deve passar.');
$assertSame([], $nicknameValidation['violations'], 'Apelido em campo de creditos nao deve gerar violacoes.');

$contentWithPhone = ['contact' => ['phone' => '71999999999']];
$phoneValidation = validate_content_against_rules($contentWithPhone);
$assertTrue(!$phoneValidation['valid'], 'Sequencia numerica longa deve ser detectada como possivel telefone.');
$assertTrue(count($phoneValidation['violations']) >= 1, 'Telefone detectado deve gerar violacao.');

// Epic 3: Recursos Complementares e Continuidade
// Story 3.1: Cartao de Relatorio Publico
$supportWithReport = present_support_links([
    'whatsapp' => null,
    'report' => null,
    'directory' => 'https://www.na.org.br/virtual/',
]);
$assertTrue($supportWithReport['has_items'], 'Com link de diretorio valido, a secao complementar deve ser exposta.');
$assertSame('external_hint', array_key_exists('external_hint', $supportWithReport['items'][0]) ? 'external_hint' : 'missing', 'Todo item de apoio deve declarar external_hint.');
$assertTrue(is_string($supportWithReport['items'][0]['external_hint'] ?? null) && ($supportWithReport['items'][0]['external_hint'] ?? '') !== '', 'external_hint nao deve ser vazio.');

// Story 3.2: Diretorio e Outras Reunioes de Apoio
$supportWithDirectory = present_support_links(['whatsapp' => null, 'report' => null, 'directory' => 'https://www.na.org.br/virtual/']);
$assertSame('directory', $supportWithDirectory['items'][0]['key'], 'O link do diretorio deve preservar a chave de identificacao.');
$assertSame('Diretório e outras reuniões', $supportWithDirectory['items'][0]['label'], 'O diretorio deve ter rotulo explicito.');
$assertTrue(str_contains($supportWithDirectory['items'][0]['external_hint'], 'NA'), 'O external_hint do diretorio deve contextualizar o ecossistema.');

// Story 3.3: Link de WhatsApp para Membros
$supportWithWhatsApp = present_support_links(['whatsapp' => 'https://chat.whatsapp.com/abc123', 'report' => null, 'directory' => null]);
$assertTrue($supportWithWhatsApp['has_items'], 'Com link de WhatsApp valido, a secao complementar deve ser exposta.');
$assertSame('whatsapp', $supportWithWhatsApp['items'][0]['key'], 'O link do WhatsApp deve preservar a chave.');
$assertTrue(is_string($supportWithWhatsApp['items'][0]['member_context'] ?? null) && ($supportWithWhatsApp['items'][0]['member_context'] ?? '') !== '', 'O WhatsApp deve incluir contexto de membro (member_context).');
$assertTrue(str_contains(strtolower($supportWithWhatsApp['items'][0]['label']), 'membros'), 'O rotulo do WhatsApp deve mencionar membros.');

// Story 3.4: Navegacao Externa com Fallback e Clareza
$allLinks = present_support_links(['whatsapp' => null, 'report' => null, 'directory' => 'https://www.na.org.br/virtual/']);
$prevSupportSection = $viewData['support_section'];
$viewData['support_section'] = $allLinks;
$renderedSupportSection = '';
ob_start();
require dirname(__DIR__) . '/app/views/partials/support_links.php';
$renderedSupportSection = (string) ob_get_clean();
$assertTrue(str_contains($renderedSupportSection, 'rel="noopener noreferrer"'), 'Links de apoio externos devem proteger a navegacao com rel noopener.');
$assertTrue(str_contains($renderedSupportSection, 'support-links__external-indicator'), 'Links externos devem exibir indicador visual (icone).');
$assertTrue(str_contains($renderedSupportSection, 'aria-hidden="true"'), 'Indicador externo deve ser oculto para leitores de tela.');
$assertTrue(!str_contains($renderedSupportSection, 'support-links__member-context'), 'Link sem contexto de membro nao deve renderizar bloco de contexto.');
$assertTrue(str_contains($renderedSupportSection, 'Diretório e outras reuniões'), 'A secao complementar deve expor o rotulo amigavel do diretorio.');

$viewData['support_section'] = present_support_links(['whatsapp' => 'https://chat.whatsapp.com/abc123', 'report' => null, 'directory' => null]);
ob_start();
require dirname(__DIR__) . '/app/views/partials/support_links.php';
$renderedWhatsApp = (string) ob_get_clean();
$assertTrue(str_contains($renderedWhatsApp, 'support-links__member-context'), 'WhatsApp deve renderizar bloco de contexto de membro.');
$assertTrue(str_contains($renderedWhatsApp, 'participantes recorrentes'), 'Contexto de membro do WhatsApp deve mencionar participantes.');
$viewData['support_section'] = $prevSupportSection;

// CSS para indicador externo
$css = (string) file_get_contents(dirname(__DIR__) . '/public/assets/css/home.css');
$assertTrue(str_contains($css, '.support-links__external-indicator'), 'O CSS deve estilizar o indicador de link externo.');
$assertTrue(str_contains($css, '.support-links__member-context'), 'O CSS deve estilizar o bloco de contexto de membro.');

require_once dirname(__DIR__) . '/app/support/analytics.php';

// Story 5.1: SEO e Semantica da Home
ob_start();
require dirname(__DIR__) . '/public/index.php';
$seoHtml = (string) ob_get_clean();
$assertTrue(str_contains($seoHtml, '<meta name="description"'), 'A home deve incluir meta description para SEO.');
$assertTrue(str_contains($seoHtml, '<meta property="og:title"'), 'A home deve incluir Open Graph title.');
$assertTrue(str_contains($seoHtml, '<meta property="og:description"'), 'A home deve incluir Open Graph description.');
$assertTrue(str_contains($seoHtml, '<meta property="og:type" content="website"'), 'A home deve declarar og:type website.');
$assertTrue(str_contains($seoHtml, '<meta name="robots" content="index, follow"'), 'A home deve permitir indexacao por mecanismos de busca.');
$assertTrue(str_contains($seoHtml, '<link rel="canonical"'), 'A home deve incluir link canonical.');
$assertTrue(str_contains($seoHtml, '<title>'), 'A home deve ter title tag preenchido.');
$assertSame(1, substr_count($seoHtml, '<title>'), 'A home deve ter exatamente um title tag.');

// Story 5.2: Acessibilidade do Fluxo Principal
$assertTrue(str_contains($seoHtml, 'class="skip-link"'), 'A home deve manter skip link acessivel.');
$assertTrue(str_contains($seoHtml, 'id="main-content"'), 'O main deve manter id navegavel para skip link.');
$assertTrue(str_contains($seoHtml, 'role="main"'), 'O main deve declarar role=main.');
$assertTrue(str_contains($seoHtml, 'aria-label="Conteúdo principal"'), 'O main deve ter aria-label descritivo.');
$assertTrue(str_contains($seoHtml, '<html lang="pt-BR"'), 'A home deve declarar lang pt-BR no html.');
$assertTrue(str_contains($seoHtml, 'charset="UTF-8"'), 'A home deve declarar charset UTF-8.');
$assertTrue(str_contains($seoHtml, '<meta name="viewport"'), 'A home deve declarar viewport para responsividade mobile.');

// Story 5.3: Analytics Agregado e Anonimo
$storageFile = dirname(__DIR__) . '/tmp/analytics.json';
if (is_file($storageFile)) {
    unlink($storageFile);
}

track_event('meeting_cta_click');
$assertTrue(is_file($storageFile), 'track_event deve criar o arquivo de analytics.');
$data1 = json_decode((string) file_get_contents($storageFile), true);
$assertSame(1, $data1['meeting_cta_click'] ?? 0, 'O primeiro clique deve incrementar o contador para 1.');

track_event('meeting_cta_click');
track_event('whatsapp_click');
$data2 = json_decode((string) file_get_contents($storageFile), true);
$assertSame(2, $data2['meeting_cta_click'] ?? 0, 'O segundo clique deve incrementar o contador para 2.');
$assertSame(1, $data2['whatsapp_click'] ?? 0, 'WhatsApp click deve ter seu proprio contador independente.');

track_event('evento_invalido');
$data3 = json_decode((string) file_get_contents($storageFile), true);
$assertTrue(!isset($data3['evento_invalido']), 'Eventos nao registrados devem ser ignorados.');
$assertSame(2, $data3['meeting_cta_click'] ?? 0, 'Evento invalido nao deve afetar contadores validos.');

$tracked = tracked_url('https://zoom.us/j/example', 'meeting_cta_click');
$assertTrue(str_contains($tracked, 'track.php'), 'URL rastreada deve apontar para track.php.');
$assertTrue(str_contains($tracked, 'event=meeting_cta_click'), 'URL rastreada deve incluir o parametro event.');
$assertTrue(str_contains($tracked, 'redirect='), 'URL rastreada deve incluir o parametro redirect.');

if (is_file($storageFile)) {
    unlink($storageFile);
}

// Story 5.4: Checklist de Lancamento Tecnico do MVP
$checklist = launch_checklist();
$assertSame('Checklist de Lancamento Tecnico do MVP', $checklist['title'], 'O checklist deve expor o titulo esperado.');
$assertSame('v1.0.0', $checklist['version'], 'O checklist deve declarar a versao do MVP.');
$assertSame(12, count($checklist['items']), 'O checklist deve conter todos os itens de verificacao.');
$assertTrue(count($checklist['categories']) >= 4, 'O checklist deve cobrir multiplas categorias de verificacao.');

$readiness = validate_launch_readiness();
$assertTrue($readiness['total'] > 0, 'A validacao de readiness deve inspecionar itens.');
$assertTrue($readiness['ok'] >= 4, 'Pelo menos 4 itens devem ser validaveis automaticamente.');
$assertTrue($readiness['ready'], 'Nenhum item automatico deve falhar no estado atual.');
$assertTrue($readiness['manual'] > 0, 'Alguns itens devem exigir verificacao manual.');

if ($failures > 0) {
    exit(1);
}

fwrite(STDOUT, "PASS: " . (string) $assertions . " verificacoes\n");
