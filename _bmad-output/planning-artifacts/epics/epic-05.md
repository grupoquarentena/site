## Epic 5: Registrar, conferir e corrigir a ata regular pelo celular

> **Estado vigente — Epic 5: APROVADO COMO PLANEJAMENTO CONDICIONADO pelo responsável em 23/10/2026.** A aprovação do lote abrange a revisão documental das Stories e ACs, sem homologar as decisões institucionais pendentes, autorizar implementação, dados reais, integrações, deploy ou concluir o passo 03 global. As expressões anteriores “proposta”, “não aprovada”, “apreciação humana” e registros de sessões abaixo documentam o estado **histórico pré-aceite** e não revogam este checkpoint.

> **Fonte canônica global:** [índice de Epics](../epics.md), [PRD](../PRD.md), [Product Brief](../ProductBrief.md), [Arquitetura](../Arquitetura.md) e [UX Design](../UX-Design.md). **Detalhamento exclusivo:** Epic 5 — Stories, critérios e matriz RF → Story → AC.


**Objetivo e resultado verificável:** moderadores pessoalmente autenticados e escalados em uma ocorrência registram uma ata regular completa no celular, com campos históricos e nomes preservados, autossalvamento recuperável, conferência, envio oficial único, histórico, correção justificada e resumo publicável minimizado. A operação manual não depende de Zoom, IA, mensageria, dados do Kit ou migração do legado. **Estado:** nove Stories propostas, revisadas documentalmente nesta continuidade, **não aprovadas nem implementadas**. PRD RF-ATA, RNF-01–09/12–17, RB-01–05/07/10/13–17/20–21, CA-02/03/06/13/20/29/36 (parciais conforme consumidores), UX-DR16/20/25–33/39/49–53 e Arquitetura §§3/6.2–6.3/6.7–6.8. CL-01–05 aplicam-se onde pertinentes, sem substituir testes específicos.

**Fronteiras e gates:** login pessoal e capacidades de moderação/autoria do Epic 1, ocorrência e dupla da Story 2.2/2.3; o contrato público/editorial de 2.1/2.4 é consultado apenas quando necessário. O evento de envio oficial aciona **intenções duráveis**, não efeitos externos antes do commit. O transporte mínimo de 4.2, com integração efetiva do provedor, é pré-requisito apenas da Story 5.9, não da ata manual. Zoom (Epic 9) é fonte opcional de sugestão de total, não autoridade de presença; Kit (Epic 6), relatórios (Epic 11), publicação nominal (Epic 10) e cutover (Epic 15) são consumidores posteriores e não dependências para gravar uma ata. Antes de uso real: regras de publicação e dados pessoais por classe (parâmetros 03/05/06/08), capacidade de restore aplicável do Epic 14, autoridades de envio/correção e escala testadas; não exigir concluir todos esses Epics para desenvolver com fixtures sintéticas.

**Contrato transversal E5 — integridade e privacidade:** há **uma ata lógica por ocorrência** e suas versões, com revisão esperada e autorização atual na operação; rascunho não afeta indicadores, não gera Kit, resumo ou envio. Envio oficial/correção/anulação gravam, na mesma transação PostgreSQL, revisão de negócio + auditoria redigida + evento/intents/outbox mínimos duráveis, inclusive para consumidores futuros ou desabilitados, sem HTTP externo na transação. Habilitação/elegibilidade condicionam consumo e disparo, nunca a existência do evento da mutação; retenção/replay seguem política por classe. Eventos com `ata_id`, `ocorrencia_id`, `versao`, `tipo`, `evento_id` e `correlacao` não carregam contato/endereço/segredo Zoom ou conteúdo privado livre; consumidores usam unicidade do efeito e revalidam versão/autoridade. O sistema diferencia *desconhecido/nulo* de zero informado e não soma detalhe a total declarativo uma segunda vez. Nome exibido no relato da ocorrência é histórico imutável na respectiva versão; vínculo posterior a `pessoas` é opcional, autorizado e nunca fusão automática. Valores da 7ª Tradição/despesas informados na ata são **declarativos** e não lançam movimento financeiro. Dados públicos exigem classificação e liberação editorial próprias, não o mero estado `enviada`.

<a id="story-5-1"></a>

### Story 5.1: Abrir a ata de uma ocorrência para a dupla legitimamente escalada

**Como** moderador escalado para uma ocorrência de reunião,
**quero** abrir ou retomar a ata correta com meus dados de reunião e papel conhecidos,
**para** iniciar o registro sem duplicar atas ou precisar localizar a reunião por texto livre.

**Dependências:** 1.3/1.6 e 2.2/2.3 entregues nas capacidades efetivamente usadas; modelo inicial mínimo de ata/revisão. **RF principais:** RF-ATA-001/002/004/005 e contribuição a RF-ATA-003/028; RF-ATA-026 é responsabilidade principal de 2. **RNF:** 01/03–07/13/15; **RB:** 01/05/15/16; **CA:** 20/26 parciais; **UX:** DR5/16/25–26/49/51. **Fatia:** selector de ocorrência, identificação da dupla e criação idempotente de rascunho, sem formulário completo ou envio oficial.

**Acceptance Criteria:**

**AC-01.** **Given** moderador com conta e pessoa ativas, escala vigente na ocorrência existente e papel de fala ou chat; **When** escolher a ocorrência pelo calendário autorizado; **Then** mostrar data, horário local, fuso, tipo, dupla e papel derivados da agenda/escala, criar ou abrir a **mesma ata lógica**; **And** não confiar em `ocorrencia_id`, `usuario_id` ou papel enviado pelo navegador sem Policy/consulta vigente no servidor.

**AC-02.** **Given** dupla com papéis intercambiados ou substituição oficialmente registrada; **When** abrir o rascunho após a mudança; **Then** resolver a capacidade atual dos dois moderadores e sugerir responsável por chat/ata conforme escala, mantendo histórico de papéis; **And** não criar dois cargos permanentes ou duplicar ata por troca de papel.

**AC-03.** **Given** dois moderadores abrindo a mesma ocorrência simultaneamente ou visitante repetindo requisição; **When** criar rascunho; **Then** unicidade PostgreSQL por ocorrência e tratamento atômico de concorrência retornam uma ata existente/mesmo identificador; **And** ausência de autorização não revela rascunho de outra ocorrência.

**AC-04.** **Given** ocorrência inexistente, cancelada, encerrada fora de janela autorizada, pessoa/conta inativa ou escala expirada; **When** abrir ou editar; **Then** negar ou indicar indisponibilidade sem criar rascunho nem conceder acesso por sessão antiga; **And** critérios para registro tardio/contingência exigem regra institucional aprovada, sem conceder automaticamente root ou papel editorial. **Given** CG/SG vigente, conta/pessoa ativa e contingência legitimada pela regra homologada; **When** justificar criação ou apoio ao rascunho da ocorrência; **Then** permitir esse caminho administrativo com autoria pessoal, motivo e trilha, na mesma ata lógica, sem exigir participação na dupla nem root; **And** negar sem justificativa/condição institucional ou a outro membro da Mesa sem autoridade. A regra precisa explicitar também a competência de envio em contingência antes de habilitar esse uso real; não inferir identidade de moderador nem alterar escala.

**AC-05.** **Given** falha transacional, conflito ou acesso por outro servidor; **When** iniciar a ata pelo celular/teclado; **Then** mostrar apenas estado real, manter foco/semântica e possibilidade de retomar o rascunho existente; **And** registrar criação/autoria no evento de auditoria de negócio sem registrar dados privados ou exigir provedor externo.

**AC-06.** **Given** ocorrência com horário/tipo/dupla e referência institucional vigente da 7ª Tradição, e eventualmente sugestão de contagem Zoom disponível; **When** abrir a ata; **Then** pré-preencher os valores locais autorizados de ocorrência/data/horário/tipo/dupla e referência monetária declarativa, identificando a origem editável de cada sugestão; **And** ausência/falha de Zoom produz estado `indisponível`/`sem sugestão`, nunca zero inventado, não bloqueia abertura e mantém contagem humana editável. A integração efetiva de sugestões Zoom pertence ao Epic 9; este AC não afirma que o webhook/lista existe. Antes do envio, permitir corrigir valores históricos sugeridos de data/horário/tipo/referência na ata com origem/divergência e autoria registradas, sem alterar silenciosamente agenda, `ocorrencia_id` ou autoridade. Seleção equivocada de ocorrência exige revalidar a ocorrência correta e sua ata única; troca de dupla/servidor usa seleção autorizada e escala/substituição de 2.3, nunca texto/ID arbitrário. Testar correção legítima de cada grupo sugerido e negativa de forjar ocorrência/papel ou editar agenda sem permissão.

<a id="story-5-2"></a>

### Story 5.2: Preencher contagens e valores da reunião com autossalvamento recuperável

**Como** moderador autorizado à ata,
**quero** preencher totais e dados objetivos com salvamento confirmado,
**para** não perder registros e não confundir valores desconhecidos com zeros.

**Dependência:** 5.1. **RF:** RF-ATA-003/008/012/013/015/028, contribuição RF-ATA-007; **RNF:** 03–07/13–15; **RB:** 01/02/03; **CA:** 02/03/13 parciais; **UX:** DR13–16/26–29/49/51. **Fatia:** editor da ata e autossalvamento incremental apenas de dados objetivos já presentes, sem envio oficial.

**Acceptance Criteria:**

**AC-01.** **Given** rascunho autorizado; **When** informar presenças declaradas, partilhas, quantidades, 7ª Tradição, despesas e demais campos objetivos previstos no PRD; **Then** validar tipo, unidade, nulidade e quantidades inteiras não negativas ou dinheiro decimal quando aplicável; **And** não converter ausência em zero, valor inválido em sucesso ou quantia informativa em lançamento de Tesouraria.

**AC-02.** **Given** alteração de campo; **When** executar autossalvamento autenticado; **Then** persistir revisão esperada com autoria/trilha apropriadas e mostrar `Salvando`, `Salvo` somente após commit ou `Falha` com recuperação; **And** manter o servidor como persistência autoritativa, sem guardar dados de ata, nomes Zoom, contato ou endereço em localStorage ou outro armazenamento persistente do navegador. Cache temporário excepcional somente em memória, limitado e expurgado ao encerrar/perder a sessão; testar ausência de cópia persistente e limpeza após logout/revogação, inclusive em dispositivo compartilhado. Não depender de script externo ou estado efêmero como fonte autoritativa. Sem JavaScript, o formulário permite salvamento explícito equivalente.

**AC-03.** **Given** duas abas/dispositivos ou moderadores alterando a mesma revisão; **When** salvar versões concorrentes; **Then** detectar conflito de revisão sem sobrescrever silenciosamente a versão confirmada, mostrar campos divergentes e permitir revisão humana autorizada; **And** duplicar requisição de mesmo conteúdo/revisão não produz duas alterações inesperadas.

**AC-04.** **Given** interrupção de rede, expiração da sessão, token inválido, falha do banco ou da trilha; **When** salvar/retomar; **Then** manter o último estado comprovadamente persistido e distinguir edição ainda não gravada; **And** o servidor nega escrita desautorizada mesmo com cache/permissões antigas, sem alegar sucesso após falha.

**AC-05.** **Given** sugestão futura Zoom indisponível ou conflitante com total informado; **When** editar a presença; **Then** manter campo humano editável e fonte/estado da sugestão explícitos, sem importar lista nominal ou travar registro; **And** a integração real de RF-ATA-006 pertence ao Epic 9, não a esta Story.

**AC-06.** **Given** celular de largura reduzida ou formulário com campos inválidos; **When** preencher/retomar; **Then** labels, ajuda de unidade, erro associado, teclados apropriados e foco atendem CL-04; **And** a UI não declara salvamento só pela digitação e nunca transmite conteúdo privado a analytics/cache público.

<a id="story-5-3"></a>

### Story 5.3: Registrar visitas, ingressos e conquistas preservando a identidade histórica

**Como** moderador responsável pelo registro,
**quero** registrar visitas, ingressos e conquistas sem exigir conta para seus participantes,
**para** preservar os fatos relatados e permitir reconciliação cuidadosa no futuro.

**Dependências:** 5.2 e cadastro/contexto de pessoas do Epic 1 apenas quando disponível; criação de conta nunca é pré-requisito. **RF:** RF-ATA-013/016/017/019/020/027/028 e RF-IDENTIDADE-003; **RNF:** 03–06/13/15/17; **RB:** 03/05/14/20/21; **CA:** 07/29/36 parciais; **UX:** DR15/26–28/49/51–52. **Fatia:** listas estruturadas da ata e vínculo opcional confirmado; sem criar ficha de Kit ou usar dados de contato.

**Acceptance Criteria:**

**AC-01.** **Given** rascunho com total de visitas e ingressos; **When** incluir ingresso; **Then** exigir nome mínimo/apelido da ocorrência e refletir a relação lógica de ingresso associado a visita, sem impedir visita sem ingresso; **And** não duplicar contagem quando detalhes e totais declarados coexistirem; inconsistências geram aviso de conferência, não correção arbitrária.

**AC-02.** **Given** visita; **When** registrar quantidade, origem e observação autorizada; **Then** salvar valores estruturados e preservar texto histórico, inclusive origem desconhecida declarada como desconhecida; **And** contato/endereço/campo operacional Kit não aparece no formulário ou log comum.

**AC-03.** **Given** conquista; **When** informar quantidade, tempo limpo e nome/apelido quando houver; **Then** persistir valores válidos conforme requisito, preservando a declaração e sem fazer inferência de participação Zoom ou criar identidade automaticamente; **And** dado íntimo/nominal permanece restrito à finalidade, sem publicação por gravar a ata.

**AC-04.** **Given** nome semelhante ou homônimo cadastrado em `pessoas`; **When** sugerir vínculo opcional a ingresso/conquista; **Then** mostrar contexto mínimo autorizado, exigir seleção/confirmação humana explícita e guardar referência separada do nome originalmente informado; **And** não exigir `usuario`, não fundir pessoas e não reescrever versões históricas quando cadastro for alterado. Entregar projeção de busca contextual própria deste consumidor, autorizada pela competência atual sobre a ocorrência, com ID estável e nome/apelido/contexto mínimo permitido, sem contatos/notas/Kit nem acesso à gestão cadastral de 1.2. Testar moderador autorizado, pessoa sem login, homônimos, outra ocorrência e perda de competência; resultado ambíguo permanece sem vínculo, sem ampliar a Policy administrativa de pessoas. A mesma projeção atende 5.4 AC-01.

**AC-05.** **Given** edição/remoção de detalhe, reapresentação de formulário ou divergência com quantidade declarada; **When** salvar; **Then** aplicar versão esperada, autoria e coerência sem apagar histórico nem gerar evento Kit por rascunho; **And** resultado de contagem mostra separadamente total declarado e detalhes, sem somar ambos outra vez.

**AC-06.** **Given** visitante, conta sem escala ou consulta pública a endpoints de detalhe; **When** acessar entradas nominais; **Then** negar exposição/edição por Policy e impedir índice/cache/exportação pública; **And** eventual publicação de nome mínimo obedece regras e opt-out do Epic 10, não esta edição.

<a id="story-5-4"></a>

### Story 5.4: Completar temática, partilhador, campos históricos e observações

**Como** moderador da ocorrência,
**quero** concluir os campos contextuais e narrativos da reunião,
**para** substituir o formulário antigo sem perder informações importantes.

**Dependências:** 5.2/5.3; apenas campos existentes na ata, sem exigir Kit, Zoom, Tesouraria ou Drive. **RF:** RF-ATA-004/013/014/015/021/027/028, contribuição RF-ATA-003; **RNF:** 03–07/13–15; **RB:** 01/03/05/14/21; **CA:** 02/20/29 parciais; **UX:** DR15/26–29/49/51. **Fatia:** completar formulário histórico sem duplicar armazenamento de pessoa ou módulo alheio.

**Acceptance Criteria:**

**AC-01.** **Given** ata de ocorrência; **When** preencher temática e nome/apelido de partilhador; **Then** guardar valor histórico da ocorrência e permitir vínculo contextual opcional com `pessoas` mediante conferência de homônimos; **And** preservar original após correção cadastral sem exigir conta de login.

**AC-02.** **Given** modalidade de informação objetiva com opção `outro`; **When** escolher `outro`; **Then** exigir complemento quando a regra aprovada o pedir e registrar contexto sem aceitar enumerador arbitrário; **And** campos subjetivos utilizam texto livre controlado e protegido conforme finalidade.

**AC-03.** **Given** formulário completo do legado como referência; **When** revisar campos de presença, partilha, visitas/origem, ingresso/nome, conquista/tempo, tema/partilhador, 7ª Tradição/referência, despesas, adicionais, eleições e observações; **Then** assegurar representação de cada grupo de informação aplicável na ata e assinalar campos realmente ausentes; **And** sem preencher dados inexistentes com zero/nome fictício nem antecipar módulo financeiro/Kit. O legado é referência de paridade funcional, não permissão para copiar coluna Z/PII.

**AC-04.** **Given** alteração de papel fala/chat dentro da dupla autorizada ou troca de editor; **When** continuar preenchimento; **Then** manter autoria de cada alteração e sugestão atual de responsável pela ata, sem perder campos salvos; **And** uso de escala nunca concede leitura fora da ocorrência.

**AC-05.** **Given** textos com HTML/script, PII de Kit, telefone, endereço, links perigosos ou conteúdo incorreto; **When** editar/renderizar/consultar; **Then** tratar texto como não confiável e renderizar escapado, sem confundir escape/restrição da ata com autorização Kit; **And** não distribuir original por preview pública, toast, evento, log ou mensagem de resumo. Antes de habilitar texto livre com dados reais, homologar o tratamento de conteúdo indevido/misto e seus responsáveis no procedimento existente de finalidade/PII: contato/endereço/PII Kit não pode ser persistido nas versões comuns nem liberado à dupla, CG/SG ou exportadores por terem acesso à ata. O fluxo deve recusar o trecho indevido e permitir saneamento pelo próprio autor antes da gravação, sem reter o original em histórico/log/cache; eventual coleta necessária usa exclusivamente o fluxo privado Kit autorizado quando disponível. Aviso, autodeclaração, regex ou IA isolados não provam saneamento; ensaiar conteúdo misto, leitura posterior e falha da validação, mantendo esse uso real bloqueado enquanto tratamento/ensaio não forem homologados. Não criar papel de triador, acesso Kit ou dependência obrigatória do Epic 6 para a ata sintética.

**AC-06.** **Given** edição concorrente e conexão intermitente; **When** salvar/retomar esta seção; **Then** aplicar o mesmo mecanismo de revisão/persistência da 5.2, com confirmação do último commit real e UX acessível; **And** recusa de gravação não deixa estado declarado como salvo.

<a id="story-5-5"></a>

### Story 5.5: Conferir e enviar oficialmente uma ata com recibo único

**Como** moderador com competência de envio da ocorrência,
**quero** conferir a ata e efetivar seu envio oficial uma única vez,
**para** tornar o registro válido sem disparos acidentais ou perda de autoria.

**Dependências:** 5.1–5.4 e contrato de trilha/Policy do Epic 1; não depende de transporte de mensagens nem de consumidor Kit. **RF:** RF-ATA-001/003/007/009/011/012/028; contribuição RF-AUTOMACOES-020; **RNF:** 03–09/13/15; **RB:** 01/03/04/13/16; **CA:** 02/06/13/20 parciais; **UX:** DR18/20/26–30/49/51–53. **Fatia:** revisão/recibo e evento oficial atômico, sem envio externo.

**Acceptance Criteria:**

**AC-01.** **Given** rascunho salvo com versão conhecida; **When** abrir a etapa de conferência; **Then** mostrar todos os campos aplicáveis, total declarado versus detalhe, origem de sugestões, papel/remetente e avisos de incoerência; **And** não confirmar automaticamente ausência de dados, números incompatíveis nem declaração de autoria por outro operador.

**AC-02.** **Given** moderador legitimado, sessão/escala vigentes e confirmação explícita, ou CG/SG vigente legitimado para envio pela contingência justificada de 5.1 AC-04; **When** enviar oficialmente; **Then** revalidar transacionalmente todos os totais obrigatórios como inteiros não negativos e os demais campos exigidos, rejeitando total nulo/inválido sem inventar zero, e criar revisão oficial no estado `enviada`, remetente pessoal, instante UTC, recibo/ID e trilha transacional, desabilitando edição livre da versão enviada; **And** zero explicitamente informado é válido, campos realmente opcionais preservam sua nulidade e nenhum caso de autoria/total histórico desconhecido autoriza novo envio incompleto. Nenhuma mensagem/Kit/indicador nasce de mero salvamento de rascunho. Testar ausência de cada total obrigatório, valores negativos/fracionários e zero declarado.

**AC-03.** **Given** confirmação duplicada, duas pessoas enviando simultaneamente, requisição repetida após perda de resposta ou sessão com capacidade encerrada; **When** repetir o envio; **Then** transação/constraints/revisão esperada garantem único envio oficial/mesmo recibo ou conflito explícito sem dupla oficialização; **And** a expiração da escala bloqueia operações novas independentemente do scheduler/cache.

**AC-04.** **Given** saída oficial que exige futuros consumidores; **When** persistir ata/enviar; **Then** gravar na mesma transação evento institucional/outbox único por ata/versão/tipo com payload mínimo, correlação e política de leitura; **And** falha da trilha/outbox reverte oficialização e impede sucesso falso; chamadas externas e integração Kit/relatórios só após commit quando os respectivos consumidores existirem.

**AC-05.** **Given** falha do Zoom, mensageria, IA ou produtor de Kit ausente; **When** enviar ata manual completa; **Then** permitir conclusão oficial local com intents duráveis para consumidores futuros, sem forçar integração; **And** não declarar entrega de aviso/Kit/resumo nem liberar dados nominalmente por isso. Se política exigir um consumidor crítico antes do uso real, registre gate sem transformar serviço externo em pré-requisito de teste local.

**AC-06.** **Given** ata já oficialmente `enviada` e operador autorizado à conferência pela matriz remetente/CG/SG/root elevado quando disponível; **When** concluir conferência formal sem alterar o conteúdo oficial, com motivo e versão esperada; **Then** registrar transição `enviada → revisada`, autoria/instante/motivo e trilha atômica, preservando conteúdo e remetente originais, sem novo envio oficial ou efeitos Kit/mensagem; **And** negar `rascunho → revisada`, inclusive rascunho vazio ou ocorrência sem participação. Zero declarado segue as validações normais de envio, não é condição de revisão; alteração de conteúdo é `corrigida` pelo fluxo 5.7 quando disponível, não pré-requisito desta conferência.

**AC-07.** **Given** celular, perda de conexão e sessão expirada; **When** revisar/confirmar/retomar; **Then** mostrar confirmação ou falha real, ID/horário do recibo só após commit e possibilidade de consultar oficial anterior; **And** nenhuma confirmação falsa, submit oculto ou duplicate por duplo toque. Ensaio observado e WCAG pertencem à homologação futura.

<a id="story-5-6"></a>

### Story 5.6: Consultar e exportar o histórico autorizado das atas

**Como** servidor com competência de consulta,
**quero** localizar atas oficiais e imprimir/exportar somente os registros que posso consultar,
**para** conferir o histórico sem reabrir planilhas nem divulgar dados de outras finalidades.

**Dependência:** 5.5; usar filtros da ocorrência de 2.2 e controle de acesso do Epic 1. **RF:** RF-ATA-024/025, complementos RF-ATA-011/027; **RNF:** 03–06/13–15/17; **RB:** 01/05/10/20; **CA:** 05/13 parciais; **UX:** DR17/20/23/39/49/51–52. **Fatia:** listagem/detalhe/export por Policy, sem dashboards do Epic 11.

**Acceptance Criteria:**

**AC-01.** **Given** servidor autorizado; **When** consultar histórico por período, tipo, dia/horário, ocorrência, servidor e estados, e filtros de visitas/ingressos/conquistas/temática; **Then** retornar páginas ordenadas de atas acessíveis e seus metadados, preservando histórico mesmo após troca de mandato; **And** anônimo/outro encargo/ID manipulado não obtém detalhe indevido nem contorna filtro da Policy.

**AC-02.** **Given** rascunhos e atas oficiais; **When** compor indicadores/lista oficiais; **Then** manter rascunhos fora das métricas de produção e destacar estado e versão corrente, sem somar versões antigas à ata oficial; **And** total desconhecido não aparece como zero confirmado.

**AC-03.** **Given** exportação CSV ou impressão; **When** operador autorizado solicitar; **Then** aplicar ao resultado a mesma Policy e projeção autorizada da consulta, com fórmulas de planilha neutralizadas no CSV e controle contra vazamento por arquivo/cache/log; **And** CSV com encoding, datas/unidades e cabeçalho claros, sem contato Kit, identidade Zoom, segredo, dado íntimo desnecessário ou contribuinte pessoal.

**AC-04.** **Given** leitura alterada durante paginação/exportação ou versão corrigida; **When** gerar arquivo/impressão; **Then** identificar versão/filtros/instante de referência e não misturar dados de versões incompatíveis silenciosamente; **And** exportações sensíveis seguem autorização atual, trilha mínima de acesso/retentiva e política de descarte por classe.

**AC-05.** **Given** lista vazia, erro de consulta, celular ou teclado; **When** usar filtros e abrir detalhe; **Then** UI apresenta estados corretos, paginação acessível e orientação clara sem expor dados em URLs, analytics e mensagens de erro; **And** não criar relatórios públicos nominalmente liberados nesta Story.

<a id="story-5-7"></a>

### Story 5.7: Retificar ou anular ata enviada com versões e consequências rastreáveis

**Como** remetente do envio oficial, CG ou SG vigente — ou root pessoalmente elevado quando disponível,
**quero** corrigir ou anular uma ata já enviada mediante justificativa,
**para** preservar a verdade histórica e sinalizar a revisão aos consumidores sem apagar o passado.

**Dependências:** 5.5 e contrato institucional de correção do Epic 1; não exige Kit/relatórios existentes. **RF:** RF-ATA-010/011/024, contribuição RF-AUTOMACOES-020 e RF-IDENTIDADE-003; **RNF:** 03–09/13–15; **RB:** 01/04/05/13/20; **CA:** 06/36 parciais; **UX:** DR18/20/32–33/49/51–52. **Fatia:** versão nova/justificativa/efeitos pendentes, sem alterar remessas e dashboards de outros módulos.

**Acceptance Criteria:**

**AC-01.** **Given** ata oficialmente enviada e autor com permissão da matriz (remetente identificado, CG/SG vigente ou root elevado); **When** solicitar `corrigir` ou `anular`; **Then** exigir motivo e comparar versão atual, criar revisão com autor/instante/antes/depois redigidos e estado canônico `corrigida` ou `anulada`, preservando versões anteriores e autoria original; **And** não autorizar qualquer outro moderador por ter editado um rascunho anterior. Aplicar as transições de `enviada`/`revisada`/`corrigida` previstas no PRD/UX; conferência sem alteração de uma ata `corrigida` usa o contrato de 5.5 AC-06 e produz `corrigida → revisada`, sem nova retificação ou envio externo. Testar esse caminho e rejeitar uso de `revisada` para mascarar alteração material.

**AC-02.** **Given** conta/pessoa inativa, ID/autor manipulados, motivo ausente ou nenhuma fonte válida de autorização; **When** enviar correção por tela ou endpoint; **Then** negar sem alteração nem disclosure, inclusive com sessão/cache antigos; **And** avaliar separadamente remetente pessoal do envio, CG/SG vigente e root elevado válido: mandato expirado nega o ramo por encargo, e ausência de elevação nega o ramo root, sem retirar o direito próprio do remetente com conta/pessoa ativas. Testar remetente com moderação encerrada permitido, ex-CG/SG não remetente negado e outro ex-moderador negado; essa regra específica de RF-ATA-010 prevalece sobre exigir mandato/escala em CL-01. Status `revisada` não é sinônimo de correção.

**AC-03.** **Given** duas retificações concorrentes, retry duplicado ou falha da auditoria/outbox; **When** persistir; **Then** exigir versão esperada, garantir uma revisão consistente e registrar evento mínimo por versão/tipo atomicamente, revertendo tudo se falhar; **And** requisição duplicada com mesma chave/efeito não gera revisão nem aviso duplicado.

**AC-04.** **Given** efeitos de envio/Kit/resumo/publicação anteriormente solicitados; **When** corrigir ou anular; **Then** publicar intenção durável de **revisão humana** por consumidor atingido (inclusive Coordenador Kit quando ele existir), com versão/correlação, sem apagar remessa já expedida, sem contato/destino sensível e sem mutação cruzada direta; **And** ausência do consumidor mantém pendência verificável, não sucesso fictício ou necessidade de concluir todos os módulos antes de corrigir localmente.

**AC-05.** **Given** relatórios/versões derivadas existentes; **When** consultar após retificação; **Then** exibir revisão corrente autorizada, separar histórico oficial e eventos de compensação/reprocessamento ainda pendentes, nunca duplicar indicadores ou usar versão anulada como atual; **And** integração real com Kit/publicação/indicadores será ensaiada nos respectivos módulos.

**AC-06.** **Given** operador móvel/leitor e alteração material; **When** comparar e confirmar; **Then** mostrar diferenças legíveis, justificativa, efeitos esperados e recibo após commit conforme CL-04; **And** textos/nomes históricos da ocorrência não mudam por atualização de `pessoas` sem ação humana própria.

<a id="story-5-8"></a>

### Story 5.8: Gerar e conferir um resumo sanitizado a partir da ata oficial

**Como** responsável pela comunicação da reunião,
**quero** produzir um resumo autorizado da ata enviada para conferência,
**para** comunicar informações permitidas sem divulgar o registro integral ou dados privados.

**Dependências:** 5.5 e, para revisar versão corrigida, 5.7; não depende de transporte externo. **RF:** RF-ATA-022, RF-AUTOMACOES-002 (parte de geração), contribuição RF-ATA-011/023; **RNF:** 03–06/09/13/15/17; **RB:** 01/04/07/10/20; **CA:** 06/25 parciais; **UX:** DR18/20/28/30/33/49/51–52. **Fatia:** resumo/publicabilidade com modelo autorizado, prévia e origem/versão, sem envio real.

**Acceptance Criteria:**

**AC-01.** **Given** envio oficial confirmado e seu evento durável, com modelo de resumo homologado; **When** o consumidor de geração processar automaticamente esse evento após commit, sem depender de solicitação manual; **Then** compor resumo privado idempotente exclusivamente com campos de allowlist publicável, versão/ocorrência/fonte, mantendo ligação à revisão de origem e conteúdo passível de conferência; **And** rascunho jamais gera resumo comunicável. Testar geração inicial sem clique adicional, replay concorrente e retomada após falha sem duplicar; modelo ausente/não homologado deixa pendência verificável, sem reverter ata nem divulgar texto. Geração/regeneração solicitada de revisão válida também usa esse mecanismo; conferência/liberação e envio externo permanecem separados, e correção não autoriza reenvio automático.

**AC-02.** **Given** original com nome completo, telefone/endereço, coluna Z legada, contribuição identificável, dados Kit, identidade nominal Zoom, credencial/link secreto ou observação íntima; **When** compor prévia; **Then** excluir esses campos pela projeção explicitamente permitida, sem depender de regex ou IA como única barreira; **And** não incluir ingresso/conquista nominal sem decisão editorial/opt-out/liberação conforme Epic 10.

**AC-03.** **Given** alterações de template, correção ou anulação da ata após gerar resumo; **When** reabrir/publicar prévia; **Then** detectar origem obsoleta, não substituir silenciosamente texto histórico já enviado e bloquear envio de versão desatualizada até decisão autorizada; **And** não transformar erro de resumo em reversão do envio oficial da ata.

**AC-04.** **Given** dados de origem parcialmente desconhecidos ou tema livre não aprovado para divulgação; **When** gerar; **Then** omitir ou sinalizar os dados não publicáveis, permitindo revisão humana e bloqueando publicação sem classificação confirmada, sem inventar zero/nome/título; **And** ausência de IA/Zoom/Telegram não impede prévia local.

**AC-05.** **Given** geração concorrente/replay e falha no armazenamento/auditoria; **When** salvar resumo aprovado para a versão; **Then** usar chave única por ata/revisão/modelo/finalidade, gravar autor/instante/versão e impedir duplicatas sem anunciar sucesso falso; **And** texto completo fica em armazenamento privado apropriado, não no payload de outbox/log técnico.

**AC-06.** **Given** revisor sem direito de acesso ao conteúdo da ata ou tela móvel; **When** consultar prévia/modelo; **Then** exigir Policy no backend e exibir estados claro/erro/pendência acessíveis, sem exposição por link público ou export comum; **And** integração de mensagens da Story 5.9 é fluxo distinto.

<a id="story-5-9"></a>

### Story 5.9: Enviar ou reenviar explicitamente o resumo autorizado sem duplicação

**Como** responsável pela comunicação institucional autorizado,
**quero** encaminhar resumo aprovado a destino permitido e acompanhar seu resultado,
**para** avisar o grupo com idempotência sem transformar falha externa em perda da ata.

**Dependências:** 5.8 e **capacidade de transporte 4.2** aprovada/disponível, sem exigir editor/rotinas completos do Epic 4; 5.7 fornece eventos de correção. **RF:** RF-ATA-023, RF-AUTOMACOES-002/014/020; contribuição RF-ATA-022. **RNF:** 03–06/08–10/13/15/17; **RB:** 01/04/07/09/10/13/20/23; **CA:** 06/17/25/36 parciais; **UX:** DR18–19/30/32–33/43–44/49/51–52. **Fatia:** envio externo de resumo já autorizado, não engine nova nem implementação do bot de comandos.

**Acceptance Criteria:**

**AC-01.** **Given** ata oficialmente confirmada, resumo aprovado da versão vigente, transporte/destino e rotina habilitados; **When** solicitar envio permitido; **Then** gravar intenção idempotente com referência de resumo/versão/destino/efeito e trilha, deixando a chamada externa para worker posterior ao commit; **And** não copiar contato/endereço, coluna Z, nome Zoom, conteúdo privado ou segredo para envelope/logs.

**AC-02.** **Given** integração Evolution válida e canal/grupo autorizado; **When** processar intenção; **Then** usar template/versão aprovada e mecanismos de 4.2 (auth, allowlist, rate limit, retry/backoff, resultado por efeito), separando aceito pelo provedor de entregue comprovado; **And** falha/desativação não reverte a ata e não permite retry cego de resultado incerto.

**AC-03.** **Given** opção Telegram; **When** configurar canal e envio; **Then** somente habilitar após comprovação institucional de uso atual, contrato real autenticado e proteção equivalente por destino, sem reintroduzir bot legado por suposição; **And** Telegram inativo/inexistente não impede envio via Evolution ou operação local da ata. Registrar equivalência/descontinuação da função legada sob o Epic 15 quando aplicável.

**AC-04.** **Given** alteração/correção/anulação da ata depois de pedido de envio, ou flag/destino desativado; **When** worker executar ou responsável solicitar reenvio; **Then** revalidar versão, modelo, autoridade e estado; enviar nova mensagem apenas por ação explícita, com correlação entre versões e indicação de revisão, nunca duplicar efeito da anterior ou divulgar versão cancelada; **And** revisão/anulação solicita encaminhamento privado ao dono do Kit conforme consumidor 6 quando existir, sem usar grupo público ou dados pessoais.

**AC-05.** **Given** reprocessamento concorrente, erro 429, timeout ou crash após resposta incerta; **When** retomar; **Then** registrar tentativa/estado e reconciliação segura, sem prometer exactly-once externo não suportado; **And** chave de efeito por resumo/versão/finalidade/destino evita reenvio repetido pela mesma intenção.

**AC-06.** **Given** pessoa sem competência, destino manipulado, relatório sensível ou root não elevado; **When** pré-visualizar/enviar/reprocessar; **Then** negar uso indevido, revalidar Policy inclusive com sessão antiga, auditar ação permitida e exibir status honesto e acessível; **And** autorização de escrever ata não concede automaticamente editar integração/segredo ou enviar para qualquer grupo.

### Rastreabilidade proposta do Epic 5 — 29 RF principais

A matriz reflete **cobertura de planejamento**, não implementação. Componente externo somente é considerado aceito com integração real/ensaio do respectivo consumidor; contratos transversais permanecem pendentes onde declarado. As relações `RF-ATA-002/006/018/026` são referências entre Epics 2/5/6/9, **não** redistribuição de responsabilidade principal.

| RF principal | Story / AC | Limite de aceite e consumidor |
|---|---|---|
| RF-ATA-001 | 5.1 AC-01–04; 5.5 AC-01–03 | Dois moderadores autorizados; contingência apenas com regra legítima. |
| RF-ATA-002 | 5.1 AC-01/02/06; 5.2 AC-05 | Pré-preenchimento local, Zoom apenas condicional ao produtor do Epic 9. |
| RF-ATA-003 | 5.1 AC-06; 5.2 AC-01–04; 5.4 AC-03; 5.5 AC-01 | Dados sugeridos/editáveis antes do envio, sem alterar agenda/escala ou forjar ocorrência. |
| RF-ATA-004 | 5.1 AC-02; 5.4 AC-04 | Papel chat sugerido e trocável por escala existente. |
| RF-ATA-005 | 5.1 AC-01/02; 5.4 AC-04 | Dupla por ocorrência, sem cargos permanentes. |
| RF-ATA-007 | 5.5 AC-01/07 | Conferência explícita antes da oficialização. |
| RF-ATA-008 | 5.1 AC-03; 5.2 AC-02–04 | Rascunho/autossalvamento autenticados e retomáveis. |
| RF-ATA-009 | 5.5 AC-02–05 | Envio oficial atômico, separado da mensageria. |
| RF-ATA-010 | 5.7 AC-01–06 | Remetente/CG/SG/root elevado; justificativa, antes/depois. |
| RF-ATA-011 | 5.5 AC-02/06; 5.7 AC-01/05 | Estados canônicos, conferência de oficial sem alterar conteúdo, versões e anulação. |
| RF-ATA-012 | 5.2 AC-01/05; 5.5 AC-01/02 | Rascunho admite nulo; novo envio exige totais inteiros não negativos, sem zero inventado ou dupla contagem. |
| RF-ATA-013 | 5.2 AC-01; 5.3 AC-01–05; 5.4 AC-02/03 | Campos estruturados e seleção autorizada. |
| RF-ATA-014 | 5.4 AC-02/05 | Observações livres protegidas. |
| RF-ATA-015 | 5.4 AC-02/03 | `outro` com complemento validado. |
| RF-ATA-016 | 5.3 AC-01/05 | Ingresso implica visita; visita independente. |
| RF-ATA-017 | 5.3 AC-01/04 | Nome mínimo histórico, pessoa opcional, sem login. |
| RF-ATA-019 | 5.3 AC-03/04 | Conquistas/tempo com vínculo opcional humano. |
| RF-ATA-020 | 5.3 AC-02 | Visitas/quantidade/origem/contexto. |
| RF-ATA-021 | 5.4 AC-01/03 | Temática e partilhador histórico; vínculo opcional. |
| RF-ATA-022 | 5.8 AC-01–06 | Geração automática idempotente após oficial; conferência/classificação antes de liberação, sem envio implícito. |
| RF-ATA-023 | 5.9 AC-01–06 | Evolution e/ou Telegram homologado; transporte 4.2. |
| RF-ATA-024 | 5.6 AC-01/02/04; 5.7 AC-05 | Histórico/versões/filtros autorizados. |
| RF-ATA-025 | 5.6 AC-03–05 | CSV/impressão sem fórmula injetada/PII. |
| RF-ATA-027 | 5.3 AC-04/06; 5.4 AC-01 | Identidade sugerida e vínculo humano, nunca fusão automática. |
| RF-ATA-028 | 5.2 AC-01; 5.3 AC-01–03; 5.4 AC-03; 5.5 AC-01 | Paridade de grupos de campos, legado apenas evidência. |
| RF-AUTOMACOES-002 | 5.8 AC-01–05; 5.9 AC-01/04 | Resumo pós-oficial/allowlist, não coluna Z, dedup envio. |
| RF-AUTOMACOES-014 | 5.9 AC-03 | Telegram só se comprovado ativo. |
| RF-AUTOMACOES-020 | 5.7 AC-04; 5.9 AC-01/04/05 | Mensagem só após oficial, correção exige reenvio explícito. |
| RF-IDENTIDADE-003 | 5.3 AC-04; 5.4 AC-01; 5.7 AC-06 | Vínculo contextual opcional, preservar nome histórico. |

### Gate global do Epic 5 — avaliação documental para revisão externa

**Situação: PROPOSTAS COMPLETAS, NÃO APROVADAS.** Nove Stories com valor próprio, precedência somente de capacidades anteriores e 29 RF com responsável principal. A operação manual segue funcional sem integrações futuras; aprovação/produção estão condicionadas aos gates por Story e dados reais. Riscos prioritários da conferência editorial: autoria de remetente/dupla em alteração temporal; unicidade da ata/versões; totais declarados versus detalhe; rascunho não produzir efeitos; sigilo de pessoa/Zoom/Kit e proteção dos nomes históricos; retificação sem duplo efeito; resumo conforme versão/publicabilidade; resultado externo incerto; política de guarda e restauração. Conferir formalmente no repositório antes do aceite humano, inclusive com revisão adversarial BMAD real pelo OpenCode; esta elaboração não executou subagentes, testes ou runtime.
