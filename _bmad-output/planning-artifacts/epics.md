---
status: draft
stepsCompleted:
  - step-01-validate-prerequisites
  - step-02-design-epics
currentStep: step-03-create-stories
checkpoint: elaboracao-documental-aprovada-15-epics-readiness-com-gates-abertos
layout: index-plus-epic-files
layoutVersion: 1
approvedPlanningEpicDetails: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15]
planningApprovalDate: 2026-10-24
planningApprovalScope: story-documents-only-with-open-gates
storyElaborationComplete: true
finalValidationStatus: concerns-open-gates
implementation_authorized: false
pendingDetailedEpics: []
pendingReviewEpics: []
epicDetailDirectory: _bmad-output/planning-artifacts/epics
epic1HumanApproval: approved-conditional-planning
epic1ReviewMode: propostas-em-lote-com-auditoria-independente
epic1ProposedStoryCount: 12
epic1IndependentReview: completed-with-corrections-revalidated
epic1DecisionGates: [D1, D2, D3, D4, D5, D6, D7, politica-de-dados-por-classe]
currentEpic: 15
epics2to5HumanApproval: approved-conditional-planning
epics2to5ReviewMode: propostas-em-lote-com-revisao-independente-do-conjunto
epics2and3HumanApproval: approved-conditional-planning
activeBatchEpics: []
approvedStoryIds: ["1.1", "1.2", "1.3", "1.4", "1.5", "1.6", "1.7", "1.8", "1.9", "1.10", "1.11", "1.12", "2.1", "2.2", "2.3", "2.4", "2.5", "2.6", "3.1", "3.2", "3.3", "3.4", "4.1", "4.2", "4.3", "4.4", "4.5", "4.6", "4.7", "4.8", "5.1", "5.2", "5.3", "5.4", "5.5", "5.6", "5.7", "5.8", "5.9", "6.1", "6.2", "6.3", "6.4", "6.5", "6.6", "6.7", "7.1", "7.2", "7.3", "7.4", "7.5", "7.6", "8.1", "8.2", "8.3", "8.4", "8.5", "8.6", "9.1", "9.2", "9.3", "9.4", "9.5", "10.1", "10.2", "10.3", "10.4", "11.1", "11.2", "11.3", "11.4", "11.5", "11.6", "12.1", "12.2", "12.3", "12.4", "12.5", "12.6", "12.7", "12.8", "13.1", "13.2", "13.3", "13.4", "13.5", "13.6", "14.1", "14.2", "14.3", "14.4", "14.5", "14.6", "15.1", "15.2", "15.3", "15.4", "15.5", "15.6", "15.7", "15.8"]
story11DirectivesApproved: true
requirementsInventoryApproved: true
epicsApproved: true
epicsConceptApproved: true
proposalRevision: 2
issue: 35
baselineCommit: 4ad7814bca49b89ffbb39f19b60c6c389f68ccd8
inputDocuments:
  - _bmad-output/planning-artifacts/ProductBrief.md
  - _bmad-output/planning-artifacts/PRD.md
  - _bmad-output/planning-artifacts/UX-Design.md
  - _bmad-output/planning-artifacts/Arquitetura.md
evidenceDocuments:
  - _bmad-output/planning-artifacts/QuarenteNA.md
  - database/schema/pgsql-schema.sql
  - docs/banco/db.md
  - AGENTS.md
  - docs/git-workflow-bmad.md
---

# Grupo QuarenteNA v2 — Epics e Stories

## Visão geral

Backlog derivado dos quatro documentos normativos aprovados, no workflow `bmad-create-epics-and-stories` (BMAD 6.12.4), sob a [Issue #35](https://github.com/grupoquarentena/site/issues/35). **Inventário/revisão 2 aprovados; elaboração documental dos 15 Epics e 101 Stories aceita como planejamento condicionado.** Epics 1–5 foram aceitos em 23/10 e 6–15 em 24/10/2026. Passos 01/02 concluídos; passos 03/04 não concluídos formalmente por gates de prontidão abertos. Decisões operacionais pendentes, implementação, readiness e produção não estão aprovadas.

Os IDs RF/RNF/RB/CA abaixo são os IDs originais do [PRD.md](PRD.md); as descrições são extratos para planejamento e não substituem suas condições completas. Em especial, títulos resumidos não dispensam condições de autorização, privacidade, exceção root, idempotência e aceite presentes na linha original. Não converter os IDs para FR/NFR nem renumerá-los. Os identificadores `UX-DR` são referências locais de extração de contratos UX, não equivalências ou novos IDs do PRD.

`QuarenteNA.md` e Issues antigas são evidências históricas, nunca requisitos concorrentes. A fundação da [#33](https://github.com/grupoquarentena/site/issues/33), integrada pelo [PR #34](https://github.com/grupoquarentena/site/pull/34), é baseline reutilizável; não gerar Stories fictícias concluídas nem um Epic para refazê-la. Os detalhamentos dos Epics 1–15 aprovados como planejamento condicionado ficam nos arquivos individuais vinculados; arquivos individuais de Story para execução permanecem just-in-time quando exigidos pelo workflow de implementação.

## Estado do backlog segmentado — fonte autoritativa

**Aceite humano vigente — 24/10/2026:** o responsável aprovou expressamente os Epics 6–15 e suas 62 Stories como **planejamento condicionado**, somando-os aos Epics 1–5. Estão aceitos documentalmente **15 Epics / 101 Stories / 483 AC / 236 RF / 22 famílias**, com parecer **CONCERNS**. A elaboração documental está encerrada; não há Epic aguardando redação ou aceite documental. As notas anteriores de proposta/revisão/aceite pendente são proveniência histórica e não revogam este aceite.

**Validação final autorizada, sem PASS de prontidão:** cobertura, IDs, matrizes, referências e grafo são verificáveis no backlog lógico B. Os critérios de prontidão do passo 04 ainda encontram decisões institucionais abertas (D1–D7, E2-K e demais gates pertinentes). Por isso `status: draft`, passo 03 e apenas passos 01/02 concluídos permanecem no YAML; `storyElaborationComplete: true` registra o término da elaboração, enquanto `finalValidationStatus: concerns-open-gates` impede confundi-lo com conclusão formal dos passos 03/04 ou `planning_complete: true`. `currentEpic: 15` identifica o último Epic conferido, não um Epic 16 ou próximo trabalho autorizado. Nenhuma implementação, sprint, dado real, migration ou deploy está autorizado. O responsável autorizou separadamente a integração Git da #35 e promoção por PR para main como exceção específica, sem alterar permanentemente a governança.

**Continuidade operacional:** [Issue #36 — runtime/Boost e readiness antes do sprint](https://github.com/grupoquarentena/site/issues/36) acompanha a próxima fase, com os gates canônicos preservados neste índice e nos detalhes; [#30](https://github.com/grupoquarentena/site/issues/30) continua responsável pela identidade visual. O validador distingue aceite documental completo de prontidão formal e nega autorização de implementação neste backlog; suíte ampliada para 28 regressões, incluindo todos os IDs aprovados com gates abertos e tentativa de autorizar build.

**Aprovação humana em 23/10/2026:** aceito o parecer BMAD **CONCERNS** para os Epics 1–5 como **planejamento condicionado**, após revisão documental do OpenCode (12 achados corrigidos; 11 MAJOR e 1 MINOR) e conferência estrutural da versão recebida. A aprovação não resolve D1–D7, E2-K, PII do texto livre da ata, continuidade administrativa, destino/canal de avisos, política por classe e gates operacionais; **não autoriza implementar as Stories afetadas até decidir os respectivos bloqueios**, usar dados reais ou avançar o passo 04. As revisões independentes registradas são as relatadas no artefato histórico; esta reorganização não executou subagentes novos.

**Status BMAD vigente:** elaboração e aceite documental dos 15 Epics concluídos; validação formal de prontidão com gates abertos, sem sprint planning. O YAML é o estado global único; detalhes não replicam `approvedStoryIds`. O desenho macro da revisão 2 continua aprovado. A conclusão da Issue #35 refere-se à entrega/integração do planejamento condicionado, não à implementação ou aprovação das decisões pendentes.

**Fonte de verdade:** o índice `epics.md` contém inventário normativo derivado, mapa RF → Epic principal, os 15 Epics macro, contratos compartilhados, estado global e links. Os arquivos `epics/epic-NN.md` contêm **exclusivamente** Stories/ACs, rastreabilidade RF → Story → AC, gates e proveniência próprios. Não existe segunda cópia monolítica de Stories nem compilador de sincronização; não voltar a acrescentar Stories ao índice.

| Epic | Detalhamento canônico | Stories | AC | RF principais | Aprovação de planejamento |
|---|---|---:|---:|---:|---|
| 1 | [epic-01.md](epics/epic-01.md) | 12 | 82 | 38 | Condicionada |
| 2 | [epic-02.md](epics/epic-02.md) | 6 | 33 | 14 | Condicionada |
| 3 | [epic-03.md](epics/epic-03.md) | 4 | 25 | 6 | Condicionada |
| 4 | [epic-04.md](epics/epic-04.md) | 8 | 41 | 16 | Condicionada |
| 5 | [epic-05.md](epics/epic-05.md) | 9 | 54 | 29 | Condicionada |
| **1–5** | **39 Stories** | **39** | **235** | **103** | **Não equivale a build** |
| 6 | [epic-06.md](epics/epic-06.md) | 7 | 28 | 24 | Condicionada em 24/10/2026 |
| 7 | [epic-07.md](epics/epic-07.md) | 6 | 24 | 21 | Condicionada em 24/10/2026 |
| 8 | [epic-08.md](epics/epic-08.md) | 6 | 24 | 12 | Condicionada em 24/10/2026 |
| 9 | [epic-09.md](epics/epic-09.md) | 5 | 20 | 13 | Condicionada em 24/10/2026 |
| 10 | [epic-10.md](epics/epic-10.md) | 4 | 16 | 5 | Condicionada em 24/10/2026 |
| 11 | [epic-11.md](epics/epic-11.md) | 6 | 24 | 14 | Condicionada em 24/10/2026 |
| 12 | [epic-12.md](epics/epic-12.md) | 8 | 32 | 15 | Condicionada em 24/10/2026 |
| 13 | [epic-13.md](epics/epic-13.md) | 6 | 24 | 6 | Condicionada em 24/10/2026 |
| 14 | [epic-14.md](epics/epic-14.md) | 6 | 24 | 11 | Condicionada em 24/10/2026 |
| 15 | [epic-15.md](epics/epic-15.md) | 8 | 32 | 12 | Condicionada em 24/10/2026 |
| **6–15** | **Detalhamento aceito** | **62** | **248** | **133** | **Planejamento condicionado; sem build** |
| **1–15** | **Elaboração documental encerrada** | **101** | **483** | **236** | **Todos aprovados condicionalmente; gates preservados** |

**Auditoria anterior e aceite vigente:** a auditoria OpenCode de 23–24/10/2026 corrigiu/revalidou **62 Stories / 248 AC / 133 RF** dos Epics 6–15 e preservou **39 Stories / 235 AC / 103 RF** dos Epics 1–5. **Total: 101 Stories / 483 AC / 236 RF / 22 famílias / 15 Epics.** Após essa auditoria o responsável aprovou os Epics 6–15 em 24/10/2026; `pendingReviewEpics: []` registra ausência de aceite documental pendente. O histórico abaixo preserva os estados anteriores, não autorização de implementação nem conclusão formal de readiness.

**Consulta dirigida:** para ler/escrever uma Story, abrir somente o Epic correspondente, o índice e as cláusulas normativas pertinentes. IDs e âncoras explícitas estáveis: [Story 1.1](epics/epic-01.md#story-1-1), [Story 2.5](epics/epic-02.md#story-2-5), [Story 3.4](epics/epic-03.md#story-3-4), [Story 4.8](epics/epic-04.md#story-4-8), [Story 5.9](epics/epic-05.md#story-5-9). As 39 Stories conservam a numeração e conteúdo original de AC; os links usam âncoras independentes do título da Story.

**Contrato da customização BMAD v6.12.4:** o passo 03 publicado escreve diretamente em `{planning_artifacts}/epics.md`; esta segmentação é **convenção de projeto aprovada**, não suporte nativo presumido. A continuidade usa explicitamente a skill local `quarentena-backlog`, conforme [contrato operacional central](../../docs/git-workflow-bmad.md#backlog-b); os overrides oficiais carregam esse contrato, mas não substituem passos. Não executar o criador distribuído para escrever/finalizar o índice como monólito. Validação trata índice + detalhes como backlog lógico; sprint futuro recebe só detalhes aprovados, após conclusão e readiness autorizados. Dúvida de compatibilidade suspende escrita. Ver [governança e integridade](#governanca-de-artefatos-e-integridade-do-backlog).

## Inventário de requisitos

### Requisitos funcionais

Conferência automatizada das linhas de definição do PRD: **236 IDs únicos, 22 famílias, sem duplicatas e com sequências contínuas por família**. A presença neste inventário significa requisito extraído, **não cobertura por Epic/Story nem funcionalidade implementada**.

#### RF-PUBLICO — Portal e conteúdo público (5)

- `RF-PUBLICO-001`: landing pública em GrupoQuarenteNA.org, HTTPS por Cloudflare Tunnel em produção, sem login.
- `RF-PUBLICO-002`: exibir dias, horários e link Zoom.
- `RF-PUBLICO-003`: links para relatórios públicos permitidos.
- `RF-PUBLICO-004`: acesso ao painel `/admin` pela página pública.
- `RF-PUBLICO-005`: público e painel responsivos, priorizando celular.

#### RF-ACESSO — Identidade, autenticação e autorizações (13)

- `RF-ACESSO-001`: login com senha e conta ativa; reutilizar autenticação da fundação.
- `RF-ACESSO-002`: logout seguro; reutilizar fundação.
- `RF-ACESSO-003`: redefinição administrativa de senha; e-mail futuro se configurado.
- `RF-ACESSO-004`: pessoa sem login em `pessoas`, nunca autogerar conta por ingresso ou Zoom.
- `RF-ACESSO-005`: CG/SG administram usuários e mandatos; root exige login pessoal prévio, autenticação forte, motivo e auditoria.
- `RF-ACESSO-006`: proteger credencial de elevação root com MFA, TTL e recuperação segura; sem login compartilhado.
- `RF-ACESSO-007`: adaptar identidade sem papéis comerciais; privilégios por encargo vigente e ações de conta/rotação identificadas.
- `RF-ACESSO-008`: capacidades por encargo; delegações excepcionais com prazo, motivo e auditoria, sem romper segregação ordinária.
- `RF-ACESSO-009`: Policy/Gate em toda operação; Tesoureiro/Kit exclusivos no ordinário, exceção root elevada auditada.
- `RF-ACESSO-010`: relatórios públicos sem login, somente dados publicáveis.
- `RF-ACESSO-011`: concessão/revogação por vigência; Policy nega mandato expirado mesmo sem scheduler; invalidar privilégios em sessão/cache.
- `RF-ACESSO-012`: elevação temporária pessoal, segunda autenticação, MFA, justificativa, aviso e auditoria; poderes integrais excepcionais.
- `RF-ACESSO-013`: CG/SG administram acessos/integrações, sem herdar edição financeira nem acesso operacional ao Kit.

#### RF-AUDITORIA — Auditoria (9)

- `RF-AUDITORIA-001`: auditar mutações persistentes de negócio/identidade/mandatos/permissões/configurações por humano/CLI/job/integração, atomicamente; eventos de segurança próprios, sem auditoria recursiva de persistência técnica ou da própria trilha (delimitação aprovada na #35).
- `RF-AUDITORIA-002`: ator, instante, IP/origem e agente quando disponíveis, módulo, entidade/ID, ação, antes/depois e motivo aplicável.
- `RF-AUDITORIA-003`: exigir justificativa para editar registro oficial já enviado.
- `RF-AUDITORIA-004`: consulta institucional por CG/SG; financeira pelo Tesoureiro; root elevado integral; sem PII Kit a terceiros.
- `RF-AUDITORIA-005`: registrar jobs/webhooks como sistema com origem identificada, sem usuário humano fictício.
- `RF-AUDITORIA-006`: logs não editáveis na interface comum.
- `RF-AUDITORIA-007`: mutação, permissões/configuração e auditoria redigida na mesma transação, ator/correlação e timestamp UTC.
- `RF-AUDITORIA-008`: auditar revelação de segredo Zoom, leitura de PII Kit e elevação sem copiar segredos.
- `RF-AUDITORIA-009`: versionar/excluir logicamente, preservar razão/autoria; expurgo pessoal conforme obrigação e retenção.

#### RF-ATA — Atas regulares (28)

- `RF-ATA-001`: dupla escalada cria ata; administrador em contingência justificada; conta pessoal e vigência obrigatórias.
- `RF-ATA-002`: pré-preencher ocorrência, horário, tipo, dupla, contagem Zoom e referência da 7ª Tradição; conferível, sem associação automática de identidade.
- `RF-ATA-003`: permitir editar dados sugeridos antes do envio.
- `RF-ATA-004`: sugerir chat/secretaria pela escala e permitir troca entre moderadores.
- `RF-ATA-005`: dupla por ocorrência com fala/chat intercambiáveis, não novos encargos permanentes.
- `RF-ATA-006`: sugestão de presença Zoom pode ser corrigida pelo servidor.
- `RF-ATA-007`: conferência antes do envio oficial.
- `RF-ATA-008`: rascunho automático inicial e incremental.
- `RF-ATA-009`: envio oficial após conferência.
- `RF-ATA-010`: retificar enviada somente remetente do envio, CG, SG ou root elevado; usuário remetente, motivo, versão e antes/depois.
- `RF-ATA-011`: rascunho/enviada/revisada/corrigida/anulada; conferência sem conteúdo é revisada, retificação é corrigida; rascunho fora dos indicadores.
- `RF-ATA-012`: validar contagens e valores numéricos, impedindo inválidos.
- `RF-ATA-013`: seletores/cadastros para campos objetivos.
- `RF-ATA-014`: texto livre para observações e informações subjetivas.
- `RF-ATA-015`: opção outro com texto complementar quando necessária.
- `RF-ATA-016`: ingresso implica visita, mas visita não exige ingresso; sugerir coerência sem impedir visita isolada.
- `RF-ATA-017`: ingresso com nome mínimo, sem login automático; vínculo contextual com pessoa exige confirmação humana.
- `RF-ATA-018`: envio oficial gera Kit aplicável; correção/anulação abre revisão privada, preservando remessa expedida e histórico.
- `RF-ATA-019`: conquista com quantidade/tempo, pessoa opcional confirmada e nome histórico preservado.
- `RF-ATA-020`: quantidade, origem e contexto de visitas.
- `RF-ATA-021`: temática/partilhador com nome histórico, pessoa opcional confirmada, sem exigir login.
- `RF-ATA-022`: resumo publicável após envio, sanitizado; excluir coluna Z, contatos, endereço, segredo e dados individuais Kit.
- `RF-ATA-023`: envio de resumo via Evolution e/ou Telegram se configurado.
- `RF-ATA-024`: histórico com filtros por período, reunião, servidor e indicadores.
- `RF-ATA-025`: exportação CSV e impressão de atas autorizadas.
- `RF-ATA-026`: reunião diária 20h–22h com dupla treinada e funções intercambiáveis.
- `RF-ATA-027`: servidor selecionado por cadastro/escala; normalização sugere, nunca funde homônimos automaticamente.
- `RF-ATA-028`: paridade dos campos históricos, inclusive 7ª Tradição/referência, despesas, adicionais, eleições e observações; contatos só no Kit.

#### RF-ATASERVICO — Atas e reuniões de serviço (10)

- `RF-ATASERVICO-001`: criar ata de reunião de serviço.
- `RF-ATASERVICO-002`: template padrão configurável.
- `RF-ATASERVICO-003`: pré-preencher data/tipo, esperados, pauta e calendário quando disponíveis.
- `RF-ATASERVICO-004`: SG abre template vigente, persiste rascunho e finaliza oficialmente sem segunda aprovação; rascunho sem indicadores/PDF definitivo.
- `RF-ATASERVICO-005`: finalização gera PDF oficial privado no Drive e metadados/versionamento no PostgreSQL; preservar original.
- `RF-ATASERVICO-006`: presenças/ausências/justificativas e decisões; totais próprios mesmo sem listas, desconhecido nulo e nunca zero inventado.
- `RF-ATASERVICO-007`: histórico/filtros por totais próprios; conferência, correção/anulação justificadas, revisões e PDFs imutáveis.
- `RF-ATASERVICO-008`: decisões/consciências com data, contexto e ata.
- `RF-ATASERVICO-009`: decisões publicáveis autorizadas; faltas/justificativas restritas e PDF oficial vinculado ao Drive.
- `RF-ATASERVICO-010`: SG mantém template versionado, prévia, PDF e arquivo privado no Drive.

#### RF-RELATORIOS — Dashboards e relatórios (20)

- `RF-RELATORIOS-001`: substituir Looker por páginas da aplicação.
- `RF-RELATORIOS-002`: visão clássica equivalente para conferir números históricos.
- `RF-RELATORIOS-003`: dashboard melhorado com filtros, cards, gráficos e tabelas confiáveis.
- `RF-RELATORIOS-004`: filtros de período, dia, horário, tipo, visitas, ingressos, conquistas, temática, servidor e status.
- `RF-RELATORIOS-005`: resumo de reuniões, presenças, partilhas, visitas, ingressos e conquistas.
- `RF-RELATORIOS-006`: dados detalhados de atas/reuniões.
- `RF-RELATORIOS-007`: conquistas e ingressos.
- `RF-RELATORIOS-008`: temáticas, títulos, partilhadores e presenças.
- `RF-RELATORIOS-009`: visitas/origens e relação com ingressos.
- `RF-RELATORIOS-010`: quadro de serviço, vagas, histórico e encerramentos.
- `RF-RELATORIOS-011`: reuniões de serviço, atas/PDFs, participantes/faltas/justificativas e decisões autorizadas.
- `RF-RELATORIOS-012`: finanças, reserva prudente, média de contribuição e autossustentabilidade.
- `RF-RELATORIOS-013`: Kit, estados, pendências, rastreio e responsáveis, sob exclusividade.
- `RF-RELATORIOS-014`: participantes Zoom apenas à mesa/permissão específica, nunca público ou servidor comum.
- `RF-RELATORIOS-015`: links de relatórios agregados públicos sem login.
- `RF-RELATORIOS-016`: proibir contato/endereço, identidade Zoom, logs e dados internos Kit nas projeções públicas.
- `RF-RELATORIOS-017`: prestação financeira pública sem nomes de contribuintes.
- `RF-RELATORIOS-018`: exportação CSV autorizada.
- `RF-RELATORIOS-019`: impressão/PDF pelo navegador.
- `RF-RELATORIOS-020`: ingresso novo com nome mínimo opt-out; Fale Conosco e revisão humana; históricos só após revisão de privacidade.

#### RF-MIGRACAO — Migração e reconciliação (16)

- `RF-MIGRACAO-001`: importar histórico de atas da planilha Responses.
- `RF-MIGRACAO-002`: importar histórico Kit.
- `RF-MIGRACAO-003`: importar tesouraria e reserva prudente disponíveis.
- `RF-MIGRACAO-004`: importar encargos, servidores e vigências/histórico.
- `RF-MIGRACAO-005`: importar/referenciar atas de serviço e PDFs.
- `RF-MIGRACAO-006`: mapear colunas para campos de destino.
- `RF-MIGRACAO-007`: varredura anterior de inconsistências, dados quebrados, duplicidades e conflitos.
- `RF-MIGRACAO-008`: sugerir normalização/mesclagem por grafias e atributos, com revisão humana.
- `RF-MIGRACAO-009`: revisão manual antes de consolidar conflitos/duplicidades.
- `RF-MIGRACAO-010`: relatório por importação com linhas, importados, erros, avisos, duplicatas e decisões.
- `RF-MIGRACAO-011`: comparar totais com planilhas/Looker antes da virada.
- `RF-MIGRACAO-012`: inventariar todas as abas/colunas/fórmulas operacionais e relacionamentos, com destino de cada regra/campo.
- `RF-MIGRACAO-013`: equivalência segura das funções ata-resumo, ata-kitingresso, kit-ingresso e servico-terminio com testes/exceções.
- `RF-MIGRACAO-014`: exportar/catalogar/substituir workflows n8n ativos; descontinuação justificada.
- `RF-MIGRACAO-015`: VAGO, relação de servidores, Docs e Looker derivados do banco, sem cópia manual autoritativa.
- `RF-MIGRACAO-016`: reconciliação cruzada de pessoas/fichas/serviço por servidores antigos, fila de ambiguidades e confirmação humana justificada.

#### RF-SERVIDORES — Servidores, mandatos e escalas (17)

- `RF-SERVIDORES-001`: cadastro de encargos/funções.
- `RF-SERVIDORES-002`: reuniões/dias/tipos e múltiplos horários no mesmo dia.
- `RF-SERVIDORES-003`: encargos configuráveis, preservando seis cargos da Mesa; mudanças por decisão registrada.
- `RF-SERVIDORES-004`: designar pessoa a encargo/reunião; operador com conta pessoal vinculada; histórico sem exigir conta antiga.
- `RF-SERVIDORES-005`: início/fim; padrão seis meses moderadores e um ano Mesa, parametrizável.
- `RF-SERVIDORES-006`: ativo/concluido/entregue/destituido; VAGO derivado, substituição com vigência; destituição apenas por fato autorizado, nunca inferência Zoom.
- `RF-SERVIDORES-007`: histórico por encargo.
- `RF-SERVIDORES-008`: vagas calculadas automaticamente.
- `RF-SERVIDORES-009`: relação por dia/horário/encargo.
- `RF-SERVIDORES-010`: alertas configuráveis 90/60/30/15/7/3/2/1 dias e conclusão.
- `RF-SERVIDORES-011`: avisos privados autorizados, grupo sem contato; desativação/expiração impede alertas indevidos.
- `RF-SERVIDORES-012`: registrar encerramento/entrega/destituição com motivo, histórico e atualização da vaga.
- `RF-SERVIDORES-013`: !vago consulta banco, não Docs manual.
- `RF-SERVIDORES-014`: !servidores consulta relação atual do banco.
- `RF-SERVIDORES-015`: seis encargos da Mesa distintos, com responsabilidade e mandato.
- `RF-SERVIDORES-016`: dois moderadores por ocorrência, alternando fala/chat, sem confundir cargos gerais.
- `RF-SERVIDORES-017`: ativação concede; expiração/entrega/destituição revoga na operação, com autor/motivo/instante.

#### RF-KIT — Kit ingresso (18)

- `RF-KIT-001`: pendência idempotente só no envio oficial; rascunho não contata/encomenda, correção abre revisão.
- `RF-KIT-002`: Kit vinculado a ingresso, sem exigir login do ingressante.
- `RF-KIT-003`: contato, CEP/endereço, estado, observações, responsável e rastreio protegidos.
- `RF-KIT-004`: consulta/preenchimento por CEP quando disponível.
- `RF-KIT-005`: verificar/falta endereço/falta enviar/enviado/recebido/devolvido/cancelado.
- `RF-KIT-006`: solicitar endereço por mensagem permitida.
- `RF-KIT-007`: pendência de envio notifica privadamente; grupo só agregado não identificável, nunca código/contato/endereço.
- `RF-KIT-008`: rastreio só ao destinatário/Coordenador Kit por canal privado; não em grupo/relatório público.
- `RF-KIT-009`: job configurável consulta remessas.
- `RF-KIT-010`: persistir eventos de rastreio.
- `RF-KIT-011`: normalizar entrega/devolução e auditar; aviso privado, grupo no máximo agregado.
- `RF-KIT-012`: acesso/modificação somente Coordenador Kit vigente; root elevado excepcional auditado.
- `RF-KIT-013`: telefone/endereço restritos e leitura auditada; exceção root pessoal, MFA e motivo.
- `RF-KIT-014`: auditar toda alteração no Kit.
- `RF-KIT-015`: correios-rastreamento por REST autenticado privado; PostgreSQL é fonte oficial.
- `RF-KIT-016`: consultas periódicas não finais, idempotência, limites, falhas e reconsulta manual.
- `RF-KIT-017`: eventos normalizados/deduplicados com data/hora; sem destinatário/contato/endereço públicos.
- `RF-KIT-018`: filas/contatos/remessas exclusivos no ordinário; Mesa/root sem acesso ordinário, exceção elevada identificada.

#### RF-TESOURARIA — Tesouraria e ASAAS (17)

- `RF-TESOURARIA-001`: receber webhook ASAAS.
- `RF-TESOURARIA-002`: eventos elegíveis geram lançamentos automáticos.
- `RF-TESOURARIA-003`: deduplicar evento e efeito por transação/tipo de movimento, inclusive concorrência/replay e eventos distintos.
- `RF-TESOURARIA-004`: entradas/saídas.
- `RF-TESOURARIA-005`: descrição obrigatória; PIX recebido = 7ª Tradição - PIX recebido; despesa sanitizada ou fallback, lançamento imediato.
- `RF-TESOURARIA-006`: categorias institucionais configuradas por responsáveis.
- `RF-TESOURARIA-007`: somente Tesoureiro vigente corrige/categoriza efetivados, com trilha.
- `RF-TESOURARIA-008`: antes/depois e justificativa aplicável em edição.
- `RF-TESOURARIA-009`: Tesoureiro salva manual com efeito imediato, sem rascunho/conferência obrigatória.
- `RF-TESOURARIA-010`: reserva prudente, inclusive manual.
- `RF-TESOURARIA-011`: saldo por período/conta/categoria e geral.
- `RF-TESOURARIA-012`: dashboard com evolução, contribuição média e projeções de autossustentabilidade.
- `RF-TESOURARIA-013`: consultas só às projeções autorizadas; edição exclusiva Tesoureiro; root elevado excepcional auditado.
- `RF-TESOURARIA-014`: público sem contribuintes, apenas informações contábeis permitidas.
- `RF-TESOURARIA-015`: !saldo com resumo autorizado, sem detalhes restritos.
- `RF-TESOURARIA-016`: evento validado/elegível e manual salvo lançam diretamente; sem aprovação intermediária.
- `RF-TESOURARIA-017`: Tesoureiro corrige/estorna/exclui logicamente, preservando origem, evento, valores, motivo e impacto no saldo.

#### RF-WHATSAPP — WhatsApp / Evolution API (10)

- `RF-WHATSAPP-001`: receber webhook Evolution.
- `RF-WHATSAPP-002`: identificar grupo de serviço/membros/outro configurado.
- `RF-WHATSAPP-003`: parser de comandos iniciados por !.
- `RF-WHATSAPP-004`: catálogo do grupo de serviço conforme linha original; !kit descontinuado, !participantes só agregado e !senha estritamente autorizado/auditado.
- `RF-WHATSAPP-005`: catálogo de membros conforme linha original, incluindo links e grupos de outros grupos autorizados.
- `RF-WHATSAPP-006`: dados estruturados atuais para !vago/!servidores/!saldo/!relatorio/!ata/!resumo.
- `RF-WHATSAPP-007`: logs mínimos de recebimento/comando/resposta com retenção.
- `RF-WHATSAPP-008`: proteção contra abuso/repetição.
- `RF-WHATSAPP-009`: logs dos envios automatizados.
- `RF-WHATSAPP-010`: rate limit, falhas e reenvio/pendência quando possível.

Catálogos literais do PRD a preservar no detalhamento: serviço = `!ajuda`, `!ata`, `!coletiva`, `!diretriz`, `!link`, `!gbatepapo`, `!gingresso`, `!participantes`, `!relatorio`, `!resumo`, `!saldo`, `!script`, `!senha`, `!servidores`, `!setima`, `!telas`, `!tematica`, `!textos`, `!vago`; membros = `!ajuda`, `!coletiva`, `!diretriz`, `!link_cpsanto`, `!link_quarentena`, `!link_novaera`, `!relatorio`, `!saldo`, `!servidores`, `!setima`, `!zap_cpsanto`, `!zap_novaera`, `!zap_quarentena`.

#### RF-ZOOM — Zoom e eventos (11)

- `RF-ZOOM-001`: receber webhook Zoom.
- `RF-ZOOM-002`: validar endpoint/origem segundo contrato técnico vigente na implementação.
- `RF-ZOOM-003`: suportar meeting.participant_joined com campos úteis e necessários.
- `RF-ZOOM-004`: verificar disponibilidade/finalidade de event/event_ts/account/meeting/host/topic/type/time/duration/timezone e campos de participante; minimizar identificáveis.
- `RF-ZOOM-005`: eventos em estrutura separada de usuarios.
- `RF-ZOOM-006`: sugestão de total de presença.
- `RF-ZOOM-007`: correção manual pelo responsável pela ata.
- `RF-ZOOM-008`: lista nominal só Mesa/permissão específica.
- `RF-ZOOM-009`: proibir frequência individual pública ou operacional comum.
- `RF-ZOOM-010`: conferir documentação atual e outros eventos úteis antes da integração, sem inventar contrato externo.
- `RF-ZOOM-011`: nome temporário cifrado e possíveis duplicações sugeridas; conferência humana, total editável, default 365 dias configurável CG/SG.

#### RF-ADMINISTRACAO — Administração e configurações (10)

- `RF-ADMINISTRACAO-001`: dados do grupo, links, reuniões/horários, textos e contatos.
- `RF-ADMINISTRACAO-002`: tipos/formatos configuráveis.
- `RF-ADMINISTRACAO-003`: horários padrão configuráveis.
- `RF-ADMINISTRACAO-004`: textos dos comandos editáveis quando aplicável.
- `RF-ADMINISTRACAO-005`: credenciais/estado de integrações em ambiente seguro.
- `RF-ADMINISTRACAO-006`: situação dos jobs de rastreio/avisos/backup.
- `RF-ADMINISTRACAO-007`: logs técnicos CG/SG e root excepcional, sem conceder acesso aos dados privados Kit/tesouraria.
- `RF-ADMINISTRACAO-008`: URLs/APIs/webhooks/parâmetros/testes por CG/SG/root elevado, segredos cifrados/mascarados e mudanças auditadas.
- `RF-ADMINISTRACAO-009`: documentos/PDFs no Drive com ACL e metadados PostgreSQL, não banco Google.
- `RF-ADMINISTRACAO-010`: Redis/MinIO apenas por necessidade medida, com configuração/diagnóstico se habilitados.

#### RF-BACKUP — Recuperação e operação (6)

- `RF-BACKUP-001`: procedimento de backup PostgreSQL.
- `RF-BACKUP-002`: procedimento de restauração.
- `RF-BACKUP-003`: backup dos importados/arquivos históricos antes da virada.
- `RF-BACKUP-004`: instalação Docker documentada, reaproveitando fundação.
- `RF-BACKUP-005`: orientação operacional para servidores por fluxo.
- `RF-BACKUP-006`: documentação técnica de arquitetura, ambiente, jobs, webhooks, integrações, backup/deploy.

#### RF-DOCUMENTACAO — Documentação e replicabilidade (5)

- `RF-DOCUMENTACAO-001`: material simples para iniciantes.
- `RF-DOCUMENTACAO-002`: orientações por módulo e comandos.
- `RF-DOCUMENTACAO-003`: administração, acessos, auditoria, migração e correções.
- `RF-DOCUMENTACAO-004`: instalação, deploy, backup/restore, jobs/webhooks/ambiente para responsáveis técnicos.
- `RF-DOCUMENTACAO-005`: fork parametrizado com marca/domínio/contatos/integrações/ambiente e contingência root, sem herdar dados/segredos.

#### RF-AUTOMACOES — Rotinas operacionais migradas (25)

- `RF-AUTOMACOES-001`: inventário verificável por origem/gatilho/credencial referenciada/payload/dependências/aceite.
- `RF-AUTOMACOES-002`: resumo sanitizado sem Z/contatos/segredo/Kit, só após envio oficial, com outbox/idempotência.
- `RF-AUTOMACOES-003`: ingresso gera Kit por outbox após envio, correção/anulação abre revisão humana.
- `RF-AUTOMACOES-004`: endereço ao destinatário por canal autorizado.
- `RF-AUTOMACOES-005`: substituir funções CEP/endereço/expedição/mensagens com segurança.
- `RF-AUTOMACOES-006`: correios-rastreamento substitui rastreios dispersos, eventos únicos.
- `RF-AUTOMACOES-007`: alertas 90/60/30/15/7/3/2/1 e conclusão configuráveis.
- `RF-AUTOMACOES-008`: encerrar encargos por regra aprovada, histórico e VAGO derivado sem duplicar linhas.
- `RF-AUTOMACOES-009`: Robô n8n vira comandos autenticados/autorizados.
- `RF-AUTOMACOES-010`: avisos da agenda em jobs/templates parametrizados.
- `RF-AUTOMACOES-011`: mensagens aos servidores com consentimento/canal autorizado, limites e histórico mínimo.
- `RF-AUTOMACOES-012`: ASAAS substitui fluxo n8n com lançamento e conciliação auditáveis.
- `RF-AUTOMACOES-013`: fórmulas/agregações/filtros Looker e Sheets rastreáveis.
- `RF-AUTOMACOES-014`: Telegram equivalente se confirmado ativo pela operação.
- `RF-AUTOMACOES-015`: desligar legado só após comparação, backup, reprocessamento e aceite por rotina.
- `RF-AUTOMACOES-016`: flag, template, frequência/destino, inspeção/reprocessamento sem duplicar; comandos em handlers Laravel.
- `RF-AUTOMACOES-017`: editor visual básico de gatilhos/condições/ações permitidas, prévia/teste, sem código arbitrário.
- `RF-AUTOMACOES-018`: CG/SG gerem comuns, Tesoureiro financeiras e Coordenador Kit suas rotinas; root elevado excepcional.
- `RF-AUTOMACOES-019`: aviso por ocorrência 15 min antes por padrão, independente para múltiplos horários.
- `RF-AUTOMACOES-020`: ata só envia mensagem oficialmente; correção gera revisão, reenvio explícito e sem duplicação/contatos em grupo.
- `RF-AUTOMACOES-021`: endpoint OpenAI compatível, modelo/chave protegida, diagnóstico/limites; desligado até habilitação.
- `RF-AUTOMACOES-022`: configuração geral e específica por encargo, com permissões separadas para editar segredo/URL/template e enviar.
- `RF-AUTOMACOES-023`: resumo de conversas WhatsApp distinto da ata, inicialmente desligado, sem coleta/envio pré-habilitação e política de minimização.
- `RF-AUTOMACOES-024`: parser/rate limit/destinos/respostas por grupo; sem !kit; !senha restrito e !saldo do livro PostgreSQL.
- `RF-AUTOMACOES-025`: efeito financeiro imediato elegível e manual salvo, dedup por evento e efeito, retificação/estorno auditados posteriores.

#### RF-IDENTIDADE — Identidade operacional (4)

- `RF-IDENTIDADE-001`: pessoa sem login, PK estável e contatos/localização opcionais sob proteção.
- `RF-IDENTIDADE-002`: busca anterior ao cadastro, sugestões semelhantes e confirmação humana de homônimos.
- `RF-IDENTIDADE-003`: vínculos contextuais com ingresso/conquista/temática/serviço preservam nome original da ocorrência.
- `RF-IDENTIDADE-004`: usuário humano pessoal com pessoa NOT NULL UNIQUE reutilizada; ator técnico sem login fictício; fim do mandato não exige bloquear conta.

#### RF-ROOT — Elevação excepcional (3)

- `RF-ROOT-001`: sessão pessoal prévia, segredo root/MFA/motivo/TTL/identidade; nunca login direto.
- `RF-ROOT-002`: poderes integrais excepcionais inclusive Kit/tesouraria, leitura/mutação com autor/modo/motivo, sem herança ordinária.
- `RF-ROOT-003`: expiração/logout/inatividade/revogação encerram elevação; negar replay, sessão compartilhada e tentativa anônima.

#### RF-IA — Assistência e NotebookLM (4)

- `RF-IA-001`: NotebookLM-py privado por REST/CLI/MCP apropriado e habilitável, sem dependência dos fluxos críticos.
- `RF-IA-002`: notebook por ID/finalidade, fontes manuais, prompt/formato versionado; separar treinamento/meditação/literatura.
- `RF-IA-003`: literatura pública autorizada com referências, ausência de fonte explícita, sem notebook interno e com limite de abuso.
- `RF-IA-004`: meditação/treinamento a grupos permitidos, desligados inicialmente, com modelo/prompt/destino/horário configurados.

#### RF-ATENDIMENTO — Fale Conosco (2)

- `RF-ATENDIMENTO-001`: contato geral/ocultação/retificação com texto, contato opcional, protocolo/confirmação e aviso privado sanitizado sempre a CG e SG; sem alterar registros automaticamente.
- `RF-ATENDIMENTO-002`: triagem humana CG/SG de todos os pedidos; alvo opcional em allowlist; decisão/motivo/resposta e projeções auditadas; Kit encaminhado privadamente ao Coordenador Kit.

#### RF-RSG — Representação (1)

- `RF-RSG-001`: relatório bimestral em período livre e modelo do grupo; dados do banco, conferência/complemento RSG, PDF e arquivo referenciado.

#### RF-COMUNICACAO — Publicação (2)

- `RF-COMUNICACAO-001`: primeiro nome/apelido de ingressos/conquistas por opt-out, revisão humana para ocultação; histórico triado e sem PII Kit.
- `RF-COMUNICACAO-002`: consulta financeira pública por data/categoria/valor/saldo, sem contribuinte/ID de pagador/dado bancário pessoal.

### Requisitos não funcionais

Fonte: PRD §4, **18 IDs únicos**. Nenhum limiar numérico adicional é criado por esta extração.

- `RNF-01`: HTTPS/sessões protegidas e segredos ausentes de repositório/HTML.
- `RNF-02`: hash apropriado de senha e recuperação testada, inclusive acessos negados.
- `RNF-03`: autorização no servidor por rota/dado e testes negativos da matriz.
- `RNF-04`: auditoria insert/update/delete com antes/depois redigidos e retenção aprovada.
- `RNF-05`: LGPD/anonimato, finalidade e separação Zoom/Kit/finanças, inclusive exportação.
- `RNF-06`: mobile-first, teclado/leitor de tela e auditoria WCAG 2.1 AA como alvo.
- `RNF-07`: dado crítico da home sem dependência de API/JS.
- `RNF-08`: webhook repetido/fora de ordem sem efeito duplicado.
- `RNF-09`: jobs retomáveis, retries idempotentes, logs e falhas tratadas.
- `RNF-10`: WhatsApp autorizado por origem/grupo/frequência/comando.
- `RNF-11`: restore real isolado, não apenas existência de dump.
- `RNF-12`: migração verificável/reversível, reconciliação e divergências.
- `RNF-13`: servidor não técnico executa tarefa usando orientação publicada.
- `RNF-14`: desempenho móvel/carga típica com metas após baseline.
- `RNF-15`: observabilidade sem ranking de presença nominal.
- `RNF-16`: formatos abertos, exportação importável e contingência.
- `RNF-17`: cache/consulta/exportação/busca públicos sem registros privados.
- `RNF-18`: single-group parametrizável por fork com instalação limpa, sem dados/credenciais reais herdados.

### Regras de negócio transversais

Fonte: PRD §5, **23 IDs únicos**, a vincular às Stories pertinentes sem transformar regra transversal em entrega isolada.

- `RB-01`: oficial exige permissão; rascunho não conta em indicador.
- `RB-02`: Zoom sugere contagem, não prova presença pessoal.
- `RB-03`: visita e ingresso distintos, relações/contagens coerentes.
- `RB-04`: revisão/correção/anulação de atas preserva versões, autor/motivo e efeitos; retificação regular limitada a remetente/CG/SG/root elevado.
- `RB-05`: pessoa sem login permitida; usuário humano com pessoa e senha; sem conta automática ou job fictício.
- `RB-06`: root excepcional protegido, sem IDs/nomes institucionais fixos em material público.
- `RB-07`: Kit exclusivo, contato/endereço só para entrega; código privado, nunca grupo; relatório apenas nome mínimo opt-out.
- `RB-08`: ASAAS válido lança; Tesoureiro retifica posteriormente com trilha.
- `RB-09`: comandos respeitam grupo/permissão, !saldo sem detalhes proibidos.
- `RB-10`: política editorial de agregados e exceções de nomes mínimos, sem identidade Zoom/Kit/contribuinte.
- `RB-11`: designação com titular e quatro estados; VAGO derivado; fato institucional confirmado, sem inferência Zoom nem divulgação íntima.
- `RB-12`: desligamento de n8n/Sheets/Apps Script/Looker somente após cutover aceito.
- `RB-13`: automação não substitui consciência coletiva/validação sensível.
- `RB-14`: cadastro/fusão de pessoas com prevenção de colisão e validação humana.
- `RB-15`: seis encargos Mesa distintos da dupla intercambiável da ocorrência.
- `RB-16`: capacidades por vigência, revogação por término/entrega/destituição, conta pessoal.
- `RB-17`: segregação ordinária Tesoureiro/Coordenador Kit; root pessoal elevado integral excepcional.
- `RB-18`: contingência root com MFA, motivo e operador identificado, sem substituir login pessoal.
- `RB-19`: salvar manual/ASAAS elegível efetiva, sem rascunho/aprovação financeira.
- `RB-20`: publicação mínima padrão opt-out; legado `em_triagem` até liberação humana de lote; sem PII/sobrenomes completos por padrão.
- `RB-21`: grafias semelhantes são sugestões, não identidade única nem fusão automática.
- `RB-22`: template de serviço SG, Drive documental e PostgreSQL autoritativo.
- `RB-23`: jobs/handlers com flags/agendas/templates, sem Redis/MinIO obrigatórios.

### Casos de aceite ponta a ponta

Fonte: PRD §6, **38 IDs únicos e contínuos**. São contratos de validação, não resultados de testes já executados.

| ID | Cenário e resultado a comprovar |
|---|---|
| CA-01 | Smartphone anônimo encontra agenda/CTA mesmo com terceiros indisponíveis. |
| CA-02 | Ata retoma rascunho ou explicita falha, sem perda silenciosa; revisão e envio registrados. |
| CA-03 | Zoom indisponível/incorreto permite total manual sem lista pública. |
| CA-04 | Eventos distintos/repetidos do pagamento produzem um efeito por movimento; estorno próprio auditado. |
| CA-05 | Sem encargo nega leitura Kit/escrita financeira em interface/endpoint, inclusive admin geral. |
| CA-06 | Correção justificada registra antes/depois e recalcula indicadores. |
| CA-07 | Ingresso gera Kit acompanhado só pelo autorizado, PII invisível aos demais. |
| CA-08 | Término/vaga refletem escala e !vago/!servidores. |
| CA-09 | Homônimo/data faltante na importação exige revisão e registra divergência. |
| CA-10 | Financeiro público conciso sem contribuinte. |
| CA-11 | Webhook Evolution falso/duplicado não executa comando indevido. |
| CA-12 | Restore isolado compara dados e documenta contingência. |
| CA-13 | Servidor novo conclui ata/documentação móvel sem instrução de planilha. |
| CA-14 | Sheets/Looker conciliados por período, exceções aceitas pelo responsável. |
| CA-15 | Fork limpo parametrizado sem dados/segredos herdados. |
| CA-16 | Rastreio indisponível mantém Kit, retry/manual sem duplicatas/vazamento. |
| CA-17 | Rotina migrada equivale funcionalmente, reprocessa falhas e só desliga legado após aceite. |
| CA-18 | Toda aba/campo/fórmula ativa tem destino ou descarte justificado e paridade. |
| CA-19 | Designar concede; expirar sem cron/entregar/destituir revoga e mantém histórico. |
| CA-20 | Dupla alterna fala/chat e usa identidades estáveis. |
| CA-21 | ASAAS/manual efetivam uma vez; somente Tesoureiro corrige/exclui logicamente, com trilha. |
| CA-22 | Kit só Coordenador vigente/root elevado; opt-out e nenhum código individual em grupo. |
| CA-23 | Admin configura segredos mascarados; root pessoal com MFA/motivo/TTL/autoria. |
| CA-24 | SG versiona template, retoma rascunho e finaliza PDF privado Drive sem apagar anteriores. |
| CA-25 | Flag impede envio; template afeta próxima mensagem; comandos Evolution idempotentes. |
| CA-26 | Scheduler desligado não prolonga mandato; conta pessoal continua autenticável. |
| CA-27 | Usuário sem encargo eleva root com controles e autoria integral inclusive Kit/tesouraria. |
| CA-28 | ASAAS efetiva uma vez e Tesoureiro ajusta sem alterar evento bruto. |
| CA-29 | Apelido de homônimo exige busca e confirmação de vínculo. |
| CA-30 | Pedido de anonimato recebe protocolo, aviso restrito e análise humana, sem exclusão automática. |
| CA-31 | Duas ocorrências no dia geram lembretes próprios 15 minutos antes, salvo configuração. |
| CA-32 | NotebookLM/OpenAI fora não bloqueiam ata/serviço/financeiro/site. |
| CA-33 | Fim de mandato mantém histórico e conta sem capacidades antigas. |
| CA-34 | Nome Zoom corrigido/duplicado: lista restrita, sugestão, ajuste manual e retenção configurável. |
| CA-35 | Domínio/Tunnel HTTPS funciona, admin protegido, sem portas privadas públicas e com webhooks validados. |
| CA-36 | Retificação/anulação alerta só Coordenador Kit, preserva remessa expedida e revisa sem PII pública. |
| CA-37 | Contato geral sem alvo notifica CG e SG; vínculo opcional, decisão/retificação auditadas e resposta viável. |
| CA-38 | Serviço: template/rascunho/finalização, PDF/revisões, agregados históricos sem presenças inventadas. |

### Requisitos adicionais da Arquitetura

Referências de seção são para [Arquitetura.md](Arquitetura.md); não são novos IDs do PRD.

- §§1–4: reutilizar Laravel 13/PHP 8.5/PostgreSQL 17, lockfile, identidade/login, Docker e gates da #33, sem copiar template novamente. Verificar ambiente Windows/WSL escolhido antes de implementação, sem reinstalação automática.
- §§2–3: modular monolith, banco único por ambiente, schema `public`, sem SaaS/multi-tenancy. Domínio em `app/Funcionalidades/`, Eloquent em `app/Models/`, controllers finos e sem hierarquias duplicadas/vazias.
- §§3/6.8/síntese 2: gravação de negócio + auditoria redigida + outbox atômicos; entrega externa após commit; atores `usuario`, `integracao`, `job`, `sistema`, sem credenciais humanas artificiais. Contexto de SQL fora do ORM precisa de cobertura definida/testada.
- §§5/6.11: PK estável, FK/NOT NULL/UNIQUE/CHECK/índices testados em PostgreSQL; dinheiro decimal, jamais float; instantes UTC e exibição `America/Bahia`; nulo distinto de zero.
- §§4/6.1: capacidades por designação vigente, verificação por requisição, delegação temporária auditada sem ultrapassar segregação; root como sessão pessoal excepcional, não novo login/papel permanente.
- §§6.1/6.10: nomes NFKC/trim/espaços/casefold em busca não única; homônimos distintos, preservar nomes da ocorrência; contatos cifrados e HMAC separado somente quando necessário à busca autorizada.
- §§6.2/6.11: agenda recorrente gera ocorrência inequívoca, suporta múltiplos horários e links HTTPS autorizados; proteger segredo Zoom conforme decisão pendente registrada na #35.
- §§6.3/6.11: uma ata lógica por ocorrência e revisões únicas; remetente obrigatório no novo envio; legado sem autor não recebe autor fictício. Totais declarados não se somam novamente aos detalhes; valores financeiros da ata são declarativos, não lançam.
- §§6.3/6.11: público exige ata oficial válida, publicação liberada e ausência de opt-out; histórico inicia em triagem até liberação humana, sem autorização individual nova para cada linha.
- §§6.4/8: template usado imutável; finalização oficial do SG gera versão/PDF, metadados no banco, arquivo privado Drive; totais históricos de serviço independem de lista nominal, desconhecido permanece nulo.
- §§6.5/8.1: contato/endereço/observações Kit cifrados, leitura sensível auditada; remessas e eventos únicos; Correios FastAPI separado via REST privado autenticado, token obrigatório em produção, timeout/backoff/jitter/limites/circuit breaker e fallback manual.
- §§6.6/8.2: inbox por evento + chave única de efeito financeiro por transação/tipo; lançamento elegível imediato, correção/estorno posteriores, evento de origem preservado; não inventar conferência prévia.
- §§6.7/8.3: inbox autentica e deduplica, outbox com payload mínimo; workers/scheduler homologados antes do uso, execução única e retries sem efeitos duplicados; nenhuma chamada de envio dentro da transação.
- §§8/síntese 4: editor declarativo básico com catálogo permitido, sem PHP/SQL/shell livre nem clone n8n; flags verificadas antes do disparo, versão/prévia/teste, dono do módulo e escopos separados.
- §§8.3/síntese 4: comandos por grupo/autoridade, rate limit, !kit ausente, !senha restrito/auditado e !saldo de projeção autorizada; Telegram condicionado a canal ativo confirmado.
- §§6.7/8.4: Zoom separado de pessoa/conta, nomes temporários cifrados, expurgo real e default 365 dias configurável; sugestão de duplicação, sem vigilância, total corrigível e indisponibilidade distinta de zero.
- §§8/6.8: configuração web sem retorno de segredo, rotação/teste auditados, URLs/destinos autorizados, logs minimizados; Redis/MinIO só com necessidade medida, sem implantação obrigatória.
- §§6.8/9: migração offline/CLI com IA apenas assistiva e revisão humana, sem tela de lotes no admin; fonte imutável/hash/lote/linha, proveniência e unicidade, ambiguidades antes da consolidação e paridade contra originais.
- §9: inventariar funções ativas de Sheets/Forms/Docs/Looker/Apps Script/n8n; destino/descontinuação por função, snapshots e rollback, desligamento só após aceite. Inventário histórico não substitui exportação atual.
- §§10–12: produção futura em Swarm privado via Cloudflare Tunnel, imagem imutável, segredos externos, hosts/proxies/cookies/caches testados, sem portas públicas de origem/serviços auxiliares e sem bloquear home com autenticação geral.
- §§10–12: backup de dados + arquivos/chaves protegidas, armazenamento externo ao volume único, restore/contingência isolados, DNS/Tunnel e rotação exercitados; snapshot estrutural não prova esses requisitos.
- §§10/12: novas migrations por evolução, gates SQLite/PG direto/snapshot, catálogo/snapshot reais regenerados apenas após mudança efetiva de schema; nenhum schema proposto copiado como dump.
- §12: testes unitários/Feature, PostgreSQL, contratos externos anonimizados, negativos/cross-user/cache, concorrência, migração, E2E/UX e recuperação por incremento; documentação e treinamento junto do fluxo entregue.
- Síntese 5: IA OpenAI compatível e NotebookLM em adaptadores privados versionados/habilitáveis, notebooks separados, fontes/direitos humanos, perfil Google protegido e sem concorrência insegura; falha não bloqueia operação essencial.
- Governança: escopo/aceite nas fontes existentes e #35; review independente do diff completo antes do PR para `dev`; sem produção, merge ou funcionalidade nesta tarefa de planejamento.

### Requisitos de UX

**63 extratos acionáveis**, de [UX-Design.md](UX-Design.md), separados dos requisitos arquiteturais. Valores visuais ilustrativos permanecem sujeitos à homologação; a [#30](https://github.com/grupoquarentena/site/issues/30) não aprova reformulação nem bloqueia planejamento/fluxos independentes.

- `UX-DR1` (§§1–2): navegação pública curta com CTA único de reunião; acesso admin secundário e sem cadastro obrigatório ao visitante.
- `UX-DR2` (§2): hero mostra ocorrência/data, horário, fuso, tipo e orientação de elegibilidade sem depender de JS/API externa.
- `UX-DR3` (§2): compor blocos de NA, história do grupo, agenda semanal, apoio/transparência e rodapé privacidade/referências.
- `UX-DR4` (§2): início `/admin` orientado à próxima tarefa autorizada e avisos operacionais, sem módulos irrelevantes.
- `UX-DR5` (§2): menu por capacidade vigente com verificação real no backend, inclusive acesso direto por URL/API.
- `UX-DR6` (§3): preservar escudo/ave/asas/cores/lettering existentes; nenhuma reforma de marca sem #30 aprovada.
- `UX-DR7` (§3): conferir original CDR/fontes/direitos e SVG lado a lado; prévia raster não vale como SVG vetorial final.
- `UX-DR8` (§3): derivados PNG transparentes 1024/512/256/128/64 e favicon 16/32/48 em temas claro/escuro; homologar antes de `public/`.
- `UX-DR9` (§3): aplicar marca aprovada no header mobile/desktop, login, rodapé e OpenGraph.
- `UX-DR10` (§3): tokens semânticos de fundo/superfície/primária/ação/texto/secundária/sucesso/aviso/erro/painel, cores propostas sujeitas a contraste/brand.
- `UX-DR11` (§3): fonte sans-serif com fallback nativo, base 16px, CDN não impede leitura, hierarquia não só por cor.
- `UX-DR12` (§3): escala de espaçamento 4/8/12/16/24/32/48px, formulário móvel uma coluna, cards limitados e resumo de tabelas sem rolagem essencial.
- `UX-DR13` (§4): botão normal/foco/carregando/desabilitado/sucesso/erro, sucesso só após gravação.
- `UX-DR14` (§4): campo vazio/preenchido/inválido/somente leitura/indisponível, label persistente e erro associado/anunciado.
- `UX-DR15` (§4): autocomplete buscando/sem resultado/múltiplos/seleção; conferir contexto antes de vínculo, sem fusão automática.
- `UX-DR16` (§4): seletor de reunião com agenda, horário/data/estado, ocorrência e `America/Bahia` explícitos.
- `UX-DR17` (§4): tabela carregando/vazia/paginada/filtrada/erro, exportar só autorizado.
- `UX-DR18` (§4): confirmação cancelar/confirmar/processando/concluído para ações sensíveis/irreversíveis, sem adicionar aprovação financeira.
- `UX-DR19` (§4): banner normal/degradado/indisponível/reprocessando, distinguindo serviço externo e banco.
- `UX-DR20` (§4): status de ata com texto/explicação, não apenas cor, nos cinco estados canônicos.
- `UX-DR21` (§4): status Kit nos sete estados, mostrando origem/autor da transição ao autorizado.
- `UX-DR22` (§4): financeiro efetivado/divergência/corrigido/estornado/excluído logicamente, jamais rascunho ou nome de contribuinte público.
- `UX-DR23` (§4): gráfico sem dados/parcial/carregando/disponível com eixos, unidades, período, fonte e tabela alternativa.
- `UX-DR24` (§5): entrada Zoom com fallback de orientação; exposição de ID/senha da proposta UX depende da decisão de compatibilização registrada na #35.
- `UX-DR25` (§§5/10): ensaiar 360/375/768/1024/1440px, zoom 200%, teclado, sem JS e tecnologia assistiva.
- `UX-DR26` (§6): assistente de ata seleciona ocorrência, pré-preenche dupla, totais/detalhes, revisão, envio e recibo.
- `UX-DR27` (§6): contagens não negativas, moeda decimal, visitas/origens e detalhes estruturados, opção outro quando aplicável.
- `UX-DR28` (§6): preservar todos os campos históricos, inclusive eleições/despesas/adicionais; contato/endereço somente fluxo privado Kit.
- `UX-DR29` (§6): autossalvamento autenticado no servidor com Salvando/Salvo/Falha; proibir `localStorage` persistente sensível, memória efêmera expurgada.
- `UX-DR30` (§6): prevenir envio duplo e mostrar recibo/estado/hora/ID só depois do commit; interrupção não apaga rascunho confirmado.
- `UX-DR31` (§6): lista Zoom temporária só ao autorizado; sugestão de total editável, sem bloquear envio se integração falhar.
- `UX-DR32` (§6): ação Corrigir ata somente remetente/CG/SG/root elevado, motivo/comparação/histórico e indicadores atualizados.
- `UX-DR33` (§6): retificação/anulação avisa privadamente Coordenador Kit e abre revisão humana, preservando remessa já expedida.
- `UX-DR34` (§7): fila/detalhe/expedição/rastreio Kit exclusivos, com contato/endereço/observações cifrados e leitura/mudança auditadas.
- `UX-DR35` (§7): registrar código/data/comprovante sem repetição e mostrar eventos por data/cidade/UF/estado.
- `UX-DR36` (§7): Correios fora mantém último evento/próximo retry e atualização manual auditada; navegador não chama serviço externo diretamente.
- `UX-DR37` (§8): livro financeiro com origem/categorias/saldo/histórico, criação manual e ASAAS imediatos e correção posterior pelo Tesoureiro.
- `UX-DR38` (§8): financeiro público por período/categoria sem contribuinte, instrumento/identificador bancário ou referência privada.
- `UX-DR39` (§8): dashboard de atas por ocorrência/dia/horário/período sem frequência nominal Zoom; ingresso com nome mínimo opt-out.
- `UX-DR40` (§8): quadro de encargos/calendário/vagas/histórico filtrável, nomes conforme diretriz editorial própria de serviço.
- `UX-DR41` (§8): gráficos de presença/data, visitas/ingressos/conquistas, distribuição semanal, saldo, Kits por estado e encargos, cada um com tabela alternativa.
- `UX-DR42` (§8): tela integrações URL/versão/webhook/estado/eventos, segredo mascarado, teste/rotação auditados.
- `UX-DR43` (§8/complementos): lista de rotinas com dono, ativo, última/próxima execução/falha; editar frequência e retry sem envio duplicado.
- `UX-DR44` (§8/complementos): templates com variáveis permitidas, prévia sanitizada/teste/aprovação e histórico de versões.
- `UX-DR45` (§8/complementos): elevação em sessão pessoal com segredo/MFA/motivo, aviso de poderes/TTL, saída explícita e auditoria; nunca sobreviver ao logout.
- `UX-DR46` (§8): tela SG edita/versiona template de serviço com pauta/seções/texto e prévia PDF.
- `UX-DR47` (§8): ata de serviço mostra salvamento/totais/status/versões/PDFs privados, rascunho retomável e finalização oficial.
- `UX-DR48` (§8): correção/anulação serviço exige motivo e preserva PDF anterior; histórico só agregado continua filtrável sem inventar nomes/zeros.
- `UX-DR49` (§9): mensagens claras para sessão expirada, 403, gravação falha, atraso de webhook, rastreio fora e duplicidade, sem dados privados no HTML/JSON.
- `UX-DR50` (§9): estados de importação parcial, usuário desativado e período vazio, sem simular ausência como zero validado nem criar tela de importação.
- `UX-DR51` (§§9–10): HTML semântico, foco/labels/erros coerentes, contraste AA como alvo, teclado/leitor, movimento reduzido e alvos de toque utilizáveis.
- `UX-DR52` (§9): analytics/log/toast/export comum sem nomes de ingresso, contato/endereço, identidade Zoom, pagador, mensagem integral ou segredos.
- `UX-DR53` (§§10–11): ensaio observado por servidor leigo no celular, interrupção/retomada/correção e fluxos degradados, sem presumir validação prática pela aprovação documental.
- `UX-DR54` (complementos): buscar antes de cadastrar pessoa; apresentar contexto permitido sem expor Kit, contatos opcionais/localização e conta pessoal somente ao operar.
- `UX-DR55` (complementos): gestão de usuários lista/busca pessoa, cria/ativa/desativa login vinculado único, designa vigência e justifica revogação com trilha.
- `UX-DR56` (complementos): Fale Conosco geral com protocolo/confirmação/contato opcional, alvo não obrigatório e aviso privado aos dois CG/SG.
- `UX-DR57` (complementos): caixa restrita de atendimento por protocolo/categoria/assunto/status/responsável, alvo permitido opcional, decisão/resposta e correlação; Kit encaminhado sem ampliar acesso.
- `UX-DR58` (complementos): ação Solicitar ocultação ou correção nos relatórios nominais mínimos, sem ocultação automática; histórico revisado antes de publicar.
- `UX-DR59` (complementos): editor visual gatilho→condição→ação permitido, sem código; rotinas financeiras/Kit só pelos respectivos donos e root excepcional.
- `UX-DR60` (complementos): IA com endpoint compatível OpenAI, modelo/token mascarado e flag inicial desligada, sem bloquear fluxos essenciais.
- `UX-DR61` (complementos): NotebookLM com notebook por finalidade, prompt editável, agenda/grupos permitidos e estado de saúde; fontes curadas pelos servidores.
- `UX-DR62` (complementos): RSG com período bimestral livre, métricas do banco, texto complementar, conferência, prévia/PDF e arquivo referenciado.
- `UX-DR63` (§2/complementos): migração exclusivamente offline/CLI com IA assistiva, relatório de reconciliação e revisão humana, sem menu/tela de lotes no admin.

## Baseline técnica e lacunas de implementação

Esta classificação não estima porcentagem funcional. “Parcial” significa algum suporte existente, não requisito integralmente aceito. Resultados de execução são os históricos da #33, não testes reexecutados na #35.

| Área / famílias | Evidência existente em `4ad7814` | Delta a planejar |
|---|---|---|
| Acesso / identidade / root | `AutenticacaoController`, `UsuarioAtivo`, `Pessoa`/`Usuario`, `CriarUsuario`; testes `AutenticacaoTest`, `IdentidadeTest`, `CriarUsuarioTest`, `CsrfTest`; vínculo pessoa não nulo/único, hash, normalização de e-mail, login/logout/inativação | Login/logout e vínculo mínimo reaproveitados; identidade parcial (busca não implementada, contatos/localização incompletos); recuperação administrativa, gestão web, encargos/Policies/delegação/root ausentes. |
| Público | `routes/web.php`, home Blade inicial, login/admin mínimo e `/up` | Sem agenda/CTA funcional/conteúdo completo/Fale Conosco/relatórios; responsividade/acessibilidade completas por homologar; #30 só para marca definitiva. |
| Auditoria | Sem tabela `auditorias`, serviços de trilha ou testes de escrita de negócio | Implementar trilha transacional, leituras sensíveis e consultas autorizadas junto dos incrementos; logs técnicos não a substituem. |
| Ata / atas de serviço / servidores | Nenhuma migration/modelo/rota de domínio na baseline | Fluxos novos; reaproveitar pessoas/contas/guard, não criar outro login nem gerar todas as entidades candidatas em bloco. |
| Kit / tesouraria | Nenhum fluxo de domínio ou endpoint de integração correspondente | Fluxos novos com segregação, idempotência, trilha e falhas; campos declarativos de ata não efetivam finanças. |
| Relatórios / RSG / comunicação editorial / atendimento | Nenhuma implementação desses fluxos | Projeções públicas/restritas, opt-out/triagem humana e RSG novos, sem herdar aprovação funcional do MVP PHP. |
| WhatsApp / Zoom / automações / IA | Dependência de fila e tabelas técnicas, não handlers de domínio | Integrações, inbox/outbox, editor básico, jobs reais, comandos e IA ausentes; habilitar progressivamente com testes, IA inicialmente desligada. |
| Migração | Inventário histórico e contratos, sem importadores/lotes implementados | Inventário ativo/exportações, CLI, mapas/ambiguidade/paridade/cutover futuros; IA só assistiva, revisão humana obrigatória. |
| Backup / documentação | README, scripts Docker/banco/testes, catálogo/snapshot estrutural e CI; #33 documenta restauração do schema e homologação dev | Não confundir com backup/restore operacional de dados/arquivos, documentação por módulo, treinamento e release/contingência completos. |
| Infra e dependências transversais | `composer.lock` (Laravel 13.38.0/Boost 2.10.3 dev), PHP 8.5 no Docker, PostgreSQL 17; SQLite/PG direto/snapshot, preflight isolado e CI do PR #34 | Reutilizar; sem Sanctum/clientes/Redis/MinIO obrigatórios. Swarm dev histórico não é produção; worker/scheduler/serviços externos e Tunnel aguardam homologação de seus fluxos. |

Snapshot e catálogo contêm dez tabelas: `pessoas`, `usuarios`, `password_reset_tokens`, `sessions`, `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`, `migrations`; somente três migrations próprias na fundação. `password_reset_tokens` não comprova fluxo de recuperação implementado. `nome_busca` indexado não comprova algoritmo/interface de busca. Não atribuir os 236 RF a “done” a partir dessas estruturas.

**Evidência histórica:** #33 registra SQLite 32 testes/103 asserções, PostgreSQL direto e snapshot 32/104 cada, Pint/Larastan e revisão independente; checks `imagem`/`qualidade` do PR #34 aprovados, run `39579088633`. Esta tarefa consulta evidências e código, sem reexecutar a suíte técnica nem comprovar o runtime no novo checkout.

## Parâmetros operacionais e dependências

PRD §10 preservado. Responsáveis específicos abaixo são **proposta de atribuição para aprovação**, onde a fonte descreve apenas a disciplina; não são pessoas já designadas. Não inventar SLA, RTO/RPO, p95 ou data de cutover. O default Zoom 365 dias já consta em RF-ZOOM-011/UX/Arquitetura; resta homologar finalidade/política, não inventar outro prazo.

| Nº original | Decisão / responsáveis propostos | Momento e dependência |
|---|---|---|
| 01 | Mesa/CG/SG: agenda vigente, tipos e ocorrências | Antes de cadastrar/publicar agenda real; modelagem suporta múltiplos horários sem aguardar dado real. |
| 02 | Mesa/CG/SG + técnico: prazos e vigência por encargo | Antes de ativar designações; preservar padrões aprovados de seis meses/um ano e matriz vigente. |
| 03 | Mesa/responsáveis por dados + técnico; Coordenador Kit/Tesoureiro nos próprios dados | Antes de coletar dados reais e ativar expurgo/envios; Zoom default 365 configurável, mensagens/PII e backups com política por classe. |
| 04 | Mesa + responsável técnico/segurança: cofre, MFA, custódia e TTL | Antes de habilitar root, sem conta direta nem segredo em artefatos; teste de recuperação/revogação. |
| 05 | Servidores antigos/Mesa + responsáveis pelos dados | Antes de consolidar vínculos/lotes/indicadores; homônimos nunca fundidos automaticamente. |
| 06 | CG/SG, servidores e técnico: ensaiar retificação/anulação | Antes de homologar atas; permissões já aprovadas, sem segunda aprovação criada. |
| 07 | Tesoureiro + Mesa | Antes da carga financeira/publicação: categorias, saldo inicial, eventos elegíveis/estornos e projeções públicas; não criar etapa de aprovação de lançamento. |
| 08 | CG/SG + responsáveis por comunicação/serviço | Antes de habilitar comandos/envios: catálogo real, grupos/destinos/autoridade, Telegram ativo ou não; !kit permanece descontinuado. |
| 09 | Responsáveis por cada fonte + servidores antigos/Mesa | Inventário/exportações antes de importadores definitivos; aceite/paridade/rollback antes de liberar cada lote/cutover. |
| 10 | Mesa + responsável técnico | Antes do release: DNS/ownership/Tunnel/HTTPS do domínio já escolhido, sem antecipar deploy nesta Issue. |
| 11 | Responsável técnico + servidores/Mesa | Baseline por fluxo antes de fixar metas de UX/desempenho/backup/disponibilidade e antes do aceite operacional correspondente. |
| 12 | Mesa/privacidade + operadores autorizados | Antes da coleta/consulta nominal Zoom; compatibilizar público/segredo da reunião e permissões de informes sem ampliar acesso por inferência. |
| 13 | Mesa + técnico e donos dos módulos | Antes dos primeiros efeitos automatizados: atores técnicos, autoria, limites ordinários e ensaios root integral excepcional. |

Dependência externa de marca: #30, responsável pelo projeto/homologação visual; antes de publicar arte final, não antes de modelar, testar ou implementar jornadas independentes. Dependência local: conferir Docker/MCP/mounts/permissões no checkout `/mnt/c/...` antes da implementação; não bloqueia o backlog nem autoriza novo clone/reinstalação.

## Conferência cruzada e rastreabilidade

Achados classificados e decisões ficam na #35, não em relatórios paralelos. A extração abrange todas as famílias/RNF/RB/CA e contratos UX; o mapa a seguir propõe responsáveis por Epic, **não comprova cobertura por Stories/testes nem implementação**. Os conflitos documentais não serão silenciosamente resolvidos nas Stories. A #28 é referência concreta para agenda/links/redirect/privacidade do portal; suas correções são históricas, não testes do novo Laravel. O inventário §§14–15 contextualiza automações, sem substituir coleta atual nem reintroduzir coluna Z nos resumos.

### Mapa de cobertura RF

**Mapa da revisão 2 aprovado definitivamente no passo 02.** Cada RF tem exatamente um Epic responsável principal por sua cobertura integral/coordenação. Isso não significa exclusividade de código: regras transversais são aplicadas em todos os consumidores. Dependências abaixo são de capacidades verificáveis, não da conclusão indiscriminada de um Epic inteiro. Entrega parcial útil não autoriza concluir um RF composto nem o Epic: qualquer obrigação aplicável ainda faltante mantém sua conclusão pendente, inclusive integração, ensaio ou homologação de produção expressamente exigidos.

Redistribuições da revisão 2: RF-PUBLICO-001 (14→2), RF-ATENDIMENTO-001–002 (10→2), RF-ATA-002 (9→5), RF-AUTOMACOES-016–017 (4→12). RF-AUTOMACOES-018/022 continuam em 4 para configuração/escopos ordinários; 12 acrescenta composição visual e integração do catálogo. Nenhum ID/conteúdo normativo foi alterado.

Login/logout já homologados aparecem como **reaproveitamento e regressão**, não como nova entrega. Auditoria/Policies têm origem no Epic 1 e aplicação obrigatória nos demais; sua cobertura global só se comprova com os consumidores. Documentação tem consolidação no Epic 14, mas acompanha cada incremento desde o primeiro. Essa distinção impede dependências futuras artificiais e evita antecipar tabelas/handlers de módulos inexistentes.

| RF original | Epic responsável proposto |
|---|---|
| RF-PUBLICO-001 | Epic 2 |
| RF-PUBLICO-002 | Epic 2 |
| RF-PUBLICO-003 | Epic 2 |
| RF-PUBLICO-004 | Epic 2 |
| RF-PUBLICO-005 | Epic 2 |
| RF-ACESSO-001 | Epic 1 |
| RF-ACESSO-002 | Epic 1 |
| RF-ACESSO-003 | Epic 1 |
| RF-ACESSO-004 | Epic 1 |
| RF-ACESSO-005 | Epic 1 |
| RF-ACESSO-006 | Epic 1 |
| RF-ACESSO-007 | Epic 1 |
| RF-ACESSO-008 | Epic 1 |
| RF-ACESSO-009 | Epic 1 |
| RF-ACESSO-010 | Epic 10 |
| RF-ACESSO-011 | Epic 1 |
| RF-ACESSO-012 | Epic 1 |
| RF-ACESSO-013 | Epic 1 |
| RF-AUDITORIA-001 | Epic 1 |
| RF-AUDITORIA-002 | Epic 1 |
| RF-AUDITORIA-003 | Epic 1 |
| RF-AUDITORIA-004 | Epic 1 |
| RF-AUDITORIA-005 | Epic 1 |
| RF-AUDITORIA-006 | Epic 1 |
| RF-AUDITORIA-007 | Epic 1 |
| RF-AUDITORIA-008 | Epic 1 |
| RF-AUDITORIA-009 | Epic 1 |
| RF-ATA-001 | Epic 5 |
| RF-ATA-002 | Epic 5 |
| RF-ATA-003 | Epic 5 |
| RF-ATA-004 | Epic 5 |
| RF-ATA-005 | Epic 5 |
| RF-ATA-006 | Epic 9 |
| RF-ATA-007 | Epic 5 |
| RF-ATA-008 | Epic 5 |
| RF-ATA-009 | Epic 5 |
| RF-ATA-010 | Epic 5 |
| RF-ATA-011 | Epic 5 |
| RF-ATA-012 | Epic 5 |
| RF-ATA-013 | Epic 5 |
| RF-ATA-014 | Epic 5 |
| RF-ATA-015 | Epic 5 |
| RF-ATA-016 | Epic 5 |
| RF-ATA-017 | Epic 5 |
| RF-ATA-018 | Epic 6 |
| RF-ATA-019 | Epic 5 |
| RF-ATA-020 | Epic 5 |
| RF-ATA-021 | Epic 5 |
| RF-ATA-022 | Epic 5 |
| RF-ATA-023 | Epic 5 |
| RF-ATA-024 | Epic 5 |
| RF-ATA-025 | Epic 5 |
| RF-ATA-026 | Epic 2 |
| RF-ATA-027 | Epic 5 |
| RF-ATA-028 | Epic 5 |
| RF-ATASERVICO-001 | Epic 8 |
| RF-ATASERVICO-002 | Epic 8 |
| RF-ATASERVICO-003 | Epic 8 |
| RF-ATASERVICO-004 | Epic 8 |
| RF-ATASERVICO-005 | Epic 8 |
| RF-ATASERVICO-006 | Epic 8 |
| RF-ATASERVICO-007 | Epic 8 |
| RF-ATASERVICO-008 | Epic 8 |
| RF-ATASERVICO-009 | Epic 8 |
| RF-ATASERVICO-010 | Epic 8 |
| RF-RELATORIOS-001 | Epic 11 |
| RF-RELATORIOS-002 | Epic 11 |
| RF-RELATORIOS-003 | Epic 11 |
| RF-RELATORIOS-004 | Epic 11 |
| RF-RELATORIOS-005 | Epic 11 |
| RF-RELATORIOS-006 | Epic 11 |
| RF-RELATORIOS-007 | Epic 11 |
| RF-RELATORIOS-008 | Epic 11 |
| RF-RELATORIOS-009 | Epic 11 |
| RF-RELATORIOS-010 | Epic 11 |
| RF-RELATORIOS-011 | Epic 8 |
| RF-RELATORIOS-012 | Epic 7 |
| RF-RELATORIOS-013 | Epic 6 |
| RF-RELATORIOS-014 | Epic 9 |
| RF-RELATORIOS-015 | Epic 10 |
| RF-RELATORIOS-016 | Epic 10 |
| RF-RELATORIOS-017 | Epic 7 |
| RF-RELATORIOS-018 | Epic 11 |
| RF-RELATORIOS-019 | Epic 11 |
| RF-RELATORIOS-020 | Epic 10 |
| RF-MIGRACAO-001 | Epic 15 |
| RF-MIGRACAO-002 | Epic 15 |
| RF-MIGRACAO-003 | Epic 15 |
| RF-MIGRACAO-004 | Epic 15 |
| RF-MIGRACAO-005 | Epic 15 |
| RF-MIGRACAO-006 | Epic 3 |
| RF-MIGRACAO-007 | Epic 3 |
| RF-MIGRACAO-008 | Epic 3 |
| RF-MIGRACAO-009 | Epic 3 |
| RF-MIGRACAO-010 | Epic 15 |
| RF-MIGRACAO-011 | Epic 15 |
| RF-MIGRACAO-012 | Epic 3 |
| RF-MIGRACAO-013 | Epic 15 |
| RF-MIGRACAO-014 | Epic 15 |
| RF-MIGRACAO-015 | Epic 15 |
| RF-MIGRACAO-016 | Epic 15 |
| RF-SERVIDORES-001 | Epic 1 |
| RF-SERVIDORES-002 | Epic 2 |
| RF-SERVIDORES-003 | Epic 1 |
| RF-SERVIDORES-004 | Epic 1 |
| RF-SERVIDORES-005 | Epic 1 |
| RF-SERVIDORES-006 | Epic 1 |
| RF-SERVIDORES-007 | Epic 1 |
| RF-SERVIDORES-008 | Epic 1 |
| RF-SERVIDORES-009 | Epic 2 |
| RF-SERVIDORES-010 | Epic 4 |
| RF-SERVIDORES-011 | Epic 4 |
| RF-SERVIDORES-012 | Epic 1 |
| RF-SERVIDORES-013 | Epic 12 |
| RF-SERVIDORES-014 | Epic 12 |
| RF-SERVIDORES-015 | Epic 1 |
| RF-SERVIDORES-016 | Epic 2 |
| RF-SERVIDORES-017 | Epic 1 |
| RF-KIT-001 | Epic 6 |
| RF-KIT-002 | Epic 6 |
| RF-KIT-003 | Epic 6 |
| RF-KIT-004 | Epic 6 |
| RF-KIT-005 | Epic 6 |
| RF-KIT-006 | Epic 6 |
| RF-KIT-007 | Epic 6 |
| RF-KIT-008 | Epic 6 |
| RF-KIT-009 | Epic 6 |
| RF-KIT-010 | Epic 6 |
| RF-KIT-011 | Epic 6 |
| RF-KIT-012 | Epic 6 |
| RF-KIT-013 | Epic 6 |
| RF-KIT-014 | Epic 6 |
| RF-KIT-015 | Epic 6 |
| RF-KIT-016 | Epic 6 |
| RF-KIT-017 | Epic 6 |
| RF-KIT-018 | Epic 6 |
| RF-TESOURARIA-001 | Epic 7 |
| RF-TESOURARIA-002 | Epic 7 |
| RF-TESOURARIA-003 | Epic 7 |
| RF-TESOURARIA-004 | Epic 7 |
| RF-TESOURARIA-005 | Epic 7 |
| RF-TESOURARIA-006 | Epic 7 |
| RF-TESOURARIA-007 | Epic 7 |
| RF-TESOURARIA-008 | Epic 7 |
| RF-TESOURARIA-009 | Epic 7 |
| RF-TESOURARIA-010 | Epic 7 |
| RF-TESOURARIA-011 | Epic 7 |
| RF-TESOURARIA-012 | Epic 7 |
| RF-TESOURARIA-013 | Epic 7 |
| RF-TESOURARIA-014 | Epic 7 |
| RF-TESOURARIA-015 | Epic 12 |
| RF-TESOURARIA-016 | Epic 7 |
| RF-TESOURARIA-017 | Epic 7 |
| RF-WHATSAPP-001 | Epic 12 |
| RF-WHATSAPP-002 | Epic 12 |
| RF-WHATSAPP-003 | Epic 12 |
| RF-WHATSAPP-004 | Epic 12 |
| RF-WHATSAPP-005 | Epic 12 |
| RF-WHATSAPP-006 | Epic 12 |
| RF-WHATSAPP-007 | Epic 12 |
| RF-WHATSAPP-008 | Epic 12 |
| RF-WHATSAPP-009 | Epic 4 |
| RF-WHATSAPP-010 | Epic 4 |
| RF-ZOOM-001 | Epic 9 |
| RF-ZOOM-002 | Epic 9 |
| RF-ZOOM-003 | Epic 9 |
| RF-ZOOM-004 | Epic 9 |
| RF-ZOOM-005 | Epic 9 |
| RF-ZOOM-006 | Epic 9 |
| RF-ZOOM-007 | Epic 9 |
| RF-ZOOM-008 | Epic 9 |
| RF-ZOOM-009 | Epic 9 |
| RF-ZOOM-010 | Epic 9 |
| RF-ZOOM-011 | Epic 9 |
| RF-ADMINISTRACAO-001 | Epic 2 |
| RF-ADMINISTRACAO-002 | Epic 2 |
| RF-ADMINISTRACAO-003 | Epic 2 |
| RF-ADMINISTRACAO-004 | Epic 4 |
| RF-ADMINISTRACAO-005 | Epic 4 |
| RF-ADMINISTRACAO-006 | Epic 4 |
| RF-ADMINISTRACAO-007 | Epic 4 |
| RF-ADMINISTRACAO-008 | Epic 4 |
| RF-ADMINISTRACAO-009 | Epic 8 |
| RF-ADMINISTRACAO-010 | Epic 4 |
| RF-BACKUP-001 | Epic 14 |
| RF-BACKUP-002 | Epic 14 |
| RF-BACKUP-003 | Epic 14 |
| RF-BACKUP-004 | Epic 14 |
| RF-BACKUP-005 | Epic 14 |
| RF-BACKUP-006 | Epic 14 |
| RF-DOCUMENTACAO-001 | Epic 14 |
| RF-DOCUMENTACAO-002 | Epic 14 |
| RF-DOCUMENTACAO-003 | Epic 14 |
| RF-DOCUMENTACAO-004 | Epic 14 |
| RF-DOCUMENTACAO-005 | Epic 14 |
| RF-AUTOMACOES-001 | Epic 3 |
| RF-AUTOMACOES-002 | Epic 5 |
| RF-AUTOMACOES-003 | Epic 6 |
| RF-AUTOMACOES-004 | Epic 6 |
| RF-AUTOMACOES-005 | Epic 6 |
| RF-AUTOMACOES-006 | Epic 6 |
| RF-AUTOMACOES-007 | Epic 4 |
| RF-AUTOMACOES-008 | Epic 1 |
| RF-AUTOMACOES-009 | Epic 12 |
| RF-AUTOMACOES-010 | Epic 4 |
| RF-AUTOMACOES-011 | Epic 4 |
| RF-AUTOMACOES-012 | Epic 7 |
| RF-AUTOMACOES-013 | Epic 11 |
| RF-AUTOMACOES-014 | Epic 5 |
| RF-AUTOMACOES-015 | Epic 15 |
| RF-AUTOMACOES-016 | Epic 12 |
| RF-AUTOMACOES-017 | Epic 12 |
| RF-AUTOMACOES-018 | Epic 4 |
| RF-AUTOMACOES-019 | Epic 4 |
| RF-AUTOMACOES-020 | Epic 5 |
| RF-AUTOMACOES-021 | Epic 13 |
| RF-AUTOMACOES-022 | Epic 4 |
| RF-AUTOMACOES-023 | Epic 13 |
| RF-AUTOMACOES-024 | Epic 12 |
| RF-AUTOMACOES-025 | Epic 7 |
| RF-IDENTIDADE-001 | Epic 1 |
| RF-IDENTIDADE-002 | Epic 1 |
| RF-IDENTIDADE-003 | Epic 5 |
| RF-IDENTIDADE-004 | Epic 1 |
| RF-ROOT-001 | Epic 1 |
| RF-ROOT-002 | Epic 1 |
| RF-ROOT-003 | Epic 1 |
| RF-IA-001 | Epic 13 |
| RF-IA-002 | Epic 13 |
| RF-IA-003 | Epic 13 |
| RF-IA-004 | Epic 13 |
| RF-ATENDIMENTO-001 | Epic 2 |
| RF-ATENDIMENTO-002 | Epic 2 |
| RF-RSG-001 | Epic 11 |
| RF-COMUNICACAO-001 | Epic 10 |
| RF-COMUNICACAO-002 | Epic 7 |

## Lista de Epics

**Revisão 2 dos mesmos 15 Epics — aprovada definitivamente pelo responsável com C.** A numeração identifica agrupamentos, não uma sequência obrigatória de conclusão. A ordem recomendada por capacidades consta adiante; especialmente 3, 12 e 14 podem começar cedo. Intervalos abaixo são apenas notação de listagem dos IDs existentes; o mapa anterior explicita cada um dos 236 RF.

Não separar backend, banco, frontend ou testes em Epics: cada resultado inclui interface/CLI apropriada, regras, persistência necessária, autorização, privacidade, auditoria, documentação e testes do fluxo. Os componentes de UI e controles comuns evoluem com o primeiro uso; não criar camadas vazias ou toda a modelagem de antemão.

### Epic 1: Organizar pessoas, mandatos e acesso responsável

**Objetivo:** CG/SG encontram pessoas existentes, administram contas e responsabilidades vigentes; cada servidor acessa apenas suas funções e a contingência root mantém autoria individual.

**Resultado verificável:** buscar antes de cadastrar, criar/reutilizar conta vinculada, designar/encerrar mandato, consultar histórico/vagas e trilha; expiração nega capacidade mesmo sem scheduler. Elevar/encerrar sessão excepcional com MFA, motivo e duração limitada, sem login root direto. O primeiro fluxo de gestão já é auditado e utilizável, sem depender de atas/Kit/finanças futuros.

**RFs responsáveis (38):** RF-ACESSO-001–009, RF-ACESSO-011–013; RF-AUDITORIA-001–009; RF-SERVIDORES-001, RF-SERVIDORES-003–008, RF-SERVIDORES-012, RF-SERVIDORES-015, RF-SERVIDORES-017; RF-AUTOMACOES-008; RF-IDENTIDADE-001–002, RF-IDENTIDADE-004; RF-ROOT-001–003.

**Dependências — início:** apenas fundação #33/PR #34. **Por capacidade:** controles de autoria/auditoria e gestão pessoal vêm antes de mandatos/Policies; root depende de sessão pessoal e seus controles fortes, mas não é pré-requisito para uso ordinário autorizado. Expor funções de outro módulo exige as capacidades efetivas de autorização/auditoria que ele utiliza, não os 38 RF concluídos. **Incremental:** escala por ocorrência do Epic 2 e aplicação dos controles nos consumidores. **Produção:** política de dados, custódia/MFA/TTL antes de habilitar root e recuperação mínima do Epic 14 antes de dados reais. Delimitar auditoria técnica/cardinalidade e parâmetros 02/03/04/13 antes das Stories afetadas.

**Limites e reaproveitamento:** não refazer login/logout/hash/sessões, vínculo de identidade ou provisionamento existente. Políticas e trilha nascem com a gestão real, sem simular módulos futuros; testes de exclusividade/leitura Kit, financeiro e segredo Zoom são completados nos Epics consumidores. Contatos operacionais não concedem acesso aos contatos do Kit. Auditoria é transversal, não nove features técnicas isoladas. O volume de 38 RF inclui controles compartilhados e requisitos de fundação reaproveitados, não 38 novas telas; dimensionamento por agente será comprovado no passo 03.

### Epic 2: Encontrar a reunião, organizar a dupla e solicitar atendimento

**Objetivo:** visitantes encontram agenda/Zoom e solicitam contato/correção sem login; responsáveis mantêm reuniões/duplas e CG/SG fazem triagem humana, sem depender de Kit, financeiro ou dashboards.

**Resultado verificável:** home móvel com CTA e agenda utilizável sem terceiros/JS; configurar ocorrências e escalar/substituir dupla. Fale Conosco geral recebe relato sem alvo obrigatório, gera protocolo/confirmação e avisa privadamente CG e SG; caixa restrita registra atribuição, análise, motivo e resposta. Alvos existentes podem ser vinculados por allowlist/Policy; pedido nunca altera registro automaticamente.

**RFs responsáveis (14):** RF-PUBLICO-001–005; RF-ATA-026; RF-SERVIDORES-002, RF-SERVIDORES-009, RF-SERVIDORES-016; RF-ADMINISTRACAO-001–003; RF-ATENDIMENTO-001–002.

**Dependências — início:** fundação para o portal público. **Por capacidade:** gestão autenticada/escala/triagem exigem mandatos/Policies/trilha necessários do Epic 1; o aviso aos dois responsáveis exige canal privado validado pelo incremento de envio mínimo do Epic 4, nunca seu módulo completo/editor. O canal concreto continua por decisão operacional, não foi escolhido nesta revisão. **Incremental:** adaptadores de retificação/publicação do Epic 10 e de dados dos donos dos módulos, somente quando esses registros existirem. **Produção:** agenda/editorial/credenciais conferidas, #30 para marca final e origem HTTPS/Tunnel verificada pelo incremento de release do Epic 14. Isso condiciona RF-PUBLICO-001 completo, não o desenvolvimento/demonstração inicial da landing. Parâmetros 01/02/03/08/12 aplicáveis.

**Limites:** links/alvos só para capacidades existentes, sem recursos fictícios. Solicitação genérica funciona antes de dados operacionais; se pedir Kit, protocolo/alerta sanitizados não concedem PII a CG/SG, e encaminhamento seguro ao Coordenador Kit exige sua capacidade de acesso/recebimento, não todo o módulo de remessas. A retificação concreta usa a regra da fonte já implementada; adaptadores posteriores são integrados pelos respectivos donos. RF-ATENDIMENTO-001 não pode ser aceito sem comprovar aviso privado a ambos; canal não foi escolhido por inferência. RF-PUBLICO-001 pertence integralmente a 2, com evidência de infraestrutura fornecida por 14; não marcar o RF completo apenas por teste local.

### Epic 3: Diagnosticar o legado e preparar uma migração sem perda

**Objetivo:** responsáveis conhecem fontes/rotinas ativas, campos/fórmulas e ambiguidades antes de construir equivalências ou consolidar registros.

**Resultado verificável:** CLI offline lê exportações autorizadas imutáveis, mapeia colunas e regras para destinos propostos, identifica erros/homônimos e permite registrar decisões humanas em diagnóstico rastreável. Entrega inventário e exceções úteis sem precisar de todas as tabelas de destino.

**RFs responsáveis (6):** RF-MIGRACAO-006–009, RF-MIGRACAO-012; RF-AUTOMACOES-001.

**Dependências — início:** fundação e acesso autorizado às fontes, sem exigir conclusão do Epic 1. **Por capacidade:** diagnóstico CLI deve identificar operador, limitar acesso/saídas e registrar execução/hash/decisões de forma auditável no próprio incremento isolado; usar trilha institucional do Epic 1 quando disponível, sem dispensar rastreabilidade enquanto não estiver. **Incremental:** mapas e decisões alimentam cargas do Epic 15, sem depender de tabelas futuras. **Produção/dados reais:** autorização por fonte, política de guarda/PII, backup das exportações e isolamento verificados (parâmetros 03/05/09). Examinar fontes reais é tarefa futura, não executada nesta proposta; AS-IS não comprova atividade atual.

**Limites:** diagnóstico/revisão são somente leitura das fontes, sem importar/publicar nem escrever no banco operacional antes de seus controles estarem disponíveis; decisões de diagnóstico ficam no registro protegido dessa execução isolada. Não exigir domínios futuros: mapa de destino é contrato, não FK para tabela inexistente. Adaptadores/cargas de 15 reutilizam mapa/decisões com proveniência. Nenhuma tela de lotes no `/admin`; IA assistiva não é obrigatória para executar a CLI.

### Epic 4: Programar avisos e mensagens institucionais com segurança

**Objetivo:** responsáveis configuram integrações e rotinas permitidas sem editar código e acompanham entrega/falha de avisos de reunião e mandato.

**Resultado verificável:** transporte/configuração mínimos, destinos autorizados, segredo protegido, templates versionados com prévia/teste, flags e reprocessamento. Avisos de ocorrência (15 min antes por padrão) e mandato têm envio único, estados claros e logs mínimos. Rotinas comuns e específicas respeitam dono/escopo; desativação impede novo envio. Não exigir editor visual para enviar aviso.

**RFs responsáveis (16):** RF-SERVIDORES-010–011; RF-WHATSAPP-009–010; RF-ADMINISTRACAO-004–008, RF-ADMINISTRACAO-010; RF-AUTOMACOES-007, RF-AUTOMACOES-010–011, RF-AUTOMACOES-018–019, RF-AUTOMACOES-022.

**Dependências — início:** fundação e controles mínimos de acesso/autoria/segredo do Epic 1, não todos os seus RF. Não depende do Epic 3. **Por capacidade:** envio exige transporte autenticado/destino permitido/idempotência/trilha; avisos de mandato precisam de mandato implementado em 1 e avisos de reunião de ocorrência em 2. **Incremental:** ações específicas dos módulos são anexadas quando existentes; editor/gestão composta RF-AUTOMACOES-016–017 ficam em 12 e não bloqueiam o envio. **Produção:** contrato do provedor, canal/destinos autorizados, parâmetros 03/08/13 e worker/scheduler/locks testados antes do uso real. Equivalência com rotina legada exige evidência específica, não o diagnóstico integral do Epic 3 como barreira universal.

**Limites:** começar pelo envio seguro reutilizável; depois agendamentos com produtores já existentes. Consumidores registram ações reais e usam o mesmo transporte/outbox, sem handlers fictícios e sem duplicar engine. O editor posterior apenas compõe catálogo permitido; não transforma o módulo em clone n8n ou permite código livre. RFs de avisos não estão concluídos só porque existe transporte, e RFs de delegação exigem comprovar os escopos aplicáveis. Serviços opcionais não são obrigatórios sem necessidade medida.

### Epic 5: Registrar, conferir e corrigir a ata regular pelo celular

**Objetivo:** a dupla escalada registra a reunião completa sem planilhas, com rascunho recuperável, envio oficial único e retificação autorizada.

**Resultado verificável:** ocorrência/dupla pré-preenchidas, contagens e campos históricos completos, ingressos/conquistas/temáticas com vínculo humano opcional; conferência/recibo, filtros, CSV/impressão e versões com motivo. Gerar/reenviar resumo sanitizado por canal configurado, incluindo Telegram se confirmado ativo.

**RFs responsáveis (29):** RF-ATA-001–005, RF-ATA-007–017, RF-ATA-019–025, RF-ATA-027–028; RF-AUTOMACOES-002, RF-AUTOMACOES-014, RF-AUTOMACOES-020; RF-IDENTIDADE-003.

**Dependências — início:** capacidades de autorização/trilha/mandato do Epic 1 e ocorrência/escala do Epic 2, não seus Epics inteiros. **Por capacidade:** ata manual completa (preencher, persistir rascunho, conferir, enviar oficialmente, corrigir, consultar/exportar) não exige WhatsApp, Zoom, IA, diagnóstico legado ou editor. Somente envio externo de resumo exige transporte mínimo de 4; presença sugerida de Zoom usa 9 quando disponível. **Incremental:** Kit (6) e mensagens (4) consomem eventos persistidos com chave estável e versão, com replay idempotente. **Produção:** recuperação dos dados, escala/autorizações, parâmetros 03/05/06 e escopo nominal; parâmetro 08 só para canal a habilitar. Falha externa não reverte envio oficial nem simula entrega de mensagem.

**Limites:** RF-ATA-002 passa integralmente à coordenação de 5: pré-preencher data/horário/tipo/dupla/campos institucionais/referência da 7ª Tradição disponíveis localmente, editáveis. A cláusula Zoom é condicional à disponibilidade; 9 fornece enriquecimento por adaptador e é responsável por RF-ATA-006. Não deixar os campos locais vazios à espera de 9 nem alegar Zoom implementado antes dele. Registrar negócio/auditoria/eventos-outbox atomicamente no envio/correção; consumidores futuros não recriam ingresso, não duplicam efeito e só processam as versões/escopo autorizados. RF-ATA-023 e automações de envio permanecem pendentes até seu aceite próprio, embora a ata manual já funcione. Kit só se completa em 6; cutover da função antiga exige equivalência efetiva. Valores financeiros da ata continuam declarativos.

### Epic 6: Atender o Kit ingresso do pedido à entrega, com sigilo

**Objetivo:** Coordenador Kit recebe pendências oficiais, coleta endereço em canal privado, expede e acompanha entrega sem expor dados aos demais encargos.

**Resultado verificável:** ingresso oficial gera um Kit; fila/detalhe/observações cifrados, CEP, solicitação privada, expedição, rastreio Correios e relatório privativo. Falha externa permite retry/manual sem duplicação; retificação/anulação da ata abre revisão privada e preserva remessa expedida.

**RFs responsáveis (24):** RF-ATA-018; RF-RELATORIOS-013; RF-KIT-001–018; RF-AUTOMACOES-003–006.

**Dependências — início:** ingresso oficial/eventos da ata manual em 5 e controle de acesso/leitura auditada/mandato Kit em 1. **Por capacidade:** fila, endereço, expedição e registro manual de acompanhamento funcionam sem Correios/mensageria; consulta automática exige adaptador Correios autenticado privado; mensagem exige apenas transporte mínimo de 4. **Incremental:** reprocessamento/rastreio e avisos anexados sem duplicar efeitos do ingresso/correção. **Produção:** parâmetros 03/06/13, recuperação real e política de contato/canal; testes negativos de exclusividade e sigilo. Ausência de provedor não dispensa integrar/testar autenticação antes de aceitar a capacidade automática correspondente.

**Limites:** consumo idempotente dos eventos/outbox existentes, sem duplicar envio da ata; leitura de PII auditada, edição ordinária exclusiva, exceção root pessoal identificada. Relatório/avisos do Kit pertencem a este fluxo, não ficam dependentes do dashboard global. Grupo recebe no máximo agregado não identificável; nenhum código individual de rastreio.

### Epic 7: Escriturar e prestar contas sem duplicar movimentações

**Objetivo:** Tesoureiro mantém livro financeiro e reserva prudente; ASAAS efetiva movimentos elegíveis e o público consulta prestação autorizada sem contribuinte.

**Resultado verificável:** manual salvo e webhook válido produzem efeito imediato; duplicatas, concorrência e estados sucessivos não duplicam saldo. Corrigir/categorizar/estornar/excluir logicamente preserva origem, motivo e antes/depois. Dashboard financeiro próprio e projeção pública por data/valor/categoria/saldo.

**RFs responsáveis (21):** RF-TESOURARIA-001–014, RF-TESOURARIA-016–017; RF-RELATORIOS-012, RF-RELATORIOS-017; RF-AUTOMACOES-012, RF-AUTOMACOES-025; RF-COMUNICACAO-002.

**Dependências — início:** controles efetivos de Tesoureiro vigente, trilha e persistência da fundação/Epic 1. Nenhuma dependência obrigatória de 3 ou 4. **Por capacidade:** manual imediato/livro/saldo/ajuste auditado exigem apenas regras financeiras e categorias aprovadas; ASAAS exige contrato de autenticação/eventos elegíveis, inbox e unicidade de efeito do próprio incremento; publicação exige projeção sem contribuinte e política de divulgação. **Incremental:** notificações usam envio mínimo de 4 se habilitadas; carga histórica usa 3/15; !saldo usa 12, nenhum deles bloqueia o livro. **Produção:** parâmetro 07, recuperação testada, saldo inicial/categorias e aceite de cada origem; webhook real não aceito só por teste do manual.

**Limites:** sem rascunho/aprovação financeira e sem lançar pelo saldo declarado na ata. Livro/dashboard próprios não dependem dos indicadores globais do Epic 11. !saldo acrescenta um canal no Epic 12, não condição para consultar/usar o livro. Auditoria financeira só revela conteúdo pertinente ao autorizado.

### Epic 8: Formalizar reuniões de serviço e preservar decisões e documentos

**Objetivo:** SG mantém modelo, redige/finaliza atas de serviço e preserva decisões/PDFs com consulta apropriada.

**Resultado verificável:** template versionado, pré-preenchimento, rascunho retomável, finalização oficial sem aprovação adicional, PDF privado no Drive, histórico e correção/anulação justificada com versões anteriores preservadas. Relatório próprio usa totais históricos mesmo sem presenças nominativas.

**RFs responsáveis (12):** RF-ATASERVICO-001–010; RF-RELATORIOS-011; RF-ADMINISTRACAO-009.

**Dependências — início:** autorização SG/trilha/pessoa vigentes de 1, sem exigir todos os RF de 1/2/4. **Por capacidade:** template/rascunho requerem persistência e versão; finalização/PDF/arquivo oficial exige integração Drive privada funcional, entregue no próprio incremento. **Incremental:** pré-preenchimento por agenda de 2 quando disponível; notificações usam transporte mínimo de 4 somente se previstas/habilitadas. **Produção:** parâmetro 06, credencial/ACL Drive, recuperação dos documentos/chaves e preservação de revisões comprovadas. Falha Drive não permite declarar arquivamento definitivo bem-sucedido nem aceitar RF-ATASERVICO-005.

**Limites:** desconhecido continua nulo; não inventar listas/zeros na migração. Versionar documento, template e metadados sem transformar Drive em banco. Publicação só de decisões autorizadas; faltas/justificativas permanecem restritas. O acervo do RSG aproveitará essa capacidade no Epic 11.

### Epic 9: Conferir a presença sugerida pelo Zoom sem vigiar pessoas

**Objetivo:** servidor autorizado recebe sugestão de total e consulta temporariamente possíveis duplicações, mantendo a ata utilizável sem Zoom.

**Resultado verificável:** webhook validado/deduplicado correlaciona ocorrência, guarda mínimos cifrados, apresenta lista somente ao autorizado, permite total manual e expurga pelo prazo configurado; falha não se transforma em zero validado nem cria pessoas/contas.

**RFs responsáveis (13):** RF-ZOOM-001–011; RF-ATA-006; RF-RELATORIOS-014.

**Dependências — início:** ocorrência local em 2, ata manual funcional em 5 e Policies/trilha necessários de 1. **Por capacidade:** recepção/conferência requer contrato atual Zoom, autenticação/deduplicação, armazenamento cifrado e retenção/expurgo; não depende de mensageria/editor do Epic 4. **Incremental:** adaptador de sugestão para RF-ATA-002 coordenado por 5; ausência de eventos nunca impede o fluxo manual. **Produção:** parâmetros 03/12, finalidade/acesso por campo, default 365 dias preservado e política homologada; teste de indisponibilidade e expurgo efetivo.

**Limites:** completa pré-preenchimento assistido aproveitando ata manual já funcional; não reimplementa formulário/salvamento. Não criar controle de frequência individual nem vínculo Zoom→pessoa. Conferência e relatório nominal próprios não esperam indicadores globais.

### Epic 10: Publicar com privacidade e retificar informações públicas

**Objetivo:** disponibilizar projeções públicas autorizadas e aplicar decisões editoriais de ocultação/correção com anonimato, opt-out e histórico, reutilizando o atendimento já entregue em 2.

**Resultado verificável:** projeção de ingresso/conquista com nome mínimo opt-out, sem PII restrita; decisão humana correlacionada ao protocolo atualiza fonte/projeção/cache/export autorizado com trilha, sem ocultação automática. Registro histórico permanece em triagem até liberação humana. Não criar outra caixa de atendimento.

**RFs responsáveis (5):** RF-ACESSO-010; RF-RELATORIOS-015–016, RF-RELATORIOS-020; RF-COMUNICACAO-001.

**Dependências — início:** triagem/protocolo/correlação de atendimento em 2 e autorização/trilha necessários de 1. **Por capacidade:** cada projeção exige apenas sua fonte existente e regras editoriais; ingresso/conquista requer registro oficial de 5, não remessas de 6, livro de 7 ou dashboards de 11. **Incremental:** novos tipos de fonte se registram pela allowlist/Policy com seus donos, sem antecipar schema/PII. **Produção:** parâmetros 03/05/12 aplicáveis, fonte liberada, opt-out/correção e invalidação de cache/export testados; histórico só após aceite do lote em 15.

**Limites:** publicação só sobre dados existentes e permitidos; não precisa do dashboard melhorado para oferecer projeções mínimas. Reutilizar protocolo/triagem/encaminhamento de 2 e a regra de retificação do dono da fonte, sem segunda caixa de entrada ou herança de acesso ao Kit. O Epic 11 reutiliza publicação/opt-out, não cria outro. Histórico importado permanece em triagem até liberação humana no lote de 15.

### Epic 11: Entender os resultados do grupo e preparar o relatório do RSG

**Objetivo:** servidores/público autorizado consultam indicadores reproduzíveis e RSG confere/complementa o relatório bimestral com dados do grupo.

**Resultado verificável:** visão clássica e melhorada de resumo, atas, conquistas/ingressos, temáticas, visitas e quadro de serviço, com filtros, tabelas alternativas, exportação autorizada e impressão. RSG escolhe período livre, confere métricas, complementa, gera PDF e arquiva referência.

**RFs responsáveis (14):** RF-RELATORIOS-001–010, RF-RELATORIOS-018–019; RF-AUTOMACOES-013; RF-RSG-001.

**Dependências — início:** autorização/trilha/consulta necessárias de 1 e pelo menos uma fonte implementada; não todos os Epics 5–10. **Por capacidade:** quadro de serviço usa mandatos/escalas de 1/2; resumo/atas/visitas/conquistas/temáticas usam atas de 5; projeção nominal pública exige 10; RSG exige métricas/fontes previstas no modelo e armazenamento/PDF apropriado (capacidade Drive de 8), sem exigir todo o módulo de serviço. **Incremental:** filtros/painéis ampliam fontes existentes; 6/7/8/9 só são pré-requisitos quando o relatório realmente os usa. Comparação de fórmula histórica depende de exportação/definição correspondente de 3, e paridade integral do lote é de 15. **Produção:** parâmetros 05/11, acesso/export/cache e fórmula do relatório testados; ausência de fonte não vira zero nem licença para encerrar RF que a exige.

**Limites:** não reescrever livro financeiro, fila Kit, serviço ou lista Zoom; agregar/reutilizar consultas autorizadas entregues pelos donos. Comparação inicial usa fixtures/exportações autorizadas sem declarar legado migrado; paridade do acervo completo é comprovada no Epic 15. Nunca somar duas vezes totais declarados e detalhes nem incluir rascunho/anulada.

### Epic 12: Consultar os serviços do grupo por comandos WhatsApp autorizados

**Objetivo:** membros/servidores recebem respostas atuais por comandos do próprio grupo, respeitando audiência, contexto e capacidade.

**Resultado verificável:** webhook Evolution autenticado/deduplicado, parser/allowlist por grupo, catálogo de serviço/membros, rate limit, histórico mínimo e consultas !vago/!servidores/!saldo/!ata/!resumo/!relatorio aos dados reais. !participantes só agregado; !senha restrito/auditado; !kit não possui handler.

**RFs responsáveis (15):** RF-WHATSAPP-001–008; RF-SERVIDORES-013–014; RF-TESOURARIA-015; RF-AUTOMACOES-009, RF-AUTOMACOES-016–017, RF-AUTOMACOES-024.

**Dependências — início:** transporte mínimo de 4, identidade de origem/grupo/autorização e trilha necessárias de 1; sem exigir oito Epics concluídos. **Por capacidade:** webhook/parser/rate limit e comandos informativos com conteúdo aprovado podem funcionar primeiro; !vago/!servidores exigem mandatos/escala 1/2; !ata/!resumo exigem consulta oficial de 5; !saldo exige livro/projeção de 7; !relatorio exige relatório pertinente de 11; !participantes/!senha exigem fonte e autorização próprias, sem antecipar dados nem segredos. **Incremental:** RF-AUTOMACOES-016–017 acrescentam editor visual básico/composição e gestão integrada usando transporte/flags/templates existentes de 4; só ações previamente implementadas entram no catálogo. **Produção:** parâmetro 08, decisão sobre segredo Zoom/audiência, origem/grupo, replay/rate limit e permissão por comando comprovados. Não aceitar o catálogo completo só porque !ajuda funciona.

**Limites:** reutilizar provedor de saída/templates/flags/filas de 4; o editor é básico e declarativo conforme RF-AUTOMACOES-017, não um editor n8n avançado nem código livre. Primeiro homologar canal/handlers simples, depois compor ações já verificáveis. Comando sem produtor ainda não integra o catálogo funcional entregue: responder indisponível não conta como RF atendido. As Stories futuras serão ordenadas por produtores antes dos consumidores; se o produtor não estiver entregue, adiar a Story correspondente, sem criar dependência de Story futura. Kit/financeiro mantêm exclusividade.

### Epic 13: Apoiar aprendizado e literatura com IA habilitada conscientemente

**Objetivo:** responsáveis selecionam fontes/notebooks e habilitam assistência, meditação/treinamento ou resumo de conversas sem interferir na operação essencial.

**Resultado verificável:** notebook por finalidade e fontes manuais, endpoint/modelo/chave protegidos, limites/teste/diagnóstico; consulta pública à literatura só com fontes autorizadas/referências. Rotinas de treinamento/meditação a destinos permitidos e resumo de conversas separado da ata, todos desligados inicialmente.

**RFs responsáveis (6):** RF-IA-001–004; RF-AUTOMACOES-021, RF-AUTOMACOES-023.

**Dependências — início:** controles de acesso/segredo/auditoria necessários de 1 e fontes/notebooks autorizados. **Por capacidade:** literatura pública usa portal de 2 e segregação de fontes; envio de meditação/treinamento usa transporte mínimo de 4; resumo de conversas exige canal/recepção autorizado de 12, política de coleta/retenção e habilitação específica. Não exigir todos os módulos de 10/12 para cadastrar/conferir notebook ou consultar literatura. **Incremental:** cada finalidade e rotina é habilitada separadamente, desligada por padrão. **Produção:** fontes/direitos/termos, proteção de perfil/segredo, parâmetros 03/08, limites e contrato real do provedor; mocks comprovam cenários locais, não homologam integração real.

**Limites:** implementação desligada e testes com fixtures podem ser entregas verificáveis, mas não substituem aceite das integrações reais exigidas pelo RF nem permitem concluir 13 com capacidades faltantes. Ativação exige validação do provedor/termos/direitos e decisão operacional. Não conectar notebook interno ao público, coletar conversas previamente ou impor IA a ata/financeiro/migração. Indisponibilidade mantém fluxos essenciais.

### Epic 14: Manter o serviço recuperável, documentado e replicável

**Objetivo:** responsáveis operam os fluxos publicados, recuperam incidentes e preparam instalação segura no domínio oficial ou fork limpo, sem depender do conhecimento de uma única pessoa.

**Resultado verificável:** material de tarefa por módulo e administração, instalação parametrizada, backup de dados/arquivos e recuperação isolada conferida, contingência e teste de fork sem dados/segredos herdados. Validar acesso HTTPS via Tunnel/Swarm, hosts/proxies/cookies/cache, webhooks e ausência de portas públicas de origem, mediante autorização específica de implantação.

**RFs responsáveis (11):** RF-BACKUP-001–006; RF-DOCUMENTACAO-001–005.

**Dependências — início:** apenas fundação #33/PR #34, sem exigir 1–13 completos. **Por capacidade:** backup/restore real inicial de dados de teste, integridade e recuperação de chaves/arquivos necessários antecedem o primeiro dado operacional; ampliação cobre documentos/PII/chaves de cada módulo antes de seu uso real. Instruções acompanham os incrementos. **Incremental:** homologação da origem/Tunnel usa landing implementada em 2, não todos os módulos; documentação consolidada/fork e ensaios de release exigem exatamente os componentes incluídos no release. **Produção:** parâmetros 03/04/10/11, #30 para marca final pública, autorização de implantação, restore do escopo exposto e gates de segurança por rota; release parcial não equivale a produto completo. Infra fornece evidência para RF-PUBLICO-001, cujo responsável principal passa a 2.

**Limites:** snapshot estrutural e Swarm dev da #33 são reaproveitados, mas não substituem restore real de dados/documentos/chaves, fallback de Tunnel e treinamento observado. Validação da origem/domínio pode ocorrer em implantação controlada sem liberar dados históricos ainda não reconciliados. Publicar imagem, configurar rede, executar migration e promover produção exigem as autorizações próprias; esta proposta não as concede.

### Epic 15: Migrar o acervo e realizar a virada assistida com reconciliação

**Objetivo:** responsáveis incorporam todo o legado ativo aplicável, comprovam paridade e desligam cada ferramenta antiga somente após aceite, com retorno seguro.

**Resultado verificável:** cargas idempotentes por domínio, origem/hash/lote/linha, ambiguidades revistas por servidores antigos, totais/nomes/histórico preservados e publicação em triagem até liberação humana. Demonstrar equivalência Apps Script/n8n/Docs/Looker e reprocessamento; backup, ensaio de restore, aceite por rotina/fonte e cutover assistido com contingência.

**RFs responsáveis (12):** RF-MIGRACAO-001–005, RF-MIGRACAO-010–011, RF-MIGRACAO-013–016; RF-AUTOMACOES-015.

**Dependências — início:** diagnóstico/mapas/autorização da fonte em 3, capacidade do destino daquele lote e backup/restore do escopo em 14. Não exige todos os módulos de 1/2/4–13 nem conclusão integral de 14. **Por capacidade:** carga de atas depende apenas do destino ata, Kit de destino Kit, financeiro de livro/categorias/saldo e serviço de suas entidades/documentos; paridade usa consultas específicas e indicadores de 11 somente quando necessários à comparação. **Incremental:** ampliar fontes, vínculos e rotinas conforme destinos reais, com decisões humanas e idempotência por lote; IA nunca é pré-requisito. **Produção/virada:** parâmetros 05/09/11, recuperação/retorno do escopo, equivalência funcional de todas as funções ativas abrangidas e aceite por responsável antes de desligar cada legado. Para o encerramento integral de 15, toda fonte/rotina ativa aplicável deve estar conciliada ou formalmente descontinuada, não apenas o primeiro lote.

**Limites:** reutilizar regras/rotinas/importação diagnóstica e projeções anteriores, sem reimplementar cada domínio. Não transportar scripts inseguros, criar autor fictício, inferir identidade ou transformar totais desconhecidos em zero. Fluxos descontinuados precisam de aprovação explícita; IA/Telegram desativados não são presumidos ativos. A numeração final deste Epic não adia inventário (Epic 3), prevenção de perda ou ensaios pequenos por domínio; formaliza a consolidação e o aceite, nunca impõe big-bang.

## Dependências, critérios transversais e pendências da proposta

### Tipos de dependência e critério de conclusão

Cada Epic acima distingue **início**, **aceite por capacidade**, **integração incremental** e **produção**. Os rótulos da tabela abaixo são blocos funcionais para explicitar o grafo, **não Stories criadas, novos RFs ou estados de execução**. `BASE` é a fundação #33/PR #34; sua operação no checkout atual ainda será validada antes de implementação. A tabela explicita pré-requisitos funcionais; autorizações humanas, fontes, contratos externos e gates de produção descritos em cada Epic continuam obrigatórios.

Uma capacidade manual não depende de provedor externo para funcionar; uma capacidade de integração **depende** de contrato/autenticação/replay/falhas do respectivo provedor para ser aceita. Desabilitar uma feature, responder indisponível, usar mocks ou entregar só transporte não completa os RFs que exigem sua funcionalidade. Não encerrar Epic enquanto houver requisito/critério aplicável faltante; registrar separadamente capacidade entregue, RF parcial e Epic ainda pendente.

No passo 03, decompor respeitando o grafo e os produtores já entregues. Não fazer uma Story depender de Story futura, nem mascarar dependência cruzada por um rótulo de capacidade. Se faltar produtor, adiar o consumidor ou revisar a ordem antes de autorizar implementação. A aprovação deste grafo não aprova Stories ainda inexistentes.

### Grafo explícito por capacidade

| Capacidade | Pré-requisitos funcionais diretos | Resultado/finalidade do vínculo |
|---|---|---|
| E1-acesso | BASE | Gestão mínima de pessoa/conta/mandato, Policy e trilha necessárias ao uso ordinário; sem exigir root. |
| E1-root | E1-acesso | Contingência pessoal forte, separada do caminho ordinário. |
| E2-portal | E1-acesso | Agenda/configuração/escala administráveis e portal; SSR e conteúdo podem ser prototipados desde BASE, sem confundir protótipo com RF aceito. |
| E2-atendimento | E1-acesso, E4-envio | Protocolo/triagem básica e aviso privado efetivo aos dois responsáveis; depende só do transporte mínimo, não de avisos agendados/editor. |
| E2-producao | E2-portal, E14-origem | RF-PUBLICO-001 completo no domínio/HTTPS, sem depender de todos os RF de 14. |
| E3-diagnostico | BASE | CLI isolada com autorização da fonte e rastreabilidade próprias, sem gestão completa de acesso da aplicação. |
| E4-envio | E1-acesso | Transporte/destino/segredo/template mínimo, idempotência e resultado verificável do envio. |
| E4-avisos-mandato | E4-envio, E1-acesso | Programação de mandato, flags e reprocessamento; não exige agenda 2, diagnóstico 3 ou editor 12. |
| E4-avisos-agenda | E4-envio, E2-portal | Programação por ocorrência, flags e reprocessamento; não exige diagnóstico 3 ou editor 12. |
| E5-manual | E1-acesso, E2-portal | Ata manual completa, pré-preenchimento local, rascunho/envio/correção e evento-outbox atômico, independente de envio externo. |
| E5-envios | E5-manual, E4-envio | Resumo sanitizado e envio/reenviar com chave de efeito e versão; não repetir o envio oficial da ata. |
| E6-manual | E1-acesso, E5-manual | Pendência idempotente, endereço, expedição/atualização manual e revisão de Kit, privativos. |
| E6-integracoes | E6-manual, E4-envio | Conclusão do ciclo automatizado: rastreio autenticado, falhas/retry e avisos privados; rastreio isolado exige só manual+adaptador, não transporte. |
| E7-manual | E1-acesso | Livro/manual imediato/saldo/ajuste auditado, sem 3/4. |
| E7-asaas | E7-manual | Recepção autenticada, classificação e efeito financeiro único; sem depender do transporte de mensagens. |
| E7-publico | E7-manual, E2-portal | Projeção contábil sem contribuinte; não exige publicação nominal 10 ou dashboard 11. |
| E8-rascunho | E1-acesso | Template versionado, rascunho e totais próprios, sem mensageria. |
| E8-oficial | E8-rascunho | Finalização/PDF/Drive real com ACL/versionamento; adaptador/credencial Drive são obrigatórios neste aceite. |
| E9-assistencia | E1-acesso, E2-portal, E5-manual | Sugestão Zoom cifrada/restrita por ocorrência, expurgo e total editável, sem bloquear ata manual. |
| E10-editorial | E2-atendimento, E5-manual | Opt-out de nomes de ingresso/conquista e retificação fonte/projeção, sem Kit/financeiro obrigatórios. |
| E11-servico | E1-acesso, E2-portal | Relatório útil de encargos/vagas/escalas sem outros módulos de negócio. |
| E11-atas | E5-manual | Relatórios de atas/resumo/visitas/conquistas/temáticas a partir dos seus dados reais. |
| E11-publico | E11-atas, E10-editorial | Publicação nominal mínima dos relatórios, sem extrapolar Política da fonte. |
| E11-rsg | E11-atas, E8-oficial | Métricas/serviço e arquivo/PDF do relatório bimestral; outras fontes só se previstas no modelo aprovado. |
| E12-basicos | E1-acesso, E4-envio | Origem/grupo/parser/limites e comandos informativos com conteúdo aprovado, sem saldo/atas fictícios. |
| E12-servico | E12-basicos, E2-portal | !vago/!servidores com mandatos/escalas já disponíveis. |
| E12-atas | E12-basicos, E5-manual | !ata/!resumo com dados oficiais; envio de resumo usa texto/rotina já entregue. |
| E12-saldo | E12-basicos, E7-manual | !saldo usa livro/projeção autorizados, não exige comandos de atas/relatórios. |
| E12-relatorios | E12-basicos, E11-atas | !relatorio para visão já implementada; outras visões exigem somente seus próprios produtores. |
| E12-zoom | E12-basicos, E9-assistencia | Consulta agregada de participantes; !senha exige separadamente a fonte/decisão de segredo autorizada, nunca acesso nominal implícito. |
| E12-editor | E12-basicos, E4-avisos-mandato | RF-AUTOMACOES-016–017: composição visual básica/gestão integrada de ações existentes; começar por rotinas de mandato disponíveis, ampliar catálogo depois, sem esperar comandos de negócio/agenda. |
| E13-notebooks | E1-acesso | Notebook/finalidade/fontes/credenciais protegidos, inicialmente desligados. |
| E13-literatura | E13-notebooks, E2-portal | Consulta pública com fontes autorizadas e referências; sem depender do catálogo completo WhatsApp. |
| E13-envios | E13-notebooks, E4-envio | Treinamento/meditação por canal habilitado, sem depender de consulta pública à literatura. |
| E13-conversas | E13-notebooks, E12-basicos | Resumo distinto de conversas só após habilitação/política; não basta existir resumo da ata. |
| E14-recuperacao | BASE | Backup e restauração real isolada de dados/arquivos/chaves do escopo inicial; habilitar antes do primeiro dado operacional, ampliar por domínio. |
| E14-origem | E2-portal, E14-recuperacao | Origem privada/HTTPS/Tunnel/hosts/cookies/cache para o escopo a publicar; autorização de implantação separada. |
| E14-consolidacao | E14-recuperacao, E14-origem | Material/treinamento/fork e homologação final dos componentes incluídos no release, não de módulos alheios ao escopo. |
| E15-atas | E3-diagnostico, E5-manual, E14-recuperacao | Carga/reconciliação incremental de atas; sem esperar financeiro/Kit/IA completos. |
| E15-kit | E3-diagnostico, E6-manual, E14-recuperacao | Carga/reconciliação do Kit com sigilo; virada do rastreio exige também E6-integracoes quando substitui rotina ativa. |
| E15-financeiro | E3-diagnostico, E7-manual, E14-recuperacao | Carga/reconciliação financeira; virada ASAAS exige E7-asaas e aceite do efeito por transação. |
| E15-mandatos | E3-diagnostico, E1-acesso, E14-recuperacao | Carga de mandatos/pessoas/histórico com vínculo humano conferido; não espera atas/PDFs de serviço. |
| E15-servico | E3-diagnostico, E8-oficial, E14-recuperacao | Carga/reconciliação de reuniões/atas de serviço/documentos com totais históricos e versões preservadas. |
| E15-virada-integral | E15-atas, E15-kit, E15-financeiro, E15-mandatos, E15-servico, E14-origem | Encerramento integral exige também paridade/recuperação e aceite das demais rotinas/fontes ativas do inventário, não apenas essas cargas. |

As cargas e a virada integral são marcos de escopo, não uma exigência de esperar todas para cada lote ou virada parcial. Para **cada** desativação parcial, exigir: diagnóstico da fonte + destino e equivalentes funcionais usados por ela + reconciliação/retorno + publicação segura do escopo + aceite humano. Se comandos/avisos/rastreio/IA estiverem ativos na fonte, sua paridade depende das respectivas capacidades acima. Isso é dependência real e deve ser explicitada no lote/rotina; desligado/obsoleto exige classificação aprovada, não presunção.

### Ordem recomendada, sem bloqueio por Epic inteiro

1. **Desde a baseline:** diagnóstico isolado de 3 e recuperação inicial de 14; em 1, estabelecer acesso ordinário/mandatos/trilha. Não exigir root concluído para trabalho ordinário nem mudar a fundação.
2. **Com controles mínimos:** portal/agenda/dupla de 2, transporte mínimo de 4, manual financeiro de 7 e rascunho de serviço de 8 podem entregar valor independentemente. Antecipar atendimento de 2 assim que houver aviso privado validado; comandos informativos de 12 usam o mesmo transporte mínimo.
3. **Com produtores existentes:** ata manual de 5; relatórios de serviço de 11; depois Kit manual de 6, ASAAS de 7 e finalização Drive de 8 conforme contratos próprios. Mensagens da ata/Kit dependem só do transporte, não do editor. Ensaiar recuperação do escopo antes de dados reais.
4. **Por necessidade do fluxo:** Zoom 9, editorial/opt-out 10, relatórios de atas/RSG 11 e comandos de negócio 12 entram quando seus produtores estiverem prontos. Editor básico permanece em 12 sem bloquear os fluxos manuais. IA 13 tem finalidades independentes e inicialmente desligadas.
5. **Ao longo dos incrementos:** 15 importa/reconcilia cada fonte cujo destino e recuperação já existam; 14 valida origem/release do escopo, atualiza treinamento e testa fork. Nenhuma dependência universal de concluir 1–13 antes de backup, contato, carga ou release parcial.
6. **Encerramento completo:** comprovar todos os RF/CA aplicáveis, integrações reais exigidas, documentação/recuperação, aprovação visual final e aceites de cada legado. Capacidade parcial útil não autoriza declarar Epic/produto inteiro concluído.

Essa ordem não autoriza escrita simultânea nos mesmos arquivos. O grafo distingue disponibilidade funcional e condições de homologação; não é calendário, sprint, lista de Stories ou autorização de produção. As fronteiras mantêm os 15 resultados funcionais, corrigindo as barreiras artificiais sem esconder escopo faltante.

### Aplicação transversal obrigatória

| Contrato preservado | Responsabilidade na proposta |
|---|---|
| RNF-01–05 | Epic 1 inicia identidade/autorização/trilha; cada Epic aplica a seus dados/rotas; recuperação inicial de 14 antecede dados operacionais e sua homologação de release amplia o escopo. Root não é pré-requisito para uso ordinário; nenhuma rota fica desprotegida por ser incremento inicial. |
| RNF-06–07 | 2 inicia padrões públicos/mobile/acessíveis; todos os formulários/relatórios os aplicam; 5/6/7/8/10/11/13 ensaiam jornadas próprias. |
| RNF-08–10 | 4 entrega envio/falhas/idempotência; 5/6/7/9/12/13 testam seus eventos/canais, inclusive replay/concorrência/origem. |
| RNF-11–12 | 3 evita consolidação prematura; cada domínio protege dados e ensaia export/retorno; 14 comprova restore integrado; 15 valida reconciliação/virada. |
| RNF-13–16 | Material de tarefa, observabilidade mínima, metas após baseline e formatos abertos em cada Epic; consolidação/treinamento/contingência em 14. |
| RNF-17–18 | 2/7/10/11/13 protegem projeções/caches/exports; 14 testa domínio/fork limpo. Single-group preservado em todos. |
| RB-01–04 | 5 e 8 preservam estados/versões; 9 só sugere; 6 trata efeitos de correção; 11/15 validam totais/proveniência. |
| RB-05–06, RB-14–18, RB-21 | 1 aplica identidade, mandatos, segregação/root; 2 escala; todos os consumidores aplicam; 3/15 revisam homônimos sem fusão automática. |
| RB-07–10, RB-19–20 | 6 sigilo Kit; 7 livro/projeção financeira; 10 opt-out/triagem; 4/5/12 canais autorizados; 11 consulta sem ampliar dados; 15 histórico em triagem. |
| RB-11–13, RB-22–23 | 1 estados/mandatos; 8 template/Drive; 4/12 rotinas/handlers; 3/15 paridade e desligamento só aceito; nenhum automatismo substitui decisão humana sensível. |

| CAs preservados | Epics onde comprovar o cenário completo (não testes já executados) |
|---|---|
| CA-01, CA-20 | 2 (com uso da dupla na ata em 5) |
| CA-02, CA-06, CA-13 | 5 (e ensaio operacional consolidado em 14) |
| CA-03, CA-34 | 9, reutilizando 5 |
| CA-04, CA-10, CA-21, CA-28 | 7 |
| CA-05 | 1 fornece controle; 6/7/9 comprovam negações nos dados reais dos módulos |
| CA-07, CA-16, CA-22, CA-36 | 6, integrando 5 e publicação de 10 quando nominal |
| CA-08 | 1/2 mantêm estado, 12 comprova comandos |
| CA-09, CA-14, CA-17, CA-18 | 3 prepara diagnóstico; 15 comprova importação/paridade e desativação |
| CA-11 | 12 |
| CA-12, CA-15, CA-35 | 14 começa restore desde a baseline e comprova fork/release no escopo; 2 coordena RF-PUBLICO-001; 15 repete salvaguardas no acervo/cutover |
| CA-19, CA-23, CA-26, CA-27, CA-29, CA-33 | 1; consumidores completam o alcance de poderes/negações conforme seus recursos surgem |
| CA-24, CA-38 | 8; 15 comprova também o acervo histórico de totais |
| CA-25 | 4 comprova transporte/flags/avisos/templates; 12 completa roteamento e composição visual básica |
| CA-30, CA-37 | 2 entrega protocolo/triagem/aviso/resposta; 10 integra ocultação e projeções nominais; cada dono retifica sua fonte autorizada |
| CA-31 | 4 usando ocorrências do Epic 2 |
| CA-32 | 13 sem bloquear 5/7/8 |

| Extratos UX | Epics responsáveis por implementar/propagar os contratos |
|---|---|
| UX-DR1–3, UX-DR6–12, UX-DR16, UX-DR24–25 | 2 inicia portal/identidade/componentes; #30 governa marca final; 14 homologa domínio; demais consumidores ensaiam o padrão. |
| UX-DR4–5, UX-DR15, UX-DR45, UX-DR54–55 | 1; 2/5/6/7/8/9/10/11/12/13 reaproveitam permissões/busca conforme finalidade. |
| UX-DR13–14, UX-DR17–19, UX-DR49–53 | Primeiro uso em 1/2/4, aplicação em cada jornada e homologação integrada em 14; não um Epic de componentes. |
| UX-DR20, UX-DR26–30, UX-DR32 | 5 |
| UX-DR21, UX-DR33–36 | 6 |
| UX-DR22, UX-DR37–38 | 7 |
| UX-DR23, UX-DR39–41, UX-DR62 | 11, aproveitando visões próprias de 1/2/6/7/8/9 e privacidade de 10. |
| UX-DR31 | 9 |
| UX-DR42–44 | 4 entrega configuração/templates mínimos e extensões pelos donos, sem duplicar engine. |
| UX-DR59 | 12 acrescenta editor visual básico/gestão composta sobre transporte/flags de 4 e ações já existentes. |
| UX-DR46–48 | 8 |
| UX-DR56–57 | 2 entrega atendimento geral/triagem básicos; adaptadores de alvo usam capacidades existentes e permissões próprias. |
| UX-DR58 | 10 reutiliza atendimento de 2 para ocultação/correção nas projeções editoriais. |
| UX-DR60–61 | 13 |
| UX-DR63 | 3/15, nunca gestão de importações no admin. |

### Pendências que a aprovação dos Epics não resolve

Registro da [conferência na #35](https://github.com/grupoquarentena/site/issues/35#issuecomment-6329813629), atualizado pelas decisões posteriores do responsável. A aprovação dos Epics não resolveu implicitamente os itens; somente auditoria e bootstrap receberam a decisão específica abaixo. Os demais permanecem abertos antes das Stories afetadas:

| Pendência | Antes das Stories de | Decisor proposto / encaminhamento |
|---|---|---|
| Alcance literal da auditoria em mutações técnicas e trilha | 1; depois consumidores | **Resolvido por decisão específica do responsável:** negócio/identidade/mandatos/permissões/configuração atômicos, eventos de segurança próprios e sem recursão técnica; conciliado em PRD/Arquitetura, implementação ainda futura. |
| Cardinalidade CREDENCIAL no diagrama versus pessoa única por usuário | 1 | Corrigir diagrama com confirmação, preservando regra textual/constraint; nenhuma conta adicional. |
| Senha da reunião pública versus segredo de anfitrião/!senha | 2/9/12 | Responsável + Mesa/segurança: distinguir credenciais e audiência; inventário é evidência, não decisão normativa. |
| Texto genérico de retenção versus default Zoom 365 dias e políticas por classe | 1/4/6/9/10/13/14/15 conforme dado | Mesa/donos de dados + técnico: preservar default explícito, registrar finalidade/guarda/descarte, sem fabricar prazos. |
| Nomes mínimos em relatórios versus proibição genérica de pessoas no resumo | 5/10/12; 2 mantém triagem sem decidir política editorial | Responsável/editorial: precisar audiência/template sem remover opt-out aprovado nem reintroduzir contato/Z. |
| Acervo/marca definitiva não presente nesta baseline | 2/14 | #30: reunir/homologar; não bloquear decomposição ou tarefas independentes nem supor reformulação. |
| Frase residual 62 migrations versus 63 entidades candidatas | Antes de qualquer Story que materialize schema | Correção editorial confirmada; quantidade de entidades não manda gerar migrations em bloco. |

Os 13 parâmetros operacionais continuam na tabela existente, com atribuições ainda propostas. Associar ao detalhamento: 01→2/4; 02→1/2; 03→todos os fluxos que tratam dados pessoais; 04→1; 05→3/5/11/15; 06→2/5/6/8 nos respectivos alvos autorizados; 07→7; 08→2/4/5/12/13; 09→3/15; 10→2/14; 11→medições em cada fluxo e consolidação 14/15; 12→2/9/12; 13→1 e integrações 4/6/7/9/12/13. Nenhuma nomeação, prazo ou decisão operacional foi inferida como aprovada.

**Histórico do checkpoint do passo 02 (antes do aceite dos Epics 1–5):** o responsável aprovou definitivamente a revisão 2 dos 15 Epics, sua distribuição de RFs e dependências por capacidade. A elaboração segue no passo 03, um Epic por vez e uma Story por revisão; aprovação de estrutura não resolve os contratos pendentes nem comprova Stories implementadas, readiness ou aceite operacional.

## Contratos compartilhados dos Epics 2–5 e dependências de integração

**Histórico pré-aprovação do lote:** a execução OpenCode foi interrompida por quota enquanto revisava Epics 2–3; o arquivo físico também continha sete Stories do Epic 4, anteriores à interrupção. Nesta continuidade documental, foram preservadas as 12 Stories do Epic 1, revisadas pontualmente as seis propostas de 2 e quatro de 3, revisadas as sete de 4, explicitado o consumidor de aviso privado em 4.8 e elaboradas nove Stories completas do Epic 5. Os relatórios de revisão do OpenCode são evidência histórica; **não houve subagentes independentes executados nesta edição externa**. A conferência editorial/automatizada desta edição não substitui a revisão adversarial BMAD do runtime do repositório nem aprovação humana. Nenhum AC/Story foi implementado, nenhum teste de Laravel/PostgreSQL executado, nenhum PR/commit autorizado. Os Epics macro 1–15, mapa dos 236 RF e conteúdo do Epic 1 foram preservados.

### Contratos de aceite comuns do lote (CL)

Integram cada AC aplicável abaixo, sem substituir os cenários específicos:

**Extensão explícita aos Epics 6–15 nesta auditoria:** CL-01–05 também integram seus AC pertinentes. Isso propaga os controles já exigidos pelo PRD/Arquitetura, sem mudar o aceite condicionado dos Epics 1–5: leitura sensível falha fechada se não auditar, autorização é revalidada antes da entrega/efeito, dados sensíveis não persistem no navegador, outbox é durável na transação e incerteza externa não admite retry cego. Testar duas conexões PostgreSQL, revogação/versão concorrentes, fila parada/reinício e rollback; driver `array`/mocks isolados não comprovam esses contratos. Atores técnicos usam autorização institucional de rotina, nunca sessão humana fictícia. Não criar tabelas futuras, nem impor Redis/MinIO ou reconstruir login/logout da fundação.

- **CL-01 — Autoridade e autoria:** reutilizar login/identidade da fundação e capacidades/trilha das propostas 1.2/1.3/1.5/1.6; só implementar consumidor após aprovação humana e disponibilidade comprovada de suas predecessoras. Cada entrada HTTP/CLI/job tem autorização no servidor, escopo por recurso, pessoa/conta ativa e mandato/escala vigente; testar anônimo, outro encargo, ID manipulado, expiração com scheduler parado, cache aquecido e corrida com revogação. Sem conta fictícia para atores técnicos. Root é integração excepcional apenas após 1.12 aprovada/disponível: testar motivo/modo/TTL/autoria, nunca exigir root para caminho ordinário.
- **CL-02 — Persistência:** mutação de negócio + auditoria redigida + evento/outbox quando houver efeito externo são atômicos; falha em qualquer parte reverte tudo e não mostra sucesso. Ator, origem, módulo, entidade/ID, ação, correlação, UTC, IP/agente quando disponíveis e motivo aplicável; nenhum segredo/corpo privado desnecessário. Segurança/negação/leitura sensível usam eventos próprios, sem recursão de sessão/cache/fila/trilha. Leitura sensível obrigatória falha fechada se seu evento falhar. Revisão esperada comparada atomicamente, sem atualização perdida. Testar constraints/FKs/NOT NULL/UNIQUE/CHECK e concorrência de duas conexões PostgreSQL, não só mocks/SQLite.
- **CL-03 — Dados e ciclo de vida:** minimizar/cifrar contatos, corpo privado e referências sensíveis; não retornar em HTML/JSON público, sugestões, logs, analytics, toast, cache/CDN ou export comum. Versionar/excluir logicamente preservando história; expurgo legal é distinto de soft delete, condicionado à política por classe e recuperação do escopo. Antes de dados reais, homologar finalidade/guarda/descarte/chaves e restore isolado via E14-recuperacao; esta condição operacional não exige conclusão integral de 14 nem cria Story fictícia dele. Testes sintéticos não autorizam coleta real.
- **CL-04 — UX e evidência:** telas mobile-first, coluna única, labels/erros anunciados, foco/teclado/leitor, contraste AA como alvo, movimento reduzido, estados vazio/carregando/erro/sucesso pós-commit e instrução curta no material existente. Ensaiar 360/375/768/1024/1440px e zoom 200%, sem rolagem horizontal essencial; observar tarefa de servidor leigo. Nada disso foi executado pelo planejamento. Novas migrations só para entidades usadas no incremento, com snapshot/catálogo reais regenerados na implementação; não gerar todo o dicionário ou reescrever fundação. RNF-01/03–06/09/13–17 e regras pertinentes acompanham cada fluxo, não um Epic técnico separado.
- **CL-05 — Integrações:** envio externo só após commit; transporte/segredos/destinos/flags e autoridade revalidados antes do disparo, rate limit/retry e correlação por efeito. Distinguir solicitado, aceito pelo provedor, entregue quando comprovável, falha e resultado desconhecido. HTTP 200 não comprova recebimento. Não prometer exactly-once externo sem suporte do provedor: resultado incerto vai a reconciliação/intervenção, sem retry cego. Contrato vigente/autenticação/limites reais homologados antes de habilitar; mocks e flag desligada não completam RF de integração.

### Ordem de implementação proposta por capacidade, não por número de Epic

Após aprovação/disponibilidade dos incrementos necessários de 1: **2.1 → 2.2 → 2.3/2.4**; depois de 2.4, **2.5 → 2.6**, sem depender de transporte, worker ou Story futura de 4. O núcleo de atendimento entrega recepção/protocolo, registro durável de intenção de aviso, triagem e resposta humana mínima operacional. **O aviso privado real aos dois responsáveis será entregue pelo consumidor 4.8**, que usa o evento de 2.5 e transporte de 4.2; não está entregue pelo registro da intenção. Transporte mínimo de 4 também precede envios externos automatizados dos demais consumidores, não seus fluxos locais. Diagnóstico de 3 é independente; ata manual pode começar após 2.3 sem atendimento/mensageria. E14-origem fornece evidência de produção, não é Story inventada neste lote. Nenhum número de Story futura representa pré-requisito de 2.1–2.6. Integrações futuras com 6/9/10/12/14/15 não bloqueiam o núcleo independente nem contam como completas por contrato sintético.

**Precisão da decomposição autorizada de 2:** o mapa macro anterior permanece literalmente preservado. Sua capacidade composta `E2-atendimento` distingue agora núcleo local (2.5/2.6) e aviso efetivo dependente de `E4-envio`; a aresta macro não é dependência obrigatória das Stories locais. As predecessoras abaixo exigem a capacidade local aprovada/disponível que o consumidor usa, não a conclusão global de RF composto: 2.5 pode usar a landing local de 2.4 sem esperar seu gate externo de produção ou relatório futuro. RF-ATENDIMENTO-001 continua integralmente sob responsabilidade principal de 2 e **parcial até prova real de aviso privado a CG e SG** pelo consumidor de 4. Não há redistribuição dos 236 RF/15 Epics, alteração de requisito nem uma segunda engine/outbox. As referências numéricas anteriores a Stories de 3–5 são propostas históricas a confirmar na elaboração respectiva, não contratos implementados ou autorização de elaborá-las nesta rodada.

**Gate E2-K — texto livre e Kit, decisão humana ainda pendente:** Brief §3, PRD RF-ATENDIMENTO-002/RB-07/17 e UX complementos vedam PII Kit a CG/SG, mas não definem quem pode examinar relato genérico potencialmente misto antes de liberá-lo. Antes de implementar essa liberação e **antes de habilitar qualquer recepção real**, responsável/Mesa, privacidade, Coordenador Kit e técnico devem homologar, no registro operacional existente: (a) quem está legitimado a conferir o original, com qual finalidade/autoridade vigente, sem conceder Kit a CG/SG; (b) percurso protegido do original e contato, produção humana de projeção sanitizada, autorização/versionamento da liberação e encaminhamento privado ao dono; (c) tratamento de categoria genérica contendo PII Kit, caso misto, dúvida, ausência/revogação do conferente e falha de trilha; (d) guarda/chaves/descarte e ensaios positivos/negativos. **Candidata técnica para apreciação, não decisão institucional tomada:** quarentena universal do original/assunto/contato até conferência humana legitimada, com metadados de catálogo visíveis a CG/SG e projeção separada liberada somente após saneamento. A escolha de categoria, aviso no formulário, regex, IA ou autodeclaração do visitante nunca provam que o original é seguro. Autoridade para Kit continua exclusiva do seu Coordenador vigente (ou root elevado excepcional quando disponível); não criar papel genérico de triador com acesso ao Kit. Se o esquema escolhido demandar nova atribuição sobre relatos gerais, homologá-la expressamente e harmonizar UX/Arquitetura existentes antes da implementação afetada, sem inferi-la nesta proposta. Sem decisão/ensaio, manter recebimento real desabilitado, demonstrar apenas fixtures sintéticas e não declarar o atendimento operacional. A solução homologada precisa caber no fluxo 2.5/2.6, sem exigir Story futura de Kit, root ou classificador.

## Histórico de conferência cruzada dos Epics 1–5 — precedente do aceite de planejamento

**Método e limite:** revisão documental e validações determinísticas deste arquivo, não execução de BMAD Party Mode com agentes reais e não aprovação de Story. Perspectivas aplicadas: **Analista (Mary)** — preservar 236 RF e ownership; **PM (John)** — valor e fatias implementáveis; **UX (Sally)** — SSR/teclado/mobile e estados reais; **Arquiteto (Winston)** — dependências por capacidade e ciclos; **Dev (Amelia)** — Laravel/PostgreSQL, atomicidade, sessões, idempotência; **QA (Quinn)** — GWT/negativos e evidência; **Segurança/privacidade** — Kit, Zoom, mensagens, dados legados e publicação. O OpenCode deve executar revisão independente real do conjunto, com contexto completo dos quatro normativos e diff, corrigir e registrar suas sessões sem atribuir autonomia a esta conferência editorial.

**Achados concretos tratados nesta continuidade:** (a) transporte 4.2 não precede obrigatoriamente captura local 2.5; o consumidor 4.8 materializa os avisos reais; (b) Epic 4 tinha sete Stories e não cobria explicitamente consumo do protocolo e envio aos dois titulares; incluída 4.8; (c) Epic 5 não possuía Stories: criada sequência 5.1–5.9, com 29 RF principais rastreados; (d) rascunho, ata oficial, revisões, resumo e envio externo agora têm gates distintos, sem Kit/Zoom/Telegram como dependência de preenchimento; (e) Epic 3 conserva diagnóstico offline sem migração operacional. **Pendente:** conferência externa dos 1–5 com normativos e o código no checkout atual, gates operacionais/decisões humanas e aceite formal; nenhum resultado de testes técnicos ou integração é afirmado.

**Condições de parada na rodada histórica anterior ao aceite (não são o estado vigente):** naquela etapa, as Stories 1–5 ainda aguardavam aprovação e os Epics 6–15 não tinham detalhamento. O estado atual está exclusivamente no frontmatter e no quadro de aprovação do início deste índice: 1–5 aprovados como planejamento condicionado e 6–15 detalhados/revisados aguardando aceite. Não avançar automaticamente o passo 04, sprint planning ou build por inferência desta nota histórica.

### Revisão final documental no OpenCode — 23/10/2026

**Parecer: CONCERNS — pronto para apreciação humana como planejamento condicionado, não para build ou produção.** Nesta execução, dois contextos independentes de leitura foram efetivamente utilizados: A requisitos/produto/UX, `ses_e967e85e8ffe5XXhsZe63l0uNq`, e B arquitetura/Laravel/PostgreSQL/segurança, `ses_e967e3f07ffes7SubGKoKhwyJC`. Ambos declararam leitura das 39 Stories/235 AC; A comparou os 103 RF principais e B auditou contratos técnicos e baseline. A leitura inicial de A atravessou atualização externa, sem certificação retrospectiva de snapshot único; B identificou o snapshot autorizado. O responsável confirmou a atualização legítima de `ddc41b93a0f09d8eb01fd46f565b0eb6b9047ca9b7b2afe033d48bf6594b9c93` para `e8a46925a96950ad6161d46802b658dc3203c99c19d94b3dc80f43d620f98a66`. Após as correções exclusivas do coordenador, ambos leram integralmente o diff dessa baseline para `a456e5ef3b6c03304185d93d85a41f9a0a1bbad6ccfa63146a638bb8bdce3ff6`, revalidaram focalmente seus achados e declararam ausência de residual documental concreto nesse diff. Este último hash identifica o conteúdo comportamental revalidado antes desta nota de proveniência, não o hash final do arquivo.

**Correções mínimas, sem regeneração:** 12 achados distintos tratados (11 MAJOR e um MINOR editorial): estados de conferência, totais oficiais obrigatórios, barreira/gate de PII Kit em texto livre, contingência CG/SG, proibição de persistência sensível no navegador, geração automática do resumo, busca contextual mínima, edição segura de sugestões, contagem do Epic 4, direito próprio do remetente ativo, evento durável independente de consumidor habilitado e obrigação lógica do aviso perante troca de destino. Epic 1, inventário/mapa macro e 15 Epics preservados. Matrizes conferidas: 38/14/6/16/29 RF principais; 12/6/4/8/9 Stories e 82/33/25/41/54 AC. Não equiparar cobertura planejada ao atendimento integral de RF composto; RF-ATENDIMENTO-001 exige os avisos efetivos de 4.8 via 4.2, e Kit/Zoom/publicação/descarte continuam nos respectivos consumidores.

**Conferência multidisciplinar:** skill local `bmad-party-mode` em modo `session`, com perspectivas simuladas pelo coordenador de Analyst, PM, Architect, UX, Developer, QA e Segurança; não sete sessões autônomas nem revisão independente adicional. A segunda pauta comparou monolítico, índice por Epic, fonte segmentada com compilação e fonte única com navegação/leitura dirigida. Sprint planning instalado admite múltiplos arquivos, mas passo 03 escreve explicitamente em `epics.md` e a governança atual exige consolidado. Recomenda-se preservar fonte única nesta etapa; segmentação futura requer decisão humana, compatibilização explícita, validação e migração reversível. Nenhum sharding/workflow foi executado ou alterado.

**Checkpoint humano preservado:** D1–D7, continuidade administrativa, E2-K, tratamento verificável de PII no texto livre da ata, publicação/opt-out, autoridade de contingência e parâmetros operacionais continuam sujeitos a homologação antes da implementação afetada/uso real conforme cada gate. Ausência de defeito residual no diff não resolve essas decisões. Validações desta execução são documentais (contagens, matrizes, referências, grafos, UTF-8, Markdown/links/tabelas, leitura salva e whitespace, inclusive arquivo novo); não testes de runtime, homologação técnica, readiness ou revisão integral para PR. `status: draft`, passo 03 aberto e `approvedStoryIds: []` mantidos; sem sprint, commit, push, PR ou início do Epic 6. O hash final e a triagem dos achados são entregues na resposta ao responsável, sem relatório paralelo.

<a id="governanca-de-artefatos-e-integridade-do-backlog"></a>

## Governança de artefatos e integridade do backlog

### Histórico da auditoria integral anterior ao aceite dos Epics 6–15 — 23–24/10/2026

**Precedência de estado:** as propostas 6–15 já receberam revisão/revalidação documental independente; somente o aceite humano do checkpoint permanece pendente. Frases anteriores sobre revisão futura são proveniência da elaboração, não o estado atual.

**Fechamento executado em 24/10/2026:** validador real com `errors: []`, 101 Stories/483 AC/236 RF/22 famílias; suíte `uv run --with 'ruamel.yaml>=0.18' python -B -m unittest discover -s scripts/tests -p test_validar_backlog.py -v` com **26/26 testes OK**. O primeiro rerun identificou fixture parcial sem o alvo 6.2 agora referenciado neste registro; corrigida somente a fixture sintética, sem afrouxar validador ou aprovações. `git diff --check` sem erros (aviso de conversão LF/CRLF da `.gitignore` preexistente). Comparação externa SHA-256 confirmou preservação byte a byte de AGENTS, governança, `.gitignore`, inventário AS-IS, quatro normativos e cinco detalhes aprovados. Revisão estrutural final confirmou cinco recomendações resolvidas; lentes globais revalidaram seus seis contratos sem residual focal. `planning_complete: false`, `implementation_authorized: false`, 39 IDs aprovados e revisão/aceite 6–15 pendentes mantidos intencionalmente.

**Parecer: CONCERNS para os 15 Epics e o conjunto — aptos à apreciação humana como planejamento condicionado, não readiness/build/produção.** Nenhum BLOCKER/MAJOR documental residual identificado após correções/revalidações; gates institucionais e evidência de runtime continuam pendentes. A regressão preservou integralmente os cinco detalhes aprovados e os 39 IDs de `approvedStoryIds`. Os demais 62 IDs não foram aprovados. `draft`, passo 03 e somente passos 01/02 em `stepsCompleted` preservados; nenhuma geração de sprint, código Laravel, migration, commit, push ou PR.

**Evidência e método:** BMAD 6.12.4 confirmado no manifesto, skill local carregada, passos 03/04 lidos como critérios sem finalizar. PRE-01 normalizou títulos/matrizes com igualdade literal do conteúdo das Stories/AC; PRE-02 protege finalização mesmo sem elaboração pendente, considerando revisão/aprovações; PRE-03 altera apenas campos necessários da fixture temporária, sem reserializar todo o YAML. Revisão independente do preflight `ses_e949d8b7dffellWJNDkdyG3MYt` confirmou as três correções e 26 testes. Os resultados finais são documentais, não testes de acesso/concorrência/integração dos futuros módulos.

**Cobertura examinada, não simples presença de IDs:** cada uma das 62 Stories/248 AC foi confrontada individualmente por ao menos um dos cinco contextos de revisão/revalidação abaixo, em ordem crescente dentro de cada lote; o coordenador aplicou correções em sequência 6→15. Os 133 RF principais foram comparados ao texto integral do PRD, com RNF/RB/CA/UX pertinentes; matrizes compostas não autorizam declarar RF implementado por uma fatia parcial. Os 103 RF dos Epics 1–5 receberam regressão dirigida e conferência dos contratos consumidores, não nova aprovação institucional. Ledgers individuais e achados corrigidos vivem no próprio Epic; as sessões registram as leituras e revalidações, sem relatório paralelo.

| Contexto independente somente leitura | Escopo efetivo |
|---|---|
| `ses_e949cc042ffelQAdv6ld6a1cFg` | Regressão integral dos detalhes 1–5, hashes, 39 Stories/235 AC/103 RF e contratos com 6/9/10/11/15 |
| `ses_e949c9602ffek86gmh22KfLrIQ` | 6→7: 13 Stories/52 AC/45 RF; correções focais e matriz financeira residual X-01 revalidadas |
| `ses_e949c70b4ffeP3CPTRSZfPhilp` | 8→9: 11 Stories/44 AC/25 RF; revalidação focal dos oito achados |
| `ses_e949c48d7ffe9cZqN60z6Bjjof` | 10→11: 10 Stories/40 AC/19 RF; revalidação dos dez achados |
| `ses_e949c24a6ffeWEqI3feNpRXHRk` | 12→13: 14 Stories/56 AC/21 RF; revalidação dos cinco achados |
| `ses_e949bfb7effeMsV1as7jHml9pw` | 14→15: 14 Stories/56 AC/23 RF; revalidação dos sete achados |
| `ses_e949bc664ffeuGRGSgMuvd27V8` | Consumidores reais BMAD, overrides, baseline Laravel e documentação 13.x; sem execução de workflows |
| `ses_e93e2b1c5ffenCfejA93vo0YkY` | Segunda revisão global, lente adversarial BMAD; 15 detalhes/índice/contratos; dois residuais corrigidos/revalidados |
| `ses_e93e2b1a6ffe3fTcd8oaXdrlkF` | Segunda revisão global, caminhos de borda BMAD; quatro residuais corrigidos/revalidados |
| `ses_e93e2b1a4ffeEas13SXAb01XCk` | Segunda revisão global, estrutura BMAD; cinco recomendações, sem edição |

**Triagem consolidada, sem contar duplicações entre revisores:** 58 achados tratados — **1 BLOCKER, 37 MAJOR e 20 MINOR**. São os três PRE, 45 achados dos lotes (X-01 transversal contado uma vez) e dez globais G-01–10. Classificações intermediárias MODERATE/MÉDIO foram triadas como MINOR documental quando não faltava capacidade principal; ALTA como MAJOR. Repetições da regressão e a recomendação de agenda IA foram fundidas aos mesmos achados. Nenhum OBSERVAÇÃO/IMPROCEDENTE foi inventado para completar quota. Gates abertos abaixo não são achados corrigidos nem decisões aprovadas.

| ID / severidade | Epic/Story/AC | Evidência original | Fonte normativa | Correção | Resultado | Revalidação |
|---|---|---|---|---|---|---|
| PRE-01 BLOCKER | 6–15 títulos/matrizes | 20 erros no parser instalado | Adaptador B; validador | Normalização sem mudar Stories/AC/RF | Corrigido | Sessão preflight; parser e suíte |
| PRE-02 MAJOR | Validador/estado | Finalização só verificava elaboração pendente | Governança §4.1 | Revisão/aprovações incompletas também bloqueiam final/currentStep/steps | Corrigido | 26 testes positivos/negativos; sessão preflight |
| PRE-03 MAJOR | Fixture temporária | Dump YAML introduzia whitespace | Regressões B | Alteração seletiva dos campos, preservando restante/corpo | Corrigido | Testes parcial/completo não aprovado e completo aprovado sintético |
| X-01 MINOR | Matrizes 6/7 | RFs compostos apontavam só primeiro incremento | PRD integral; BMAD 04 | Vínculos aos produtores/consumidores, inclusive RF-TESOURARIA-004 | Corrigido | Revisor 6/7, última conferência focal sem residual |
| G-01 MAJOR | 14.2 AC-03 | Restore retrocede dedup de efeitos externos | RNF-09/11; CL-05 | Quarentena e reconciliação pós-backup antes da retomada produtiva | Corrigido | Lente bordas, revalidação focal |
| G-02 MAJOR | 12.1 AC-01 | ACK antes de durabilidade podia perder comando | RNF-09; Arquitetura §3 | Inbox antes do ACK e recuperação sem reentrega | Corrigido | Idem |
| G-03 MAJOR | 7.3 AC-04 | Estorno manual e externo podiam compensar duas vezes | RF-TESOURARIA-003/017; CA-04 | Correlacionar reversão econômica e aplicar só diferença restante | Corrigido | Idem |
| G-04 MAJOR | 13.6 AC-03 | Sanitização só após transferência ao provedor | RNF-05; RF-AUTOMACOES-023 | Minimização/negação antes da chamada IA | Corrigido | Idem |
| G-05 MAJOR | 6.2 AC-01/04 | Adaptador Kit prometido em 2.6 sem aceite concreto | RF-ATENDIMENTO-002; E2-K | Protocolo/Policy/efeito/trilha/conclusão consistente e resposta segregada | Corrigido | Lente adversarial, revalidação focal |
| G-06 MAJOR | 13.5 AC-01 | Agenda de mandato tomada como agenda genérica | RF-IA-004; BMAD 03 | Agenda IA entregue aqui, sem mandato a vencer; reuso sem segunda engine | Corrigido | Lentes adversarial/estrutura; focal adversarial |
| G-07 MINOR | 12.7 AC-01 | Gestão nova indistinta de 4 | RF-AUTOMACOES-016; macro 12 | Catálogo integrado por capacidade/dono/produtor, mecanismos de 4 reutilizados | Corrigido | Lente estrutura, conferência focal |
| G-08 MINOR | Matriz 11 | Clássico/substituição/fórmulas ligados só a 11.1 | RF-RELATORIOS-001/002/003; RF-AUTOMACOES-013 | Composição com páginas e gate de paridade 15.6/15.7 | Corrigido | Idem |
| G-09 MINOR | 6–15 rastreabilidade | RNF/RB/CA/UX só genéricos por Epic | PRD §11; BMAD 03 | Associação compacta por Story no detalhe, sem copiar requisitos | Corrigido | Idem |
| G-10 MINOR | 6–15 Markdown | AC/metadados consecutivos viravam um parágrafo | BMAD 03; lente estrutura | Linhas em branco; linhas não vazias preservadas por asserção | Corrigido | Idem; parser/UTF-8/whitespace |

**Integração e grafo:** tabela macro auditada: **44 capacidades / 83 arestas / zero ciclos / zero nós ausentes**, com separação entre início, aceite composto, incremento futuro e produção. 2.5 + 4.2 → 4.8 permanece sem bloqueio retroativo; eventos de 5 são duráveis mesmo sem consumidores. Corrigidas barreiras manuais em 6/7, exportação 11.5 e cargas/paridade/cutover 15 por escopo. Catálogos 12.3/12.4 são incrementos independentes e RFs compostos só se completam com 12.5/12.6, não por indisponibilidade simulada. 8 fornece Drive/PDF a RSG; 10 usa fonte oficial/atendimento antes de 11; 3 + destino de domínio + recuperação 14 precedem apenas a carga afetada de 15. As referências de 11 à paridade de 15 são gates de aceite histórico, não dependências para implementar consultas com dados novos. Sem dependência obrigatória de Story posterior dentro de um Epic identificada após ajustes.

**Dimensionamento:** incrementos reutilizam fundação/consultas/transporte e materializam somente entidades necessárias. 11.1 limita-se ao painel de atas e consultas existentes; 12.7 integra gestão sem refazer 4; 15.5 usa adaptadores estreitos de 15.1 e permite mandatos sem Drive. Formatos reais, D1/D2/D3/D7 e contratos externos podem exigir nova divisão antes de build; não prometer uma sessão nem autorizar ampliar escopo silenciosamente. Preservados os 101 IDs nesta auditoria, sem Story artificial por tabela/campo.

**Conferência multidisciplinar BMAD:** skill real `bmad-party-mode` carregada, customização/config/roster resolvidos; modo `session`, sem memória/artefato paralelo. Analyst/PM/UX/Architect/Developer/QA/Segurança foram perspectivas analíticas, não sete especialistas autônomos. As revisões independentes reais são exclusivamente as sessões listadas. A colisão principal foi valor incremental versus aceite integral: manter manual útil e catálogo parcial é válido, mas não conclui RF composto; proteção mínima de PII/versão/idempotência nasce na primeira persistência, não numa Story futura. Parecer humano não foi substituído por consenso de personas.

**Compatibilidade BMAD e tecnológica:** consumidores instalados consultados por leitura, sem executá-los: criador/passos 03/04, readiness-only/sprint, build/cache, code-review e correct-course. Overrides usam `persistent_facts`; o pacote segue monolítico e não foi modificado. Fallback do resolver não é sandbox: AGENTS/§4.1 continuam obrigatórios; índice é contexto, detalhes são entradas explícitas, cache precisa invalidar mudanças em subdiretórios, parser de sprint não conhece aprovações/gates e review exige base `origin/dev`, untracked e estado `review` até merge. Nada disso autoriza gerar tracking agora.

Baseline física conferida: Laravel **13.38.0**, PHP **8.5**, PostgreSQL **17** (snapshot 17.11), Boost dev **2.10.3**, fila database com `after_commit`, três migrations e dez tabelas; login/logout/identidade existentes não reconstruídos. Documentação oficial [Laravel 13 queues](https://laravel.com/docs/13.x/queues), transações/casts e [constraints PostgreSQL 17](https://www.postgresql.org/docs/17/ddl-constraints.html) sustenta viabilidade, não prova runtime. Locks/ShouldBeUnique/after_commit não substituem constraint/efeito único/outbox durável; cifra usa TEXT/cast, dinheiro decimal sem float e instantes UTC com exibição Bahia. Policies por domínio, auditoria e integrações continuam futuras.

**Limitação Boost/runtime comprovada:** descoberta MCP retornou zero recursos/templates; Docker ausente no PowerShell, mas disponível no Debian/WSL já em execução. `docker ps` confirmou containers dev existentes; inspeção somente dos mounts mostrou `quarentena_dev-app-1` ainda ligado ao checkout antigo `/home/cirinho/repositorios/grupoquarentena/site`, não ao checkout escolhido. Nem esse diretório antigo nem `vendor/autoload.php` no checkout atual estavam presentes na verificação. Portanto não se executou handshake/consulta Boost do projeto atual, Artisan, teste Laravel/PostgreSQL ou integração. Não iniciar/recriar serviços, instalar dependências ou corrigir mounts por inferência; homologar o runtime antes da implementação. Esta limitação não foi convertida em falsa evidência de compatibilidade operacional.

**Gates humanos consolidados, sem aprová-los:** (1) D1 prova independente/identidade da instalação/restore e bootstrap; (2) D2 fronteiras civis/duração/vigência; (3) D3 entrega/recuperação de credenciais pessoais e eventual ajuste de escopo; (4) D4 acumulação/titularidade/substituição/aptidão e janela da dupla; (5) D5 autoridade confirmadora e regra de destituição manual/automática; (6) D6 capacidades delegáveis/escopo/autoridade/ciclo; (7) D7 custódia/MFA/recuperação/TTL/inatividade/revogação root; (8) continuidade administrativa sem rearmar bootstrap; (9) E2-K original misto/autoridade/percurso e PII do texto livre da ata, com harmonização das fontes antes da parte afetada; (10) política por classe/finalidade/cifra/chaves/retensão/expurgo/backups/restore, preservando default Zoom 365 dias; (11) editorial por classe, nomes de serviço/resumos/opt-out/liberação histórica e distinção senha pública/anfitrião/!senha; (12) agenda/ocorrências/canais/destinos/identidade de comandos/frequência e Telegram ativo ou não; (13) categorias/abertura/elegibilidade/compensações ASAAS/fórmulas/divulgação financeira e operação Kit/Correios/Drive; (14) fontes/direitos/termos/modelos/limites/transferência e captura IA por finalidade; (15) fontes/formato/handoff/autorizações/tolerâncias/paridade/editorial/aceite de cada rotina/lote/cutover; (16) recuperação, destino/custódia, metas após baseline, treinamento, fork, domínio/Tunnel/#30 e autorizações específicas de infraestrutura/produção. Resolver somente a parcela pertinente antes de implementar/usar dados reais/ativar conforme o gate original; homologação técnica não é decisão institucional.

**Conflitos e decisões institucionais ainda abertos:** cardinalidade CREDENCIAL versus pessoa única; senha pública versus credencial administrativa; audiência/autorização dos nomes mínimos, opt-out e resumos; retenção por classe versus textos genéricos; unidade civil versus `duracao_dias` proposto. **Esclarecimentos editoriais nesta conferência:** a Arquitetura não transforma as 63 entidades candidatas em quantidade obrigatória de migrations; a proibição de publicar lista/identidade Zoom ou PII não elimina a publicação excepcional de primeiro nome/apelido permitida pelo PRD sob política editorial e opt-out. Permanecem pendentes as escolhas operacionais e a harmonização material que dependem de decisão humana; PRD/Brief e decisões explícitas prevalecem. Não alterar requisitos aprovados para esconder conflito.

**Proteção do trabalho:** branch `bmad/issue-35-epics-stories-sprint`, HEAD `4ad7814bca49b89ffbb39f19b60c6c389f68ccd8`; índice externo inicial SHA-256 `723c353dbf64230e3f3ff437391683b8cbe467bb95ee206e3ae2de4792cd5b32` confirmado. Baseline/hashes fora do Git em `C:\Users\cirin\AppData\Local\Temp\opencode\quarentena-audit-20261023`; alterações locais anteriores preservadas. Arquivos desta rodada: índice, detalhes 6–15, validador/testes e CHANGELOG; detalhes 1–5 e quatro normativos não alterados. Hash final do próprio índice é entregue na resposta, não autorreferenciado neste conteúdo. Parar para aprovação humana dos Epics 6–15; sem autorização de build.

- **Estado/ownership único:** o frontmatter deste índice é o estado global (passo, revisão macro, Stories aprovadas como planejamento); cada `epic-NN.md` é fonte autoritativa exclusiva de suas Stories, ACs, rastreabilidade e gates. Evitar duplicação de Story no índice e atualização manual de contagens sem revalidação.
- **Compatibilidade:** seguir o [contrato central B](../../docs/git-workflow-bmad.md#backlog-b) e a entrada local `quarentena-backlog`. BMAD 6.12.4 conserva passos distribuídos monolíticos; fatos persistentes não os substituem. **Não executar novamente o criador completo em modo que regenere/sobrescreva o índice.** Overrides oficiais + adaptador local são camadas distintas, versionadas; nenhuma altera o pacote distribuído.
- **Novo Epic:** selecionar o Epic macro aprovado e os RFs do mapa, criar apenas `epics/epic-NN.md`, elaborar e revisar Stories ali, aprovar por lote com checkpoint humano, atualizar no índice somente estado, link e contagem; não inventar arquivos vazios para 6–15.
- **Links:** do índice para `epics/epic-NN.md`; do arquivo de Epic para `../epics.md`, `../PRD.md`, `../ProductBrief.md`, `../Arquitetura.md` e `../UX-Design.md`. Âncoras `story-N-M` são estáveis. Todo link para Epic não elaborado deve ser texto sem hyperlink, para evitar 404.
- **Validação por lote:** comparar inventário 236 RF/22 famílias com PRD, macro 15 Epics e ownership único, contagem das Stories/AC, vínculo RF → Story → AC, conteúdo/hashes antes/depois, ausência de duplicações de IDs, grafos e referências relativas. Validar UTF-8, tabelas/fences e `git diff --check`; não confundir cobertura de planejamento com implementação.
- **Migração/reversão:** a versão fonte pré-divisão recebida tinha SHA-256 `5333c311c3c8f0a664b3fa050f9d707a0672d47690ba0277bd18dbd9d49f2c56`. Preservar cópia fora da árvore versionada até confirmar extração em diff. Reversão requer recomposição controlada a partir da baseline, sem descartar mudanças legítimas posteriores. Nenhuma implementação/PR/sprint foi criada por esta operação.
- **Homologação documental B no OpenCode — 23/10/2026: CONCERNS.** Instalação 6.12.4 inspecionada, seis overrides resolvidos e skill local `quarentena-backlog` reconhecida/carregada. Conferidos 15 Epics/236 RF/22 famílias, 39 Stories/235 AC/103 RF principais; YAML e corpos das Stories preservados em relação à baseline segmentada desta execução. A cópia monolítica final confiável não foi localizada: não se certifica equivalência integral pré-divisão. Três sessões reais somente leitura: integridade/produto `ses_e94cd38dcffekHR4fe9afakAUc`, consumidores/arquitetura `ses_e94cd148effejdnZqkKgUu4kzZ` e diff final/QA `ses_e94c3c8a0ffenSCuiib7XmUTcB`, com correções focais revalidadas; Party Mode `session` foi conferência de perspectivas simuladas, não sete agentes. Passaram 21 regressões documentais, incluindo descoberta, contexto cruzado, writer sintético isolado, parsing nativo puro e repetibilidade; sem geração de sprint/build ou prova E2E generativa. Pacote distribuído intacto; passos monolíticos nativos não foram substituídos. Continuidade local não tem bloqueio técnico residual identificado, mas exige pedido específico, leitura desta governança, gates próprios e checkpoint humano; Epic 6 real não iniciado, passo 03 mantido.
