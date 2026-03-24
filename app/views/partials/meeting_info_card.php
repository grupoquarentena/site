<?php

declare(strict_types=1);

$meeting = $viewData['meeting'];
$meetingDisplay = $viewData['meeting_display'];
?>
<section class="meeting-card" aria-labelledby="meeting-card-title">
    <p class="meeting-card__eyebrow">Dados da reuniao</p>
    <h2 id="meeting-card-title" class="meeting-card__title">Informacoes essenciais antes de entrar</h2>

    <dl class="meeting-card__list">
        <div class="meeting-card__item">
            <dt class="meeting-card__term">Horario</dt>
            <dd class="meeting-card__description"><?= $escape($meetingDisplay['schedule_label']) ?></dd>
        </div>
        <div class="meeting-card__item">
            <dt class="meeting-card__term">ID</dt>
            <dd class="meeting-card__description"><?= $escape($meetingDisplay['meeting_id_label']) ?></dd>
        </div>
        <div class="meeting-card__item">
            <dt class="meeting-card__term">Senha</dt>
            <dd class="meeting-card__description"><?= $escape($meetingDisplay['password_label']) ?></dd>
        </div>
        <div class="meeting-card__item">
            <dt class="meeting-card__term">Tipo</dt>
            <dd class="meeting-card__description">
                <?= $escape($meetingDisplay['type_label']) ?>
                <span class="meeting-card__type-note"><?= $escape($meetingDisplay['type_description']) ?></span>
            </dd>
        </div>
    </dl>

    <div class="meeting-card__meta">
        <span class="meeting-card__meta-label">Reuniao</span>
        <span class="meeting-card__meta-value"><?= $escape($meeting['title']) ?></span>
        <span class="meeting-card__meta-label">Atualizado em</span>
        <span class="meeting-card__meta-value"><?= $escape($meetingDisplay['updated_at_label']) ?></span>
    </div>
</section>
