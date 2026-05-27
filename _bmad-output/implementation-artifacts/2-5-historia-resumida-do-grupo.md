# Story 2.5: Historia Resumida do Grupo

Status: done

## Story

As a publico geral,
I want acessar uma historia resumida do grupo,
so that eu perceba continuidade e existencia real do QuarenteNA.

## Acceptance Criteria

1. **Given** que o usuario procura sinais de legitimidade
   **When** ele chega a secao historica
   **Then** encontra uma versao resumida e publica da historia do grupo.
2. **Given** que a secao historica foi publicada
   **When** o conteudo e renderizado
   **Then** o bloco permanece subordinado ao objetivo principal da pagina
   **And** nao traz fotos ou relatos que violem anonimato.

## Tasks / Subtasks

- [x] Adicionar bloco `group_history` em `app/content/home.php` com copy aprovada.
  - [x] Incluir eyebrow `Nossa historia`, titulo `Nascemos na pandemia para nao deixar ninguem so`.
  - [x] Incluir body explicando a origem na pandemia, o nome QuarenteNA e a continuidade do grupo.
- [x] Renderizar o bloco na home via partial dedicada `app/views/partials/group_history.php`.
- [x] Garantir que o bloco aparece depois do bloco `about_group` na hierarquia da pagina.
- [x] Adicionar CSS dedicado `.info-section--history` em `public/assets/css/home.css`.
- [x] Validar que o conteudo nao expoe nomes pessoais (ex: "Ciro").
- [x] Validar que o bloco menciona a origem do nome QuarenteNA (referencia a quarentena).
- [x] Validar semantica com `aria-labelledby="group-history-title"`.

## Dev Notes

- O bloco de historia fica abaixo do bloco sobre o grupo, mantendo a prioridade do CTA.
- A copy menciona "pandemia de COVID-19", "20 de marco de 2020", "quarentena" e a continuidade do grupo.
- Nenhum nome pessoal ou foto esta presente no conteudo.
- O CSS segue o padrao dos demais blocos `.info-section`.
- Atualizacao 2026-05-27: claims numericos nao documentados foram removidos da copy publica; a historia agora comunica continuidade sem afirmar volumes de reunioes, visitas ou ingressos.
- Atualizacao 2026-05-27: acentuacao da secao historica foi corrigida para PT-BR.

## File List

- `app/content/home.php` (bloco `group_history`)
- `app/views/partials/group_history.php`
- `app/views/home.php` (require da partial)
- `public/assets/css/home.css` (`.info-section--history`)
- `tests/run.php` (asserts)
