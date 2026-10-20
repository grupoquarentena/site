# AGENTS.md — Grupo QuarenteNA

## Instruções obrigatórias

- Projeto: **Grupo QuarenteNA v2** (`GrupoQuarenteNA.org`); documentação/UX em pt-BR; fuso `America/Bahia`.
- A política **APROVADA** de Issues, branches, revisões, commits, PRs, squash, merges e releases é **[`docs/git-workflow-bmad.md`](docs/git-workflow-bmad.md)**. Ler antes de qualquer operação Git. `main` é a **branch padrão do GitHub e permanece congelada**; `dev` integra trabalho por PR, sem push direto no fluxo normal.
- Um agente/orquestrador conduz a tarefa; especialistas ou outros modelos são consultados somente sob demanda, **sem escrita simultânea nos mesmos arquivos**. Não presumir autorização para merge, deploy, mudança de escopo ou descarte de trabalho existente.

## Contexto e ferramentas

1. Ler esta política, a Issue/Story e **somente** as seções relevantes de `ProductBrief.md`, `PRD.md`, `UX-Design.md` e `Arquitetura.md` em `_bmad-output/planning-artifacts/`. `QuarenteNA.md` e `legado/*` são históricos, não requisitos normativos.
2. **BMAD 6.12.4** para Epics/Stories, aceite, status, revisão e mudanças de curso. **Laravel Boost**, MCP e documentação do runtime para implementar e testar Laravel. Nenhum deles substitui decisões humanas ou testes reais.
3. A aplicação fica em **`grupoquarentena/site`** e adaptará `cirinho/template-laravel-bmad` em uma futura branch baseada na `dev`, sem herdar indiscriminadamente os papéis comerciais do template. Uma mudança necessária nessa decisão de repositório deve ser conciliada na Arquitetura no mesmo PR da adaptação.

## Menos artefatos, mais consistência

- **Não gerar arquivos de relatório, changelog, ADR, resumo de review ou documentação paralela por Epic, Story ou fix.** Centralizar evolução e evidências na Issue, PR e artefatos BMAD ativos. Preferir **um `epics.md`** consolidado; permitir `sprint-status.yaml`, Story e `spec-{slug}.md` **somente quando realmente exigidos** pela versão instalada, atualizando-os em vez de criar duplicatas.
- Mudanças em requisitos/UX/arquitetura atualizam os **documentos canônicos existentes** no mesmo PR. Mudanças no schema: nova migration, testes e documentação/snapshot reais do template; não sobrescrever migrations aplicadas.
- `tmp/` é local/ignorado. **Após o uso, remover temporários criados pela tarefa que não sejam mais necessários**, sem eliminar material de outras tarefas ou evidências aguardando revisão.

## Segurança e qualidade

- Respeitar pessoa/usuário, encargo vigente, root excepcional auditável, segregação de Kit e tesouraria, anonimato, PII mínima e eventos idempotentes. Incluir testes de acesso negado quando pertinente.
- Nunca versionar `.env`, credenciais, dados pessoais reais, backups, dumps produtivos ou artefatos temporários.
- Antes de abrir PR: conferir diff completo da branch, inclusive arquivos novos; executar `git diff --check`, testes adequados e **review por outro modelo/sessão sem o histórico do implementador**, com evidências registradas na Issue/PR. Usar `./scripts/testar.sh` e `./scripts/testar.sh --postgres` conforme impacto **depois que a fundação estiver disponível**.
- **`review` até aprovação e merge; `done` só depois.** Código, testes, documentos afetados e rastreamento BMAD devem concordar para encerrar a Story. Agente não aprova ou mescla PR sem autorização humana expressa.
