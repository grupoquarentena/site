# Story 2.3: Bloco Sobre o Grupo QuarenteNA

Status: done

## Story

As a visitante,  
I want entender o que e o Grupo QuarenteNA e sua frequencia regular,  
so that eu reconheca o site como referencia oficial do grupo.

## Acceptance Criteria

1. **Given** que o conteudo institucional foi aprovado para publicacao  
   **When** a home apresenta o bloco do grupo  
   **Then** o texto explica a relacao do grupo com NA e o contexto publico de funcionamento.
2. **Given** que o usuario busca sinais de legitimidade  
   **When** ele le o bloco do grupo  
   **Then** o texto comunica legitimidade e regularidade.
3. **Given** que o conteudo e publicado  
   **When** o usuario o consome  
   **Then** ele nao expoe identidades pessoais.

## Tasks / Subtasks

- [x] Adicionar o bloco `Sobre o Grupo QuarenteNA` abaixo do contexto inicial sobre NA.
  - [x] Registrar a copy em `app/content/home.php`.
  - [x] Renderizar a secao SSR do grupo sem logica de negocio.
  - [x] Comunicar relacao com NA e frequencia regular sem expor pessoas.
- [x] Integrar o bloco a hierarquia institucional da home.
  - [x] Posicionar a secao abaixo de `O que e NA` e acima dos recursos complementares.
  - [x] Aplicar estilo consistente com os blocos institucionais existentes.
  - [x] Preservar leitura curta, clara e mobile-first.
- [x] Validar a Story.
  - [x] Cobrir conteudo, render e estilo em `tests/run.php`.
  - [x] Rodar lint PHP via `wsl docker`.
  - [x] Fazer smoke render da home com o novo bloco.

## Dev Notes

- Este bloco reforca legitimidade publica e frequencia regular do grupo, sem virar historia longa nem area de creditos.
- A copy deve evitar nomes completos, depoimentos e qualquer identificacao pessoal.
- O bloco precisa permanecer subordinado ao fluxo principal da reuniao.

### References

- Source: `_bmad-output/planning-artifacts/epics.md` -> `Epic 2 / Story 2.3`
- Source: `_bmad-output/planning-artifacts/ux-design-specification.md` -> `UX-DR7`, `Pattern 3`, `Content Hierarchy`
- Source: `_bmad-output/planning-artifacts/prd.md` -> `FR8`, `FR12`, `FR15`
- Source: `docs/project-context.md` -> `Critical Implementation Rules`

### Git Workflow

- Branch recomendado: `codex/feature/2-3-sobre-grupo-quarentena`
- Commits devem usar Conventional Commits em PT-BR e referenciar este arquivo no `Ref:`
- Issue e PR devem citar esta Story como artefato de referencia

## Dev Agent Record

### Agent Model Used

Codex GPT-5

### Debug Log References

- Story file criado automaticamente a partir dos artefatos de planejamento em 2026-03-24.
- Issue GitHub aberta: `#18`.
- Lint PHP executado via `wsl docker run ... php:8.2-cli php -l`.
- Suite local executada via `wsl docker run ... php:8.2-cli php tests/run.php`.
- Revisao adversarial/fresca com findings de copy/hierarquia absorvidos antes do fechamento.
- Conferencia tecnica curta em PARTY MODE com lentes de PM, arquitetura, dev e QA sem bloqueios para merge.

### Completion Notes List

- O bloco `Sobre o Grupo QuarenteNA` passou a existir como partial SSR propria, logo abaixo de `O que e NA`.
- A copy institucional foi ajustada para comunicar relacao com NA e agenda regular sem afirmar frequencia nao comprovada nem expor pessoas.
- O tratamento visual do bloco do grupo ficou secundario em relacao ao contexto inicial, evitando colapso visual entre as secoes.
- `tests/run.php` agora cobre conteudo, render e estilo do bloco sobre o grupo via `wsl docker`.
- Atualizacao 2026-05-27: copy recebeu acentuacao correta e foi mantida como contexto secundario, preservando a prioridade do CTA e do card da reuniao.

### File List

- `_bmad-output/implementation-artifacts/2-3-bloco-sobre-o-grupo-quarentena.md`
- `_bmad-output/implementation-artifacts/sprint-status.yaml`
- `app/content/home.php`
- `app/views/home.php`
- `app/views/partials/about_group.php`
- `public/assets/css/home.css`
- `tests/run.php`
