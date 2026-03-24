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

    <aside class="hero__surface" aria-label="Resumo oficial da home">
        <p class="hero__surface-label"><?= $escape($heroContent['surface_label'] ?? '') ?></p>
        <h2 class="hero__surface-title"><?= $escape($meeting['title']) ?></h2>
        <p class="hero__surface-copy"><?= $escape($heroContent['surface_copy'] ?? '') ?></p>
        <p class="hero__surface-meta">Atualizado em <?= $escape($meeting['updated_at']) ?></p>
    </aside>
</section>
