# Story 2.4: Explicacao Aberta versus Fechada

Status: done

## Story

As a visitante,  
I want entender a diferenca entre reuniao aberta e fechada,  
so that eu saiba se posso participar antes de clicar no link principal.

## Acceptance Criteria

1. **Given** que o site apresenta uma reuniao com tipo definido  
   **When** o usuario le a explicacao de elegibilidade  
   **Then** ele encontra a diferenca entre `aberta` e `fechada` em copy clara.
2. **Given** que a home explica os tipos de reuniao  
   **When** o usuario consome esse conteudo  
   **Then** a explicacao e compativel com as regras de anonimato e comunicacao publica do grupo.

## Tasks / Subtasks

- [x] Adicionar um bloco curto explicando `aberta` versus `fechada`.
  - [x] Registrar a copy em `app/content/home.php`.
  - [x] Criar renderizacao SSR dedicada para a explicacao.
  - [x] Manter linguagem clara, publica e sem jargao excessivo.
- [x] Integrar a explicacao a hierarquia da home.
  - [x] Posicionar a secao perto do contexto da reuniao, logo abaixo do hero/card principal e antes dos blocos institucionais, conforme `UX-DR4` e a jornada do recem-chegado.
  - [x] Preservar a prioridade do CTA e do card principal.
  - [x] Manter texto curto e leitura facil em mobile.
- [x] Validar a Story.
  - [x] Cobrir conteudo, render, ordem e estilo em `tests/run.php`.
  - [x] Rodar lint PHP via `wsl docker`.
  - [x] Fazer smoke render da home com a explicacao de elegibilidade.

## Dev Notes

- A explicacao nao substitui a nota curta no card principal; ela aprofunda a elegibilidade em linguagem publica e clara.
- O texto deve evitar afirmar regras alem do que o grupo ja pratica no conteudo autorizado.
- Nenhuma copy pode expor membros, creditos ou casos pessoais.

### References

- Source: `_bmad-output/planning-artifacts/epics.md` -> `Epic 2 / Story 2.4`
- Source: `_bmad-output/planning-artifacts/ux-design-specification.md` -> `UX-DR4`, `Journey 2: Recem-chegado inseguro`
- Source: `_bmad-output/planning-artifacts/prd.md` -> `FR10`
- Source: `docs/project-context.md` -> `Critical Implementation Rules`

### Git Workflow

- Branch recomendado: `codex/feature/2-4-aberta-vs-fechada`
- Commits devem usar Conventional Commits em PT-BR e referenciar este arquivo no `Ref:`
- Issue e PR devem citar esta Story como artefato de referencia

## Dev Agent Record

### Agent Model Used

Codex GPT-5

### Debug Log References

- Story file criado automaticamente a partir dos artefatos de planejamento em 2026-03-24.

### Completion Notes List

- Bloco SSR `aberta versus fechada` adicionado com copy publica em `app/content/home.php` e partial dedicada.
- Hierarquia da home ajustada para refletir `UX-DR4`: explicacao fica imediatamente apos o hero/card e antes do conteudo institucional.
- Suite local validada com `wsl docker` (`php -l` e `php tests/run.php`), incluindo asserts de conteudo e ordem de renderizacao.
- Atualizacao 2026-05-27: copy de reuniao aberta/fechada recebeu acentuacao correta e foi preservada em bloco secundario, sem virar chamada primaria.

### File List

- `_bmad-output/implementation-artifacts/2-4-explicacao-aberta-versus-fechada.md`
- `app/content/home.php`
- `app/views/home.php`
- `app/views/partials/meeting_types.php`
- `public/assets/css/home.css`
- `tests/run.php`
