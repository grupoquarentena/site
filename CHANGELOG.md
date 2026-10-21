# Histórico de alterações — Site do Grupo QuarenteNA

Histórico sintético do projeto. Registrar mudanças relevantes na versão corrente; detalhes e evidências permanecem nas Issues, PRs e commits. **Não** criar changelogs por Story.

## Não lançado — Fundação Laravel (Issue #33)

- Preparada a aplicação mínima Laravel 13 / PHP 8.5 / PostgreSQL 17, com Docker Compose, configuração inicial do Swarm, testes e CI.
- Introduzido o vínculo obrigatório `usuarios.pessoa_id` com `pessoas`, permitindo pessoas sem conta; sem cadastro público ou permissões comerciais.
- Integrados os pontos de entrada do Laravel Boost; instalação BMAD já existente preservada.
- Auditoria estática independente e revisão de scripts, identidade, segurança, ambiente e documentação; **instalação, lockfile, migrations, testes de runtime e snapshot PostgreSQL ainda pendentes de homologação**.
- Documentação técnica conciliada com o planejamento aprovado, sem multiplicar artefatos.

## 2026-10-20 — Planejamento homologado e governança Git

- Homologados e versionados os cinco documentos canônicos: `QuarenteNA.md` (inventário histórico), `ProductBrief.md`, `PRD.md`, `UX-Design.md` e `Arquitetura.md` (`c7ca269`).
- Aprovadas as regras em `docs/git-workflow-bmad.md`, com `AGENTS.md`, README e templates de Issue/PR (`c55a1cc`).
- Definido o trabalho em branches derivadas de `dev`, com Issue, revisão independente, testes, PR, aprovação humana e squash; `main` continua padrão e estável.

## 2026-10-18 — Preservação do legado e preparação do planejamento

- Preservado o rascunho de 2025 em `legado/rascunho-2025`, com referência histórica `site-2025-rascunho`.
- Preservado o MVP anterior de 2026 em `legado/site-2026`, com referência histórica `site-2026-legado` (não implantado em produção).
- Separados os históricos legados do desenvolvimento do novo site, preservando `main` e estabelecendo `dev` como linha de integração.
- Preparada a base do BMAD e OpenCode para o planejamento da plataforma v2.

> Datas e referências identificam marcos de desenvolvimento, não releases ou implantações do novo Laravel. Não reescrever entradas históricas; acrescentar notas concisas por alteração relevante.
