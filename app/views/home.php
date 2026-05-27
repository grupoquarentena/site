<?php

declare(strict_types=1);

$escape = static fn (mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$site = $viewData['site'] ?? [];
$siteName = $escape($site['name'] ?? 'Grupo QuarenteNA');

$metaDescription = 'Ponto oficial da reunião online do Grupo QuarenteNA de Narcóticos Anônimos. '
    . 'Horário, ID, senha e tipo da reunião disponíveis em um único lugar. '
    . 'Acolhimento direto, sem JavaScript obrigatório.';
?>
<!DOCTYPE html>
<html lang="<?= $escape($site['locale'] ?? 'pt-BR') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $siteName ?></title>
    <meta name="description" content="<?= $escape($metaDescription) ?>">
    <meta property="og:title" content="<?= $siteName ?>">
    <meta property="og:description" content="<?= $escape($metaDescription) ?>">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="pt_BR">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://quarentena.na.org.br/">
    <link rel="stylesheet" href="assets/css/home.css">
</head>
<body class="home-body">
    <a class="skip-link" href="#main-content">Pular para o conteúdo principal</a>
    <header class="site-header" aria-label="Identificação do site">
        <p class="site-header__mark">Grupo QuarenteNA</p>
        <p class="site-header__context">Narcóticos Anônimos · reunião online</p>
    </header>
    <main id="main-content" class="page-shell" role="main" aria-label="Conteúdo principal">
        <?php require __DIR__ . '/partials/hero_meeting_cta.php'; ?>
        <?php require __DIR__ . '/partials/meeting_types.php'; ?>
        <?php require __DIR__ . '/partials/about_na.php'; ?>
        <?php require __DIR__ . '/partials/about_group.php'; ?>
        <?php require __DIR__ . '/partials/group_history.php'; ?>
        <?php require __DIR__ . '/partials/support_links.php'; ?>
    </main>
    <footer class="site-footer" aria-label="Rodapé do site">
        <p>Conteúdo público revisado para preservar anonimato. O caminho principal continua sendo o botão de entrada da reunião.</p>
    </footer>
</body>
</html>
