<?php

declare(strict_types=1);

$heroContent = $viewData['home_content']['hero'] ?? [];
$meeting = $viewData['meeting'];
$meetingDisplay = $viewData['meeting_display'];
$joinUrl = $meetingDisplay['join_url'] ?? null;
$hasJoinUrl = is_string($joinUrl) && $joinUrl !== '';
?>
<section class="hero" aria-labelledby="hero-title">
    <div class="hero__copy">
        <p class="hero__eyebrow"><?= $escape($heroContent['eyebrow'] ?? '') ?></p>
        <h1 id="hero-title" class="hero__title"><?= $escape($meeting['title']) ?></h1>
        <p class="hero__lead"><?= $escape($heroContent['lead'] ?? '') ?></p>
        <p class="hero__welcome"><?= $escape($heroContent['welcome_message'] ?? '') ?></p>

        <?php if ($hasJoinUrl): ?>
            <a
                class="hero__cta"
                href="<?= $escape(tracked_url('meeting', 'meeting_cta_click')) ?>"
                aria-label="<?= $escape('Entrar na reunião: ' . $meeting['title']) ?>"
                rel="noopener noreferrer"
            >
                <span class="hero__cta-label"><?= $escape($heroContent['cta_label'] ?? 'Entrar na reunião') ?></span>
                <span class="hero__cta-context"><?= $escape($meeting['title']) ?></span>
            </a>
        <?php else: ?>
            <p class="hero__cta hero__cta--disabled">
                <span class="hero__cta-label"><?= $escape($heroContent['cta_disabled_label'] ?? 'Sem reunião ativa') ?></span>
                <span class="hero__cta-context"><?= $escape($heroContent['cta_disabled_note'] ?? 'Aplicativo arquivado — apenas referência histórica') ?></span>
            </p>
        <?php endif; ?>

        <div class="hero__quick-facts" aria-label="Resumo rápido da reunião">
            <span>Status: <?= $escape($meetingDisplay['status_label']) ?></span>
            <span>Horário: <?= $escape($meetingDisplay['schedule_label']) ?></span>
        </div>

        <p class="hero__trust"><?= $escape($heroContent['trust_note'] ?? '') ?></p>
    </div>
    <?php require __DIR__ . '/meeting_info_card.php'; ?>
</section>
