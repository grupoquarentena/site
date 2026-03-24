<?php

declare(strict_types=1);

$escape = static fn (mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$meeting = $viewData['meeting'];
$status = $viewData['meeting_status'];
$supportLinks = $viewData['support_links'];
?>
<!DOCTYPE html>
<html lang="<?= $escape($viewData['site']['locale']) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $escape($viewData['site']['name']) ?></title>
</head>
<body>
    <main>
        <h1><?= $escape($meeting['title'] ?? 'Reunião do Grupo QuarenteNA') ?></h1>
        <p>Fundação técnica do MVP público em preparação.</p>

        <p>
            <a href="<?= $escape($meeting['join_url'] ?? '#') ?>">Entrar na reunião</a>
        </p>

        <dl>
            <dt>Horário</dt>
            <dd><?= $escape((string) ($meeting['starts_at'] ?? '')) ?></dd>

            <dt>ID</dt>
            <dd><?= $escape($meeting['meeting_id'] ?? '') ?></dd>

            <dt>Senha</dt>
            <dd><?= $escape($meeting['password'] ?? '') ?></dd>

            <dt>Tipo</dt>
            <dd><?= $escape($meeting['type'] ?? '') ?></dd>

            <dt>Status</dt>
            <dd><?= $escape($status['label']) ?></dd>

            <dt>Atualizado em</dt>
            <dd><?= $escape($meeting['updated_at'] ?? '') ?></dd>
        </dl>

        <?php if (!empty($supportLinks['directory'])): ?>
            <p>
                <a href="<?= $escape($supportLinks['directory']) ?>">Diretório oficial de reuniões virtuais</a>
            </p>
        <?php endif; ?>
    </main>
</body>
</html>
