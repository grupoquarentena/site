# Story 3.2: Diretorio e Outras Reunioes de Apoio

Status: done

## Story

As a visitante,
I want acessar outras referencias de apoio quando necessario,
so that eu nao dependa exclusivamente de um unico caminho de ajuda.

## Acceptance Criteria

1. Links aprovados para diretorio e outras reunioes estao disponiveis.
2. Esses links aparecem como apoio, nao como substitutos do CTA principal.

## Tasks / Subtasks

- [x] O link `directory` ja esta seedado em `app/data/meeting.php` com URL oficial do NA.
- [x] `present_support_links()` rotula como "Diretório e outras reuniões".
- [x] `external_hint` contextualiza como recurso do ecossistema NA.
- [x] A secao complementar e renderizada no final da pagina, apos todos os blocos principais.
- [x] Testes validam chave, rotulo e external_hint do diretorio.

## File List

- `app/data/meeting.php`
- `app/support/external_links.php`
- `tests/run.php`

## Atualizacao 2026-05-27

- Rotulo publico do diretorio recebeu acentuacao correta em PT-BR.
- A secao complementar permanece no fim da pagina e visualmente secundaria ao CTA principal.
