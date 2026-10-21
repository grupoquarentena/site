# Histórico de alterações — Site do Grupo QuarenteNA

Histórico sintético do projeto. Registrar mudanças relevantes na versão corrente; detalhes e evidências permanecem nas Issues, PRs e commits. **Não** criar changelogs por Story.

## Não lançado — Fundação Laravel (Issue #33)

- Gerado e validado o lockfile real no Docker: Laravel 13.38.0, PHP 8.5 e Boost 2.10.3 apenas em desenvolvimento; auditoria Composer sem avisos de vulnerabilidade.
- Corrigidos build do PHP 8.5 (OPcache integrado), bootstrap de proxies, tipagem das relações e isolamento do cache Larastan na verificação Pint.
- Homologados SQLite e PostgreSQL 17, login case-insensitive, constraints de identidade, HTTP com CSRF e persistência de sessão após restart.
- Adicionado ambiente `.env.dev` por link local protegido, integração MCP OpenCode v2/WSL e stack de homologação interna isolada no CIRO, sem portas ou redes públicas.
- Gerados snapshot e catálogo reais; incluídos histórico técnico de migrations e cliente PostgreSQL 17 para restauração Laravel, também na CI.
- Configurado o repositório oficial PGDG com assinatura na CI descartável após falha real de disponibilidade do cliente PostgreSQL 17 no Ubuntu do runner.
- Endurecidos os gates após review independente: preflight antes de migrations destrutivas, cache de testes isolado (validado com sentinela persistente), variantes PostgreSQL direta/snapshot, credenciais dotenv citadas, CSRF negativo e limite bcrypt em bytes; PHPUnit falha também em warnings/risky.

- Preparada a aplicação mínima Laravel 13 / PHP 8.5 / PostgreSQL 17, com Docker Compose, configuração inicial do Swarm, testes e CI.
- Introduzido o vínculo obrigatório `usuarios.pessoa_id` com `pessoas`, permitindo pessoas sem conta; sem cadastro público ou permissões comerciais.
- Integrados os pontos de entrada do Laravel Boost; instalação BMAD já existente preservada.
- Auditoria estática independente inicial e revisão de scripts, identidade, segurança, ambiente e documentação; evidências da homologação técnica e do review final permanecem na Issue/PR, sujeitos ao aceite humano.
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
