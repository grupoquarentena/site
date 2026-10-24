## Epic 12: Consultar os serviços do grupo por comandos WhatsApp autorizados
> **Estado vigente — APROVADO COMO PLANEJAMENTO CONDICIONADO em 24/10/2026.** Aceite humano expresso após auditoria/revalidação. Stories e AC abaixo preservados; expressões de proposta/revisão/aceite pendente são proveniência histórica. Gates institucionais e operacionais continuam abertos; sem autorização de build, dados reais, sprint ou deploy. Conclusão documental não encerra formalmente os passos 03/04 nem comprova readiness.

> **Fontes canônicas:** [índice global](../epics.md) · [PRD](../PRD.md) · [Product Brief](../ProductBrief.md) · [Arquitetura](../Arquitetura.md) · [UX Design](../UX-Design.md). **Detalhamento exclusivo deste Epic:** Stories, critérios de aceite, gates e matriz RF → Story → AC.

## Escopo aprovado no passo 02
**Objetivo:** membros/servidores recebem respostas atuais por comandos do próprio grupo, respeitando audiência, contexto e capacidade.

**Resultado verificável:** webhook Evolution autenticado/deduplicado, parser/allowlist por grupo, catálogo de serviço/membros, rate limit, histórico mínimo e consultas !vago/!servidores/!saldo/!ata/!resumo/!relatorio aos dados reais. !participantes só agregado; !senha restrito/auditado; !kit não possui handler.

**RFs responsáveis (15):** RF-WHATSAPP-001–008; RF-SERVIDORES-013–014; RF-TESOURARIA-015; RF-AUTOMACOES-009, RF-AUTOMACOES-016–017, RF-AUTOMACOES-024.

**Dependências — início:** transporte mínimo de 4, identidade de origem/grupo/autorização e trilha necessárias de 1; sem exigir oito Epics concluídos. **Por capacidade:** webhook/parser/rate limit e comandos informativos com conteúdo aprovado podem funcionar primeiro; !vago/!servidores exigem mandatos/escala 1/2; !ata/!resumo exigem consulta oficial de 5; !saldo exige livro/projeção de 7; !relatorio exige relatório pertinente de 11; !participantes/!senha exigem fonte e autorização próprias, sem antecipar dados nem segredos. **Incremental:** RF-AUTOMACOES-016–017 acrescentam editor visual básico/composição e gestão integrada usando transporte/flags/templates existentes de 4; só ações previamente implementadas entram no catálogo. **Produção:** parâmetro 08, decisão sobre segredo Zoom/audiência, origem/grupo, replay/rate limit e permissão por comando comprovados. Não aceitar o catálogo completo só porque !ajuda funciona.

**Limites:** reutilizar provedor de saída/templates/flags/filas de 4; o editor é básico e declarativo conforme RF-AUTOMACOES-017, não um editor n8n avançado nem código livre. Primeiro homologar canal/handlers simples, depois compor ações já verificáveis. Comando sem produtor ainda não integra o catálogo funcional entregue: responder indisponível não conta como RF atendido. As Stories futuras serão ordenadas por produtores antes dos consumidores; se o produtor não estiver entregue, adiar a Story correspondente, sem criar dependência de Story futura. Kit/financeiro mantêm exclusividade.
## Contratos de elaboração e revisão
- **Fonte e estado:** o PRD rege texto integral dos RF; o índice rege a titularidade do Epic e os contratos compartilhados. Uma referência de RF não comprova sua implementação nem aceite ponta a ponta.
- **Dependências por capacidade:** cada Story usa somente baseline ou produtores anteriores mencionados. Referências externas para integração, dados reais ou consumidores posteriores são *gates de aceite/ativação*, não autorização para antecipar tabelas, criar rotas falsas ou bloquear uma capacidade manual já utilizável.
- **Segurança e governança:** capacidades pelo mandato vigente/Policy no backend e na tela; exceção root somente por sessão pessoal elevada, justificada, temporal e auditada. Evento/alteração/auditoria e intenção outbox dentro da mesma transação quando aplicáveis, nunca chamadas HTTP externas antes do commit.
- **Dados e precisão:** preservar NULL distinto de zero; datas civis em `America/Bahia`, instantes em UTC; valores monetários decimais; filtros e exports usam mesma Policy. Não inferir pessoas a partir do Zoom nem mesclar homônimos.
- **Validação posterior:** testes com fixtures sintéticas, cenários positivos/negativos, sessão dormente, falhas, repetição, concorrência, PostgreSQL real, classificação de dados, mobile/teclado/leitor e comportamento degradado pertinentes. Contrato técnico de provedor exige homologação real, não apenas mock.
- **Limite da rodada:** não executar fluxo instalado, migrations, testes Laravel, comandos de importação, integração real, sprint, commit ou deploy. A revisão independente e correção adversarial ficam para o OpenCode no checkpoint humano.

## Stories propostas

<a id="story-12-1"></a>
### Story 12.1: Receber webhook Evolution e identificar audiência válida

**Como** gestor de comunicação,

**quero** receber mensagens de grupos autorizados com origem verificável,

**para** evitar comandos falsos e vazamento entre grupos.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 4.1/4.2 transporte e integração; origem/allowlist de grupo homologadas. **RF principais:** `RF-WHATSAPP-001`, `RF-WHATSAPP-002`, `RF-WHATSAPP-007`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Webhook de entrada não reimplementa engine de saída de 4.2.

**Acceptance Criteria:**

**AC-01.** **Given** webhook Evolution autenticado, canal/grupo/instância reconhecidos; **When** receber mensagem; **Then** persistir inbox durável minimizada com evento único, origem/correlação/instante e grupo serviço/membros/outro antes do ACK ao provedor. Falha de persistência não confirma recepção; evento commitado não despachado após queda/erro de enqueue é recuperado sem depender de reentrega externa, com o mesmo efeito idempotente.

**AC-02.** **Given** origem falsa, instância desconhecida, replay ou grupo não permitido; **When** postar webhook; **Then** rejeitar sem executar comando nem enviar resposta indiscriminada.

**AC-03.** **Given** mensagem de grupo com PII ou segredo; **When** registrar log de recepção; **Then** guardar somente metadados mínimos autorizados, sem texto integral/contato pessoal em log genérico.

**AC-04.** **Given** provedor fora ou entrega repetida; **When** retomar worker; **Then** deduplicar evento por identidade confiável e tornar falha observável sem perder integridade.

**Evidência futura e gate humano:** Parâmetro 08, política de logs, grupo/instância e autenticação do provedor antes de ativar. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-12-2"></a>
### Story 12.2: Interpretar comandos e bloquear abuso por grupo

**Como** responsável de comunicação,

**quero** executar só comandos e argumentos permitidos,

**para** responder com segurança no contexto correto.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 12.1; transporte 4.2. **RF principais:** `RF-WHATSAPP-003`, `RF-WHATSAPP-008`, `RF-AUTOMACOES-009`, `RF-AUTOMACOES-024`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não criar segundo bot n8n; migrar Robô por handlers Laravel e critérios de fonte ativa.

**Acceptance Criteria:**

**AC-01.** **Given** mensagem legítima começa por ! e comando permitido no grupo; **When** processar parser normalizado; **Then** resolver handler autorizado, validar parâmetros e enfileirar uma resposta via outbox existente.

**AC-02.** **Given** mesma mensagem, flood ou sender sem poder de comando restrito; **When** processar repetidamente; **Then** deduplicar efeito, limitar taxa por origem/grupo/ação e auditar tentativa sem ecoar segredo.

**AC-03.** **Given** comando desconhecido, argumento malformado ou grupo incorreto; **When** consultar; **Then** fornecer ajuda limitada ao grupo ou negar, sem consulta a módulos sensíveis.

**AC-04.** **Given** texto parecido com comando, script ou payload malicioso; **When** processar; **Then** tratar como dado, nunca executar PHP/SQL/shell nem confiar em papel declarado na mensagem.

**Evidência futura e gate humano:** Autoridade e limites de frequência por grupo sujeitos a homologação. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-12-3"></a>
### Story 12.3: Servir catálogo informativo do grupo de serviço

**Como** servidor no grupo autorizado,

**quero** consultar ajuda e informações de serviço disponíveis,

**para** obter instruções e quadro sem Planilhas/Docs autoritativos.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 12.2; 1.6/2.3 dados reais de serviços. **RF principais:** `RF-WHATSAPP-004`, `RF-SERVIDORES-013`, `RF-SERVIDORES-014`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites de entrega/aceite:** esta Story entrega somente comandos informativos e consultas de serviço cujos produtores já existem, sem depender de 12.5/12.6. RF-WHATSAPP-004 é composto: catálogo completo só é aceito depois dos comandos dinâmicos de 12.5 e restritos de 12.6; listar em ajuda, responder indisponível ou testar mock não conclui esses comandos. Nenhuma Story anterior exige a conclusão futura do RF composto para funcionar.

**Acceptance Criteria:**

**AC-01.** **Given** mensagem autenticada no grupo de serviço; **When** executar `!ajuda`, `!vago`, `!servidores`, `!coletiva`, `!diretriz`, `!link`, `!gbatepapo`, `!gingresso`, `!script`, `!setima`, `!telas`, `!tematica` ou `!textos`; **Then** ajuda informa comandos efetivamente habilitados; vagas/servidores consultam PostgreSQL por vigência; os demais retornam respectivamente conteúdo/decisão coletiva, diretriz, link de reunião, grupos institucionais, roteiro, referência de contribuição, telas, programação temática e textos homologados da fonte versionada de 2.1/4.3. Cada comando tem teste positivo de conteúdo vigente e negativo de audiência/link/versão; reformulação exige destino funcional e aceite explícitos no catálogo operacional, não simples supressão. `!script` retorna orientação, jamais executa código. Não antecipar consultas de 12.5/12.6.

**AC-02.** **Given** dados de serviço desatualizados ou mandato expirou; **When** consultar !vago/!servidores; **Then** não usar cópia Google Docs nem scheduler como fonte; indicar ausência corretamente.

**AC-03.** **Given** pedido !kit ou comando de outro grupo; **When** consultar; **Then** não haver handler !kit e rejeitar audiência não autorizada.

**AC-04.** **Given** comando de serviço com dados privados ou excesso de campos; **When** gerar resposta; **Then** sanitizar nomes/dados conforme público autorizado e jamais listar contatos ou justificativas.

**Evidência futura e gate humano:** Confirmar textos/links/autoridade dos comandos 08; produtores específicos podem ficar gated. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-12-4"></a>
### Story 12.4: Servir catálogo informativo para membros sem dados internos

**Como** membro em grupo autorizado,

**quero** consultar links e mensagens institucionais permitidas,

**para** receber apenas informações destinadas a esse público.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 12.2, conteúdo editorial autorizado em 2.1/4.3 e consulta de servidores já entregue em 12.3. **RF principais:** `RF-WHATSAPP-005`. **Fatia vertical:** catálogo informativo de membros e relação publicável de servidores; saldo/relatórios são integrações posteriores de 12.5, sem bloquear esta entrega ou contar como concluídos aqui.

**Limites:** Não inventar grupos ou links ainda não homologados; catálogo literal consta no PRD.

**Acceptance Criteria:**

**AC-01.** **Given** mensagem no grupo de membros autorizado; **When** executar `!ajuda`, `!coletiva`, `!diretriz`, `!setima`, `!servidores`, `!link_cpsanto`, `!link_quarentena`, `!link_novaera`, `!zap_cpsanto`, `!zap_novaera` ou `!zap_quarentena`; **Then** retornar respectivamente ajuda habilitada, conteúdo/decisão coletiva, diretriz, referência institucional de contribuição, relação de servidores publicável e os links/grupos institucionais exatos da allowlist versionada. Reutilizar fonte/consulta autorizada de serviço, sem contatos pessoais ou justificativas. Testar cada comando literal, audiência e conteúdo/reformulação homologados; `*` não é wildcard de autorização. RF-WHATSAPP-005 permanece parcial até `!relatorio`/`!saldo` funcionarem em 12.5.

**AC-02.** **Given** comando do grupo de serviço não permitido a membros; **When** enviar no grupo público; **Then** negar ou oferecer alternativa permitida sem dados internos.

**AC-03.** **Given** destino/link foi despublicado ou alterado; **When** consultar novamente; **Then** usar configuração vigente, bloqueando link inválido/redirecionamento malicioso.

**AC-04.** **Given** mensagem repetida, destino offline ou rate limit; **When** responder; **Then** usar transporte 4.2 idempotente e registrar falha/pendência sem duplicar.

**Evidência futura e gate humano:** Parâmetro 08 e diretrizes públicas do grupo necessários à ativação. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-12-5"></a>
### Story 12.5: Consultar ata, resumo, saldo e relatórios autorizados por comando

**Como** membro ou servidor com acesso ao resultado,

**quero** receber consultas atuais somente das projeções disponíveis,

**para** evitar respostas fictícias ou leitura indevida.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 12.2; 5.5/5.8 atas, 7.6 saldo público, 11 relatórios conforme capacidade. **RF principais:** `RF-WHATSAPP-006`, `RF-TESOURARIA-015`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** !ata e !resumo não publicam rascunho ou ata não liberada; sem acesso financeiro ordinário ao bot.

**Acceptance Criteria:**

**AC-01.** **Given** produtor real disponível e audiência homologada; **When** executar `!ata`/`!resumo` no serviço, ou `!saldo`/`!relatorio` no serviço e nos membros conforme catálogo; **Then** reusar projeção oficial sanitizada e responder conteúdo/link autorizado com data/escopo, sem reprocessar escrita da origem. Testar positivamente cada comando/grupo e todos os produtores exigidos para seu aceite, sem encerrar Story/RF integralmente por responder indisponível. Nomes mínimos e resumos respeitam liberação/opt-out e gates editoriais da fonte.

**AC-02.** **Given** dados não implementados, retidos ou fonte sem publicação; **When** tentar consultar; **Then** não marcar requisito completo por indisponibilidade; indicar recurso não habilitado sem expor conteúdo restrito.

**AC-03.** **Given** solicitação !saldo no grupo apropriado; **When** consultar livro; **Then** usar somente projeção contábil autorizada sem pagador/ID externo ou transação individual privada.

**AC-04.** **Given** replay/frequência abusiva ou alteração de fonte depois de gerar cache; **When** responder; **Then** deduplicar por mensagem/comando e revalidar versão/visibilidade antes da saída.

**Evidência futura e gate humano:** Cada comando dinâmico exige aceite de seu produtor; catálogo indisponível não é funcionalidade pronta. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-12-6"></a>
### Story 12.6: Restringir participantes e senha Zoom por audiência e segredo

**Como** servidor expressamente autorizado,

**quero** consultar informações permitidas de reunião,

**para** respeitar sigilo da sala e proibir frequência nominal.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 12.2; fonte Zoom 9 para agregados, fonte autorizada de credencial de 2 e decisão institucional de segredo; uma consulta não exige outra fonte que não utiliza. **RF principais (contribuição, sem mudar ownership):** `RF-WHATSAPP-004`, `RF-AUTOMACOES-024`; aplica RF-AUDITORIA-008. **Fatia vertical:** handlers restritos de agregado e segredo sobre fontes reais, sem acesso nominal Zoom ou credencial administrativa de anfitrião.

**Limites:** !senha não autoriza tornar credencial do host ou token administrativo públicos.

**Acceptance Criteria:**

**AC-01.** **Given** !participantes com fonte de contagem vigente e audiência autorizada; **When** solicitar resposta; **Then** exibir somente número agregado, sem lista, apelidos/IDs individuais ou inferência de pessoas.

**AC-02.** **Given** !senha em grupo de servidores com titular autorizado; **When** pedir segredo; **Then** revalidar Policy e contrato de publicação/audiência, auditar revelação sem gravar segredo em log.

**AC-03.** **Given** visitante/membro comum pede !senha ou usa outro grupo; **When** consultar; **Then** negar sem refletir segredo ou informar credencial parcialmente.

**AC-04.** **Given** Zoom offline ou segredo expirado; **When** processar comando; **Then** não inventar senha ou total zero e não criar link público alternativo inseguro.

**Evidência futura e gate humano:** Decisão de visibilidade/segredo Zoom, parâmetro 08 e teste de autorização são gates. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-12-7"></a>
### Story 12.7: Administrar flags, modelos e falhas de rotinas autorizadas

**Como** gestor do módulo da rotina,

**quero** habilitar e ajustar execução permitida sem editar código,

**para** operar automações com reprocessamento seguro.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 12.2 e configuração/flags de 4.3/4.4; apenas ação já implementada. **RF principais:** `RF-AUTOMACOES-016`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Comandos Evolution continuam handlers; não criar engine alternativa de agenda/filas. O resultado novo é uma visão integrada e filtrada por capacidade das rotinas reais de múltiplos produtores, com dono, ativo, versão, última/próxima execução, falha e intervenção no mesmo lugar. Reutilizar configuração/modelos/diagnóstico/escopos de 4.3/4.6/4.7, sem reimplementar seus mecanismos; começar com produtores já disponíveis e ampliar sem esperar catálogo completo.

**Acceptance Criteria:**

**AC-01.** **Given** rotinas reais de produtores existentes e operador com capacidade; **When** abrir gestão integrada e configurar flag, template, frequência/destino permitido de uma rotina; **Then** apresentar catálogo unificado filtrado por dono/escopo, estado/versão/última/próxima execução/falha e encaminhar edição ao mesmo mecanismo de 4, persistindo trilha e impedindo disparo após desativação efetiva. Visão integrada não concede acesso a payload Kit/financeiro.

**AC-02.** **Given** job falhou e operador autorizado pede retry; **When** reprocessar; **Then** usar mesma identidade do efeito e não duplicar mensagem/lançamento/efeito de negócio.

**AC-03.** **Given** usuário sem capacidade do módulo ou troca de mandato; **When** editar flag ou destino; **Then** negar gestão, mantendo Tesoureiro para rotinas financeiras e Coordenador Kit para suas rotinas.

**AC-04.** **Given** template contém variável proibida ou destino externo não homologado; **When** salvar/prévia; **Then** rejeitar sem executar script ou expor segredo, usando prévia sanitizada.

**Evidência futura e gate humano:** Allowlist de rotinas/donos e destinos por parâmetro 08. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-12-8"></a>
### Story 12.8: Montar fluxos declarativos com ações previamente permitidas

**Como** gestor autorizado de rotina,

**quero** compor gatilho, condição e ação por campos visuais,

**para** configurar o básico sem executar código arbitrário.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 12.7; catálogo mínimo de ações implementadas de 4. **RF principais:** `RF-AUTOMACOES-017`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não clonar n8n nem permitir programação arbitrária; editor cobre somente ações já disponíveis.

**Acceptance Criteria:**

**AC-01.** **Given** gatilho, condição e ação já registrados em allowlist tipada; **When** compor e pré-visualizar fluxo; **Then** persistir configuração versionada e validar cada transição, sem linguagem livre ou acesso a classes PHP.

**AC-02.** **Given** regra contém código PHP/SQL/shell, URL livre ou ação de módulo não autorizado; **When** salvar ou testar; **Then** rejeitar com mensagem clara e sem execução do payload.

**AC-03.** **Given** regra de Tesouraria/Kit ou acesso root excepcional; **When** gestor geral tenta alterar; **Then** respeitar dono/mandato; exceção root exige sessão pessoal elevada e auditoria.

**AC-04.** **Given** teste de rotina ou reprocessamento depois de mudança; **When** executar em sandbox/ambiente controlado; **Then** não disparar mensagem ou efeito real por simples prévia, aplicar versão e dedup no processamento autorizado.

**Evidência futura e gate humano:** Ação/composição e permissões precisam de ensaio por rotina antes de ativação real. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

### Rastreabilidade proposta do Epic 12 — RF → Story → AC
Os IDs e a titularidade principal são os do mapa global aprovado. ACs indicam contratos **propostos**, não testes executados; RFs compostos dependem das integrações/gates explicitados no escopo.

| RF principal | Story e critérios propostos | Objeto de cobertura | Situação |
|---|---|---|---|
| RF-WHATSAPP-001 | 12.1 AC-01–04; [Story 12.1](#story-12-1) | Receber webhook Evolution API | Proposto; execução/aceite condicionados ao RF integral |
| RF-WHATSAPP-002 | 12.1 AC-01–04; [Story 12.1](#story-12-1) | Identificar grupo de origem | Proposto; execução/aceite condicionados ao RF integral |
| RF-WHATSAPP-007 | 12.1 AC-01–04; [Story 12.1](#story-12-1) | Logs | Proposto; execução/aceite condicionados ao RF integral |
| RF-WHATSAPP-003 | 12.2 AC-01–04; [Story 12.2](#story-12-2) | Processar comandos | Proposto; execução/aceite condicionados ao RF integral |
| RF-WHATSAPP-008 | 12.2 AC-01–04; [Story 12.2](#story-12-2) | Proteção contra abuso | Proposto; execução/aceite condicionados ao RF integral |
| RF-AUTOMACOES-009 | 12.2 AC-01–04; [Story 12.2](#story-12-2) | Robô WhatsApp | Proposto; execução/aceite condicionados ao RF integral |
| RF-AUTOMACOES-024 | 12.2 AC-01–04; 12.3 AC-01/03; 12.4 AC-01/02; 12.5 AC-01–04; 12.6 AC-01–04; [Story 12.2](#story-12-2) | Parser/rate limit/audiência, saldo real, segredo restrito e ausência de !kit | Composto; parser ou indisponibilidade não completam consultas |
| RF-WHATSAPP-004 | 12.3 AC-01–04; 12.5 AC-01–04; 12.6 AC-01–04; [Story 12.3](#story-12-3) | Catálogo de serviço: informativos/serviço, dinâmicos, participantes agregado e senha restrita | Composto/parcial em 12.3; aceite integral exige os 19 comandos homologados nos respectivos produtores |
| RF-SERVIDORES-013 | 12.3 AC-01–04; [Story 12.3](#story-12-3) | Comando !vago | Proposto; execução/aceite condicionados ao RF integral |
| RF-SERVIDORES-014 | 12.3 AC-01–04; [Story 12.3](#story-12-3) | Comando !servidores | Proposto; execução/aceite condicionados ao RF integral |
| RF-WHATSAPP-005 | 12.4 AC-01–04; 12.5 AC-01/03/04; [Story 12.4](#story-12-4) | Catálogo de membros: informativos/servidores e saldo/relatórios | Composto/parcial em 12.4; aceite integral exige os 13 comandos homologados |
| RF-WHATSAPP-006 | 12.5 AC-01–04; [Story 12.5](#story-12-5) | Dados vindos do banco | Proposto; execução/aceite condicionados ao RF integral |
| RF-TESOURARIA-015 | 12.5 AC-01–04; [Story 12.5](#story-12-5) | Comando !saldo | Proposto; execução/aceite condicionados ao RF integral |
| RF-AUTOMACOES-016 | 12.7 AC-01–04; [Story 12.7](#story-12-7) | Rotinas e modelos configuráveis | Proposto; execução/aceite condicionados ao RF integral |
| RF-AUTOMACOES-017 | 12.8 AC-01–04; [Story 12.8](#story-12-8) | Construtor visual básico com gatilhos, condições e ações previamente permitidos. | Proposto; execução/aceite condicionados ao RF integral |

## Dependências, gates e aceite do Epic
- **Início e ordem:** implementar somente Stories autorizadas, precedidas pelos produtores reais necessários; a ordem editorial não substitui a verificação de capacidade já entregue. Qualquer dependência de componente futuro deve ser sinalizada e o consumidor adiado, sem simular funcionalidade.
- **Decisões humanas:** permanecem abertas as do índice e da cláusula de cada Story. Ausência de decisão não pode virar default normativo silencioso. Segurança, finalidade, canais, fornecedores, documentação e política de dados por classe antecedem a habilitação afetada.
- **Critério de encerramento:** revisar **todos** os RF principais acima, seus negativos e os contratos RNF/RB/CA/UX pertinentes no texto integral do PRD/UX. Não concluir Epic enquanto faltar funcionalidade composta exigida, homologação externa ou gate explicitamente aplicável.
- **Ponto de parada BMAD:** aguardar revisão independente individual/global, conciliação multidisciplinar e aceite humano do lote. Não adicionar ID a `approvedStoryIds` nem avançar `stepsCompleted` nesta entrega.

### Auditoria documental integral — 23–24/10/2026

Revisão/revalidação focal `ses_e949c24a6ffeWEqI3feNpRXHRk`: **8 Stories / 32 AC / 15 RF principais**, cada AC-01–04 analisado individualmente; F1/F2 resolvidos, sem residual focal. CL-01–05 aplicáveis; coordenador único editor. Catálogos: serviço 13 informativos/serviço + 4 dinâmicos + 2 restritos = 19; membros 11 informativos/serviço + 2 dinâmicos = 13. Resultado global no índice; aprovação e ativação pendentes.

| ID / severidade | Epic/Story/AC | Evidência original | Fonte normativa | Correção persistida | Resultado | Revalidação |
|---|---|---|---|---|---|---|
| F1 MAJOR | 12.3; 12.6; matriz | Catálogo integral antes de 12.5/12.6 | RF-WHATSAPP-004; BMAD 03/04 | Entrega independente e RF composto ligado aos três consumidores | Corrigido | Sessão acima; sem residual |
| F2 MAJOR | 12.3/12.4 AC-01; 12.5 AC-01 | Comandos/audiências não enumeráveis por comportamento | RF-WHATSAPP-004/005 | Catálogos literais e resultados por finalidade/grupo; nenhum wildcard | Corrigido | Idem |

**Contratos por Story, AC-01–04:** 12.1 → RNF-01/03–05/08–10, RB-05/09/17, CA-11/25/35, UX-DR49/52; 12.2 → RNF-03–05/08–10, RB-09/17/23, CA-11/25, UX-DR49/52; 12.3 → RNF-03/05/08–10, RB-09/10/16, CA-08/25/26, UX-DR40/43/52; 12.4 → RNF-03/05/08–10/17, RB-09/10/20, CA-10/25, UX-DR38/40/52; 12.5 → RNF-03/05/08–10/17, RB-01/09/10/19/20, CA-10/22/25, UX-DR38/39/52; 12.6 → RNF-01/03–05/10/15, RB-02/09/10, CA-05/23/34, UX-DR31/52; 12.7 → RNF-03–06/08–10, RB-07/17/23, CA-05/25, UX-DR43/44/59; 12.8 → RNF-03–06/08–10, RB-13/17/23, CA-25, UX-DR59. Textos/links/reformulações, identidade verificável para comandos restritos, grupos, segredo Zoom, retenção e contrato Evolution seguem gates, não comandos concluídos por indisponibilidade.
