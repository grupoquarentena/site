## Epic 4: Programar avisos e mensagens institucionais com segurança

> **Estado vigente — Epic 4: APROVADO COMO PLANEJAMENTO CONDICIONADO pelo responsável em 23/10/2026.** A aprovação do lote abrange a revisão documental das Stories e ACs, sem homologar as decisões institucionais pendentes, autorizar implementação, dados reais, integrações, deploy ou concluir o passo 03 global. As expressões anteriores “proposta”, “não aprovada”, “apreciação humana” e registros de sessões abaixo documentam o estado **histórico pré-aceite** e não revogam este checkpoint.

> **Fonte canônica global:** [índice de Epics](../epics.md), [PRD](../PRD.md), [Product Brief](../ProductBrief.md), [Arquitetura](../Arquitetura.md) e [UX Design](../UX-Design.md). **Detalhamento exclusivo:** Epic 4 — Stories, critérios e matriz RF → Story → AC.


**Objetivo:** envio mínimo reutilizável, modelos/configurações seguros e avisos por mandato/ocorrência com falhas tratadas. **Estado:** oito propostas, 16 RF principais; sem editor visual de workflows, comandos recebidos, IA, Redis/MinIO obrigatórios ou implementação de outros módulos. PRD RF-SERVIDORES/WHATSAPP/ADMINISTRACAO/AUTOMACOES, UX §8/complementos e Arquitetura §§3/6.7/8/12/síntese 4. CL-01–05 aplicáveis; necessidade de fonte legada correspondente somente para provar equivalência específica, não dependência universal do diagnóstico 3.

<a id="story-4-1"></a>

### Story 4.1: Configurar e testar uma integração sem revelar seus segredos

**As a** CG ou SG vigente,
**I want** configurar endpoint, credencial protegida e estado de uma integração,
**So that** eu possa manter conectividade institucional sem expor segredos ou acessar dados privados de outros serviços.

**Estado:** PROPOSTA. **Dependência direta:** 1.6 (gestão/autoria transitivas); root opcional só após 1.12. **RF:** RF-ADMINISTRACAO-005/008, parcela RF-AUTOMACOES-022. **RNF:** 01/03–05/09/15/17; **RB:** 05/17/18/23; **CA:** 23 parcial; **UX:** DR5/13–14/18–19/42/49/51–52. **Fatia:** uma integração inicial de saída homologada, configuração web/teste técnico sem mensagem de negócio; adaptadores adicionais pelos donos, não todos provedores de uma vez.

**Acceptance Criteria:**

**AC-01.** **Given** gestor com capacidade explícita de configurar integração; **When** manter URL/API/versão/webhook/parâmetros e referência de credencial/estado; **Then** validar contrato tipado e persistir mudança/trilha sem valores secretos, com segredo cifrado ou referência a secret protegido; **And** leitura/lista/edição nunca devolvem segredo existente em claro, HTML/JSON/flash/export/log. Campo vazio não apaga segredo por engano.

**AC-02.** **Given** endpoint alterado ou teste solicitado; **When** validar/conectar pelo servidor; **Then** restringir esquema/hosts/portas/destinos segundo allowlist homologada, inclusive redirects/resolução de endereço e serviços privados explicitamente autorizados; **And** negar SSRF a metadados/loopback/outros serviços, sem tornar indiscriminadamente permitido todo host privado. Não aceitar URL arbitrária de visitante nem executável editável no painel.

**AC-03.** **Given** credencial válida, inválida, revogada ou serviço fora; **When** executar teste técnico autorizado com timeout e resposta minimizada; **Then** mostrar sucesso/falha/indisponibilidade reais sem mensagem de negócio, registrar operador/correlação/teste sanitizado e não afirmar conectividade antes da resposta; **And** sem segredo/versão válida integração fica desabilitada para envio. Contrato do provedor vigente e credenciais/destinos reais são homologação futura, não presumida.

**AC-04.** **Given** rotação/configuração concorrente ou resultado do cofre desconhecido; **When** confirmar; **Then** comparar versão/revalidar autoridade e manter versão anterior comprovadamente íntegra ou integração desabilitada, sem mistura de versões ou sucesso falso; **And** falha de trilha reverte metadados e impede usar segredo não confirmado, sem alegar transação distribuída com cofre. Testar chaves sintéticas/rotação/decifragem e recuperação antes de dados reais.

**AC-05.** **Given** operador sem gestão, mandato expirado, payload de escopo financeiro/Kit ou URL/segredo manipulado; **When** configurar/testar; **Then** negar por Policy/evento próprio e CL-01/02; **And** gerir conectividade não permite consultar payload/dados privados, editar template ou disparar negócio sem capacidade separada. UI aplica CL-04 e mascara erros/segredos.

<a id="story-4-2"></a>

### Story 4.2: Enviar uma mensagem autorizada com resultado rastreável e falhas seguras

**As a** responsável autorizado por uma comunicação institucional,
**I want** enviar uma mensagem mínima a destino permitido e acompanhar seu resultado,
**So that** os avisos possam funcionar sem duplicação acidental ou exposição de dados.

**Estado:** PROPOSTA; transporte reutilizável que pode ser desenvolvido independentemente da agenda/atendimento. A Story 2.5 persiste suas intenções mesmo antes de 4.2; o consumidor explícito é 4.8, desenvolvido após ambos. **Dependências diretas:** 4.1 e 1.6. **RF:** RF-WHATSAPP-009/010; parcelas RF-AUTOMACOES-011/018/022. **RNF:** 01/03–05/08–10/15/17; **RB:** 05/07/09/17/23; **CA:** 23/25 parciais; **UX:** DR13/18–19/42–44/49/51–52. **Fatia:** transporte inicial Evolution autenticado, destino autorizado, modelo mínimo fixo/versionado de teste institucional sanitizado e ação manual restrita para comprovar entrega técnica; outbox/worker mínimo com estados/falhas. 4.3 ampliará edição de modelos, sem bloquear esse primeiro envio. Nenhum parser/webhook de comandos ou engine visual.

**Acceptance Criteria:**

**AC-01.** **Given** integração homologada ativa, dono/capacidade de envio e destino/canal autorizados; **When** confirmar uma mensagem institucional mínima pela ação protegida; **Then** persistir intenção/outbox mínima e trilha na mesma transação com chave única de efeito, dono, origem e correlação; **And** worker só chama provedor após commit, sem contato/corpo privado desnecessários no job/log. Rota de teste não aceita texto/destino livre para contornar sanitização/permissão.

**AC-02.** **Given** intenção pendente e edição/revogação/desativação do remetente, rotina, integração ou destino antes do disparo; **When** worker revalidar; **Then** negar/suspender efeito inelegível, aplicar versão/autoridade de origem e destino válidos e flag persistida; **And** teste de permissão separa editar URL/segredo/template de enviar, não usa usuario fictício para job. Comunicações automáticas usam autorização institucional da rotina vigente, não prolongam poder de um usuário expirado por snapshot de sessão.

**AC-03.** **Given** timeout, 429, erro transitório/permanente ou reinício após envio antes do registro da resposta; **When** processar/retomar; **Then** registrar falha/pendência/resultado desconhecido com rate limit, backoff e política de retry homologados; **And** deduplicar efeito no PostgreSQL entre dois workers/replays. Quando provedor não garantir dedup/consulta do resultado, incerteza exige reconciliação/intervenção sem retry cego ou promessa de exactly-once externo; erro permanente não roda indefinidamente.

**AC-04.** **Given** resposta do provedor; **When** registrar resultado; **Then** distinguir aceitação técnica de entrega confirmada quando houver suporte real, com ID externo mínimo e estado/instantes/tentativas/correlação; **And** logs de envio automatizado não guardam mensagem integral, segredo ou contato em claro, sucesso técnico não afirma leitura/recebimento humano. Falha externa nunca reverte negócio de origem já confirmado.

**AC-05.** **Given** destinatário privado de servidor autorizado ou grupo permitido; **When** enviar/testar; **Then** exigir autorização/consentimento aplicável e manter grupo sem contatos pessoais/PII Kit/identidade Zoom, com negativos de destino indevido/mandato expirado/ID manipulado; **And** CL-04 mostra pendência/falha/sucesso com instrução de intervenção, sem expor dado privado na lista. Parâmetros 03/08/13, worker/locks/contrato real precedem habilitação.

<a id="story-4-3"></a>

### Story 4.3: Versionar modelos de mensagem e textos de comandos com prévia segura

**As a** responsável autorizado pelo conteúdo institucional,
**I want** editar e conferir modelos e textos parametrizados permitidos,
**So that** a próxima comunicação use conteúdo aprovado sem alterar código nem divulgar informação indevida.

**Estado:** PROPOSTA. **Dependências diretas:** 4.2, 1.6. **RF:** RF-ADMINISTRACAO-004; parcelas RF-AUTOMACOES-018/022. **RNF:** 03–06/09/10/13/17; **RB:** 07/09/10/17/23; **CA:** 25 parcial; **UX:** DR13–14/18/43–44/49/51–52. **Fatia:** catálogo de modelos das ações já entregues, versões/prévia/teste/aprovação do conteúdo, não editor visual nem implementação de comandos do Epic 12.

**Acceptance Criteria:**

**AC-01.** **Given** responsável com escopo de editar modelo e ação existente; **When** alterar texto e variáveis da allowlist, conferir prévia e confirmar aprovação da versão conforme autoridade homologada; **Then** persistir versão imutável anterior/autor/instante/trilha e indicar a versão vigente para próximos envios; **And** não impor segunda aprovação universal sem regra institucional, nem permitir que teste/prévia dispare produção implicitamente.

**AC-02.** **Given** variável desconhecida, HTML/script/código/PHP/SQL/shell ou tentativa de inserir contato/segredo/campo restrito; **When** salvar/renderizar; **Then** rejeitar expressão não permitida e sanitizar saída segundo canal/audiência; **And** preview usa fixture sintética ou dados mínimos autorizados, sem resolver PII de módulo privado por variável.

**AC-03.** **Given** texto retornável por comando WhatsApp e catálogo de textos institucionalmente autorizado; **When** editar/pré-visualizar o texto quando aplicável; **Then** guardar versão e demonstrar sua resolução pelo contrato de conteúdo sem receber/executar comandos inexistentes; **And** handlers em 12 usarão a mesma fonte, sem copiar textos autoritativos. RF-ADMINISTRACAO-004 tem configuração planejada aqui e prova no consumidor quando comando existir; !kit não é reintroduzido.

**AC-04.** **Given** mensagem já expedida e nova versão do modelo; **When** consultar histórico/enfileirar próxima comunicação; **Then** preservar versão/texto autorizado da anterior no armazenamento protegido mínimo e usar versão aprovada nas novas; **And** política de mensagem já pendente versus nova versão é explícita e testada antes de habilitar, sem alterar retroativamente evidência nem reenvio automático. Retenção não autoriza corpo integral no log técnico.

**AC-05.** **Given** duas edições, perda de autoridade ou teste de envio sem capacidade/destino; **When** salvar/aprovar/testar; **Then** aplicar CL-01/02/05, recusar versão obsoleta e teste externo não autorizado; **And** UI CL-04 diferencia prévia, teste restrito e envio real, sem sucesso falso. Autoridade editorial/variáveis/canais são parâmetros a homologar, não lacuna preenchida por suposição.

<a id="story-4-4"></a>

### Story 4.4: Programar lembretes de mandato sem prolongar acessos ou expor contatos

**As a** CG ou SG vigente,
**I want** configurar avisos de término e conclusão de serviço,
**So that** servidores e grupo autorizado recebam lembretes úteis sem duplicação nem alertas indevidos.

**Estado:** PROPOSTA. **Dependências diretas:** 1.6/1.7/1.8, 4.2/4.3; sem agenda/atendimento/Epic 3. **RF:** RF-SERVIDORES-010/011, RF-AUTOMACOES-007; parcelas 011/018/022. **RNF:** 03–05/09/10/13/15; **RB:** 05/11/13/16/17/23; **CA:** 08/19/25/26/33 parciais; **UX:** DR18–19/40/43–44/49/51–52. **Fatia:** rotina específica de avisos com tela mínima de habilitação/configuração, reutilizando transporte e transições de 1, não engine genérica/destituição por inferência.

**Acceptance Criteria:**

**AC-01.** **Given** mandato com término homologado e rotina habilitada; **When** calcular marcos configuráveis 90/60/30/15/7/3/2/1 dias e conclusão conforme D2; **Then** programar intenção distinta por designação/marco/destino usando calendário America/Bahia e UTC, sem duplicação em scheduler/retry concorrente; **And** permitir habilitar/desabilitar marcos/agenda/modelo/destinos permitidos, sem converter meses/ano civil em duração arbitrária.

**AC-02.** **Given** servidor com contato operacional autorizado, distinto do contato Kit; **When** marco vencer; **Then** enviar privadamente ao titular autorizado e, se autorizado, aviso sanitizado ao grupo de serviço, nunca contato/fundamento íntimo; **And** contato ausente/consentimento retirado é pendência explícita, não consulta ao Kit ou envio a terceiro.

**AC-03.** **Given** mandato entregue/destituído/expirado, regra desabilitada, término alterado ou titular substituído após agendar; **When** revalidar antes de disparar; **Then** impedir lembrete obsoleto e preservar trilha/estado da intenção; **And** conclusão natural pode gerar seu evento próprio único quando legítimo, distinto de lembrete pré-término indevido, nunca após entrega/destituição por suposição. Política de atraso/reagendamento/conclusão precisa ser homologada e testada, sem avalanche retroativa.

**AC-04.** **Given** dois schedulers/workers, repetição de conclusão ou falha no provedor/trilha; **When** executar/reprocessar; **Then** aplicar CL-02/05 e chave de efeito por marco/fato de origem, preservando conclusão e sem duplicar avisos; **And** scheduler parado jamais mantém capacidade expirada, cuja negação já é de 1, nem envio de mensagem modifica mandato.

**AC-05.** **Given** gestor não autorizado ou celular com rotina vazia/falha; **When** configurar/conferir execuções; **Then** negar alterações indevidas e mostrar última/próxima execução, estado/pendência sanitizados conforme CL-04; **And** equivalência servico-terminio exige evidência da rotina ativa correspondente e aceite próprio, não cópia insegura nem desligamento antes de 15.

<a id="story-4-5"></a>

### Story 4.5: Divulgar cada ocorrência no horário configurado

**As a** responsável pela divulgação,
**I want** programar aviso derivado de cada ocorrência da agenda,
**So that** reuniões em horários diferentes recebam lembretes próprios e atualizados.

**Estado:** PROPOSTA. **Dependências diretas:** 2.2 e 4.2/4.3; sem dupla/ata/atendimento. **RF:** RF-AUTOMACOES-010/019; parcelas 018/022. **RNF:** 03–07/09/10/13/15; **RB:** 09/10/13/17/23; **CA:** 25/31; **UX:** DR16/18–19/43–44/49/51–52. **Fatia:** aviso por ocorrência, não outra agenda nem n8n/editor.

**Acceptance Criteria:**

**AC-01.** **Given** rotina habilitada e duas ocorrências no mesmo dia; **When** calcular divulgação; **Then** gerar dois disparos independentes 15 minutos antes de cada início por padrão, salvo antecedência configurada pelo autorizado; **And** usar instantes/IDs de 2.2 e fuso America/Bahia, inclusive antecedência que atravesse dia/mês, não cron global fixo.

**AC-02.** **Given** mudança/cancelamento da ocorrência, destino/integrador desativado ou flag desligada com mensagem já pendente; **When** chegar ao disparo; **Then** revalidar fonte/versão/flag/destino e não enviar aviso obsoleto; **And** reagendamento e atraso obedecem política homologada sem mensagem duplicada para a mesma consequência/ocorrência, nem envio automático de acervo atrasado ao reiniciar.

**AC-03.** **Given** conteúdo autorizado de dia/horário/tipo/link; **When** renderizar e enviar aviso; **Then** usar modelo vigente/aprovado e só dados publicáveis da ocorrência, sem senha administrativa, lista Zoom ou contatos; **And** indisponibilidade Zoom/IA não muda agenda nem obriga consulta externa para compor texto.

**AC-04.** **Given** execução concorrente/replay/falha externa; **When** reprocessar; **Then** 4.2/CL-05 tratam chave única por ocorrência/efeito/destino, rate limit/retry/incerteza e estados honestos; **And** erro de mensagem não cancela reunião nem reverte configuração confirmada.

**AC-05.** **Given** gestor autorizado ou operador sem escopo; **When** editar antecedência/modelo/destino ou consultar próxima execução; **Then** aplicar capacidades separadas/trilha/revisão e UX CL-04, negando manipulação direta de IDs; **And** confirmar agenda/canais e equivalência n8n correspondente antes de ativação/cutover, sem depender da conclusão de 3 inteiro.

<a id="story-4-6"></a>

### Story 4.6: Diagnosticar execuções e integrações sem acessar conteúdo privado

**As a** CG ou SG vigente,
**I want** consultar status de jobs, falhas e serviços opcionais habilitados,
**So that** possa encaminhar incidentes sem obter PII de Kit ou Tesouraria.

**Estado:** PROPOSTA. **Dependência direta:** 4.2; produtores 4.4/4.5 ampliam a lista quando disponíveis, não bloqueiam diagnóstico inicial. **RF:** RF-ADMINISTRACAO-006/007/010; complemento RF-WHATSAPP-009/010. **RNF:** 01/03–06/09/13/15/17; **RB:** 07/17/18/23; **CA:** 23/25 parciais; **UX:** DR17–19/42–43/49/51–52. **Fatia:** consulta/painel mínimo de operação e intervenção autorizada no transporte existente, não implantar Redis/MinIO nem jobs de backup/rastreio futuros.

**Acceptance Criteria:**

**AC-01.** **Given** jobs existentes com execução/heartbeat; **When** consultar rotinas/integradores; **Then** mostrar estado ativo/desligado, última/próxima execução quando aplicável, fila/tentativas/correlação e falha sanitizada, distinguindo não instalado/desconhecido de saudável; **And** incluir avisos/rastreio/backup conforme seus produtores se tornem disponíveis, sem entrada fictícia de sucesso. RF-ADMINISTRACAO-006 continua transversal/parcial até seus jobs exigidos estarem integrados.

**AC-02.** **Given** CG/SG vigente ou root pessoalmente elevado disponível; **When** ler logs técnicos; **Then** exibir apenas metadados/erros redigidos autorizados sem corpo de mensagem, credenciais, contato/Kit/pagador; **And** permissão de diagnóstico não permite abrir fila privada nem payload falho bruto. Anônimo/outro encargo/ID manipulado e cache público não retornam logs; leitura sensível quando houver segue CL-02.

**AC-03.** **Given** falha reprocessável de efeito permitido e operador com capacidade separada; **When** solicitar intervenção/retry; **Then** auditar solicitação e reutilizar chave/estado da intenção de 4.2 com revalidação, sem criar segundo envio; **And** resultado desconhecido exige reconciliação da evidência antes de novo efeito, sem botão genérico de reenviar qualquer payload.

**AC-04.** **Given** Redis/MinIO não habilitados; **When** operar diagnóstico e envio; **Then** não exigir esses serviços nem simular falha por ausência. **Given** necessidade medida, decisão e configuração homologadas para habilitá-los; **When** integrar diagnóstico; **Then** exibir configuração não secreta/estado/falha reais sem expor credenciais, preservando PostgreSQL autoritativo e contingência; **And** implantação/migração desses serviços exige autorização própria, não decorre da Story. Testar estados com adaptador sintético não equivale a implantar serviço opcional.

**AC-05.** **Given** lista vazia/serviço fora/filtro inválido/erro de consulta e uso móvel; **When** navegar; **Then** aplicar CL-04 com filtro/paginação e orientação curta de falha/retry, sem transformar falta de observação em zero ou sucesso; **And** política de retenção/minimização e evidência de falha/recuperação são homologadas antes do uso real.

<a id="story-4-7"></a>

### Story 4.7: Administrar configurações e rotinas segundo dono e ação permitida

**As a** responsável vigente por um módulo,
**I want** configurar somente as rotinas e ações autorizadas ao meu encargo,
**So that** autonomia de comunicação não rompa a segregação do Kit e da Tesouraria.

**Estado:** PROPOSTA. **Dependências diretas:** 1.6 e 4.2/4.3; 4.4/4.5 agregam ações comuns existentes quando disponíveis. **RF:** RF-AUTOMACOES-018/022; parcela RF-AUTOMACOES-011. **RNF:** 03–06/09/10/13/15/17; **RB:** 05/07/16–18/23; **CA:** 05/23/25 parciais; **UX:** DR5/18/43–44/49/51–52 (DR59 é editor de 12, não este incremento). **Fatia:** controle por escopo/ação e tela sobre catálogo real mínimo, com testes de contrato financeiro/Kit sintéticos sem criar seus handlers/entidades.

**Acceptance Criteria:**

**AC-01.** **Given** CG/SG vigente, Tesoureiro ou Coordenador Kit; **When** resolver acesso às rotinas; **Then** CG/SG gerem comuns, Tesoureiro específicas financeiras e Coordenador Kit específicas Kit, sem herança ordinária pelos administradores; **And** configurar API geral/URL/segredo, editar modelo/destino/agenda, habilitar e disparar são permissões separadas verificadas no servidor, com nenhuma delas concedida só por outra. Não pressupor delegação D6 homologada; só aplicá-la se aprovada/disponível e sem romper exclusividades.

**AC-02.** **Given** rotina existente dentro do escopo permitido; **When** autorizado manter ativação/desativação, agenda/intervalo, destino e modelo; **Then** versionar/trilhar e validar parâmetros/consentimento/audiência, com prévia/efeitos claros e flag revalidada também em trabalho já pendente; **And** campos/payload não aceitam código nem dono_modulo arbitrário para migrar escopo.

**AC-03.** **Given** usuário expirado, de outro módulo ou CG/SG em modo ordinário; **When** manipular ID para template/destino/envio privado Kit/financeiro; **Then** negar sem dados/efeito, inclusive em preview/teste/log/retry; **And** testes de matriz sintética comprovam contrato, mas cobertura integral 018/022 nos módulos privados exige integração pelos donos 6/7 quando existirem. Não oferecer ação falsa para alegar completude.

**AC-04.** **Given** root de 1.12 aprovado/disponível; **When** operar excepcionalmente em escopo restrito; **Then** permitir com autoria/motivo/modo/TTL e trilha reforçados; **And** saída/expiração remove exceção sem alterar dono ou matriz ordinária. Antes dessa disponibilidade não existe bypass root nem bloqueio do caminho ordinário.

**AC-05.** **Given** alteração concorrente/revogação/falha da trilha e rotina pendente; **When** salvar/enviar; **Then** CL-01/02/05 garantem revalidação de estado/autoridade, rollback de negócio e cancelamento/suspensão do efeito inelegível; **And** UI CL-04 expõe somente catálogo funcional e dono/escopo, sem editor composto de 12 ou obrigação de concluir módulos inteiros para enviar aviso comum.

<a id="story-4-8"></a>

### Story 4.8: Notificar privadamente CG e SG de cada protocolo sem expor seu relato

**Como** CG ou SG vigente,
**quero** receber aviso privado verificável quando um pedido geral for registrado,
**para** iniciar a triagem sem que informações íntimas ou do Kit sejam distribuídas a administradores.

**Estado:** PROPOSTA REVISADA DOCUMENTALMENTE, sem teste real nem aprovação. **Dependências diretas:** 2.5 (evento e duas obrigações lógicas) e 4.2 (transporte/destinos/estados), com autoridades de 1.6; a triagem de 2.6 pode existir antes desta integração. **RF consumidor:** RF-ATENDIMENTO-001, cujo responsável principal continua Epic 2; contribuições RF-WHATSAPP-009/010 e RF-AUTOMACOES-011/018/022 do Epic 4. **RNF:** 01/03–05/08–10/15/17; **RB:** 07/10/13/17/23; **CA:** 30/37 dependem do efeito real; **UX:** DR19/43/49/51–52/56–57. **Fatia:** consumidor restrito do evento existente, sem segunda caixa de atendimento, engine de envio ou acesso à mensagem original. **Gate:** canal/destino/autorização/PII por classe, caso de vacância e sobreposição CG/SG homologados antes de habilitar.

**Acceptance Criteria:**

**AC-01.** **Given** protocolo confirmado na Story 2.5 e envelope de evento com obrigação lógica para CG e SG; **When** o consumidor 4.8 processar o evento após commit; **Then** resolver individualmente titulares/contas/canais privados atualmente elegíveis de ambos os encargos, gerar efeito por `evento_id + papel + destino aprovado` e rastrear cada um separadamente; **And** não depender de cópia congelada do telefone, de dados de Kit ou de simples existência de conta sem mandato vigente. Mesmo indivíduo em dois encargos não apaga nenhuma obrigação lógica: regra de deduplicação física entre papéis é decisão operacional expressa.

**AC-02.** **Given** solicitação genérica ou texto livre contendo telefone, endereço, segredo Zoom, dado financeiro ou detalhe Kit; **When** renderizar aviso; **Then** usar somente protocolo, categoria de catálogo, motivo institucional genérico e ligação autorizada à caixa restrita; **And** jamais incluir assunto/corpo/contato originais nem projeção ainda não saneada, mesmo em push, logs, retries e tela de diagnóstico. CG/SG podem consultar apenas a projeção sanitizada da Story 2.6, após o gate E2-K.

**AC-03.** **Given** provedor aceita, entrega comprovadamente, falha, fica incerto ou está desabilitado; **When** tentar/reprocessar; **Then** registrar resultado próprio por papel/destino, tentativa/correlação e estado honesto conforme 4.2/CL-05; **And** HTTP de aceite não é prova de entrega humana, resultado incerto não permite retry cego e a solicitação/protocolo permanecem mesmo se o transporte falhar.

**AC-04.** **Given** repetição de evento, dois workers, alteração/revogação de mandato, destino antigo ou evento fora de ordem; **When** processar; **Then** unicidade/idempotência e revalidação atual impedem aviso duplicado indevido ou envio a ex-titular; **And** não descartar obrigação não atendida nem redirecioná-la a grupo público ou operador sem Policy. A obrigação lógica única por `evento_id + papel` não é reaberta automaticamente se já satisfeita segundo a evidência de resultado homologada, mesmo com novo titular/canal/destino. Redirecionar pendência preserva tentativas e histórico; resultado incerto exige reconciliação antes de outro disparo. Novo aviso de obrigação já satisfeita exige ação/regra expressamente autorizada e novo efeito correlacionado; a chave física de AC-01 não substitui essa barreira lógica. Testar replay após troca de destino e os dois papéis do mesmo titular, sem presumir entrega por HTTP 200.

**AC-05.** **Given** CG ou SG sem titular elegível, destinatário sem canal homologado, mesma pessoa acumulando os encargos ou política de guarda pendente; **When** resolver audiência; **Then** manter pendência privada por papel, mostrar motivo sanitizado ao operador legitimado e admitir procedimento institucional de contingência somente quando homologado; **And** não fabricar entrega, destinatário, permissão Kit nem concluir integralmente RF-ATENDIMENTO-001 antes do ensaio das duas notificações efetivas sob cenário autorizado.

**AC-06.** **Given** fluxo habilitado com destinos reais e prova de envio disponível; **When** conferir atendimento ponta a ponta; **Then** demonstrar protocolo sem alvo/contato, persistência e envio privado autorizado a **ambos** CG e SG, preservação do sigilo e ligação à triagem 2.6; **And** teste sintético/mocks comprova contrato apenas, não substitui aceite de integração/canal/entrega real quando exigido pelo PRD.

### Rastreabilidade proposta do Epic 4

| RF principal | Story / AC | Limite de aceite |
|---|---|---|
| RF-SERVIDORES-010 | 4.4 AC-01–05 | Marcos configuráveis e conclusão, D2/política de atraso. |
| RF-SERVIDORES-011 | 4.4 AC-02/03/05; 4.2 AC-02/05 | Privado autorizado, grupo sem contato; revalidar desligamento. |
| RF-WHATSAPP-009 | 4.2 AC-01/03/04; 4.6 AC-01/02 | Log mínimo de envios, não corpo integral. |
| RF-WHATSAPP-010 | 4.2 AC-03/04; 4.6 AC-03 | Rate limit, falha/retry/incerteza explícitos. |
| RF-ADMINISTRACAO-004 | 4.3 AC-01–05 | Texto configurável aqui, handler efetivo em 12. |
| RF-ADMINISTRACAO-005 | 4.1 AC-01–05 | Segredo protegido e estado de integração. |
| RF-ADMINISTRACAO-006 | 4.6 AC-01/05 | Integra produtores reais; backup/rastreio futuros mantêm parcialidade. |
| RF-ADMINISTRACAO-007 | 4.6 AC-02/03/05 | Logs técnicos sem PII, sem acesso a dados por herança. |
| RF-ADMINISTRACAO-008 | 4.1 AC-01–05; 4.7 AC-01/04 | Configuração/teste auditados e root condicional. |
| RF-ADMINISTRACAO-010 | 4.6 AC-04/05 | Serviços opcionais só por necessidade medida/habilitação própria. |
| RF-AUTOMACOES-007 | 4.4 AC-01–05 | Equivalência da rotina de término, sem desligar legado aqui. |
| RF-AUTOMACOES-010 | 4.5 AC-01–05 | Avisos de agenda com paridade específica. |
| RF-AUTOMACOES-011 | 4.2 AC-01–05; 4.4 AC-02; 4.7 AC-01/02 | Mensagens individuais/grupo autorizadas, consentimento/limites/histórico. |
| RF-AUTOMACOES-018 | 4.4 AC-01; 4.5 AC-05; 4.7 AC-01–05 | Contrato e rotinas comuns; específicas integram donos 6/7. |
| RF-AUTOMACOES-019 | 4.5 AC-01–05 | Dois horários/disparos, default 15 min configurável. |
| RF-AUTOMACOES-022 | 4.1 AC-05; 4.2 AC-02; 4.7 AC-01–05 | Separação por ação/encargo, consumidores privados completam. |

**Consumidor transversal obrigatório, sem mudar ownership:** `RF-ATENDIMENTO-001` (Epic 2) → `2.5 AC-02/03`, `4.8 AC-01–06`, `2.6 AC-01/04/05`. A recepção/protocolo continua parcial até prova do aviso privado efetivo para ambos CG e SG; a Story 4.8 não adiciona décimo sétimo RF principal ao Epic 4.

### Gate global do Epic 4 — avaliação documental para revisão externa

**Situação: PROPOSTAS COMPLETAS, NÃO APROVADAS.** Oito Stories, 16 RF principais com rastreabilidade e integração transversal ao Fale Conosco. A conferência editorial corrigiu a falsa dependência 4.2→2.5: o fluxo é 2.5 (intenção durável) + 4.2 (transporte independente) → 4.8 (avisos efetivos), sem bloqueio retroativo do núcleo de atendimento. Prontidão para envio real depende do canal/autoridade/contatos, contrato do provedor, worker/locks, flags, consentimento e política por classe homologados. Logs e diagnósticos são minimizados; as rotinas específicas Kit/Tesouraria serão conferidas em consumidores futuros. Exigir revisão BMAD adversarial independente no repositório antes de aprovação; esta edição não executou subagentes nem testes de runtime.
