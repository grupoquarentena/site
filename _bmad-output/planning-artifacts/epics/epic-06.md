## Epic 6: Atender o Kit ingresso do pedido à entrega, com sigilo
> **Estado vigente — APROVADO COMO PLANEJAMENTO CONDICIONADO em 24/10/2026.** Aceite humano expresso após auditoria/revalidação. Stories e AC abaixo preservados; expressões de proposta/revisão/aceite pendente são proveniência histórica. Gates institucionais e operacionais continuam abertos; sem autorização de build, dados reais, sprint ou deploy. Conclusão documental não encerra formalmente os passos 03/04 nem comprova readiness.
> **Proveniência da elaboração:** proposta criada a partir do mapa aprovado e dos quatro normativos, antes da auditoria OpenCode/BMAD instalada. Autorização de redação não equivale a aceite institucional ou permissão de build.

> **Fontes canônicas:** [índice global](../epics.md) · [PRD](../PRD.md) · [Product Brief](../ProductBrief.md) · [Arquitetura](../Arquitetura.md) · [UX Design](../UX-Design.md). **Detalhamento exclusivo deste Epic:** Stories, critérios de aceite, gates e matriz RF → Story → AC.

## Escopo aprovado no passo 02
**Objetivo:** Coordenador Kit recebe pendências oficiais, coleta endereço em canal privado, expede e acompanha entrega sem expor dados aos demais encargos.

**Resultado verificável:** ingresso oficial gera um Kit; fila/detalhe/observações cifrados, CEP, solicitação privada, expedição, rastreio Correios e relatório privativo. Falha externa permite retry/manual sem duplicação; retificação/anulação da ata abre revisão privada e preserva remessa expedida.

**RFs responsáveis (24):** RF-ATA-018; RF-RELATORIOS-013; RF-KIT-001–018; RF-AUTOMACOES-003–006.

**Dependências — início:** ingresso oficial/eventos da ata manual em 5 e controle de acesso/leitura auditada/mandato Kit em 1. **Por capacidade:** fila, endereço, expedição e registro manual de acompanhamento funcionam sem Correios/mensageria; consulta automática exige adaptador Correios autenticado privado; mensagem exige apenas transporte mínimo de 4. **Incremental:** reprocessamento/rastreio e avisos anexados sem duplicar efeitos do ingresso/correção. **Produção:** parâmetros 03/06/13, recuperação real e política de contato/canal; testes negativos de exclusividade e sigilo. Ausência de provedor não dispensa integrar/testar autenticação antes de aceitar a capacidade automática correspondente.

**Limites:** consumo idempotente dos eventos/outbox existentes, sem duplicar envio da ata; leitura de PII auditada, edição ordinária exclusiva, exceção root pessoal identificada. Relatório/avisos do Kit pertencem a este fluxo, não ficam dependentes do dashboard global. Grupo recebe no máximo agregado não identificável; nenhum código individual de rastreio.
## Contratos de elaboração e revisão
- **Fonte e estado:** o PRD rege texto integral dos RF; o índice rege a titularidade do Epic e os contratos compartilhados. Uma referência de RF não comprova sua implementação nem aceite ponta a ponta.
- **Dependências por capacidade:** cada Story usa somente baseline ou produtores anteriores mencionados. Referências externas para integração, dados reais ou consumidores posteriores são *gates de aceite/ativação*, não autorização para antecipar tabelas, criar rotas falsas ou bloquear uma capacidade manual já utilizável.
- **Segurança e governança:** capacidades pelo mandato vigente/Policy no backend e na tela; exceção root somente por sessão pessoal elevada, justificada, temporal e auditada. Evento/alteração/auditoria e intenção outbox dentro da mesma transação quando aplicáveis, nunca chamadas HTTP externas antes do commit.
- **Dados e precisão:** preservar NULL distinto de zero; datas civis em `America/Bahia`, instantes em UTC; valores monetários decimais; filtros e exports usam mesma Policy. Não inferir pessoas a partir do Zoom nem mesclar homônimos.
- **Validação posterior:** testes com fixtures sintéticas, cenários positivos/negativos, sessão dormente, falhas, repetição, concorrência, PostgreSQL real, classificação de dados, mobile/teclado/leitor e comportamento degradado pertinentes. Contrato técnico de provedor exige homologação real, não apenas mock.
- **Limite da rodada:** não executar fluxo instalado, migrations, testes Laravel, comandos de importação, integração real, sprint, commit ou deploy. A revisão independente e correção adversarial ficam para o OpenCode no checkpoint humano.

## Stories propostas

<a id="story-6-1"></a>
### Story 6.1: Gerar pendência única de Kit a partir do ingresso oficial

**Como** Coordenador Kit vigente,

**quero** receber uma pendência por ingresso oficial e acompanhar sua origem,

**para** nenhum ingresso elegível desaparecer ou produzir duas remessas.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 5.5/5.7 envio e revisão oficial; 1.6 Policy Kit. **RF principais:** `RF-ATA-018`, `RF-KIT-001`, `RF-KIT-002`, `RF-AUTOMACOES-003`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não editar a ata nem criar usuário para ingressante.

**Acceptance Criteria:**

**AC-01.** **Given** ata oficialmente enviada com ingresso elegível e evento persistido; **When** o consumidor processar evento_id e versão; **Then** criar uma única pendência vinculada ao ingresso e preservar origem/ata/versão; disponibilizar fila mínima somente ao Coordenador Kit vigente, com Policy/trilha já neste incremento e sem exigir conta do ingressante. A identidade única do Kit é o ingresso, não cada revisão da ata; revisão identifica o evento, nunca autoriza segundo Kit para o mesmo ingresso.

**AC-02.** **Given** evento repetido, worker concorrente ou mesma origem reprocessada; **When** entregar duas vezes ou reiniciar; **Then** restrição UNIQUE da identidade do efeito conserva apenas uma pendência e uma trilha; não ocorre segundo contato.

**AC-03.** **Given** rascunho, ata não oficial ou anulação/retificação posterior; **When** consumir evento correspondente, inclusive antes do evento original atrasado; **Then** não criar pendência por rascunho nem aplicar versão superada como atual; registrar revisão humana restrita e obrigação durável de alerta privado ao Coordenador Kit. A fila mínima permite ao Coordenador vigente conferir origem/versões e registrar decisão/providência justificada e auditada, sem cancelar remessa expedida ou apagar histórico. O envio efetivo do alerta usa 4.2 quando disponível; falta de autoridade/canal deixa pendência verificável, nunca aviso concluído. RF-ATA-018 só se completa com esse aviso e tratamento real, sem tornar Kit predecessor da correção local em 5.7.

**AC-04.** **Given** consumidor ainda desabilitado quando a ata é enviada; **When** habilitar processamento posteriormente; **Then** reprocessar intenção durável sem perda, sob política de retenção, reconciliando efeitos anteriores.

**Evidência futura e gate humano:** Política de dados por classe/escopo de evento e recuperação testada antes de dados reais. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-6-2"></a>
### Story 6.2: Proteger cadastro, contatos e fila exclusiva do Kit

**Como** Coordenador Kit vigente,

**quero** consultar e manter os detalhes necessários de cada pendência,

**para** tratar o ingresso com sigilo e autoria verificável.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 6.1 e controles da 1.6/1.10; 1.12 somente na exceção. **RF principais:** `RF-KIT-003`, `RF-KIT-012`, `RF-KIT-013`, `RF-KIT-014`, `RF-KIT-018`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Sem banco paralelo de contatos; login e vínculo pessoa são pré-existentes.

**Acceptance Criteria:**

**AC-01.** **Given** Coordenador Kit vigente e pendência válida; **When** abrir detalhes ou editar telefone/endereço/observação/responsável; **Then** cifrar PII em repouso e auditar leituras sensíveis e alterações com autor e finalidade.

**AC-02.** **Given** CG/SG, moderador, Tesoureiro ou usuário inativo sem elevação; **When** pedir listagem/detalhe/API/exportação ou filtrar por telefone; **Then** negar dados e não revelá-los em contagens, caches, mensagens ou logs; menus igualmente restritos.

**AC-03.** **Given** sessão root pessoal elevada válida e justificada; **When** realizar leitura/mutação excepcional; **Then** registrar identidade, modo elevado, motivo e operação; sem ampliar permissão ordinária da Mesa.

**AC-04.** **Given** dois editores ou revogação do mandato durante a ação; **When** salvar a mesma pendência; **Then** comparar versão e revalidar mandato no commit, preservando anterior diante de conflito.

**Integração consumidora de RF-ATENDIMENTO-002 (compõe 6.2 AC-01/04):** quando o atendimento 2.6 já existir e houver decisão procedente relativa ao Kit, o Coordenador vigente vincula alvo permitido ao protocolo no mesmo atendimento, executa a alteração pela Policy Kit com versão esperada e motivo e registra efeito/trilha/correlação e conclusão consistente. Falha/interrupção mantém pendência durável e reconciliação sem repetir alteração; somente efeito comprovado permite concluir a providência. Original/contato/resposta privativos continuam inacessíveis a CG/SG, inclusive listas/exports/diagnóstico; usar o percurso E2-K e a resposta autorizada de 2.6, sem outra caixa. Esse adaptador completa o encaminhamento quando disponível, não bloqueia o cadastro manual anterior nem amplia autoridade técnica.

**Evidência futura e gate humano:** Política de finalidade/cifra/chaves/expurgo e testes cross-user antes de dados reais. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-6-3"></a>
### Story 6.3: Solicitar endereço pelo canal privado autorizado

**Como** Coordenador Kit vigente,

**quero** solicitar ao ingressante os dados necessários à entrega,

**para** obter contato de forma restrita e sem exposição em grupo.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 6.2; transporte mínimo 4.2. **RF principais:** `RF-KIT-006`, `RF-AUTOMACOES-004`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não duplicar engine/outbox do Epic 4 ou armazenar mensagem integral em auditoria.

**Acceptance Criteria:**

**AC-01.** **Given** destinatário com canal privado autorizado e pedido ainda devido; **When** Coordenador Kit solicitar endereço; **Then** persistir obrigação única e enviar modelo mínimo permitido após commit com referência privada à pendência.

**AC-02.** **Given** destinatário sem contato, consentimento/canal inválido ou transporte offline; **When** solicitar mensagem; **Then** manter pendência e indicar ação manual segura, sem marcar mensagem como entregue.

**AC-03.** **Given** pedido reprocessado ou webhook/tentativa repetidos; **When** enviar novamente sem nova autorização; **Then** deduplicar efeito por pendência/tipo/versão e registrar tentativas/resultado.

**AC-04.** **Given** usuário fora da função ou grupo coletivo como destino; **When** tentar obter conteúdo/telefone ou enviar mensagem; **Then** negar via Policy e impedir vazamento do contato/endereço em payload de grupo ou logs.

**Evidência futura e gate humano:** Canal/destinatário e política de contato precisam de homologação antes de envio real. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-6-4"></a>
### Story 6.4: Validar CEP e movimentar estados da pendência

**Como** Coordenador Kit vigente,

**quero** conferir dados de entrega e atualizar o estado do Kit,

**para** separar endereço pendente, preparado e entregue sem perder histórico.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 6.2; consulta externa CEP somente quando habilitada. **RF principais:** `RF-KIT-004`, `RF-KIT-005`, `RF-AUTOMACOES-005`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não inferir entrega por entrada de formulário; não antecipar rastreador.

**Acceptance Criteria:**

**AC-01.** **Given** CEP informado em campo protegido; **When** validar e, opcionalmente, consultar serviço de CEP; **Then** normalizar formato e oferecer sugestão conferível sem substituir endereço manual nem enviar dados além do necessário.

**AC-02.** **Given** pendência nas etapas verificar/falta endereço/falta enviar/enviado/recebido/devolvido/cancelado; **When** confirmar transição permitida; **Then** preservar estado anterior, instante, autor e motivo quando aplicável; não inventar estado extra.

**AC-03.** **Given** CEP externo indisponível ou retorna ambiguidade; **When** salvar endereço manual conferido; **Then** preservar fluxo manual e indicar indisponibilidade, sem interpretar ausência como endereço inválido.

**AC-04.** **Given** transição concorrente, código inválido ou solicitante sem encargo vigente; **When** tentar atualizar por API; **Then** rejeitar estado impossível e revisão obsoleta atomicamente, sem divulgar endereço.

**Evidência futura e gate humano:** Parâmetro 03 e regra institucional de cancelamento/guarda antes de dados reais. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-6-5"></a>
### Story 6.5: Registrar expedição e avisar em canais privados

**Como** Coordenador Kit vigente,

**quero** confirmar remessa e avisar somente destinatários permitidos,

**para** permitir acompanhamento sem divulgar código ou identidade individual.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 6.2/6.4 para expedição manual; 4.2 somente para avisos automatizados, sem exigir solicitação de endereço de 6.3 quando endereço já estiver legitimamente disponível. **RF principais:** `RF-KIT-007`, `RF-KIT-008`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Código de rastreio individual jamais integra mensagem grupal; sem atribuir remessa a pessoa sem confirmação.

**Acceptance Criteria:**

**AC-01.** **Given** pendência apta e código de rastreio conferido; **When** registrar expedição; **Then** persistir código protegido, data/status, comprovante quando disponível, trilha e intenções privadas a ingressante e Coordenador Kit na mesma transação; somente o disparo externo ocorre depois do commit. Mensageria indisponível não bloqueia expedição manual nem perde a intenção pendente.

**AC-02.** **Given** pendência de falta de envio e condição de aviso homologada para a rotina; **When** essa condição ocorrer; **Then** registrar obrigação privada para ambos, Coordenador Kit e ingressante, com resultado por destinatário e chave de efeito estável. Ausência de contato/canal/autoridade mantém a obrigação afetada pendente; efeito já satisfeito não reabre por troca de destino. Disparo/frequência são parâmetros humanos da rotina, não periodicidade inventada. Eventual aviso grupal contém apenas agregado não identificável.

**AC-03.** **Given** envio externo falha ou destino altera após entrega comprovada; **When** processar retry; **Then** não duplicar comunicação lógica já satisfeita; registrar incerteza e exigir reconciliação/novo aviso autorizado.

**AC-04.** **Given** grupo geral, usuário da Mesa ou endpoint público; **When** pedir código, destinatário ou detalhes; **Then** negar e remover identificadores de payloads, logs, listagens e caches comuns.

**Evidência futura e gate humano:** Canais/destinos/guarda de mensagens e recuperação precisam de aceite operacional. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-6-6"></a>
### Story 6.6: Consultar Correios por adaptador privado e registrar eventos únicos

**Como** Coordenador Kit vigente,

**quero** acompanhar rastreio automático e manual com tratamento de falhas,

**para** ver eventos confiáveis sem criar duplicatas ou interromper Kit.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 6.4/6.5; serviço opastorello/correios-rastreamento via REST privado. **RF principais:** `RF-KIT-009`, `RF-KIT-010`, `RF-KIT-015`, `RF-KIT-016`, `RF-AUTOMACOES-006`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Correios continua em serviço Python/FastAPI separado; Laravel usa REST, não inclui scraping PHP.

**Acceptance Criteria:**

**AC-01.** **Given** remessa não finalizada, token privado configurado e job agendado; **When** consultar REST autenticada de rastreio; **Then** validar resposta/timeout, normalizar eventos e gravar cada identidade de evento uma vez no PostgreSQL.

**AC-02.** **Given** resposta repetida, execução paralela ou ordem externa invertida; **When** processar eventos equivalentes; **Then** deduplicar por chave estável da remessa/evento, preservar cronologia e impedir regressão indevida de estado.

**AC-03.** **Given** serviço fora, CAPTCHA, HTTP inválido ou credencial rejeitada; **When** tentar consultar por agenda ou reconsulta manual autorizada; **Then** manter último evento e próximo retry com timeout, backoff/jitter, limites e circuit breaker básico; oferecer atualização manual auditada sem simular sucesso. Revalidar remessa não finalizada e mandato/autoridade da ação antes do efeito; diagnóstico comum não revela código, contato ou endereço.

**AC-04.** **Given** browser ou ator sem direito tenta acessar serviço privado/código; **When** acessar consulta diretamente; **Then** impedir acesso direto e ocultar credenciais, contato e código de saída pública; teste de segurança da rede privada.

**Evidência futura e gate humano:** Homologar serviço real, chave privada, retenção e fallback; mock isolado não conclui integração. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-6-7"></a>
### Story 6.7: Concluir estados de entrega e consultar painel privativo

**Como** Coordenador Kit vigente,

**quero** ver pendências, responsáveis e eventos de entrega,

**para** encerrar o ciclo do Kit com prestação interna sigilosa.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 6.2/6.4/6.5 para consulta e conclusão manuais próprias; 6.6 somente para eventos automáticos, 4.2 somente para disparo de avisos. **RF principais:** `RF-KIT-011`, `RF-KIT-017`, `RF-RELATORIOS-013`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não desenvolver dashboard geral nem tornar relatórios do Kit públicos.

**Acceptance Criteria:**

**AC-01.** **Given** evento normalizado entregue/devolvido ou confirmação manual autorizada; **When** atualizar a remessa; **Then** aplicar transição consistente uma vez, registrar evidência e criar avisos privados permitidos com efeito único.

**AC-02.** **Given** relatório privativo com filtros por estado/período/responsável; **When** consultar fila e histórico; **Then** exibir status, pendências, responsáveis, rastreio e eventos com data/hora/cidade/UF/estado quando disponíveis somente ao Coordenador vigente ou root elevado, sem depender do Epic 11 ou da consulta automática Correios. Campo externo ausente permanece desconhecido.

**AC-03.** **Given** repetição de evento final ou falha de mensageria; **When** reprocessar a entrega; **Then** não alterar duas vezes o estado nem duplicar aviso; falha de aviso não desfaz evento persistido.

**AC-04.** **Given** CG/SG ou visitante solicita relatório, CSV, rota ou dashboard Kit; **When** tentar consultar/exportar; **Then** negar detalhe e identificadores por backend; somente agregado anonimizado quando houver previsão explícita.

**Evidência futura e gate humano:** Teste de revogação em sessão dormente e política de expurgo/restore precedem uso real. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

### Rastreabilidade proposta do Epic 6 — RF → Story → AC
Os IDs e a titularidade principal são os do mapa global aprovado. ACs indicam contratos **propostos**, não testes executados; RFs compostos dependem das integrações/gates explicitados no escopo.

| RF principal | Story e critérios propostos | Objeto de cobertura | Situação |
|---|---|---|---|
| RF-ATA-018 | 6.1 AC-01–04; [Story 6.1](#story-6-1) | Kit ingresso automático | Proposto; execução/aceite condicionados ao RF integral |
| RF-KIT-001 | 6.1 AC-01–04; [Story 6.1](#story-6-1) | Criar pendência de kit | Proposto; execução/aceite condicionados ao RF integral |
| RF-KIT-002 | 6.1 AC-01–04; [Story 6.1](#story-6-1) | Relacionar ingressante | Proposto; execução/aceite condicionados ao RF integral |
| RF-AUTOMACOES-003 | 6.1 AC-01–04; [Story 6.1](#story-6-1) | Ingresso para kit | Proposto; execução/aceite condicionados ao RF integral |
| RF-KIT-003 | 6.2 AC-01–04; 6.4 AC-01–04; 6.5 AC-01; 6.7 AC-02; [Story 6.2](#story-6-2) | Contato, CEP/endereço, estado, observações, responsável e código protegidos | Composto; cadastro inicial não conclui expedição/rastreio |
| RF-KIT-012 | 6.2 AC-01–04; [Story 6.2](#story-6-2) | Permissão de edição | Proposto; execução/aceite condicionados ao RF integral |
| RF-KIT-013 | 6.2 AC-01–04; [Story 6.2](#story-6-2) | Proteção de telefone e endereço | Proposto; execução/aceite condicionados ao RF integral |
| RF-KIT-014 | 6.2 AC-01–04; [Story 6.2](#story-6-2) | Auditoria | Proposto; execução/aceite condicionados ao RF integral |
| RF-KIT-018 | 6.2 AC-01–04; [Story 6.2](#story-6-2) | Exclusividade do coordenador do kit | Proposto; execução/aceite condicionados ao RF integral |
| RF-KIT-006 | 6.3 AC-01–04; [Story 6.3](#story-6-3) | Mensagem solicitando endereço | Proposto; execução/aceite condicionados ao RF integral |
| RF-AUTOMACOES-004 | 6.3 AC-01–04; [Story 6.3](#story-6-3) | Solicitação de endereço | Proposto; execução/aceite condicionados ao RF integral |
| RF-KIT-004 | 6.4 AC-01–04; [Story 6.4](#story-6-4) | Busca de CEP | Proposto; execução/aceite condicionados ao RF integral |
| RF-KIT-005 | 6.4 AC-01–04; [Story 6.4](#story-6-4) | Status do kit | Proposto; execução/aceite condicionados ao RF integral |
| RF-AUTOMACOES-005 | 6.4 AC-01–04; 6.3 AC-01–04; 6.5 AC-01–04; [Story 6.4](#story-6-4) | CEP, endereço, estados, expedição e mensagens | Composto; nenhum incremento isolado conclui o RF |
| RF-KIT-007 | 6.5 AC-01–04; [Story 6.5](#story-6-5) | Mensagem de falta enviar | Proposto; execução/aceite condicionados ao RF integral |
| RF-KIT-008 | 6.5 AC-01–04; [Story 6.5](#story-6-5) | Mensagem de enviado | Proposto; execução/aceite condicionados ao RF integral |
| RF-KIT-009 | 6.6 AC-01–04; [Story 6.6](#story-6-6) | Rastreamento | Proposto; execução/aceite condicionados ao RF integral |
| RF-KIT-010 | 6.6 AC-01–04; [Story 6.6](#story-6-6) | Eventos de rastreio | Proposto; execução/aceite condicionados ao RF integral |
| RF-KIT-015 | 6.6 AC-01–04; [Story 6.6](#story-6-6) | Serviço dedicado de rastreamento | Proposto; execução/aceite condicionados ao RF integral |
| RF-KIT-016 | 6.6 AC-01–04; [Story 6.6](#story-6-6) | Atualização agendada e reprocessamento | Proposto; execução/aceite condicionados ao RF integral |
| RF-AUTOMACOES-006 | 6.6 AC-01–04; [Story 6.6](#story-6-6) | Rastreio postal | Proposto; execução/aceite condicionados ao RF integral |
| RF-KIT-011 | 6.7 AC-01–04; [Story 6.7](#story-6-7) | Atualização automática de status | Proposto; execução/aceite condicionados ao RF integral |
| RF-KIT-017 | 6.6 AC-01–04; 6.7 AC-01–04; [Story 6.7](#story-6-7) | Normalização/dedup/data-hora e sigilo do rastreio | Composto; consulta não substitui persistência e dedup |
| RF-RELATORIOS-013 | 6.7 AC-01–04; [Story 6.7](#story-6-7) | Página Kit Ingresso | Proposto; execução/aceite condicionados ao RF integral |

## Dependências, gates e aceite do Epic
- **Início e ordem:** implementar somente Stories autorizadas, precedidas pelos produtores reais necessários; a ordem editorial não substitui a verificação de capacidade já entregue. Qualquer dependência de componente futuro deve ser sinalizada e o consumidor adiado, sem simular funcionalidade.
- **Decisões humanas:** permanecem abertas as do índice e da cláusula de cada Story. Ausência de decisão não pode virar default normativo silencioso. Segurança, finalidade, canais, fornecedores, documentação e política de dados por classe antecedem a habilitação afetada.
- **Critério de encerramento:** revisar **todos** os RF principais acima, seus negativos e os contratos RNF/RB/CA/UX pertinentes no texto integral do PRD/UX. Não concluir Epic enquanto faltar funcionalidade composta exigida, homologação externa ou gate explicitamente aplicável.
- **Ponto de parada BMAD:** aguardar revisão independente individual/global, conciliação multidisciplinar e aceite humano do lote. Não adicionar ID a `approvedStoryIds` nem avançar `stepsCompleted` nesta entrega.

**Homologação específica Correios (6.6):** comprovar token obrigatório em produção, rede privada, allowlist do serviço, limites reais, timeout/backoff/jitter/circuit breaker e fuso `America/Bahia` com instantes UTC. Histórico auxiliar do serviço não é fonte oficial; limitar/expurgar sua persistência conforme política por classe. Nenhum código/PII em diagnóstico comum. Termos, estabilidade do scraper e retenção auxiliar exigem aceite operacional, sem prazo inventado.

### Auditoria documental integral — 23–24/10/2026

Leitura individual dos 28 AC por revisão independente `ses_e949c9602ffek86gmh22KfLrIQ` e conferência de integração por `ses_e949cc042ffelQAdv6ld6a1cFg`; coordenador como único editor. **7 Stories / 28 AC / 24 RF principais**, sem renumeração. CL-01–05 do índice integram os AC pertinentes. Aprovação humana continua pendente; parecer global, limitações e evidências finais estão no índice, não neste histórico de elaboração externa.

| ID / severidade | Epic/Story/AC | Evidência original | Fonte normativa | Correção persistida | Resultado | Revalidação |
|---|---|---|---|---|---|---|
| E6-01 MAJOR | 6.5/6.7 dependências | Manual exigia transporte/Correios | Macro E6-manual; BMAD 03/04 | Separados manual, consulta e aviso automático | Corrigido | Sessão independente acima |
| E6-02 MAJOR | 6.1 AC-03 | Revisão sem alerta/decisão verificáveis | RF-ATA-018; CA-36; UX §6 | Fila, obrigação privada, decisão/providência e versão | Corrigido | Idem; regressão E5→E6 |
| E6-03 MAJOR | 6.6 AC-03 | Atualização digitada não era reconsulta | RF-KIT-016 | Ação REST manual reutiliza controles do job | Corrigido | Idem |
| E6-04 MAJOR | 6.5 AC-02 | Destinatários/obrigação genéricos | RF-KIT-007 | Obrigação para Coordenador e ingressante, pendência por destinatário | Corrigido | Idem |
| E6-05 MINOR | 6.6 | Parâmetros auxiliares incompletos | Arquitetura §8.1 | Jitter/circuit breaker/fuso/token/histórico auxiliar | Corrigido | Idem |
| E6-06 MINOR | 6.5 AC-01; 6.7 AC-02 | Comprovante/eventos pouco precisos | UX §7; UX-DR35 | Comprovante disponível, data/hora/cidade/UF/estado | Corrigido | Idem |

**Rastreabilidade transversal por Story (todos os AC-01–04 examinados individualmente):** 6.1 → RNF-03–05/08/09, RB-01/04/07/17, CA-07/36, UX-DR33; 6.2 → RNF-01/03–06/17, RB-07/17, CA-05/22, UX-DR34/49–52; 6.3 → RNF-03–05/08–10, RB-07/09, CA-07/22, UX-DR34; 6.4 → RNF-03–06/09, RB-07/17, CA-07/16, UX-DR21/34/36; 6.5 → RNF-03–06/08–10, RB-07/17, CA-22/36, UX-DR35; 6.6 → RNF-01/03–05/08/09, RB-07/17, CA-16, UX-DR35/36; 6.7 → RNF-03–06/09/17, RB-07/17, CA-16/22/36, UX-DR21/34–36. Preservados gates de canal, finalidade/guarda, cancelamento e recuperação, sem decisão institucional implícita.
