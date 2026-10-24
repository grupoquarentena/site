# Histórico de alterações — Site do Grupo QuarenteNA

Histórico sintético do projeto. Registrar mudanças relevantes na versão corrente; detalhes e evidências permanecem nas Issues, PRs e commits. **Não** criar changelogs por Story.

## Não lançado — Planejamento pós-fundação (Issue #35)

- Encerrada a elaboração documental: responsável aprovou os Epics 6–15 em 24/10/2026, totalizando 15 Epics/101 Stories/483 AC/236 RF com planejamento condicionado. Conciliados PRD/Arquitetura e avisos vigentes, sem alterar corpos das Stories; validação formal permanece CONCERNS/draft pelos gates abertos, sem sprint, build, runtime homologado ou deploy. Integração por PR para dev e promoção específica para main autorizadas separadamente, sem mudança permanente da governança.

- Conferência cruzada final de 24/10/2026: conciliado o estado dos Epics 6–15 já detalhados, mas sem aceite humano, entre índice, Arquitetura e governança B; ressalvada a publicação de nome mínimo/opt-out no resumo, sem autorizar exposição de Zoom/Kit/contatos. Preservados integralmente os 101 corpos de Story, 483 AC, 236 RF e as aprovações condicionadas dos Epics 1–5. Esta conferência externa não executou o BMAD/Boost instalado nem revalidou o runtime; revisão humana continua pendente.
- Auditados os 15 Epics com revisores independentes e revalidação focal/global; corrigidas matrizes, cobertura funcional, dependências incrementais, privacidade e recuperação, preservando 101 Stories/483 AC/236 RF e o aceite condicionado de 1–5. Epics 6–15 aguardam aprovação humana; passo 03 mantido, sem sprint/build ou homologação de runtime.
- Restaurado o preflight B: normalização mecânica dos dez detalhes novos, proteção de finalização por elaboração/revisão/aprovações e fixture YAML seletiva, com regressões documentais positivas/negativas; pacote BMAD distribuído preservado.
- Harmonizada a estrutura B: índice global e detalhes por Epic, overrides de equipe por fatos persistentes, adaptador local explícito `quarentena-backlog` e regressões documentais somente leitura. BMAD distribuído preservado; sem novas Stories reais, sprint ou implementação, com compatibilidade nativa e adaptação local distinguidas.
- Iniciada a extração rastreável de requisitos em `epics.md`: 236 RF/22 famílias, 18 RNFs, 23 regras e 38 casos de aceite, com contratos UX e distinção entre baseline e trabalho futuro; decomposição e aprovação ainda pendentes.
- Confirmado o inventário pelo responsável e apresentada proposta de 15 Epics por resultado funcional, com mapa individual dos 236 RF, dependências e contratos transversais; estrutura aguarda aprovação, sem Stories ou sprint planning.
- Revisada a proposta após avaliação humana: atendimento antecipado ao Epic 2, pré-preenchimento local coordenado por 5, editor básico em 12 e recuperação inicial em 14 desde a baseline; dependências por capacidade substituem barreiras de conclusão de módulos inteiros, sem antecipar aceite integral dos RFs.
- Aprovada definitivamente a revisão 2 dos 15 Epics pelo responsável; iniciado o passo 03 pelo Epic 1, com alcance de auditoria e autorização da primeira designação em discussão antes de finalizar a primeira Story. Nenhuma Story foi marcada implementada ou homologada.
- Aprovadas as diretrizes de auditoria não recursiva/atômica e bootstrap CLI inaugural pessoal com autorização institucional independente, consumo permanente e concorrência PostgreSQL; conciliados PRD/Arquitetura preservando IDs. A Story 1.1 e seu mecanismo de prova continuam sujeitos a aprovação específica, sem execução em produção.
- Conciliadas referências temporais do Brief, PRD e Arquitetura com a fundação integrada pela #33 / PR #34, sem declarar os módulos de negócio implementados nem reexecutar seus testes históricos.
- Registradas dependências operacionais, identidade visual #30 e validação futura do checkout Windows/WSL escolhido, sem alterar código funcional ou infraestrutura.
- Consolidadas e revisadas documentalmente as 39 propostas dos Epics 1–5 (235 AC/103 RF principais), com correções de estados, autorização, PII, persistência e eventos; preservados os 15 Epics/236 RF, gates humanos e passo 03 em draft, sem aprovação de Stories, testes de runtime ou sprint.

## Não lançado — Fundação Laravel (Issue #33)

- Gerado e validado o lockfile real no Docker: Laravel 13.38.0, PHP 8.5 e Boost 2.10.3 apenas em desenvolvimento; auditoria Composer sem avisos de vulnerabilidade.
- Corrigidos build do PHP 8.5 (OPcache integrado), bootstrap de proxies, tipagem das relações e isolamento do cache Larastan na verificação Pint.
- Homologados SQLite e PostgreSQL 17, login case-insensitive, constraints de identidade, HTTP com CSRF e persistência de sessão após restart.
- Adicionado ambiente `.env.dev` por link local protegido, integração MCP OpenCode v2/WSL e stack de homologação interna isolada no CIRO, sem portas ou redes públicas.
- Gerados snapshot e catálogo reais; incluídos histórico técnico de migrations e cliente PostgreSQL 17 para restauração Laravel, também na CI.
- Configurado o repositório oficial PGDG com assinatura na CI descartável após falha real de disponibilidade do cliente PostgreSQL 17 no Ubuntu do runner.
- Preparado `.env` descartável e restrito na CI para eliminar warnings reais do Dotenv; PHPUnit configurado para falhar em todos os issues, sem suprimi-los.
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
