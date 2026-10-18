# Grupo QuarenteNA — Plataforma Web

Repositório de planejamento e desenvolvimento do novo site e sistema administrativo do Grupo QuarenteNA.

**Estado:** planejamento aprovado; implementação da nova plataforma ainda não iniciada nesta branch.

## Documentação

Os cinco documentos canônicos estão em [`_bmad-output/planning-artifacts/`](_bmad-output/planning-artifacts/):

- `QuarenteNA.md` — contexto operacional e migração do legado.
- `ProductBrief.md` — visão e objetivos.
- `PRD.md` — requisitos aprovados.
- `UX-Design.md` — experiência e interface aprovadas.
- `Arquitetura.md` — decisões técnicas e modelo de dados.

## Desenvolvimento

- **Método:** BMAD 6.12.4.
- **Agentes:** OpenCode.
- **Base técnica prevista:** adaptação de `cirinho/template-laravel-bmad`.
- **Stack:** Laravel, PostgreSQL e Docker.
- **Laravel Boost:** utilizado na implementação.

O antigo MVP, que nunca entrou em produção, está preservado na [branch `legado/site-2026`](https://github.com/grupoquarentena/site/tree/legado/site-2026) e na [release histórica `site-2026-legado`](https://github.com/grupoquarentena/site/releases/tag/site-2026-legado). As branches `main` (estável) e `dev` (desenvolvimento) pertencem à nova plataforma.

Consulte `AGENTS.md` antes de alterar requisitos, arquitetura ou código.