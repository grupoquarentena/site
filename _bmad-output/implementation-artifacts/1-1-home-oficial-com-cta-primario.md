# Story 1.1: Home Oficial com CTA Primario

Status: done

## Story

As a visitante ou participante recorrente,  
I want abrir uma home oficial que destaque imediatamente a entrada da reuniao,  
so that eu saiba em segundos onde clicar.

## Acceptance Criteria

1. **Given** que o usuario acessa a home em mobile ou desktop  
   **When** a pagina termina o carregamento inicial  
   **Then** o CTA principal da reuniao aparece acima da dobra.
2. **Given** que a home foi carregada  
   **When** o usuario observa a hierarquia visual da pagina  
   **Then** o CTA da reuniao e o unico elemento tratado como acao primaria.
3. **Given** que o usuario acessa a pagina com JavaScript indisponivel  
   **When** o HTML e renderizado  
   **Then** a home pode ser consumida sem JavaScript obrigatorio.

## Tasks / Subtasks

- [x] Substituir o placeholder atual por uma home SSR alinhada a arquitetura.
  - [x] Criar `public/index.php` como entrada HTTP real do MVP.
  - [x] Criar `app/bootstrap.php` e `app/views/home.php` para compor a resposta da home.
  - [x] Tratar `index.html` atual como legado descartavel, nao como base evolutiva.
- [x] Construir o hero principal com CTA dominante.
  - [x] Exibir CTA acima da dobra em viewport de 360px.
  - [x] Garantir que nenhum bloco secundario concorra visualmente com o CTA.
  - [x] Fazer o CTA consumir `meeting.join_url` e `meeting.title` vindos da fonte unica de verdade.
- [x] Aplicar a fundacao visual prevista no UX.
  - [x] Criar CSS base em `public/assets/css/` com tokens coerentes com a direcao escolhida.
  - [x] Usar azul profundo como base, texto claro e acento amarelo no CTA/foco.
  - [x] Priorizar layout mobile first em coluna unica para o conteudo critico.
- [x] Garantir acessibilidade estrutural ja no primeiro slice.
  - [x] Incluir skip link visivel ao foco.
  - [x] Definir landmarks e ordem semantica de headings.
  - [x] Garantir foco visivel e CTA acessivel por teclado.
- [x] Preparar a base para o card da reuniao sem duplicar dados criticos.
  - [x] Nao hardcodar horario, ID, senha, tipo ou link.
  - [x] Se `4.1` ainda nao estiver mergeada, implementar a fundacao exigida por ela no mesmo fluxo, em vez de criar placeholders divergentes.
- [x] Validar o slice.
  - [x] Verificar a home com JS desligado.
  - [x] Testar mobile 360px, tablet e desktop.
  - [x] Fazer smoke check visual de hierarquia, contraste e foco.

## Dev Notes

- Esta story e o primeiro slice visivel do MVP, mas depende do contrato de dados definido em `4.1`.
- O pacote tecnico esperado para o kickoff e:
  - `public/index.php`
  - `app/bootstrap.php`
  - `app/data/meeting.php`
  - `app/views/home.php`
  - `public/assets/css/...`
- Nao introduzir SPA, framework frontend, banco ou qualquer dependencia que desvie do monolito leve em PHP SSR.
- A home precisa nascer com a linguagem visual "Porta Oficial Acolhedora": sobria, confiavel, humana e com um unico CTA dominante.

### Project Structure Notes

- Views recebem dados resolvidos; nao fazem logica de negocio relevante.
- Links complementares nao devem aparecer acima da dobra nem competir com o CTA.
- A estrutura deve permanecer compativel com os futuros partials:
  - `app/views/partials/hero_meeting_cta.php`
  - `app/views/partials/meeting_info_card.php`
  - `app/views/partials/support_links.php`

### References

- Source: `_bmad-output/planning-artifacts/epics.md` -> `Epic 1 / Story 1.1`
- Source: `_bmad-output/planning-artifacts/architecture.md` -> `AD-01`, `AD-02`, `AD-03`, `AD-09`
- Source: `_bmad-output/planning-artifacts/architecture.md` -> `Architecture Overview`, `Implementation Patterns & Consistency Rules`
- Source: `_bmad-output/planning-artifacts/ux-design-specification.md` -> `Chosen Direction`, `Design Tokens`, `Layout Principles`, `Core Components`, `Accessibility Strategy`
- Source: `_bmad-output/planning-artifacts/implementation-readiness-report-2026-03-24.md` -> `MVP publico: READY`
- Source: `docs/project-context.md` -> `Git Workflow Integrado ao BMAD`

### Git Workflow

- Branch recomendado: `codex/feature/1-1-home-oficial`
- Commits devem usar Conventional Commits em PT-BR e referenciar este arquivo no `Ref:`
- Issue e PR devem citar esta Story como artefato de referencia
- Em PowerShell, usar `git commit -F <arquivo>` para mensagens com corpo

## Dev Agent Record

### Agent Model Used

Codex GPT-5

### Debug Log References

- Kickoff artifact created before implementation start.
- Issue GitHub aberta: `#3`.
- PR GitHub mergeado: `#4`.
- Lint, suite PHP e smoke render executados via `docker run ... php:8.2-cli`.

### Completion Notes List

- Home SSR oficial passou a renderizar um hero unico com CTA dominante consumindo `meeting.join_url` da fonte unica.
- `app/content/home.php` concentra a copy curta do hero para manter a view enxuta e alinhada ao padrao arquitetural de conteudo explicito.
- `app/views/partials/hero_meeting_cta.php` prepara a estrutura reutilizavel do primeiro bloco visual sem duplicar horario, ID, senha, tipo ou status.
- `public/assets/css/home.css` aplica a direcao visual "Porta Oficial Acolhedora" com base azul, texto claro, acento amarelo, skip link e foco visivel.
- `tests/run.php` agora valida a home pelo entrypoint publico, a ausencia de JS obrigatorio, o skip link, o CSS dedicado e a existencia de um unico CTA primario.
- Atualizacao 2026-05-27: refresh visual aplicado com OpenDesign, removendo glassmorphism, reduzindo competicao visual entre cards e mantendo o CTA como unica acao primaria.
- Atualizacao 2026-05-27: `app/views/home.php` ganhou header/footer sem navegacao complexa para reforcar contexto institucional sem competir com a reuniao.
- Atualizacao 2026-05-27: `app/views/partials/hero_meeting_cta.php` passou a exibir resumo compacto de status e horario perto do CTA, sem duplicar ID ou senha fora do card principal.

### File List

- `_bmad-output/implementation-artifacts/1-1-home-oficial-com-cta-primario.md`
- `app/content/home.php`
- `app/bootstrap.php`
- `app/views/home.php`
- `app/views/partials/hero_meeting_cta.php`
- `public/assets/css/home.css`
- `tests/run.php`
- `tmp/index-old.html` (snapshot local autocontido, ignorado pelo Git)
- `tmp/index-new.html` (snapshot local autocontido, ignorado pelo Git)
