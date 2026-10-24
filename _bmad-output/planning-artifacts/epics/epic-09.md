## Epic 9: Conferir a presença sugerida pelo Zoom sem vigiar pessoas
> **Estado vigente — APROVADO COMO PLANEJAMENTO CONDICIONADO em 24/10/2026.** Aceite humano expresso após auditoria/revalidação. Stories e AC abaixo preservados; expressões de proposta/revisão/aceite pendente são proveniência histórica. Gates institucionais e operacionais continuam abertos; sem autorização de build, dados reais, sprint ou deploy. Conclusão documental não encerra formalmente os passos 03/04 nem comprova readiness.

> **Fontes canônicas:** [índice global](../epics.md) · [PRD](../PRD.md) · [Product Brief](../ProductBrief.md) · [Arquitetura](../Arquitetura.md) · [UX Design](../UX-Design.md). **Detalhamento exclusivo deste Epic:** Stories, critérios de aceite, gates e matriz RF → Story → AC.

## Escopo aprovado no passo 02
**Objetivo:** servidor autorizado recebe sugestão de total e consulta temporariamente possíveis duplicações, mantendo a ata utilizável sem Zoom.

**Resultado verificável:** webhook validado/deduplicado correlaciona ocorrência, guarda mínimos cifrados, apresenta lista somente ao autorizado, permite total manual e expurga pelo prazo configurado; falha não se transforma em zero validado nem cria pessoas/contas.

**RFs responsáveis (13):** RF-ZOOM-001–011; RF-ATA-006; RF-RELATORIOS-014.

**Dependências — início:** ocorrência local em 2, ata manual funcional em 5 e Policies/trilha necessários de 1. **Por capacidade:** recepção/conferência requer contrato atual Zoom, autenticação/deduplicação, armazenamento cifrado e retenção/expurgo; não depende de mensageria/editor do Epic 4. **Incremental:** adaptador de sugestão para RF-ATA-002 coordenado por 5; ausência de eventos nunca impede o fluxo manual. **Produção:** parâmetros 03/12, finalidade/acesso por campo, default 365 dias preservado e política homologada; teste de indisponibilidade e expurgo efetivo.

**Limites:** completa pré-preenchimento assistido aproveitando ata manual já funcional; não reimplementa formulário/salvamento. Não criar controle de frequência individual nem vínculo Zoom→pessoa. Conferência e relatório nominal próprios não esperam indicadores globais.
## Contratos de elaboração e revisão
- **Fonte e estado:** o PRD rege texto integral dos RF; o índice rege a titularidade do Epic e os contratos compartilhados. Uma referência de RF não comprova sua implementação nem aceite ponta a ponta.
- **Dependências por capacidade:** cada Story usa somente baseline ou produtores anteriores mencionados. Referências externas para integração, dados reais ou consumidores posteriores são *gates de aceite/ativação*, não autorização para antecipar tabelas, criar rotas falsas ou bloquear uma capacidade manual já utilizável.
- **Segurança e governança:** capacidades pelo mandato vigente/Policy no backend e na tela; exceção root somente por sessão pessoal elevada, justificada, temporal e auditada. Evento/alteração/auditoria e intenção outbox dentro da mesma transação quando aplicáveis, nunca chamadas HTTP externas antes do commit.
- **Dados e precisão:** preservar NULL distinto de zero; datas civis em `America/Bahia`, instantes em UTC; valores monetários decimais; filtros e exports usam mesma Policy. Não inferir pessoas a partir do Zoom nem mesclar homônimos.
- **Validação posterior:** testes com fixtures sintéticas, cenários positivos/negativos, sessão dormente, falhas, repetição, concorrência, PostgreSQL real, classificação de dados, mobile/teclado/leitor e comportamento degradado pertinentes. Contrato técnico de provedor exige homologação real, não apenas mock.
- **Limite da rodada:** não executar fluxo instalado, migrations, testes Laravel, comandos de importação, integração real, sprint, commit ou deploy. A revisão independente e correção adversarial ficam para o OpenCode no checkpoint humano.

## Stories propostas

<a id="story-9-1"></a>
### Story 9.1: Autenticar webhook Zoom e reconhecer eventos por ocorrência

**Como** responsável técnico autorizado,

**quero** receber eventos Zoom verificáveis e correlacionáveis,

**para** fornecer evidência temporária sem aceitar origem falsa.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 2.2 ocorrência; contrato Zoom oficial vigente. **RF principais:** `RF-ZOOM-001`, `RF-ZOOM-002`, `RF-ZOOM-003`, `RF-ZOOM-004`, `RF-ZOOM-010`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não habilitar inferência de pessoa pelo evento nem tornar webhook dependência do formulário de ata.

**Acceptance Criteria:**

**AC-01.** **Given** contrato Zoom vigente verificado e evento `meeting.participant_joined` autenticado; **When** receber requisição; **Then** validar assinatura sobre os bytes recebidos, timestamp e segredo homologados, persistir inbox durável antes do ACK e deduplicar por identidade tecnicamente comprovada, não por presumir `event_id` universal. Na ausência de ID de evento confiável, homologar chave determinística minimizada que distinga evento/reentrada legítimos e deduplique retries concorrentes. Correlacionar conta/reunião/UUID da execução e intervalo à ocorrência local estável; mesmo ID recorrente com UUIDs/ocorrências distintos não se mistura. ACK não espera conferência humana nem cálculo pesado; queda antes do enqueue é retomada pela inbox.

**AC-02.** **Given** evento falso, replay ou payload fora do contrato vigente; **When** enviar requisição; **Then** rejeitar sem criar identidade, presença validada ou atualizar ata.

**AC-03.** **Given** evento válido sem ocorrência identificável ou com horário ambíguo; **When** correlacionar; **Then** reter em triagem mínima restrita ou descartar conforme política, sem atribuir reunião aleatória. Desde esta primeira persistência, inclusive envelope/triagem/falhas, minimizar campos, cifrar nome necessário separado de pessoas/usuarios, atribuir expiração default 365 dias configurável CG/SG, negar leitura vencida e aplicar descarte mínimo/controle de replay que não ressuscite PII. Política/chave/cifra ausentes impedem coleta real. 9.2 amplia sugestões e 9.5 a gestão/ensaio de expurgo; não inauguram proteção de dados já coletados.

**AC-04.** **Given** validação inicial/periódica do endpoint ou alteração da documentação Zoom; **When** homologar adaptador; **Then** implementar/testar challenge-response `endpoint.url_validation` e autenticidade/replay conforme contrato vigente; verificar campos efetivamente disponíveis/necessários de RF-ZOOM-004, eventos úteis, ACK/limites/retries e chave/correlação antes de habilitar. A pesquisa não é homologação da conta real; não inventar campo externo obrigatório nem usar apenas IP como autenticação.

**Evidência futura e gate humano:** Parâmetros 03/12 e contrato externo Zoom reais antes de webhook produtivo. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-9-2"></a>
### Story 9.2: Guardar identificadores temporários cifrados e sugerir duplicações

**Como** gestor da conferência Zoom autorizado,

**quero** examinar amostras temporárias minimizadas,

**para** corrigir duplicações sem estabelecer identidade permanente.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 9.1. **RF principais:** `RF-ZOOM-005`, `RF-ZOOM-011`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Nenhuma ligação automática com cadastro institucional; dados Zoom são temporários.

**Acceptance Criteria:**

**AC-01.** **Given** participantes com nome exibido em evento validado; **When** guardar dado necessário à conferência; **Then** cifrar nome temporário separado de pessoas/usuarios, registrar origem/instante e prazo padrão 365 dias configurável CG/SG.

**AC-02.** **Given** nomes semelhantes, reentrada ou eventos sobrepostos; **When** calcular sugestão de duplicidade; **Then** apresentar hipótese revisável, sem fusão de pessoa nem prova de presença individual.

**AC-03.** **Given** solicitante não autorizado ou sistema de logs comuns; **When** tentar ler/exportar nome/evento; **Then** rejeitar e evitar identificáveis em logs, cache público e analytics.

**AC-04.** **Given** prazo de retenção não homologado, chave ausente ou cifragem falha; **When** iniciar coleta real; **Then** bloquear a coleta afetada e sinalizar gate, sem gravar nome em claro.

**Evidência futura e gate humano:** Prazo default 365 é referência do RF, sujeito à política de finalidade e expurgo real. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-9-3"></a>
### Story 9.3: Sugerir e permitir corrigir total Zoom na ata

**Como** moderador autenticado e escalado,

**quero** comparar sugestão de presença com conferência manual,

**para** registrar total correto mesmo quando Zoom estiver fora.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 9.1–9.2; formulário e versão da ata em 5. **RF principais:** `RF-ZOOM-006`, `RF-ZOOM-007`, `RF-ATA-006`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Adaptador enriquece 5; não recriar tela, rascunho ou envio oficial da ata.

**Acceptance Criteria:**

**AC-01.** **Given** eventos válidos correlacionados à ocorrência; **When** moderador escalado abrir conferência de contagem; **Then** mostrar total sugerido e sinalização não identificável de possível duplicação, sem nomes, IDs reversíveis ou dados nominais ocultos no HTML/JSON. Somente Policy nominal específica permite evidência identificável pelo fluxo autorizado, nunca a mera escala; sugestão não prova presença individual definitiva.

**AC-02.** **Given** moderador confirma total diferente; **When** editar sugestão e salvar ata; **Then** persistir total manual com justificativa mínima não nominal, autoria/versão e não sobrescrevê-lo por webhook posterior; ata oficial continua sujeita à autorização de retificação de 5.7, não à mera escala.

**AC-03.** **Given** Zoom indisponível, sem evento ou correlação incerta; **When** abrir ata; **Then** indicar dado desconhecido/indisponível e permitir total manual, nunca atribuir zero validado por ausência.

**AC-04.** **Given** webhook atrasado após envio oficial da ata; **When** chegar evento; **Then** não alterar ata oficial automaticamente; revisão requer regra existente de correção e motivo.

**Evidência futura e gate humano:** Teste real com eventos anonimizados e falhas antes de afirmar integração homologada. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-9-4"></a>
### Story 9.4: Restringir lista nominal e relatório de conferência Zoom

**Como** CG/SG vigente ou usuário com permissão específica,

**quero** consultar lista temporária de participantes para conferência,

**para** impedir uso público ou vigilância de presença.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 9.2–9.3; Policies de 1. **RF principais:** `RF-ZOOM-008`, `RF-RELATORIOS-014`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não criar relatório de frequência pessoal; o Epic 11 não recebe nomes brutos Zoom.

**Acceptance Criteria:**

**AC-01.** **Given** usuário explicitamente autorizado e ocorrência no escopo; **When** abrir lista temporária; **Then** exibir somente nomes temporários necessários, eventos e sugestões de duplicação com leitura sensível auditada/fail-closed e indicação da retenção vigente (inicial 365 dias, configurável CG/SG).

**AC-02.** **Given** visitante ou moderador sem permissão específica; **When** abrir listagem, exportação, endpoint ou cache; **Then** negar lista nominal, incluindo filtros que revelem indivíduos.

**AC-03.** **Given** consulta apenas de contagem autorizada; **When** gerar visão agregada; **Then** fornecer total sem lista de nomes e sem permitir inferir identidade por IDs de eventos.

**AC-04.** **Given** retenção expirada ou evento invalidado; **When** reabrir relatório; **Then** ocultar dados expurgados e distinguir inexistência de dado de zero de participantes.

**Evidência futura e gate humano:** Matriz de permissão/finalidade por campo e retenção aprovadas. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-9-5"></a>
### Story 9.5: Expurgar eventos temporários e impedir vigilância individual

**Como** CG/SG responsável pela política de dados,

**quero** aplicar prazo autorizado e auditar descarte,

**para** limitar permanência de nomes e impedir reidentificação.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 9.2–9.4. **RF principais:** `RF-ZOOM-009`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Expurgo real deve incluir backups/projeções na política por classe, sem apagar trilha lícita obrigatória.

**Acceptance Criteria:**

**AC-01.** **Given** evento identificável alcança prazo configurado; **When** job de expurgo executar; **Then** eliminar os identificadores conforme política, preservar apenas contagem agregada autorizada e evidência mínima não identificável.

**AC-02.** **Given** job atrasado ou desativado; **When** consultar dado com prazo vencido; **Then** negação por data efetiva não depende do job; não permitir leitura de nome expirado.

**AC-03.** **Given** replay de webhook antigo, restore de backup ou nova importação; **When** tentar ressuscitar evento expurgado; **Then** aplicar marcador/controle de retenção e impedir reintrodução indevida de PII.

**AC-04.** **Given** usuário tenta relatório longitudinal de indivíduos; **When** pedir frequência por nome/conta; **Then** negar por concepção de dados/Policy; indicadores só agregados.

**Evidência futura e gate humano:** Política 03/12, método de descarte/restore e revisão LGPD antes de operar dados reais. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

### Rastreabilidade proposta do Epic 9 — RF → Story → AC
Os IDs e a titularidade principal são os do mapa global aprovado. ACs indicam contratos **propostos**, não testes executados; RFs compostos dependem das integrações/gates explicitados no escopo.

| RF principal | Story e critérios propostos | Objeto de cobertura | Situação |
|---|---|---|---|
| RF-ZOOM-001 | 9.1 AC-01–04; [Story 9.1](#story-9-1) | Receber webhook Zoom | Proposto; execução/aceite condicionados ao RF integral |
| RF-ZOOM-002 | 9.1 AC-01–04; [Story 9.1](#story-9-1) | Validar webhook | Proposto; execução/aceite condicionados ao RF integral |
| RF-ZOOM-003 | 9.1 AC-01–04; [Story 9.1](#story-9-1) | Evento participant_joined | Proposto; execução/aceite condicionados ao RF integral |
| RF-ZOOM-004 | 9.1 AC-01–04; [Story 9.1](#story-9-1) | Campos úteis | Proposto; execução/aceite condicionados ao RF integral |
| RF-ZOOM-010 | 9.1 AC-01–04; [Story 9.1](#story-9-1) | Pesquisa técnica futura | Proposto; execução/aceite condicionados ao RF integral |
| RF-ZOOM-005 | 9.2 AC-01–04; [Story 9.2](#story-9-2) | Salvar eventos | Proposto; execução/aceite condicionados ao RF integral |
| RF-ZOOM-011 | 9.1 AC-03; 9.2 AC-01–04; 9.3 AC-01/02; 9.4 AC-01; 9.5 AC-01–04; [Story 9.2](#story-9-2) | Cifra/retensão, duplicação sugerida, conferência, total editável e expurgo | Composto; nenhuma etapa isolada conclui o ciclo de retenção |
| RF-ZOOM-006 | 9.3 AC-01–04; [Story 9.3](#story-9-3) | Sugerir presença total | Proposto; execução/aceite condicionados ao RF integral |
| RF-ZOOM-007 | 9.3 AC-01–04; [Story 9.3](#story-9-3) | Permitir correção manual | Proposto; execução/aceite condicionados ao RF integral |
| RF-ATA-006 | 9.3 AC-01–04; [Story 9.3](#story-9-3) | Presença sugerida pelo Zoom | Proposto; execução/aceite condicionados ao RF integral |
| RF-ZOOM-008 | 9.4 AC-01–04; [Story 9.4](#story-9-4) | Restringir lista nominal | Proposto; execução/aceite condicionados ao RF integral |
| RF-RELATORIOS-014 | 9.4 AC-01–04; [Story 9.4](#story-9-4) | Página Participantes Zoom | Proposto; execução/aceite condicionados ao RF integral |
| RF-ZOOM-009 | 9.5 AC-01–04; [Story 9.5](#story-9-5) | Não vigilância individual | Proposto; execução/aceite condicionados ao RF integral |

## Dependências, gates e aceite do Epic
- **Início e ordem:** implementar somente Stories autorizadas, precedidas pelos produtores reais necessários; a ordem editorial não substitui a verificação de capacidade já entregue. Qualquer dependência de componente futuro deve ser sinalizada e o consumidor adiado, sem simular funcionalidade.
- **Decisões humanas:** permanecem abertas as do índice e da cláusula de cada Story. Ausência de decisão não pode virar default normativo silencioso. Segurança, finalidade, canais, fornecedores, documentação e política de dados por classe antecedem a habilitação afetada.
- **Critério de encerramento:** revisar **todos** os RF principais acima, seus negativos e os contratos RNF/RB/CA/UX pertinentes no texto integral do PRD/UX. Não concluir Epic enquanto faltar funcionalidade composta exigida, homologação externa ou gate explicitamente aplicável.
- **Ponto de parada BMAD:** aguardar revisão independente individual/global, conciliação multidisciplinar e aceite humano do lote. Não adicionar ID a `approvedStoryIds` nem avançar `stepsCompleted` nesta entrega.

**Pesquisa técnica pontual em 23/10/2026:** [documentação oficial de webhooks Zoom](https://developers.zoom.us/docs/api/webhooks/) descreve `event`, `event_ts`, `payload`, assinatura `x-zm-signature`/timestamp, CRC e UUID de reunião nos exemplos; não garante `event_id` universal. Esse levantamento corrige a premissa do AC, sem homologar payload/scopes/credenciais da conta institucional. Confirmar novamente contrato e corpo bruto da assinatura, janela anti-replay compatível com retries legítimos e critérios de dedup/ocorrência na implementação autorizada.

### Auditoria documental integral — 23–24/10/2026

Revisão/revalidação focal `ses_e949c70b4ffeP3CPTRSZfPhilp`: **5 Stories / 20 AC / 13 RF principais**, cada AC-01–04 analisado individualmente; sem residual dos cinco achados. CL-01–05 aplicáveis; coordenador único editor. Contrato atual pesquisado não equivale a integração homologada; resultado global no índice.

| ID / severidade | Epic/Story/AC | Evidência original | Fonte normativa | Correção persistida | Resultado | Revalidação |
|---|---|---|---|---|---|---|
| E9-01 MAJOR | 9.1 AC-01/04 | event_id universal presumido; ocorrência genérica | RF-ZOOM-002/004/010 | Chave comprovada, CRC/assinatura e correlação UUID/ocorrência | Corrigido | Sessão acima; sem residual |
| E9-02 MAJOR | 9.1 AC-03 | PII persistia antes dos controles de Stories futuras | RF-ZOOM-004/011; BMAD 03 | Cifra/TTL/expurgo/replay mínimos desde a primeira gravação | Corrigido | Idem |
| E9-03 MAJOR | 9.3 AC-01 | Evidência de duplicação podia revelar nomes à dupla | RF-ZOOM-008; RF-RELATORIOS-014 | Sinalização não identificável; nominal exige Policy específica | Corrigido | Idem |
| E9-04 MINOR | 9.3 AC-02 | Ajuste sem motivo explícito | Arquitetura §8.4 | Justificativa mínima não nominal e regra de retificação oficial | Corrigido | Idem |
| E9-05 MINOR | 9.4 AC-01 | Retenção não visível na tela | UX complemento Zoom | Prazo vigente indicado no painel autorizado | Corrigido | Idem |

**Contratos por Story, AC-01–04:** 9.1 → RNF-01/03–05/08/09, RB-02/05/17, CA-03/34/35, UX-DR31/52; 9.2 → RNF-03–05/15/17, RB-02/05/10, CA-34, UX-DR31/52; 9.3 → RNF-03–06/09, RB-01/02/04, CA-03/34, UX-DR31; 9.4 → RNF-03–06/15/17, RB-02/10/17, CA-05/34, UX-DR31/52; 9.5 → RNF-03–05/09/15/17, RB-02/10, CA-34, UX-DR31/52. Matriz nominal, finalidade/retensão/expurgo/restore e contrato da conta continuam gates humanos/técnicos, sem Zoom→pessoa ou ranking.
