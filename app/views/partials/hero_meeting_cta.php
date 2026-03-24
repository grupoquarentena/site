<?php

declare(strict_types=1);

$heroContent = $viewData['home_content']['hero'] ?? [];
$meeting = $viewData['meeting'];
?>
<section class="hero" aria-labelledby="hero-title">
    <div class="hero__copy">
        <p class="hero__eyebrow"><?= $escape($heroContent['eyebrow'] ?? '') ?></p>
        <h1 id="hero-title" class="hero__title"><?= $escape($meeting['title']) ?></h1>
        <p class="hero__lead"><?= $escape($heroContent['lead'] ?? '') ?></p>

        <a
            class="hero__cta"
            href="<?= $escape($meeting['join_url']) ?>"
            aria-label="<?= $escape('Entrar na reunião: ' . $meeting['title']) ?>"
            rel="noopener noreferrer"
        >
            <span class="hero__cta-label"><?= $escape($heroContent['cta_label'] ?? 'Entrar na reunião') ?></span>
            <span class="hero__cta-context"><?= $escape($meeting['title']) ?></span>
        </a>

        <p class="hero__trust"><?= $escape($heroContent['trust_note'] ?? '') ?></p>
    </div>
    <?php require __DIR__ . '/meeting_info_card.php'; ?>
</section>
