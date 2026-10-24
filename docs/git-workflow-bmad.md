# Git workflow integrado ao BMAD — Grupo QuarenteNA

> **Status: APROVADO.** Política operacional única para humanos e agentes. `AGENTS.md` aponta para este documento; o `README.md` apresenta apenas o projeto. Aplica-se ao repositório `grupoquarentena/site`.

## 1. Branches e autoridade

| Branch | Contrato |
|---|---|
| `main` | **Padrão do GitHub**, estável/congelada. Sem desenvolvimento ou push direto. Só recebe promoção `dev → main`, hotfix ou bootstrap administrativo com autorização humana **expressa e específica**. |
| `dev` | Integração. Todo trabalho normal chega por PR revisado; sem commit ou push direto, salvo exceção pontual expressamente autorizada. |
| `feature/*`, `fix/*`, `chore/*`, `refactor/*`, `test/*`, `docs/*`, `bmad/*` | Temporárias, nascem do `origin/dev` atualizado e fazem PR **para `dev`**. |
| `hotfix/*` | Excepcional: parte de `main` somente após autorização expressa; retorna também à `dev` após a correção. |
| `legado/*` | Histórico, somente leitura; jamais base de implementação. |

- Nome preferido: `<prefixo>/issue-<n>-<resumo>`; exemplo `feature/issue-42-atas-servico`. Um escopo revisável por Issue/PR; não criar Issue por requisito RF individual.
- Preservar trabalho não commitado. Nunca usar `reset --hard`, `clean`, `stash`, rebase compartilhado, push forçado, exclusão de branch com trabalho exclusivo ou mudanças de configuração sem autorização.
- O agente pode executar **commits locais na branch temporária** durante o trabalho, inclusive quando a skill BMAD assim proceder; nenhum commit local significa aceite/merge. Push apenas para a branch temporária autorizada.
- A `main` **não será alterada para `dev` como branch padrão**.

## 2. Fluxo obrigatório por Issue / Story

1. **Issue.** Pesquisar duplicatas; informar problema, objetivo, escopo/limites, referência à Story/Epic/RF quando pertinente, critérios de aceite e riscos. Atribuir a `@grupoquarentena` e `@cirinho` **se ambas as contas forem elegíveis**; usar apenas labels, milestones e relações realmente disponíveis. Registrar dependências e vincular a branch/PR pelo campo **Development**.
2. **Branch.** Conferir `git status` e sincronizar somente com fast-forward seguro. Criar a branch a partir de `origin/dev`, **não** pelo botão genérico “Create a branch” sem checar a base. Se houver mudanças locais conflitantes, parar e preservar os dados. Exemplo: `git fetch origin dev && git switch -c feature/issue-42-atas-servico origin/dev`.
3. **Execução.** Ler `AGENTS.md`, Issue/Story e apenas os trechos relevantes de PRD/UX/Arquitetura. **BMAD** governa requisitos, aceite, estado e mudança de curso; **Laravel Boost** consulta o Laravel real, documentação da versão, MCP e convenções. Um orquestrador coordena especialistas sob demanda; edições simultâneas no mesmo arquivo são proibidas. Mudança substantiva no contrato aprovado: justificar na Issue, solicitar aprovação e aplicar `bmad-correct-course` quando cabível.
4. **Qualidade.** Executar testes focados e negativos, revisão de permissão/PII, `git diff --check` e gates aplicáveis (tabela §4). Conferir todas as alterações, inclusive arquivos novos, migrations e documentos afetados. Não declarar executado o que ainda não existe na fundação.
5. **Review técnico independente.** Antes do PR, solicitar **outro modelo/sessão sem histórico do implementador**, fornecendo Issue/Story, contratos afetados, comandos e resultados de testes e **todo o diff** contra a base `origin/dev`. Incluir commits locais (`git diff origin/dev...HEAD`), staged/unstaged e arquivos untracked (inspecionar `git status --short` e ler arquivos novos; `git diff` isolado não os cobre). Preferir a skill de code review BMAD, **sem confiar em base implícita `main` nem aceitar marcação automática `done`**. Registrar achados e correções na Issue/PR, não em relatórios permanentes. Correções materiais exigem nova conferência.
6. **PR.** Após validação, fazer push **somente da branch temporária** e abrir PR com **base explícita `dev`**. Preencher o template, vincular manualmente Issue/PR em **Development**, atribuir responsáveis elegíveis e pedir revisão manual a conta elegível **distinta da autora do PR**. Agente não aprova o próprio PR nem faz merge autônomo.
7. **Aceite e squash.** Responsável confere PR, Issue, testes, CI (se existir), achados resolvidos e diff; ajustes permanecem no **mesmo PR**. Com autorização humana expressa, realizar **squash merge para `dev`**, consolidando os commits da branch em **um único commit** no histórico de integração. Editar o título e **o corpo do commit de squash** conforme §3, em vez de aceitar uma lista automática extensa de commits.
8. **Encerramento.** Verificar merge no remoto, sincronizar `dev` local por fast-forward e remover a branch temporária local/remota **somente após conferir que nada exclusivo será perdido**. Corrigir título/descrição de Issue ou PR se necessário, sem apagar histórico relevante nem reescrever comentários de outras pessoas. Fechar a Issue **manualmente** com nota concisa: solução, PR, commit e testes. Atualizar o estado BMAD: `review` enquanto aguarda aprovação/merge, `done` somente após integração e conferência final.

```mermaid
flowchart LR
    I[Issue e Story] --> B[Branch de origin/dev]
    B --> E[BMAD + Boost + testes]
    E --> R[Review independente]
    R --> P[Push + PR para dev]
    P --> H{Aprovação humana?}
    H -->|Ajustes| E
    H -->|Sim| M[Squash merge na dev]
    M --> F[Sync + fechamento da Issue]
```

**GitHub com `main` padrão:** as palavras-chave `Closes/Fixes #N` em PR para `dev` não criam o vínculo automático nem fecham a Issue. Usar **Development** manualmente e `Refs #N` como referência textual; encerramento após o merge é manual. As contas `@grupoquarentena` e `@cirinho` são tratadas como usuários **quando elegíveis**, não como equipe presumida. Reviewer não pode ser o autor.

## 3. Mensagens de commit e squash

- Commits locais: `tipo(escopo): resumo objetivo em pt-BR` (`feat`, `fix`, `docs`, `chore`, `refactor`, `test`, `perf`). Permitir checkpoints locais relacionados à Issue; não alterar histórico remoto compartilhado por amend/rebase sem autorização.
- **No squash da PR**: uma linha de título (`tipo(escopo): resumo`), **uma linha em branco** e, no corpo, **2–5 tópicos curtos com as modificações principais**, testes/decisões relevantes e referência `Refs #N` ou Story. Não colar logs extensos, dezenas de commits intermediários ou relatório de review.

```text
feat(atas): implementar revisão de atas de serviço

- Implementa rascunho, conclusão e retificação auditada.
- Ajusta Policies e testes de autorização.
- Atualiza os contratos afetados no PRD e na Arquitetura.

Refs #42 | Story 3.2
```

- O responsável pode usar o botão **Squash and merge** e editar título/corpo antes de confirmar. **Merge em `dev` não implica release nem deploy**.

## 4. BMAD, Boost e documentação mínima

- Preservar **cinco documentos canônicos** em `_bmad-output/planning-artifacts/`; `QuarenteNA.md` é AS-IS. Alterou requisito, fluxo UX ou arquitetura? Corrigir a seção existente no **mesmo PR**, com rastreabilidade na Issue/PR, sem gerar versão paralela. Se mudar banco: migration nova + testes PostgreSQL + snapshot real e `docs/banco/db.md` conforme o template; nunca produzir dump fictício ou reescrever migration aplicada em ambiente compartilhado.
- Menos é mais: **estrutura B aprovada na #35** — `epics.md` como índice global e `epics/epic-NN.md` como detalhamento exclusivo por Epic, sem cópia monolítica ou relatórios paralelos. Aplicar §4.1. Na execução, admitir somente arquivos efetivamente exigidos pelo BMAD instalado, por exemplo `sprint-status.yaml`, Story identificada por chave e `spec-{slug}.md` quando `bmad-build` a utilizar. Não apagar contratos ativos necessários à continuidade/review.
- Achados adiados, decisões, evidências de teste e review: **Issue/PR** (ou seção exigida de um artefato ativo), não criar `review.md`, `deferred-work.md`, ADR, relatório de teste ou changelog por Story **por padrão**. Se uma skill exigir outro arquivo para funcionar, demonstrar a necessidade antes e evitar duplicação; customizações de skills devem ter fonte versionada e ser resistentes a atualizações.
- **`tmp/`** é área local ignorada: arquivos temporários criados pela tarefa devem ser eliminados quando não forem mais necessários; **nunca apagar material de outra tarefa** nem relatórios que o responsável ainda precise analisar.
- Não replicar regras Git em README/AGENTS. Usar contexto mínimo da Issue/Story e Boost no código, evitando carregar todos os documentos e vários modelos sem necessidade.

| Alteração | Evidência obrigatória quando aplicável |
|---|---|
| Documentos | `git diff --check`, tabelas/links, IDs e referências pertinentes |
| Laravel | `./scripts/testar.sh`: Pint, Larastan, testes Unit/Feature, quando instalado |
| Banco | Migration, testes `./scripts/testar.sh --postgres`, snapshot/documentação física quando existir |
| Root, identidade, PII, Kit ou tesouraria | Testes de acesso permitido/negado, autoria, auditoria e revisão de segurança |
| Webhook/integrações | Autenticidade, idempotência, replay, retry, falhas e dados minimizados |
| Infra/release | CI disponível, backup/restore conforme impacto, imagem reproduzível, rollback e autorização humana |

<a id="backlog-b"></a>

### 4.1. Contrato operacional do backlog B — BMAD 6.12.4 / OpenCode

- **Autoridade:** `_bmad-output/planning-artifacts/epics.md` contém inventário, ownership RF → Epic, catálogo macro, contratos transversais, índice e estado global. `epics/epic-NN.md` contém Stories/AC, matriz e gates próprios; nunca duplicar essas definições no índice. Brief/PRD/UX/Arquitetura continuam normativos; QuarenteNA é histórico.
- **Estado único:** `currentStep`, `currentEpic`, `approvedStoryIds`, `pendingDetailedEpics`, `pendingReviewEpics` e aprovação global vivem apenas no YAML do índice. `pendingDetailedEpics` indica detalhamento incompleto; `pendingReviewEpics` indica arquivo já detalhado cuja aprovação humana continua pendente (mesmo após auditoria documental). O aviso vigente no detalhe registra aprovação específica e distingue evidência histórica; não replicar listas globais. `approvedStoryIds` é aceite documental, não readiness, implementação ou produção. Não alterar aprovações sem registro humano explícito; D1–D7 e demais gates permanecem independentes.
- **Entrada de continuidade:** usar explicitamente a skill local `.agents/skills/quarentena-backlog/SKILL.md` para retomar/validar a estrutura B. Ela adapta apenas roteamento de arquivos, reutilizando critérios dos passos 03/04 instalados. O criador BMAD distribuído ainda exige monólito: seus overrides carregam este contrato, mas não substituem passos. Se invocado diretamente para escrever/finalizar, parar antes de inicializar/salvar e oferecer a entrada local; não encenar redirecionamento nativo, reiniciar passos 01/02 ou copiar o workflow inteiro.
- **Consulta dirigida:** ler índice, resolver Epic N para `epics/epic-NN.md` (dois dígitos) e localizar `### Story N.M:`/âncora `story-N-M`. Ler somente trechos normativos e predecessoras/consumidores relevantes, sem carregar todos os detalhes por padrão. Conferência global percorre todos os detalhes existentes. Próximo Epic é pendente, não autorização para iniciá-lo. Os detalhes dos Epics 1–15 já existem; não criar placeholders adicionais nem reabrir/regerar os existentes sem autorização.
- **Escrita:** somente coordenador, em Epic expressamente autorizado. Arquivo existente é lido e seu hash conferido antes de editar; nunca substituí-lo por template. Novo arquivo somente ao iniciar detalhamento autorizado. Preservar IDs, ACs, gates e proveniência; atualizar no índice apenas estado/link/contagens necessários. Revisão individual e global com checkpoint humano; escrita inesperada ou divergência de hash exige investigação.
- **Validação distribuída:** índice + detalhes formam um backlog lógico. `uv run scripts/validar-backlog.py` é verificação somente leitura de inventário/mapa, IDs/AC, matrizes, YAML e links; `--story 3.2` faz descoberta dirigida. Epic em elaboração pode permanecer em `pendingDetailedEpics` mesmo após criar seu arquivo; sua cobertura incompleta aparece em `pending_coverage`, não como aprovação nem corrupção. Um Epic detalhado, inclusive se ainda constar de `pendingReviewEpics`, deve ter matriz completa e consistente; matriz ausente/incompleta é erro, salvo o tratamento explícito de elaboração parcial. Não prova semântica dos requisitos, ausência de circularidade ou readiness. Validação final real exige também auditoria semântica e grafo de dependências, todos os Epics detalhados/aprovados e gates pertinentes resolvidos; manter `draft` e passo 03 enquanto incompleto. Não concluir passo 04 por parsing bem-sucedido nem refazer a fundação #33/PR #34.
- **Aceite versus prontidão:** `storyElaborationComplete: true` registra elaboração aceita; `finalValidationStatus: concerns-open-gates` conserva `draft`/passo 03 mesmo com 101 IDs aprovados. A saída `documentary_approval_complete` não implica `planning_complete` ou autorização de implementação. `finalValidationStatus: passed` somente após validação efetiva e decisão autorizada; não usar a integração Git de planejamento condicionado para fechar gates por inferência. A #35 pode encerrar sua entrega documental sem concluir passos 03/04 formalmente ou gerar sprint.
- **Readiness/sprint:** readiness-only para após seu parecer. Parser de sprint recebe explicitamente só os detalhes existentes/aprovados; índice é contexto, nunca `--epic-file`. O parser não lê aprovações/gates e deduplica por slug, não por ID: validar unicidade `N.M` antes. Títulos auxiliares podem gerar warnings que devem ser classificados, não ocultados. `--epic-file` repetível não autoriza gerar tracking; sprint exige pedido específico, backlog completo e gate permitido. Sem geração de `sprint-status.yaml` por homologação documental.
- **Create/Dev Story reais:** não há shims desses nomes nesta instalação; o consumidor é `bmad-build`. Somente quando autorizado, passar o detalhe correto como arquivo do Epic e índice/contratos/ACs/gates como contexto explícito; spec e arquivos de implementação são just-in-time. Contexto compilado não substitui ACs canônicos; invalidar cache se mudar índice, qualquer detalhe relevante ou fonte normativa, incluindo subdiretórios. Não reutilizar cache só pela recência dos arquivos no nível superior.
- **Reviews/correct-course:** alvo por ID resolve para seu detalhe; carregar contratos e produtores/consumidores afetados. Correct-course não pode tratar o índice como monólito completo e ignorar detalhes. Review usa base explícita `origin/dev`, inclui untracked e não altera aprovação nem marca `done` antes do merge. Achados ficam na resposta/Issue/PR ou seção ativa, sem relatório novo por Epic.
- **Help/agentes:** observar YAML e aviso vigente, não mera existência de arquivos nem estados históricos. Ler o estado físico antes de recomendar avanço; informar pendências e oferecer continuidade via entrada local sem executá-la. Analyst/PM/Architect/UX/Dev instalados seguem AGENTS; SM/QA são perspectivas, não agentes instalados presumidos.
- **Durabilidade/limite:** overrides team em `_bmad/custom/*.toml` usam apenas campos expostos. A skill local e o validador são adaptações do projeto, não recursos nativos do pacote; sem edição de `.agents/skills/bmad-*`, `_bmad/scripts` ou comandos gerados. Após upgrade, conferir superfícies/consumidores e repetir regressões documentais antes de escrever. Orientação a LLM não é sandbox de filesystem.

## 5. GitHub, promoção e implantação

- **Templates `.github/`**: como `main` é a branch padrão congelada, templates apenas na `dev` **não aparecem automaticamente** na interface de novas Issues/PRs. Sua disponibilização exige **uma autorização expressa e específica para bootstrap administrativo mínimo da `main`** (somente templates necessários), com PR/validação e posterior reconciliação `main → dev`. Até lá, criar Issue/PR com corpo explícito/manual; não mudar a branch padrão nem promover código de aplicação para ativar templates.
- Configurar branch protection/rulesets em `main` e `dev` quando o plano permitir: PR obrigatório, checks efetivos, bloqueio de force-push/exclusão, conversas resolvidas; se indisponível, **não alegar proteção técnica**. A CI Laravel já foi adicionada na branch da Issue #33; validar os checks efetivos antes de integrá-la na `dev`.
- **Promoção `dev → main`**: exclusivamente PR e autorização humana expressa, com testes/release/rollback. Preferir **merge commit** para registrar promoção; depois reconciliar `main` de volta na `dev` e verificar a ancestralidade. Hotfix originado de `main` segue exceção autorizada e retorna à `dev`.
- **Build ≠ deploy**: tags mutáveis `dev`/`latest` são aliases; releases e rollback devem identificar imagem imutável por SHA/digest. Publicar imagem não autoriza migrations, Cloudflare, Swarm ou deploy; produção exige autorização separada.

**Checklist de PR/Issue:** escopo e aceite; review independente; testes verdadeiros; contratos atualizados; vínculo Development; aprovação humana; squash legível; `dev` sincronizada; branch descartada com segurança; Issue encerrada; nenhuma promoção ou implantação implícita.
