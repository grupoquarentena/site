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

O antigo MVP, que nunca entrou em produção, permanece como referência histórica na branch `main`.

Consulte `AGENTS.md` antes de alterar requisitos, arquitetura ou código.