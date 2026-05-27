# Story 3.3: Link de WhatsApp para Membros

Status: done

## Story

As a participante,
I want acessar o grupo de WhatsApp de membros a partir do site quando apropriado,
so that eu encontre continuidade de comunicacao sem confundir esse recurso com a entrada da reuniao.

## Acceptance Criteria

1. Aparece com rotulagem explicita para membros.
2. Sua apresentacao nao compete com o CTA principal da reuniao.
3. O clique pode ser medido de forma agregada e anonima.

## Tasks / Subtasks

- [x] `present_support_links()` inclui rotulo "WhatsApp de membros".
- [x] Adicionado `member_context` — texto explicativo sobre participantes recorrentes.
- [x] O partial `support_links.php` renderiza o `member_context` quando presente.
- [x] A secao complementar permanece visualmente secundaria ao CTA.
- [x] CSS `.support-links__member-context` estiliza o contexto de forma discreta.
- [x] Evento `whatsapp_click` registrado via `data-event` para medicao agregada futura.
- [x] O seed mantem `whatsapp: null` ate que o link oficial seja aprovado.
- [ ] Code review 2026-05-27 identificou que a medicao real de clique ainda nao esta integrada ao `href`; follow-up registrado na issue `#28`.

## File List

- `app/support/external_links.php`
- `app/views/partials/support_links.php`
- `public/assets/css/home.css`
- `tests/run.php`

## Atualizacao 2026-05-27

- Copy do contexto de membros recebeu acentuacao correta.
- Lacuna de integracao real do tracking foi documentada no artefato de code review do MVP.
