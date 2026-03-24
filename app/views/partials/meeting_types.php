<?php

declare(strict_types=1);

$meetingTypes = $viewData['home_content']['meeting_types'] ?? [];
?>
<section class="info-section info-section--types" aria-labelledby="meeting-types-title">
    <div class="info-section__copy">
        <p class="info-section__eyebrow"><?= $escape($meetingTypes['eyebrow'] ?? 'Aberta ou fechada') ?></p>
        <h2 id="meeting-types-title" class="info-section__title"><?= $escape($meetingTypes['title'] ?? 'Como entender o tipo da reuniao') ?></h2>
        <p class="info-section__lead"><?= $escape($meetingTypes['lead'] ?? '') ?></p>
    </div>
    <div class="meeting-types-grid">
        <article class="meeting-types-card">
            <h3 class="meeting-types-card__title"><?= $escape($meetingTypes['open_title'] ?? 'Reuniao aberta') ?></h3>
            <p class="meeting-types-card__body"><?= $escape($meetingTypes['open_body'] ?? '') ?></p>
        </article>
        <article class="meeting-types-card">
            <h3 class="meeting-types-card__title"><?= $escape($meetingTypes['closed_title'] ?? 'Reuniao fechada') ?></h3>
            <p class="meeting-types-card__body"><?= $escape($meetingTypes['closed_body'] ?? '') ?></p>
        </article>
    </div>
</section>
