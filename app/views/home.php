<?php

declare(strict_types=1);

$escape = static fn (mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="<?= $escape($viewData['site']['locale']) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $escape($viewData['site']['name']) ?></title>
    <link rel="stylesheet" href="assets/css/home.css">
</head>
<body class="home-body">
    <a class="skip-link" href="#main-content">Pular para o conteudo principal</a>
    <main id="main-content" class="page-shell">
        <?php require __DIR__ . '/partials/hero_meeting_cta.php'; ?>
    </main>
</body>
</html>
