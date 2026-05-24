<?php

declare(strict_types=1);

$groupHistory = $viewData['home_content']['group_history'] ?? [];
?>
<section class="info-section info-section--history" aria-labelledby="group-history-title">
    <div class="info-section__copy">
        <p class="info-section__eyebrow"><?= $escape($groupHistory['eyebrow'] ?? 'Nossa historia') ?></p>
        <h2 id="group-history-title" class="info-section__title"><?= $escape($groupHistory['title'] ?? '') ?></h2>
        <p class="info-section__lead"><?= $escape($groupHistory['body'] ?? '') ?></p>
    </div>
</section>