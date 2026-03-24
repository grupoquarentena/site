<?php

declare(strict_types=1);

$supportSection = $viewData['support_section'] ?? ['has_items' => false, 'items' => []];
$supportContent = $viewData['home_content']['support_links'] ?? [];

if (($supportSection['has_items'] ?? false) !== true) {
    return;
}
?>
<section class="support-links" aria-labelledby="support-links-title">
    <div class="support-links__copy">
        <p class="support-links__eyebrow"><?= $escape($supportContent['eyebrow'] ?? 'Apoio complementar') ?></p>
        <h2 id="support-links-title" class="support-links__title"><?= $escape($supportContent['title'] ?? 'Recursos externos secundários') ?></h2>
        <p class="support-links__lead"><?= $escape($supportContent['lead'] ?? 'Estes caminhos não substituem a reunião principal.') ?></p>
    </div>

    <ul class="support-links__list">
        <?php foreach ($supportSection['items'] as $item): ?>
            <li class="support-links__item">
                <a
                    class="support-links__link"
                    href="<?= $escape($item['url']) ?>"
                    rel="noopener noreferrer"
                    data-event="<?= $escape($item['event']) ?>"
                >
                    <span class="support-links__label"><?= $escape($item['label']) ?></span>
                    <span class="support-links__description"><?= $escape($item['description']) ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
