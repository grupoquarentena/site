> **Documento histórico do MVP (arquivado em 18/10/2026; nunca publicado).**
> Contexto operacional usado por agentes durante a implementação do MVP PHP em
> 2026, preservado como registro de processo. O "estado de entrega" abaixo é o
> do kickoff, em março de 2026; o resultado final está registrado em
> `_bmad-output/planning-artifacts/` e no README da branch `legado/site-2026`.

# Project Context for AI Agents

_Este arquivo concentra regras criticas que agentes devem seguir ao implementar neste projeto. Priorize o que evita retrabalho, historico sujo e desvio do metodo BMAD._

---

## Technology Stack & Versions

- MVP publico em **PHP SSR leve**
- Sem SPA no MVP
- Sem banco de dados no MVP
- Sem `/admin` no MVP
- Assets estaticos simples em `public/assets/`
- Fonte unica de verdade em `app/data/meeting.php`
- Views server-rendered com logica minima

## Critical Implementation Rules

- O escopo autorizado agora e somente **Epics 1-5** do MVP publico.
- Epics 6-9 ficam em backlog futuro e nao entram em branch, scaffold ou implementacao parcial.
- O caminho principal da reuniao e o centro do produto.
- O CTA principal deve continuar unico e dominante acima da dobra.
- Nao hardcodar `horario`, `ID`, `senha`, `tipo`, `status` ou `link` em templates.
- Views recebem dados resolvidos; helpers devem ser pequenos e puros.
- O projeto nao deve evoluir a partir do `index.html` placeholder como base estrutural.
- A fundacao correta do produto e o pacote:
  - `public/index.php`
  - `app/bootstrap.php`
  - `app/data/meeting.php`
  - `app/views/home.php`
  - `public/assets/css/...`

## Git Workflow Integrado ao BMAD

- Nunca trabalhar direto em `main` ou `master`.
- Para humanos, use branches `feature/`, `fix/`, `hotfix/`, `refactor/`, `chore/`, `docs/`.
- Quando o branch for criado pelo Codex, respeite o prefixo da ferramenta:
  - `codex/feature/<story-id>-<slug>`
  - `codex/fix/<slug>`
- O nome do branch deve preferencialmente carregar o ID da Story BMAD.
- Todos os nomes de arquivo e branch devem usar apenas ASCII, sem espacos e sem acentos.
- Em Windows/PowerShell:
  - salvar arquivos em UTF-8 sem BOM
  - definir `$OutputEncoding = [System.Text.Encoding]::UTF8` antes de comandos que retornam texto
  - nunca usar `\n` literal em `git commit -m` ou comandos similares
  - usar arquivo temporario para mensagens longas, ex: `git commit -F msg.txt`
- Commits devem seguir **Conventional Commits em PT-BR** e referenciar a Story BMAD.
- Pull Requests e issues devem citar explicitamente o artefato em `_bmad-output/implementation-artifacts/`.
- Push frequente e squash merge na `main` sao o padrao preferido.
- Ao concluir e mesclar uma Story, atualizar o status pelo fluxo BMAD adequado.

## BMAD Integration Points

- A pasta `_bmad-output/implementation-artifacts/` e a fonte da verdade para execucao.
- Antes de codar, sempre verificar:
  - Story ativa
  - `architecture.md`
  - acceptance criteria da Story
- Toda implementacao deve preservar rastreabilidade para o artefato BMAD correspondente.
- Se houver conflito entre codigo existente e Story, a Story e a arquitetura guiam a decisao, salvo ajuste formal de planejamento.

## Current Delivery State (kickoff, março de 2026)

- Fase atual: implementacao aberta formalmente
- Stories de kickoff prontas:
  - `4-1-contrato-da-fonte-unica-de-verdade.md`
  - `1-1-home-oficial-com-cta-primario.md`
- Ordem tecnica recomendada do kickoff:
  1. `4.1`
  2. `1.1`
  3. `1.2`

## Linked Policy

- Politica detalhada: `docs/git-workflow.md`
