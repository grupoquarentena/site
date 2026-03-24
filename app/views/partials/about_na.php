<?php

declare(strict_types=1);

$aboutNa = $viewData['home_content']['about_na'] ?? [];
?>
<section class="info-section" aria-labelledby="about-na-title">
    <div class="info-section__copy">
        <p class="info-section__eyebrow"><?= $escape($aboutNa['eyebrow'] ?? 'O que e NA') ?></p>
        <h2 id="about-na-title" class="info-section__title"><?= $escape($aboutNa['title'] ?? 'Narcoticos Anonimos em linguagem simples') ?></h2>
        <p class="info-section__lead"><?= $escape($aboutNa['body'] ?? '') ?></p>
    </div>
</section>
