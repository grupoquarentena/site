<?php

declare(strict_types=1);

$aboutGroup = $viewData['home_content']['about_group'] ?? [];
?>
<section class="info-section info-section--group" aria-labelledby="about-group-title">
    <div class="info-section__copy">
        <p class="info-section__eyebrow"><?= $escape($aboutGroup['eyebrow'] ?? 'Sobre o Grupo QuarenteNA') ?></p>
        <h2 id="about-group-title" class="info-section__title"><?= $escape($aboutGroup['title'] ?? 'Um grupo virtual com reunioes regulares') ?></h2>
        <p class="info-section__lead"><?= $escape($aboutGroup['body'] ?? '') ?></p>
    </div>
</section>
