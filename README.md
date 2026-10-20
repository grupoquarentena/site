# Grupo QuarenteNA — Plataforma Web v2

Site público e sistema administrativo do **Grupo QuarenteNA**, para reuniões, atas, encargos, Kit Ingresso, tesouraria, relatórios e integrações, respeitando anonimato e responsabilidade humana.

- **Domínio previsto:** `https://GrupoQuarenteNA.org`
- **Status:** planejamento-base aprovado; governança Git/BMAD aprovada; aplicação Laravel ainda não integrada.
- **Método:** BMAD 6.12.4. **Implementação:** Laravel Boost + agentes coordenados.
- **Infraestrutura planejada:** Laravel 13/PHP 8.5, PostgreSQL 17, Docker Compose local, Docker Swarm e Cloudflare Tunnel em produção.

## Governança e desenvolvimento

- **`main`**: branch padrão GitHub, estável e congelada, com promoção apenas expressamente autorizada.
- **`dev`**: integração por PR revisado, não branch de trabalho direto.
- **Branches temporárias**: originadas da `dev`, uma Issue/escopo por alteração revisável.
- **`legado/*`**: material histórico, não reutilizar como arquitetura da v2.

**Instruções:** [`AGENTS.md`](AGENTS.md) · [Git workflow aprovado](docs/git-workflow-bmad.md). O fluxo normal é Issue → branch temporária → BMAD/Boost e testes → revisão independente → PR para `dev` → revisão e aprovação humanas → squash merge.

## Planejamento canônico

Arquivos em [`_bmad-output/planning-artifacts/`](_bmad-output/planning-artifacts/): `QuarenteNA.md` (AS-IS), `ProductBrief.md`, `PRD.md`, `UX-Design.md` e `Arquitetura.md` (decisões normativas). Epics, Stories e estado BMAD serão criados conforme o workflow oficial, evitando documentos paralelos.

## Próxima etapa técnica

A aplicação ficará **neste repositório** (`grupoquarentena/site`), por integração e adaptação controladas de [`cirinho/template-laravel-bmad`](https://github.com/cirinho/template-laravel-bmad) numa **branch temporária derivada de `dev`**, com testes iniciais, CI e PR para `dev`. A fundação precisa substituir os cadastros/papéis comerciais por pessoas, usuários e encargos do QuarenteNA.

Após a integração, reutilizar os wrappers existentes do template, especialmente `./scripts/testar.sh` e `./scripts/testar.sh --postgres` quando aplicável. Esses comandos **ainda não representam testes executados no repositório atual**. Build de imagem, integração, promoção para `main` e implantação em produção são ações distintas.
