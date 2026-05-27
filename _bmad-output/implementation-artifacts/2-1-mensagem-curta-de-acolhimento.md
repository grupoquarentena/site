# Story 2.1: Mensagem Curta de Acolhimento

Status: done

## Story

As a recem-chegado inseguro,  
I want receber uma mensagem curta e acolhedora perto do CTA principal,  
so that eu sinta menos atrito antes de entrar.

## Acceptance Criteria

1. **Given** que o usuario chega pela primeira vez a home  
   **When** ele ve a area principal da pagina  
   **Then** existe uma mensagem de acolhimento curta e nao julgadora.
2. **Given** que a mensagem aparece perto do CTA principal  
   **When** o usuario percebe a hierarquia visual  
   **Then** ela aparece sem competir visualmente com o CTA.
3. **Given** que a copy de acolhimento e publicada  
   **When** o usuario a le  
   **Then** o texto evita jargao institucional pesado.

## Tasks / Subtasks

- [x] Adicionar a copy de acolhimento no contexto imediato do hero.
  - [x] Registrar a mensagem em `app/content/home.php`.
  - [x] Renderizar o bloco perto do CTA principal sem transformar a copy em acao primaria.
  - [x] Manter o texto curto, humano e sem jargao pesado.
- [x] Ajustar a apresentacao visual do acolhimento.
  - [x] Criar um tratamento secundario no CSS, coerente com a hierarquia existente.
  - [x] Preservar legibilidade mobile e contraste.
  - [x] Garantir que o CTA continue dominante acima da dobra.
- [x] Validar a Story.
  - [x] Cobrir a presenca do bloco e da copy em `tests/run.php`.
  - [x] Rodar lint PHP via `wsl docker`.
  - [x] Fazer smoke render da home garantindo que o bloco aparece perto do CTA sem JS obrigatorio.

## Dev Notes

- Esta story adiciona acolhimento, nao contexto institucional longo; os blocos de explicacao entram nas proximas Stories do Epic 2.
- A copy deve reforcar seguranca e legitimidade sem competir com o CTA e sem desviar o foco do fluxo principal.
- O bloco precisa ficar acima do conteudo institucional e continuar subordinado a `HeroMeetingCTA`.

### Project Structure Notes

- Conteudo alvo: `app/content/home.php`
- Partial alvo: `app/views/partials/hero_meeting_cta.php`
- Estilo alvo: `public/assets/css/home.css`

### References

- Source: `_bmad-output/planning-artifacts/epics.md` -> `Epic 2 / Story 2.1`
- Source: `_bmad-output/planning-artifacts/ux-design-specification.md` -> `Journey 2: Recem-chegado inseguro`, `UX-DR5`, `Pattern 3`
- Source: `_bmad-output/planning-artifacts/prd.md` -> `FR9`, `FR11`
- Source: `docs/project-context.md` -> `Critical Implementation Rules`

### Git Workflow

- Branch recomendado: `codex/feature/2-1-mensagem-acolhimento`
- Commits devem usar Conventional Commits em PT-BR e referenciar este arquivo no `Ref:`
- Issue e PR devem citar esta Story como artefato de referencia

## Dev Agent Record

### Agent Model Used

Codex GPT-5

### Debug Log References

- Story file criado automaticamente a partir dos artefatos de planejamento em 2026-03-24.
- Issue GitHub aberta: `#14`.
- Lint PHP executado via `wsl docker run ... php:8.2-cli php -l`.
- Suite local executada via `wsl docker run ... php:8.2-cli php tests/run.php`.
- Revisao adversarial/fresca sem findings bloqueantes em 2026-03-24.
- Conferencia tecnica curta em PARTY MODE com lentes de PM, arquitetura, dev e QA sem ajustes obrigatorios antes do merge.

### Completion Notes List

- A copy de acolhimento passou a ser gerida em `app/content/home.php`, preservando a separacao entre conteudo e view.
- `app/views/partials/hero_meeting_cta.php` agora renderiza um bloco curto e secundario de acolhimento perto do CTA principal.
- `public/assets/css/home.css` ganhou tratamento visual discreto para a mensagem, mantendo o CTA como unica acao primaria.
- `tests/run.php` agora cobre conteudo, render e estilo do bloco `hero__welcome` via `wsl docker`.
- Atualizacao 2026-05-27: copy do hero foi reescrita para remover linguagem tecnica interna e reforcar acolhimento humano para recem-chegados.
- Atualizacao 2026-05-27: acentuacao da copy publica foi corrigida para melhorar confianca e leitura em PT-BR.

### File List

- `_bmad-output/implementation-artifacts/2-1-mensagem-curta-de-acolhimento.md`
- `_bmad-output/implementation-artifacts/sprint-status.yaml`
- `app/content/home.php`
- `app/views/partials/hero_meeting_cta.php`
- `public/assets/css/home.css`
- `tests/run.php`
