# Story 2.2: Bloco O que e NA

Status: done

## Story

As a visitante,  
I want entender rapidamente o que e Narcoticos Anonimos,  
so that eu avalie se este espaco e relevante para minha situacao.

## Acceptance Criteria

1. **Given** que o usuario precisa de contexto antes de entrar  
   **When** ele avanca abaixo da dobra  
   **Then** encontra um bloco curto `O que e NA` em linguagem simples.
2. **Given** que o bloco e exibido na home  
   **When** o usuario le o conteudo  
   **Then** ele responde a duvida sem exigir leitura longa.
3. **Given** que o texto institucional e publicado  
   **When** o usuario o le  
   **Then** o conteudo nao expoe membros nem promete resultados indevidos.

## Tasks / Subtasks

- [x] Adicionar o bloco `O que e NA` abaixo da dobra.
  - [x] Registrar a copy em `app/content/home.php`.
  - [x] Criar renderizacao SSR dedicada para o bloco.
  - [x] Manter texto curto, claro e sem promessas indevidas.
- [x] Integrar o bloco a hierarquia da home.
  - [x] Posicionar o bloco como contexto secundario, sem competir com o hero.
  - [x] Aplicar estilo legivel em mobile e desktop.
  - [x] Preservar o fluxo sem JavaScript obrigatorio.
- [x] Validar a Story.
  - [x] Cobrir conteudo, render e estilo em `tests/run.php`.
  - [x] Rodar lint PHP via `wsl docker`.
  - [x] Fazer smoke render da home com o novo bloco.

## Dev Notes

- Este bloco responde a duvida basica sobre NA sem substituir acolhimento, elegibilidade ou contexto do grupo.
- A copy deve ser curta, leiga e segura para publicacao, sem jargao pesado nem promessas de resultado.
- O bloco entra abaixo da dobra, subordinado ao fluxo principal de entrada na reuniao.

### References

- Source: `_bmad-output/planning-artifacts/epics.md` -> `Epic 2 / Story 2.2`
- Source: `_bmad-output/planning-artifacts/ux-design-specification.md` -> `UX-DR6`, `Pattern 3`, `Content Hierarchy`
- Source: `_bmad-output/planning-artifacts/prd.md` -> `FR7`, `FR9`
- Source: `docs/project-context.md` -> `Critical Implementation Rules`

### Git Workflow

- Branch recomendado: `codex/feature/2-2-bloco-o-que-e-na`
- Commits devem usar Conventional Commits em PT-BR e referenciar este arquivo no `Ref:`
- Issue e PR devem citar esta Story como artefato de referencia

## Dev Agent Record

### Agent Model Used

Codex GPT-5

### Debug Log References

- Story file criado automaticamente a partir dos artefatos de planejamento em 2026-03-24.
- Issue GitHub aberta: `#16`.
- Lint PHP executado via `wsl docker run ... php:8.2-cli php -l`.
- Suite local executada via `wsl docker run ... php:8.2-cli php tests/run.php`.
- Revisao adversarial/fresca sem findings bloqueantes em 2026-03-24.
- Conferencia tecnica curta em PARTY MODE com lentes de PM, arquitetura, dev e QA sem ajustes obrigatorios antes do merge.

### Completion Notes List

- O bloco `O que e NA` passou a existir como partial SSR dedicada abaixo do hero, preservando a primazia do CTA principal.
- A copy institucional curta ficou centralizada em `app/content/home.php`, em linguagem simples e sem promessa indevida.
- `public/assets/css/home.css` ganhou tratamento visual para o primeiro bloco institucional da home.
- `tests/run.php` agora cobre conteudo, render e estilo do bloco `O que e NA` via `wsl docker`.
- Atualizacao 2026-05-27: copy recebeu acentuacao correta em PT-BR e manteve texto curto, sem promessa de resultado ou exposicao de membros.

### File List

- `_bmad-output/implementation-artifacts/2-2-bloco-o-que-e-na.md`
- `_bmad-output/implementation-artifacts/sprint-status.yaml`
- `app/content/home.php`
- `app/views/home.php`
- `app/views/partials/about_na.php`
- `public/assets/css/home.css`
- `tests/run.php`
