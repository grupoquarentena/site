# Story 3.4: Navegacao Externa com Fallback e Clareza

Status: done

## Story

As a publico geral,
I want saber quando estou saindo do site para um recurso externo,
so that eu nao perca a clareza sobre qual e o caminho principal.

## Acceptance Criteria

1. A interface deixa claro que se trata de um link externo complementar.
2. Se o recurso nao estiver disponivel, o site mantem mensagem de fallback sem quebrar a home.

## Tasks / Subtasks

- [x] `present_support_links()` adiciona `external_hint` descritivo para cada tipo de link.
- [x] O partial renderiza icone visual `.support-links__external-indicator` (SVG inline via mask-image).
- [x] O icone tem `aria-hidden="true"` para nao poluir leitores de tela.
- [x] `external_hint` e repassado como `title` no icone para tooltip nativo.
- [x] Texto acessivel oculto `abre recurso externo` foi adicionado ao link para complementar o indicador visual em leitores de tela.
- [x] Links usam `rel="noopener noreferrer"` para seguranca na navegacao.
- [x] Quando um link esta indisponivel (null/vazio), o item e omitido sem quebrar o layout.
- [x] Secao inteira se oculta quando nao ha links validos (comportamento ja existente).
- [x] Testes validam presenca do indicador, rel noopener, aria-hidden e fallback de links indisponiveis.

## File List

- `app/support/external_links.php`
- `app/views/partials/support_links.php`
- `public/assets/css/home.css`
- `tests/run.php`

## Atualizacao 2026-05-27

- Labels e descricoes dos links complementares receberam acentuacao correta em PT-BR.
- A acessibilidade de links externos foi reforcada com texto `.sr-only`, mantendo o icone visual com `aria-hidden="true"`.
