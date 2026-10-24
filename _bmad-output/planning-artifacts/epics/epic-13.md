## Epic 13: Apoiar aprendizado e literatura com IA habilitada conscientemente
> **Estado vigente — APROVADO COMO PLANEJAMENTO CONDICIONADO em 24/10/2026.** Aceite humano expresso após auditoria/revalidação. Stories e AC abaixo preservados; expressões de proposta/revisão/aceite pendente são proveniência histórica. Gates institucionais e operacionais continuam abertos; sem autorização de build, dados reais, sprint ou deploy. Conclusão documental não encerra formalmente os passos 03/04 nem comprova readiness.

> **Fontes canônicas:** [índice global](../epics.md) · [PRD](../PRD.md) · [Product Brief](../ProductBrief.md) · [Arquitetura](../Arquitetura.md) · [UX Design](../UX-Design.md). **Detalhamento exclusivo deste Epic:** Stories, critérios de aceite, gates e matriz RF → Story → AC.

## Escopo aprovado no passo 02
**Objetivo:** responsáveis selecionam fontes/notebooks e habilitam assistência, meditação/treinamento ou resumo de conversas sem interferir na operação essencial.

**Resultado verificável:** notebook por finalidade e fontes manuais, endpoint/modelo/chave protegidos, limites/teste/diagnóstico; consulta pública à literatura só com fontes autorizadas/referências. Rotinas de treinamento/meditação a destinos permitidos e resumo de conversas separado da ata, todos desligados inicialmente.

**RFs responsáveis (6):** RF-IA-001–004; RF-AUTOMACOES-021, RF-AUTOMACOES-023.

**Dependências — início:** controles de acesso/segredo/auditoria necessários de 1 e fontes/notebooks autorizados. **Por capacidade:** literatura pública usa portal de 2 e segregação de fontes; envio de meditação/treinamento usa transporte mínimo de 4; resumo de conversas exige canal/recepção autorizado de 12, política de coleta/retenção e habilitação específica. Não exigir todos os módulos de 10/12 para cadastrar/conferir notebook ou consultar literatura. **Incremental:** cada finalidade e rotina é habilitada separadamente, desligada por padrão. **Produção:** fontes/direitos/termos, proteção de perfil/segredo, parâmetros 03/08, limites e contrato real do provedor; mocks comprovam cenários locais, não homologam integração real.

**Limites:** implementação desligada e testes com fixtures podem ser entregas verificáveis, mas não substituem aceite das integrações reais exigidas pelo RF nem permitem concluir 13 com capacidades faltantes. Ativação exige validação do provedor/termos/direitos e decisão operacional. Não conectar notebook interno ao público, coletar conversas previamente ou impor IA a ata/financeiro/migração. Indisponibilidade mantém fluxos essenciais.
## Contratos de elaboração e revisão
- **Fonte e estado:** o PRD rege texto integral dos RF; o índice rege a titularidade do Epic e os contratos compartilhados. Uma referência de RF não comprova sua implementação nem aceite ponta a ponta.
- **Dependências por capacidade:** cada Story usa somente baseline ou produtores anteriores mencionados. Referências externas para integração, dados reais ou consumidores posteriores são *gates de aceite/ativação*, não autorização para antecipar tabelas, criar rotas falsas ou bloquear uma capacidade manual já utilizável.
- **Segurança e governança:** capacidades pelo mandato vigente/Policy no backend e na tela; exceção root somente por sessão pessoal elevada, justificada, temporal e auditada. Evento/alteração/auditoria e intenção outbox dentro da mesma transação quando aplicáveis, nunca chamadas HTTP externas antes do commit.
- **Dados e precisão:** preservar NULL distinto de zero; datas civis em `America/Bahia`, instantes em UTC; valores monetários decimais; filtros e exports usam mesma Policy. Não inferir pessoas a partir do Zoom nem mesclar homônimos.
- **Validação posterior:** testes com fixtures sintéticas, cenários positivos/negativos, sessão dormente, falhas, repetição, concorrência, PostgreSQL real, classificação de dados, mobile/teclado/leitor e comportamento degradado pertinentes. Contrato técnico de provedor exige homologação real, não apenas mock.
- **Limite da rodada:** não executar fluxo instalado, migrations, testes Laravel, comandos de importação, integração real, sprint, commit ou deploy. A revisão independente e correção adversarial ficam para o OpenCode no checkpoint humano.

## Stories propostas

<a id="story-13-1"></a>
### Story 13.1: Configurar provedor de IA compatível sem ativação implícita

**Como** gestor autorizado de integrações,

**quero** manter endpoint, modelo, limite e segredo de IA protegidos,

**para** preparar assistência opcional sem bloquear serviços essenciais.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 1.6 autorização e configuração segura de 4.1 quando disponível. **RF principais:** `RF-AUTOMACOES-021`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** IA não substitui autoria humana ou decisões institucionais; sem cobrança/limite inventados.

**Acceptance Criteria:**

**AC-01.** **Given** endpoint compatível OpenAI e segredo sob cofre/autorização; **When** salvar configurações e teste técnico; **Then** validar host/contrato, mascarar chave, registrar auditoria e estado inicialmente desabilitado.

**AC-02.** **Given** serviço retorna erro/limite ou modelo indisponível; **When** testar conexão; **Then** mostrar diagnóstico sanitizado sem bloquear site, ata, tesouraria ou serviço.

**AC-03.** **Given** parâmetro de custo/quota não definido ou destino não autorizado; **When** ativar provedor; **Then** bloquear habilitação real e manter serviço desligado até decisão operacional.

**AC-04.** **Given** visitante ou usuário sem gestão; **When** consultar/editar segredo por rota; **Then** negar acesso e não expor token em HTML, logs, preview ou erro.

**Evidência futura e gate humano:** Modelos, termos, privacidade e limites homologados antes de habilitação. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-13-2"></a>
### Story 13.2: Integrar NotebookLM-py privado e observar disponibilidade

**Como** servidor responsável por acervo autorizado,

**quero** conectar notebook privado sem disponibilizar o perfil Google,

**para** consultar fontes quando o serviço estiver permitido.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 13.1 apenas para configuração geral, adapter NotebookLM separado. **RF principais:** `RF-IA-001`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não embutir NotebookLM em PHP nem obrigar IA à migração/ata.

**Acceptance Criteria:**

**AC-01.** **Given** instância privada NotebookLM-py autenticada e versão homologada fixada no componente/adaptador executável; **When** consultar diagnóstico e contrato REST/CLI/MCP efetivamente oferecido; **Then** registrar versão/resultado e usar somente rede privada com credenciais protegidas. Atualização não homologada permanece desabilitada até reconferir contrato/rotas/autenticação/diagnóstico; não presumir endpoint health ou seleção de modelo inexistentes no fornecedor.

**AC-02.** **Given** serviço indisponível, credencial expirada ou timeout; **When** iniciar consulta; **Then** falhar de modo controlado, sem impedir rotinas críticas ou retornar resposta inventada.

**AC-03.** **Given** duas execuções disputam perfil Google/sessão; **When** coordenar chamadas; **Then** limitar concorrência para proteger sessão e detectar falhas sem duplicar processamento.

**AC-04.** **Given** acesso externo ao perfil ou endpoint privado; **When** tentar conexão sem permissão; **Then** negar e não registrar cookies/tokens em logs ou documentos do projeto.

**Evidência futura e gate humano:** Termos, disponibilidade, custódia de perfil e limite operacional homologados. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-13-3"></a>
### Story 13.3: Cadastrar notebooks por finalidade e fontes permitidas

**Como** servidor responsável pelas fontes do grupo,

**quero** catalogar notebooks, versões de prompt e finalidades,

**para** separar literatura pública, treinamento e meditação.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 13.2. **RF principais:** `RF-IA-002`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não coletar novas fontes automaticamente nem autorizar uso pelo simples cadastro.

**Acceptance Criteria:**

**AC-01.** **Given** notebook com ID legítimo, finalidade e fontes autorizadas; **When** configurar prompt e forma/formato permitido de resposta; **Then** versionar ambos com metadados, responsável, escopo, prévia sanitizada e trilha; fontes continuam geridas manualmente. Formato por finalidade não amplia fonte/audiência e não cria parâmetro externo que o adaptador não suporte.

**AC-02.** **Given** fonte interna/restrita recebe finalidade pública por engano; **When** tentar atribuir; **Then** rejeitar vínculo de classe incompatível e impedir exposição por troca de configuração.

**AC-03.** **Given** alterações simultâneas de prompt/formato/ID ou notebook removido; **When** salvar; **Then** comparar versão, preservar revisão anterior de prompt/formato e não enviar para fonte inacessível ou audiência incompatível.

**AC-04.** **Given** gestor não autorizado ou IA desligada; **When** consultar notebooks; **Then** não exibir conteúdo interno fora da Policy e não executar processamento por cadastro.

**Evidência futura e gate humano:** Fontes/direitos e separação entre notebooks devem ser aprovados. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-13-4"></a>
### Story 13.4: Responder consulta pública somente com literatura autorizada

**Como** visitante sem conta,

**quero** perguntar sobre literatura permitida e receber referências,

**para** ter resposta verificável sem acessar acervo interno.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 13.2/13.3; portal 2. **RF principais:** `RF-IA-003`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não indexar documentos internos por requisição pública; sem coleta permanente de perguntas por padrão.

**Acceptance Criteria:**

**AC-01.** **Given** notebook público habilitado com fontes autorizadas; **When** visitante formular pergunta dentro do limite; **Then** responder com citações identificáveis às fontes permitidas e declarar incerteza/ausência quando não houver base.

**AC-02.** **Given** resposta sem referência válida ou fonte indisponível; **When** concluir consulta; **Then** não apresentar afirmação como fonte verificada; oferecer ausência fundamentada/falha segura.

**AC-03.** **Given** solicitação tenta consultar notebook interno ou prompt injection; **When** processar entrada; **Then** isolar fontes/credenciais, aplicar rate limit e não revelar dados internos, system prompt ou chaves.

**AC-04.** **Given** IA desligada, fora ou em sobrecarga; **When** abrir home/agenda; **Then** mostrar recurso facultativo indisponível sem atrasar conteúdo crítico SSR.

**Evidência futura e gate humano:** Termos/direitos/fontes, rate limit e política de retenção precisam de aceite antes da ativação. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-13-5"></a>
### Story 13.5: Programar meditação e treinamento para destinos permitidos

**Como** gestor da comunicação autorizada,

**quero** agendar conteúdo a grupos aprovados,

**para** apoiar atividades sem mensagens não solicitadas.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 13.3 e transporte 4.2; reutilizar contratos de agenda/flag/idempotência por rotina já exercitados em 4.4/4.7, sem depender de mandato a vencer ou executar sua rotina de avisos. Materializar somente agenda da finalidade IA, não segunda engine. **RF principais:** `RF-IA-004`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não disparar para grupos não aprovados nem substituir decisão humana de publicação.

**Acceptance Criteria:**

**AC-01.** **Given** notebook de treinamento/meditação aprovado, destino e flag habilitados; **When** configurar modelo/provedor aplicável, prompt/formato, destinatários, horário e execução por rotina/finalidade e agendar; **Then** registrar a versão efetivamente resolvida (inclusive herança explícita da configuração geral), produzir conteúdo apenas na finalidade declarada e usar prévia/outbox/destino autorizado. Distinguir modelo OpenAI compatível da configuração do adaptador NotebookLM: não inventar seleção de modelo que sua API não ofereça. Limitação real exige decisão/harmonização antes de aceitar a capacidade, nunca sucesso fictício.

O agendamento específico por finalidade/horário é entregue em 13.5, não presumido em 4.4: demonstrar execução sem qualquer designação a vencer, flag desligada, retomada e dois schedulers concorrentes sem duplicação. Reutilizar os contratos de transporte/flags/templates, sem ampliar retroativamente avisos de mandato nem duplicar engine.

**AC-02.** **Given** flag desligada, ausência de revisão ou horário inválido; **When** scheduler executar; **Then** não coletar nem enviar conteúdo e deixar estado verificável.

**AC-03.** **Given** falha de geração/envio, replay ou execução paralela; **When** reprocessar; **Then** deduplicar efeito por ocorrência/tipo/destino/versão, sem mensagens em duplicata.

**AC-04.** **Given** destino grupal incluir material restrito ou dados pessoais; **When** validar conteúdo; **Then** bloquear envio e proteger fonte, logs e mensagens; operador recebe alerta mínimo.

**Evidência futura e gate humano:** Horários, destinatários, regras editoriais e consentimento por parâmetro 08. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-13-6"></a>
### Story 13.6: Habilitar resumo de conversas apenas sob política específica

**Como** gestor autorizado do grupo/canal,

**quero** produzir resumo de conversas quando explicitamente permitido,

**para** não confundir conversa com ata oficial nem coletar sem autorização.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 13.1 e webhook/identificação de origem de 12.1. **RF principais:** `RF-AUTOMACOES-023`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Nenhum resumo de conversa vira ata oficial ou prova de presença.

**Acceptance Criteria:**

**AC-01.** **Given** política de coleta/retensão e canal específicos aprovados com flag habilitada; **When** executar janela de resumo; **Then** processar somente mensagens permitidas e produzir artefato claramente distinto de ata/resumo oficial.

**AC-02.** **Given** flag desligada ou falta política/autorização; **When** mensagem chegar; **Then** não captar corpo para resumo, não guardar transcrição nem disparar IA.

**AC-03.** **Given** conteúdo pode conter contato, endereço, segredo ou informação pessoal; **When** preparar entrada para qualquer provedor IA externo, antes da chamada e também ao montar resumo/entrega; **Then** minimizar por campo/finalidade e negar transferência de conteúdo sem autorização específica. Sanitizar apenas a saída é insuficiente: sem garantia homologada para conteúdo misto, não enviar o original ao provedor. Restringir destino e descartar dados além da finalidade, sem registrar transcrição/segredo em logs.

**AC-04.** **Given** falha do provedor, retry ou revogação da autorização; **When** processar janela; **Then** não duplicar saída nem manter captura após revogação; fluxos ordinários continuam operantes.

**Evidência futura e gate humano:** Política de privacidade, consentimento, audiência e expurgo por classe são gates antes de coletar. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

### Rastreabilidade proposta do Epic 13 — RF → Story → AC
Os IDs e a titularidade principal são os do mapa global aprovado. ACs indicam contratos **propostos**, não testes executados; RFs compostos dependem das integrações/gates explicitados no escopo.

| RF principal | Story e critérios propostos | Objeto de cobertura | Situação |
|---|---|---|---|
| RF-AUTOMACOES-021 | 13.1 AC-01–04; [Story 13.1](#story-13-1) | IA: endpoint base compatível com OpenAI, modelo e chave protegida, teste de conectividade e limites. | Proposto; execução/aceite condicionados ao RF integral |
| RF-IA-001 | 13.2 AC-01–04; [Story 13.2](#story-13-2) | Integração NotebookLM-py por REST/CLI/MCP apropriado, instância Docker/Swarm privada e habilitável. | Proposto; execução/aceite condicionados ao RF integral |
| RF-IA-002 | 13.3 AC-01–04; [Story 13.3](#story-13-3) | Cadastro de notebooks pelo ID e finalidade, fontes geridas manualmente por servidores, prompt/forma de resposta configurável e versionado. | Proposto; execução/aceite condicionados ao RF integral |
| RF-IA-003 | 13.4 AC-01–04; [Story 13.4](#story-13-4) | Consulta pública à literatura autorizada com respostas referenciadas e indicação de ausência de fonte. | Proposto; execução/aceite condicionados ao RF integral |
| RF-IA-004 | 13.5 AC-01–04; [Story 13.5](#story-13-5) | Automação de meditação e treinamento com envio predominantemente a grupos WhatsApp autorizados. | Proposto; execução/aceite condicionados ao RF integral |
| RF-AUTOMACOES-023 | 13.6 AC-01–04; [Story 13.6](#story-13-6) | Rotina de resumo de conversas do WhatsApp distinta do resumo da ata, desabilitada inicialmente. | Proposto; execução/aceite condicionados ao RF integral |

## Dependências, gates e aceite do Epic
- **Início e ordem:** implementar somente Stories autorizadas, precedidas pelos produtores reais necessários; a ordem editorial não substitui a verificação de capacidade já entregue. Qualquer dependência de componente futuro deve ser sinalizada e o consumidor adiado, sem simular funcionalidade.
- **Decisões humanas:** permanecem abertas as do índice e da cláusula de cada Story. Ausência de decisão não pode virar default normativo silencioso. Segurança, finalidade, canais, fornecedores, documentação e política de dados por classe antecedem a habilitação afetada.
- **Critério de encerramento:** revisar **todos** os RF principais acima, seus negativos e os contratos RNF/RB/CA/UX pertinentes no texto integral do PRD/UX. Não concluir Epic enquanto faltar funcionalidade composta exigida, homologação externa ou gate explicitamente aplicável.
- **Ponto de parada BMAD:** aguardar revisão independente individual/global, conciliação multidisciplinar e aceite humano do lote. Não adicionar ID a `approvedStoryIds` nem avançar `stepsCompleted` nesta entrega.

### Auditoria documental integral — 23–24/10/2026

Revisão/revalidação focal `ses_e949c24a6ffeWEqI3feNpRXHRk`: **6 Stories / 24 AC / 6 RF principais**, cada AC-01–04 examinado individualmente; F3–F5 resolvidos, sem residual focal. CL-01–05 aplicáveis; coordenador único editor. Resultado global no índice; nenhuma API NotebookLM presumida homologada, captura real ou habilitação autorizadas.

| ID / severidade | Epic/Story/AC | Evidência original | Fonte normativa | Correção persistida | Resultado | Revalidação |
|---|---|---|---|---|---|---|
| F3 MINOR | 13.3 AC-01/03 | Só prompt versionado | RF-IA-002 | Formato configurável/versionado e concorrência | Corrigido | Sessão acima; sem residual |
| F4 MINOR | 13.5 AC-01 | Modelo por rotina não verificável | RF-IA-004; Arquitetura síntese 5 | Referência/herança de modelo/prompt/formato resolvidos sem inventar API | Corrigido | Idem |
| F5 MINOR | 13.2 AC-01 | Registro de versão sem fixação | Arquitetura §§10/síntese 5 | Versão homologada fixada e atualização gated | Corrigido | Idem |

**Contratos por Story, AC-01–04:** 13.1 → RNF-01/03–05/09, RB-13/17/23, CA-23/32, UX-DR60; 13.2 → RNF-01/03–05/09, RB-13/17, CA-32, UX-DR60/61; 13.3 → RNF-03–06/17, RB-10/13/17, CA-32, UX-DR61; 13.4 → RNF-01/05–07/10/17, RB-10/13, CA-01/32, UX-DR60/61; 13.5 → RNF-03–06/08–10, RB-09/10/13/23, CA-25/32, UX-DR43/44/61; 13.6 → RNF-03–05/08–10/15/17, RB-10/13/23, CA-25/32, UX-DR52/60/61. Fontes/direitos/termos, modelo/limites, perfil Google, transferência de dados, política de conversas e destinos são gates por finalidade; tudo desligado até habilitação explícita.
