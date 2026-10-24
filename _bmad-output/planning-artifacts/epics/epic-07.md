## Epic 7: Escriturar e prestar contas sem duplicar movimentações
> **Estado vigente — APROVADO COMO PLANEJAMENTO CONDICIONADO em 24/10/2026.** Aceite humano expresso após auditoria/revalidação. Stories e AC abaixo preservados; expressões de proposta/revisão/aceite pendente são proveniência histórica. Gates institucionais e operacionais continuam abertos; sem autorização de build, dados reais, sprint ou deploy. Conclusão documental não encerra formalmente os passos 03/04 nem comprova readiness.

> **Fontes canônicas:** [índice global](../epics.md) · [PRD](../PRD.md) · [Product Brief](../ProductBrief.md) · [Arquitetura](../Arquitetura.md) · [UX Design](../UX-Design.md). **Detalhamento exclusivo deste Epic:** Stories, critérios de aceite, gates e matriz RF → Story → AC.

## Escopo aprovado no passo 02
**Objetivo:** Tesoureiro mantém livro financeiro e reserva prudente; ASAAS efetiva movimentos elegíveis e o público consulta prestação autorizada sem contribuinte.

**Resultado verificável:** manual salvo e webhook válido produzem efeito imediato; duplicatas, concorrência e estados sucessivos não duplicam saldo. Corrigir/categorizar/estornar/excluir logicamente preserva origem, motivo e antes/depois. Dashboard financeiro próprio e projeção pública por data/valor/categoria/saldo.

**RFs responsáveis (21):** RF-TESOURARIA-001–014, RF-TESOURARIA-016–017; RF-RELATORIOS-012, RF-RELATORIOS-017; RF-AUTOMACOES-012, RF-AUTOMACOES-025; RF-COMUNICACAO-002.

**Dependências — início:** controles efetivos de Tesoureiro vigente, trilha e persistência da fundação/Epic 1. Nenhuma dependência obrigatória de 3 ou 4. **Por capacidade:** manual imediato/livro/saldo/ajuste auditado exigem apenas regras financeiras e categorias aprovadas; ASAAS exige contrato de autenticação/eventos elegíveis, inbox e unicidade de efeito do próprio incremento; publicação exige projeção sem contribuinte e política de divulgação. **Incremental:** notificações usam envio mínimo de 4 se habilitadas; carga histórica usa 3/15; !saldo usa 12, nenhum deles bloqueia o livro. **Produção:** parâmetro 07, recuperação testada, saldo inicial/categorias e aceite de cada origem; webhook real não aceito só por teste do manual.

**Limites:** sem rascunho/aprovação financeira e sem lançar pelo saldo declarado na ata. Livro/dashboard próprios não dependem dos indicadores globais do Epic 11. !saldo acrescenta um canal no Epic 12, não condição para consultar/usar o livro. Auditoria financeira só revela conteúdo pertinente ao autorizado.
## Contratos de elaboração e revisão
- **Fonte e estado:** o PRD rege texto integral dos RF; o índice rege a titularidade do Epic e os contratos compartilhados. Uma referência de RF não comprova sua implementação nem aceite ponta a ponta.
- **Dependências por capacidade:** cada Story usa somente baseline ou produtores anteriores mencionados. Referências externas para integração, dados reais ou consumidores posteriores são *gates de aceite/ativação*, não autorização para antecipar tabelas, criar rotas falsas ou bloquear uma capacidade manual já utilizável.
- **Segurança e governança:** capacidades pelo mandato vigente/Policy no backend e na tela; exceção root somente por sessão pessoal elevada, justificada, temporal e auditada. Evento/alteração/auditoria e intenção outbox dentro da mesma transação quando aplicáveis, nunca chamadas HTTP externas antes do commit.
- **Dados e precisão:** preservar NULL distinto de zero; datas civis em `America/Bahia`, instantes em UTC; valores monetários decimais; filtros e exports usam mesma Policy. Não inferir pessoas a partir do Zoom nem mesclar homônimos.
- **Validação posterior:** testes com fixtures sintéticas, cenários positivos/negativos, sessão dormente, falhas, repetição, concorrência, PostgreSQL real, classificação de dados, mobile/teclado/leitor e comportamento degradado pertinentes. Contrato técnico de provedor exige homologação real, não apenas mock.
- **Limite da rodada:** não executar fluxo instalado, migrations, testes Laravel, comandos de importação, integração real, sprint, commit ou deploy. A revisão independente e correção adversarial ficam para o OpenCode no checkpoint humano.

## Stories propostas

<a id="story-7-1"></a>
### Story 7.1: Estruturar livro, categorias e saldo reprodutível

**Como** Tesoureiro vigente,

**quero** consultar livro e configurar categorias contábeis autorizadas,

**para** ter saldo calculável sem planilhas paralelas.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 1.6 Policy Tesoureiro/trilha; BASE. **RF principais:** `RF-TESOURARIA-004`, `RF-TESOURARIA-005`, `RF-TESOURARIA-006`, `RF-TESOURARIA-011`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Livro autoritativo PostgreSQL; dados da 7ª Tradição de ata são declarativos e não lançamentos.

**Acceptance Criteria:**

**AC-01.** **Given** Tesoureiro vigente e categorias/contas institucionais homologadas; **When** cadastrar conta/categoria e registrar saldo de abertura conferido com data de referência, origem e trilha; **Then** oferecer livro consultável por conta/categoria/período, inclusive sem movimentos posteriores, sem depender da criação manual de 7.2 ou do ASAAS de 7.3. Calcular saldo inicial + entradas efetivas − saídas efetivas ± ajustes efetivos, preservando saldo anterior à janela e ordem temporal estável com decimal exato, sem float ou dupla contagem da abertura.

**AC-02.** **Given** descrição de lançamento ou categoria faltante; **When** validar registro ou classificação; **Then** exigir descrição e classificação cabível; manter valores nulos como desconhecidos, não como zero financeiro.

**AC-03.** **Given** CG/SG, Kit, visitante ou conta sem mandato; **When** pedir livro detalhado/alterar categoria por URL; **Then** negar a informação restrita e mutação, sem herança automática de admin.

**AC-04.** **Given** alteração concorrente de catálogo e transação sem trilha; **When** tentar salvar; **Then** revalidar permissão/versão e reverter mudança integralmente em caso de falha de auditoria.

**Evidência futura e gate humano:** Parâmetro 07 (categorias, saldo inicial e contas) antes de efeito real. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-7-2"></a>
### Story 7.2: Efetivar entrada ou saída manual e reserva prudente

**Como** Tesoureiro vigente,

**quero** salvar movimentação manual e administrar reserva prudente,

**para** refletir o movimento no saldo imediatamente com autoria.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 7.1. **RF principais:** `RF-TESOURARIA-009`, `RF-TESOURARIA-010`, `RF-TESOURARIA-016`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não criar etapa de conferência obrigatória nem duplicar a contabilidade pelo valor declarado da ata.

**Acceptance Criteria:**

**AC-01.** **Given** Tesoureiro ativo informa data, natureza, valor positivo decimal, categoria e descrição; **When** confirmar salvar; **Then** criar lançamento efetivo e auditoria na mesma transação; saldo considera o movimento imediatamente, sem rascunho ou segunda aprovação.

**AC-02.** **Given** registro de reserva prudente autorizado; **When** lançar entrada/saída vinculada à finalidade; **Then** preservar trilha e calcular projeção da reserva sem somar valores duas vezes.

**AC-03.** **Given** duplo clique/repetição da requisição ou falha depois de escrever; **When** repetir idempotency key; **Then** retornar mesmo lançamento/recibo sem criar segundo efeito no saldo.

**AC-04.** **Given** CG/SG ou mandato expirado; valor zero/negativo indevido; **When** tentar salvar; **Then** negar mutação e preservar livro anterior; nenhuma aprovação inventada em nome da Mesa.

**Evidência futura e gate humano:** Parâmetro 07 e restore do escopo financeiro antes de dados reais. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-7-3"></a>
### Story 7.3: Receber ASAAS autenticado e produzir efeito financeiro único

**Como** Tesoureiro responsável pela escrituração,

**quero** receber pagamentos válidos sem duplicar crédito,

**para** confiar no saldo mesmo com replay e eventos sucessivos.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 7.1; identidade de evento externa e inbox próprias; não 3/4. **RF principais:** `RF-TESOURARIA-001`, `RF-TESOURARIA-002`, `RF-TESOURARIA-003`, `RF-AUTOMACOES-012`, `RF-AUTOMACOES-025`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não exigir aceite manual entre recebimento elegível e lançamento; não copiar dados bancários para logs.

**Acceptance Criteria:**

**AC-01.** **Given** webhook ASAAS autenticado e evento elegível homologado; **When** receber o evento; **Then** preservar a evidência original imutável necessária à reconciliação, com hash/origem e acesso financeiro restrito, separada da projeção minimizada de processamento e dos logs. Registrar inbox durável e identificador único antes de confirmar recepção; falha de enqueue após commit é recuperável pela inbox sem perder evento. Ao processamento válido, efetivar lançamento, trilha e marca do efeito na mesma transação imediatamente, sem aprovação intermediária. PIX recebido usa descrição `7ª Tradição - PIX recebido` e categoria de contribuição; saída usa descrição sanitizada do evento ou fallback explícito para retificação posterior, sem bloquear lançamento elegível. Guarda/cifra/expurgo do original seguem política por classe, sem copiar pagador a logs ou projeções públicas.

**AC-02.** **Given** mesmo webhook repetido ou eventos ASAAS distintos para a mesma transação/efeito; **When** processar em concorrência; **Then** usar dedup de inbox por evento e unicidade financeira por transação+tipo de movimento; saldo muda só uma vez.

**AC-03.** **Given** evento inválido, não elegível, fora de ordem ou valor/identidade inconciliável; **When** processar; **Then** não efetivar lançamento indevido; registrar estado sanitizado/pendência para análise do Tesoureiro sem inventar pagador.

**AC-04.** **Given** eventos compensatórios/estorno emitidos legitimamente pelo provedor, inclusive após correção/estorno manual da mesma reversão econômica; **When** processar segundo regras aprovadas; **Then** correlacionar a compensação externa à cadeia manual e aplicar somente diferença econômica ainda não contabilizada, sob bloqueio e identidade do efeito. Evento distinto legítimo é preservado sem apagar origem; conflito/valor incerto exige reconciliação pelo Tesoureiro, não segundo débito/crédito automático da mesma reversão.

**Evidência futura e gate humano:** Contrato oficial ASAAS, credenciais, categorias e critérios de elegibilidade dependem de homologação anterior ao webhook real. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-7-4"></a>
### Story 7.4: Corrigir, categorizar, estornar e excluir logicamente lançamento

**Como** Tesoureiro vigente,

**quero** ajustar registros financeiros sem apagar sua origem,

**para** manter prestação de contas e histórico verificáveis.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 7.1/7.2; 7.3 quando origem ASAAS. **RF principais:** `RF-TESOURARIA-007`, `RF-TESOURARIA-008`, `RF-TESOURARIA-017`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não criar aprovação financeira intermediária; saldo correto deriva dos fatos efetivos.

**Acceptance Criteria:**

**AC-01.** **Given** lançamento efetivo existente e Tesoureiro autorizado; **When** corrigir campos contábeis permitidos, inclusive valor/data/conta/tipo/descrição/categoria, com justificativa e versão esperada; **Then** validar invariantes e versionar antes/depois, origem, autor e impacto no saldo/projeções sem modificar evento bruto ASAAS. Reprocessar o evento original não sobrescreve a correção nem reabre efeito já satisfeito; conflito exige reconciliação pelo Tesoureiro.

**AC-02.** **Given** lançamento equivocadamente duplicado ou estorno autorizado; **When** aplicar ajuste ou exclusão lógica; **Then** registrar efeito corretivo único com justificativa, valores antes/depois e impacto no saldo, sem DELETE físico. Estornar e depois excluir logicamente não compensa duas vezes o mesmo movimento; rejeitar transição incompatível e preservar origem/evento e cadeia das correções. Reversão posterior é outro ato autorizado/rastreável, nunca edição da evidência original.

**AC-03.** **Given** duas correções concorrentes ou cancelamento de mandato; **When** confirmar mudança; **Then** revalidar versão/Policy na transação e recusar sobrescrita silenciosa.

**AC-04.** **Given** administrador geral, Kit ou usuário sem mandato; **When** acessar edição/exclusão por URL/API; **Then** negar acesso ordinário, preservando apenas exceção root pessoal auditada.

**Evidência futura e gate humano:** Política de guarda de trilhas/valores e convenções de ajuste homologadas. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-7-5"></a>
### Story 7.5: Analisar indicadores financeiros e reserva em painel próprio

**Como** Tesoureiro vigente,

**quero** filtrar receitas, despesas, saldo e projeção de autossustentabilidade,

**para** acompanhar gestão sem planilha nem dashboard global.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 7.1/7.2 e ajustes manuais de 7.4; 7.3 somente para incorporar origem ASAAS, não para consultar indicadores do livro manual. **RF principais:** `RF-TESOURARIA-012`, `RF-TESOURARIA-013`, `RF-RELATORIOS-012`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não depender do dashboard do Epic 11 nem emitir nomes de contribuintes ao público.

**Acceptance Criteria:**

**AC-01.** **Given** período e conta autorizados com lançamentos e reserva; **When** abrir dashboard privativo; **Then** mostrar entradas, saídas, categorias, saldo, reserva prudente, média de contribuição, evolução temporal e projeções de autossustentabilidade com fonte/fórmula/período. Cada gráfico tem eixos, unidade, granularidade e tabela alternativa; distinguir carregando, vazio, parcial, disponível e erro, com filtros operáveis por teclado/leitor. Origem ASAAS ausente não bloqueia análise dos movimentos manuais existentes.

**AC-02.** **Given** período vazio ou dados desconhecidos; **When** consultar indicadores; **Then** diferenciar sem movimentação confirmada de dados faltantes e incluir tabela acessível; não exibir projeção fabricada.

**AC-03.** **Given** Tesoureiro e CG/SG acessando o mesmo painel; **When** consultar dados; **Then** limitar acesso a visões explicitamente autorizadas; CG/SG não recebem edição financeira nem detalhes vedados por herança.

**AC-04.** **Given** estorno ou correção financeira efetivada; **When** atualizar indicadores/cache; **Then** refletir nova versão sem duplicar valores nem manter número obsoleto ao usuário.

**Evidência futura e gate humano:** Fórmulas de média/projeção e saldo inicial devem ser validadas com Tesoureiro. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-7-6"></a>
### Story 7.6: Publicar prestação financeira sem identificação de contribuintes

**Como** visitante sem login,

**quero** consultar dados contábeis liberados por período,

**para** acompanhar transparência com anonimato preservado.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 7.1/7.4; portal 2 quando publicar link. **RF principais:** `RF-TESOURARIA-014`, `RF-RELATORIOS-017`, `RF-COMUNICACAO-002`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Publicação financeira é diferente da publicação nominal de ingressos; não depende do Epic 10.

**Acceptance Criteria:**

**AC-01.** **Given** lançamentos válidos e política de divulgação homologada; **When** abrir relatório público; **Then** exibir apenas data, categoria, valor e saldo permitidos em consulta sem login.

**AC-02.** **Given** transação contém nome, e-mail, ID externo ou dado bancário individual; **When** gerar HTML/JSON/CSV/cache/impressão públicos; **Then** não projetar nenhum identificador de contribuinte nem payload bruto do ASAAS.

**AC-03.** **Given** Tesoureiro retifica/exclui logicamente lançamento; **When** reconsultar relatório público; **Then** invalidar projeção/cache associado e recalcular a partir do livro vigente.

**AC-04.** **Given** visitante aplica filtros arbitrários ou explora URL administrativa; **When** consultar exportações/detalhes; **Then** autorizar somente campos agregados e rotas públicas previstas, com testes negativos de inferência.

**Evidência futura e gate humano:** Parâmetro 07 e política de divulgação/retensão devem ser aprovados antes da publicação real. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

### Rastreabilidade proposta do Epic 7 — RF → Story → AC
Os IDs e a titularidade principal são os do mapa global aprovado. ACs indicam contratos **propostos**, não testes executados; RFs compostos dependem das integrações/gates explicitados no escopo.

| RF principal | Story e critérios propostos | Objeto de cobertura | Situação |
|---|---|---|---|
| RF-TESOURARIA-004 | 7.1 AC-01; 7.2 AC-01/04; 7.3 AC-01/04; [Story 7.2](#story-7-2) | Entradas/saídas manuais e elegíveis automáticas | Catálogo/abertura não substituem efetivação dos movimentos |
| RF-TESOURARIA-005 | 7.1 AC-02; 7.2 AC-01; 7.3 AC-01; 7.4 AC-01; [Story 7.3](#story-7-3) | Descrição obrigatória, PIX padronizado, despesa sanitizada/fallback e correção posterior | Composto; configuração isolada não conclui regra ASAAS |
| RF-TESOURARIA-006 | 7.1 AC-01–04; [Story 7.1](#story-7-1) | Categorias | Proposto; execução/aceite condicionados ao RF integral |
| RF-TESOURARIA-011 | 7.1 AC-01–04; [Story 7.1](#story-7-1) | Saldo | Proposto; execução/aceite condicionados ao RF integral |
| RF-TESOURARIA-009 | 7.2 AC-01–04; [Story 7.2](#story-7-2) | Lançamento manual | Proposto; execução/aceite condicionados ao RF integral |
| RF-TESOURARIA-010 | 7.2 AC-01–04; [Story 7.2](#story-7-2) | Reserva prudente | Proposto; execução/aceite condicionados ao RF integral |
| RF-TESOURARIA-016 | 7.2 AC-01–04; 7.3 AC-01–04; [Story 7.2](#story-7-2) | Manual imediato e ASAAS elegível imediato | Composto; nenhum dos dois caminhos isoladamente conclui o RF |
| RF-TESOURARIA-001 | 7.3 AC-01–04; [Story 7.3](#story-7-3) | Receber webhook ASAAS | Proposto; execução/aceite condicionados ao RF integral |
| RF-TESOURARIA-002 | 7.3 AC-01–04; [Story 7.3](#story-7-3) | Registrar eventos automáticos | Proposto; execução/aceite condicionados ao RF integral |
| RF-TESOURARIA-003 | 7.3 AC-01–04; [Story 7.3](#story-7-3) | Evitar duplicidade | Proposto; execução/aceite condicionados ao RF integral |
| RF-AUTOMACOES-012 | 7.3 AC-01–04; [Story 7.3](#story-7-3) | Tesouraria/ASAAS | Proposto; execução/aceite condicionados ao RF integral |
| RF-AUTOMACOES-025 | 7.2 AC-01–04; 7.3 AC-01–04; 7.4 AC-01–04; [Story 7.3](#story-7-3) | Manual/ASAAS imediatos, dedup por evento/efeito e correção posterior | Composto; manual, webhook e correções exigem seus próprios aceites |
| RF-TESOURARIA-007 | 7.4 AC-01–04; [Story 7.4](#story-7-4) | Revisão pelo tesoureiro | Proposto; execução/aceite condicionados ao RF integral |
| RF-TESOURARIA-008 | 7.4 AC-01–04; [Story 7.4](#story-7-4) | Edição com auditoria | Proposto; execução/aceite condicionados ao RF integral |
| RF-TESOURARIA-017 | 7.4 AC-01–04; [Story 7.4](#story-7-4) | Correção, estorno e exclusão auditáveis | Proposto; execução/aceite condicionados ao RF integral |
| RF-TESOURARIA-012 | 7.5 AC-01–04; [Story 7.5](#story-7-5) | Dashboard financeiro | Proposto; execução/aceite condicionados ao RF integral |
| RF-TESOURARIA-013 | 7.5 AC-01–04; [Story 7.5](#story-7-5) | Visualização restrita | Proposto; execução/aceite condicionados ao RF integral |
| RF-RELATORIOS-012 | 7.5 AC-01–04; [Story 7.5](#story-7-5) | Página Tesouraria | Proposto; execução/aceite condicionados ao RF integral |
| RF-TESOURARIA-014 | 7.6 AC-01–04; [Story 7.6](#story-7-6) | Relatório financeiro público | Proposto; execução/aceite condicionados ao RF integral |
| RF-RELATORIOS-017 | 7.6 AC-01–04; [Story 7.6](#story-7-6) | Relatório financeiro público | Proposto; execução/aceite condicionados ao RF integral |
| RF-COMUNICACAO-002 | 7.6 AC-01–04; [Story 7.6](#story-7-6) | Informações da tesouraria para consulta pública por data, categoria, valor e saldo sem identificação de contribuinte. | Proposto; execução/aceite condicionados ao RF integral |

## Dependências, gates e aceite do Epic
- **Início e ordem:** implementar somente Stories autorizadas, precedidas pelos produtores reais necessários; a ordem editorial não substitui a verificação de capacidade já entregue. Qualquer dependência de componente futuro deve ser sinalizada e o consumidor adiado, sem simular funcionalidade.
- **Decisões humanas:** permanecem abertas as do índice e da cláusula de cada Story. Ausência de decisão não pode virar default normativo silencioso. Segurança, finalidade, canais, fornecedores, documentação e política de dados por classe antecedem a habilitação afetada.
- **Critério de encerramento:** revisar **todos** os RF principais acima, seus negativos e os contratos RNF/RB/CA/UX pertinentes no texto integral do PRD/UX. Não concluir Epic enquanto faltar funcionalidade composta exigida, homologação externa ou gate explicitamente aplicável.
- **Ponto de parada BMAD:** aguardar revisão independente individual/global, conciliação multidisciplinar e aceite humano do lote. Não adicionar ID a `approvedStoryIds` nem avançar `stepsCompleted` nesta entrega.

### Auditoria documental integral — 23–24/10/2026

Revisão independente `ses_e949c9602ffek86gmh22KfLrIQ`: **6 Stories / 24 AC / 21 RF principais**, cada AC-01–04 examinado individualmente. Coordenador único editor; CL-01–05 aplicáveis. Resultado documental condicionado, não aprovação humana ou homologação ASAAS; evidência global no índice.

| ID / severidade | Epic/Story/AC | Evidência original | Fonte normativa | Correção persistida | Resultado | Revalidação |
|---|---|---|---|---|---|---|
| E7-01 MAJOR | 7.1 AC-01 | Consulta pressupunha produtores futuros | BMAD 03/04; macro E7-manual | Conta/categoria/abertura auditada e livro utilizável sem 7.2/7.3 | Corrigido | Sessão independente acima |
| E7-02 MAJOR | 7.3 AC-01 | Descrição ASAAS só genérica | RF-TESOURARIA-005 | PIX literal/categoria, saída sanitizada/fallback imediato | Corrigido | Idem |
| E7-03 MAJOR | 7.4 AC-01/02 | Correção limitada a texto/categoria | RF-TESOURARIA-008/017; CA-21/28 | Valor/atributos contábeis, trilha/impacto e sem dupla compensação | Corrigido | Idem |
| E7-04 MAJOR | 7.3 AC-01 | Queda antes do enqueue podia perder efeito | RNF-09; Arquitetura §3 | Inbox durável retomável, efeito/trilha atômicos | Corrigido | Idem |
| E7-05 MAJOR | 7.3 AC-01 | Bruto mínimo ambíguo | CA-28; Arquitetura §6.6 | Evidência original imutável separada da projeção minimizada/logs | Corrigido | Idem; política por classe pendente |
| E7-06 MAJOR | 7.5 dependências | Dashboard manual exigia ASAAS | Macro Epic 7 | Dependência apenas da origem consultada | Corrigido | Idem |
| E7-07 MAJOR | 7.1 AC-01 | Abertura/saldo anterior ausentes | RF-TESOURARIA-011; Arquitetura §§6.6/9 | Abertura auditada e fórmula com saldo anterior | Corrigido | Idem |
| E7-08 MINOR | 7.5 AC-01 | Evolução só implícita | RF-TESOURARIA-012; UX §8 | Série temporal, tabela, eixos e estados acessíveis | Corrigido | Idem |

**Contratos por Story, AC-01–04:** 7.1 → RNF-03–06, RB-08/17/19, CA-05/21, UX-DR37; 7.2 → RNF-03–05/08/09, RB-08/17/19, CA-21, UX-DR22/37; 7.3 → RNF-01/03–05/08/09, RB-08/19, CA-04/21/28, UX-DR37/49/52; 7.4 → RNF-03–05/08/09, RB-08/17/19, CA-21/28, UX-DR22/37; 7.5 → RNF-03/05/06/17, RB-08/17/19, CA-05/21, UX-DR23/37/41; 7.6 → RNF-05–07/17, RB-09/10/19, CA-10/22, UX-DR38/52. Valores da ata continuam declarativos; política por classe, abertura/categorias, mapa elegível ASAAS, ajuste e fórmulas permanecem gates próprios.
