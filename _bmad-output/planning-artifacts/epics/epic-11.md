## Epic 11: Entender os resultados do grupo e preparar o relatório do RSG
> **Estado vigente — APROVADO COMO PLANEJAMENTO CONDICIONADO em 24/10/2026.** Aceite humano expresso após auditoria/revalidação. Stories e AC abaixo preservados; expressões de proposta/revisão/aceite pendente são proveniência histórica. Gates institucionais e operacionais continuam abertos; sem autorização de build, dados reais, sprint ou deploy. Conclusão documental não encerra formalmente os passos 03/04 nem comprova readiness.

> **Fontes canônicas:** [índice global](../epics.md) · [PRD](../PRD.md) · [Product Brief](../ProductBrief.md) · [Arquitetura](../Arquitetura.md) · [UX Design](../UX-Design.md). **Detalhamento exclusivo deste Epic:** Stories, critérios de aceite, gates e matriz RF → Story → AC.

## Escopo aprovado no passo 02
**Objetivo:** servidores/público autorizado consultam indicadores reproduzíveis e RSG confere/complementa o relatório bimestral com dados do grupo.

**Resultado verificável:** visão clássica e melhorada de resumo, atas, conquistas/ingressos, temáticas, visitas e quadro de serviço, com filtros, tabelas alternativas, exportação autorizada e impressão. RSG escolhe período livre, confere métricas, complementa, gera PDF e arquiva referência.

**RFs responsáveis (14):** RF-RELATORIOS-001–010, RF-RELATORIOS-018–019; RF-AUTOMACOES-013; RF-RSG-001.

**Dependências — início:** autorização/trilha/consulta necessárias de 1 e pelo menos uma fonte implementada; não todos os Epics 5–10. **Por capacidade:** quadro de serviço usa mandatos/escalas de 1/2; resumo/atas/visitas/conquistas/temáticas usam atas de 5; projeção nominal pública exige 10; RSG exige métricas/fontes previstas no modelo e armazenamento/PDF apropriado (capacidade Drive de 8), sem exigir todo o módulo de serviço. **Incremental:** filtros/painéis ampliam fontes existentes; 6/7/8/9 só são pré-requisitos quando o relatório realmente os usa. Comparação de fórmula histórica depende de exportação/definição correspondente de 3, e paridade integral do lote é de 15. **Produção:** parâmetros 05/11, acesso/export/cache e fórmula do relatório testados; ausência de fonte não vira zero nem licença para encerrar RF que a exige.

**Limites:** não reescrever livro financeiro, fila Kit, serviço ou lista Zoom; agregar/reutilizar consultas autorizadas entregues pelos donos. Comparação inicial usa fixtures/exportações autorizadas sem declarar legado migrado; paridade do acervo completo é comprovada no Epic 15. Nunca somar duas vezes totais declarados e detalhes nem incluir rascunho/anulada.
## Contratos de elaboração e revisão
- **Fonte e estado:** o PRD rege texto integral dos RF; o índice rege a titularidade do Epic e os contratos compartilhados. Uma referência de RF não comprova sua implementação nem aceite ponta a ponta.
- **Dependências por capacidade:** cada Story usa somente baseline ou produtores anteriores mencionados. Referências externas para integração, dados reais ou consumidores posteriores são *gates de aceite/ativação*, não autorização para antecipar tabelas, criar rotas falsas ou bloquear uma capacidade manual já utilizável.
- **Segurança e governança:** capacidades pelo mandato vigente/Policy no backend e na tela; exceção root somente por sessão pessoal elevada, justificada, temporal e auditada. Evento/alteração/auditoria e intenção outbox dentro da mesma transação quando aplicáveis, nunca chamadas HTTP externas antes do commit.
- **Dados e precisão:** preservar NULL distinto de zero; datas civis em `America/Bahia`, instantes em UTC; valores monetários decimais; filtros e exports usam mesma Policy. Não inferir pessoas a partir do Zoom nem mesclar homônimos.
- **Validação posterior:** testes com fixtures sintéticas, cenários positivos/negativos, sessão dormente, falhas, repetição, concorrência, PostgreSQL real, classificação de dados, mobile/teclado/leitor e comportamento degradado pertinentes. Contrato técnico de provedor exige homologação real, não apenas mock.
- **Limite da rodada:** não executar fluxo instalado, migrations, testes Laravel, comandos de importação, integração real, sprint, commit ou deploy. A revisão independente e correção adversarial ficam para o OpenCode no checkpoint humano.

## Stories propostas

<a id="story-11-1"></a>
### Story 11.1: Oferecer painel de indicadores com filtros e fórmulas rastreáveis

**Como** servidor autorizado,

**quero** consultar indicadores em visões clássica e melhorada,

**para** substituir Looker com números verificáveis.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** consulta oficial de atas e filtros de 5.5/5.6 e ocorrência/escala de 1/2; mapa/fórmulas de 3 para comparação histórica. **RF principais:** `RF-RELATORIOS-001`, `RF-RELATORIOS-002`, `RF-RELATORIOS-003`, `RF-RELATORIOS-004`, `RF-AUTOMACOES-013`. **Fatia vertical:** um painel de atas com uma consulta/projeção reutilizável, apresentação clássica e melhorada; páginas temáticas/resumo posteriores reutilizam o contrato, não são produtoras necessárias deste painel. Quadro independente de 11.4 pode começar antes dele.

**Limites:** Não reconstruir agregações independentes de cada módulo nem concluir equivalência histórica antes do Epic 15.

**Acceptance Criteria:**

**AC-01.** **Given** fonte oficial de atas disponível e usuário com capacidade; **When** combinar filtros por período, dia da semana, horário, tipo/formato, visitas, ingressos, conquistas, temática, servidor e status; **Then** usar a mesma consulta/projeção autorizada em tela e saídas, mostrando critérios/fórmula/origem/período e estados preservados na navegação. Cards/gráficos têm eixos, unidades e tabela alternativa para cada gráfico; controles operáveis por teclado/leitor. Sem redefinir em código concorrente filtros/fórmulas existentes de 5.6.

**AC-02.** **Given** visão clássica versus cálculo novo; **When** comparar mesmo escopo; **Then** apresentar números e diferenças rastreáveis; não afirmar equivalência ao Looker sem fonte/contrato real.

**AC-03.** **Given** fonte parcial, janela vazia, dado nulo ou falha de consulta; **When** executar filtro; **Then** distinguir carregando, sem dados, parcial, disponível e erro, sinalizando a causa sem converter desconhecido em zero validado. Um painel funcional de dados novos não comprova equivalência histórica; RF-RELATORIOS-001/002 e RF-AUTOMACOES-013 continuam compostos até conferir todas as visões/fórmulas aplicáveis contra fontes reais no escopo de 15.

**AC-04.** **Given** campo restrito ou filtro que reidentifique participante; **When** solicitar dados pelo endpoint; **Then** usar projeção e Policy da fonte, negar dados de Kit/Zoom/financeiro vedados.

**Evidência futura e gate humano:** Fórmulas/visões legadas e parâmetros 05/11 demandam conferência humana antes da homologação de paridade. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-11-2"></a>
### Story 11.2: Consultar resumo e registros oficiais de reuniões

**Como** servidor autorizado,

**quero** ver resumo e detalhe dos registros de ata,

**para** compreender atividade do grupo sem contar rascunhos.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 11.1 e atas oficiais de 5. **RF principais:** `RF-RELATORIOS-005`, `RF-RELATORIOS-006`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não lançar valores financeiros a partir de ata nem inferir identidades Zoom.

**Acceptance Criteria:**

**AC-01.** **Given** atas oficiais com revisões e totais declarados; **When** abrir página Resumo por período; **Then** apresentar reuniões, presenças, partilhas, visitas, ingressos e conquistas da versão vigente por ocorrência, com fonte/status/período e acesso autorizado às ocorrências que compõem cada total. Página Dados da Reunião oferece listagem/detalhe das atas acessíveis. Totais declarados não são somados novamente aos detalhes nem substituídos pela quantidade de nomes informados.

**AC-02.** **Given** ata em rascunho/anulada ou corrigida; **When** recalcular indicador; **Then** excluir rascunho/anulação da publicação e aplicar última versão oficial válida.

**AC-03.** **Given** usuário sem direito de detalhe ou página pública; **When** abrir listagem/CSV; **Then** restringir dados nominativos não liberados e separar visão pública de consulta administrativa.

**AC-04.** **Given** intervalo cruza fuso America/Bahia ou ocorrência múltipla no dia; **When** aplicar filtro; **Then** agrupar pela data e horário da ocorrência correta, sem duplicar reunião.

**Evidência futura e gate humano:** Política de publicação/privacidade e verificação de cálculos antes de expor indicadores reais. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-11-3"></a>
### Story 11.3: Analisar conquistas, ingressos, temáticas e visitas

**Como** servidor com permissão de relatório,

**quero** filtrar indicadores temáticos por ocorrência,

**para** conferir participação institucional sem criar perfil pessoal.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 11.2 e detalhes de atas de 5; projeção nominal pública somente com 10. **RF principais:** `RF-RELATORIOS-007`, `RF-RELATORIOS-008`, `RF-RELATORIOS-009`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Proibido ranking individual de frequência e exposição de nome Zoom.

**Acceptance Criteria:**

**AC-01.** **Given** atas oficiais com visitas, conquistas, ingressos e temática; **When** consultar relatórios específicos; **Then** oferecer conquistas/ingressos, visitas/origens/relação com ingressos e reuniões temáticas com títulos, partilhadores e presenças, por período/ocorrência, reutilizando filtros e tabelas alternativas de 11.1. Totais globais de visitas/ingressos/conquistas vêm dos respectivos totais declarados da ata; detalhes discriminam origem/tipo/nome quando existentes. Nomes históricos de partilhador não recebem automaticamente a exceção editorial opt-out de ingresso/conquista.

**AC-02.** **Given** ingresso relacionado a visita ou conquista com pessoa opcional; **When** agregar; **Then** não duplicar por vínculo contextual e jamais exigir usuário/login do ingressante.

**AC-03.** **Given** total declarado 3 com apenas um detalhe, total desconhecido, detalhe com quantidade própria ou ata retificada/anulada; **When** agregar; **Then** manter total 3 e detalhe 1 separados, sem inferir nomes/origens faltantes, contar quantidade de conquista e não meramente linhas, preservar NULL e excluir versões substituídas/rascunhos/anuladas. Exibir divergência total/detalhe para conferência, sem corrigir a origem automaticamente.

**AC-04.** **Given** visitante sem autorização de visão nominal; **When** consultar filtros de nome/export; **Then** projetar somente agregado ou nome mínimo já liberado no Epic 10, sem PII de Kit.

**Evidência futura e gate humano:** Aceite de visibilidade editorial e comparação de cálculos por fonte. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-11-4"></a>
### Story 11.4: Exibir quadro de serviço, mandatos e vagas

**Como** servidor ou pessoa da Mesa autorizada,

**quero** consultar mandatos, vagas e escalas atuais,

**para** obter quadro derivado de dados vivos.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 1.6–1.8 e 2.2/2.3. **RF principais:** `RF-RELATORIOS-010`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não recriar CRUD de encargos nem comandos !vago/!servidores do Epic 12.

**Acceptance Criteria:**

**AC-01.** **Given** designações e escalas com vigência/histórico; **When** filtrar quadro por período/dia/horário/encargo; **Then** mostrar titulares, períodos, vagas e histórico de conclusão, entrega e destituição, distinguindo instante efetivo de registro/processamento conforme contratos de 1.6/1.7. Derivar atividade/vaga do relógio/estado, sem documento paralelo nem divulgação de fundamento íntimo.

**AC-02.** **Given** mandato expirou com scheduler parado; **When** consultar quadro; **Then** exibir VAGO e negar falsa atividade sem depender de job.

**AC-03.** **Given** substituição temporária ou duas ocorrências no dia; **When** gerar relatório; **Then** separar escala de cada ocorrência e conservar histórico de substituições.

**AC-04.** **Given** pessoa ou registro interno com dado sensível; **When** consultar versão pública; **Then** aplicar classificação e não vazar contatos/justificativas pessoais.

**Evidência futura e gate humano:** Teste em cruzamento de datas e decisão D2/D4 quando aplicáveis. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-11-5"></a>
### Story 11.5: Exportar e imprimir relatórios respeitando o mesmo escopo

**Como** servidor autorizado,

**quero** obter CSV ou impressão dos indicadores filtrados,

**para** prestar contas sem dados adicionais ocultos.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** consulta/projeção do relatório efetivamente exportado em 11.1, 11.2, 11.3 ou 11.4, sem exigir todas as páginas para a primeira exportação útil. **RF principais:** `RF-RELATORIOS-018`, `RF-RELATORIOS-019`. **Fatia vertical:** exportar/imprimir consulta existente e integrar as demais ao surgirem; aceite integral dos RF exige todas as saídas aplicáveis, não relatório fictício ou parcial declarado completo.

**Limites:** Impressão/PDF pelo navegador, não infraestrutura nova de PDF por requisito isolado.

**Acceptance Criteria:**

**AC-01.** **Given** usuário com direito à consulta de determinado relatório; **When** exportar CSV ou preparar impressão; **Then** gerar mesmos campos/período/filtros da tela, com unidades, fonte e versão do indicador.

**AC-02.** **Given** sem dados ou dados incompletos; **When** exportar; **Then** sinalizar conjunto vazio/parcial, sem colunas inventadas nem esconder alerta metodológico.

**AC-03.** **Given** CSV com valores começando por fórmula ou conteúdo não confiável; **When** gerar arquivo; **Then** proteger contra CSV injection e manter UTF-8/colunas interpretáveis.

**AC-04.** **Given** usuário perdeu permissão antes da exportação ou força filtro oculto; **When** solicitar download; **Then** revalidar autorização no servidor e impedir exposição de outra classe de dados.

**Evidência futura e gate humano:** Revisar risco de inferência e relatórios com poucos indivíduos antes da publicação real. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-11-6"></a>
### Story 11.6: Elaborar, conferir e arquivar relatório bimestral do RSG

**Como** RSG vigente ou substituto legitimamente autorizado,

**quero** selecionar período livre, complementar e arquivar relatório,

**para** prestar representação com métricas conferidas e versão rastreável.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 11.1/11.2/11.4 e capacidade Drive/PDF de 8.4. **RF principais:** `RF-RSG-001`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não exigir dados de módulos ainda não incluídos no modelo homologado; não recriar templates/Drive do Epic 8.

**Acceptance Criteria:**

**AC-01.** **Given** RSG com mandato ou suplência autorizada e intervalo definido; **When** criar relatório no modelo vigente; **Then** pré-preencher métricas disponíveis do banco com período/fonte, permitindo complemento textual identificado.

**AC-02.** **Given** indicador inexistente, incompleto ou sem fonte; **When** gerar prévia; **Then** exibir pendência, não inventar zero e não declarar relatório validado automaticamente.

**AC-03.** **Given** RSG confere texto e números; **When** finalizar e gerar PDF; **Then** persistir versão/autor/instante e arquivar referência documental privada ou permitida no Drive reutilizando ACL, intenção durável, dedup, tratamento de upload incerto e reconciliação de 8.4. Falha não declara arquivo concluído; retry não sobrescreve versão anterior nem duplica documento, e divulgação depende de classificação própria.

**AC-04.** **Given** suplente sem substituição vigente ou acesso público ao acervo restrito; **When** editar/abrir arquivo; **Then** negar por capacidade/documento, sem herdar CG/SG nem financeirização automática.

**Evidência futura e gate humano:** Modelo e período bimestral livre homologados, ACL/documentos recuperáveis antes de uso real. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

### Rastreabilidade proposta do Epic 11 — RF → Story → AC
Os IDs e a titularidade principal são os do mapa global aprovado. ACs indicam contratos **propostos**, não testes executados; RFs compostos dependem das integrações/gates explicitados no escopo.

| RF principal | Story e critérios propostos | Objeto de cobertura | Situação |
|---|---|---|---|
| RF-RELATORIOS-001 | 11.1 AC-01–04; 11.2 AC-01–04; 11.3 AC-01–04; 11.4 AC-01–04; 15.6 AC-03; 15.7 AC-02; [Story 11.1](#story-11-1) | Páginas próprias e substituição do Looker | Composto; paridade de todas as visões aplicáveis exige fontes reais, não fixtures |
| RF-RELATORIOS-002 | 11.1 AC-01–04; 11.2 AC-01; 11.3 AC-01/03; 11.4 AC-01; 15.6 AC-03; 15.7 AC-02; [Story 11.1](#story-11-1) | Visão clássica e comparação histórica | Composto; painel inicial não comprova paridade do acervo |
| RF-RELATORIOS-003 | 11.1 AC-01–04; 11.2 AC-01–04; 11.3 AC-01–04; 11.4 AC-01–04; [Story 11.1](#story-11-1) | Dashboard melhorado e páginas específicas | Ampliação incremental sem declarar painel inicial equivalente ao conjunto |
| RF-RELATORIOS-004 | 11.1 AC-01–04; [Story 11.1](#story-11-1) | Filtros | Proposto; execução/aceite condicionados ao RF integral |
| RF-AUTOMACOES-013 | 11.1 AC-01–04; 11.2 AC-01; 11.3 AC-01/03; 11.4 AC-01; 15.6 AC-03; 15.7 AC-02; [Story 11.1](#story-11-1) | Fórmulas/filtros/agregações rastreáveis | Composto; comparação por fonte real e escopo, sem ciclo obrigatório para consultas novas |
| RF-RELATORIOS-005 | 11.2 AC-01–04; [Story 11.2](#story-11-2) | Página Resumo | Proposto; execução/aceite condicionados ao RF integral |
| RF-RELATORIOS-006 | 11.2 AC-01–04; [Story 11.2](#story-11-2) | Página Dados da Reunião | Proposto; execução/aceite condicionados ao RF integral |
| RF-RELATORIOS-007 | 11.3 AC-01–04; [Story 11.3](#story-11-3) | Página Conquistas e Ingressos | Proposto; execução/aceite condicionados ao RF integral |
| RF-RELATORIOS-008 | 11.3 AC-01–04; [Story 11.3](#story-11-3) | Página Temáticas | Proposto; execução/aceite condicionados ao RF integral |
| RF-RELATORIOS-009 | 11.3 AC-01–04; [Story 11.3](#story-11-3) | Página Visitas | Proposto; execução/aceite condicionados ao RF integral |
| RF-RELATORIOS-010 | 11.4 AC-01–04; [Story 11.4](#story-11-4) | Página Quadro de Serviços | Proposto; execução/aceite condicionados ao RF integral |
| RF-RELATORIOS-018 | 11.5 AC-01–04; [Story 11.5](#story-11-5) | Exportação | Proposto; execução/aceite condicionados ao RF integral |
| RF-RELATORIOS-019 | 11.5 AC-01–04; [Story 11.5](#story-11-5) | Impressão/PDF via navegador | Proposto; execução/aceite condicionados ao RF integral |
| RF-RSG-001 | 11.6 AC-01–04; [Story 11.6](#story-11-6) | Geração de relatório bimestral do RSG com período inicial/final configuráveis e modelo do grupo. | Proposto; execução/aceite condicionados ao RF integral |

## Dependências, gates e aceite do Epic
- **Início e ordem:** implementar somente Stories autorizadas, precedidas pelos produtores reais necessários; a ordem editorial não substitui a verificação de capacidade já entregue. Qualquer dependência de componente futuro deve ser sinalizada e o consumidor adiado, sem simular funcionalidade.
- **Decisões humanas:** permanecem abertas as do índice e da cláusula de cada Story. Ausência de decisão não pode virar default normativo silencioso. Segurança, finalidade, canais, fornecedores, documentação e política de dados por classe antecedem a habilitação afetada.
- **Critério de encerramento:** revisar **todos** os RF principais acima, seus negativos e os contratos RNF/RB/CA/UX pertinentes no texto integral do PRD/UX. Não concluir Epic enquanto faltar funcionalidade composta exigida, homologação externa ou gate explicitamente aplicável.
- **Ponto de parada BMAD:** aguardar revisão independente individual/global, conciliação multidisciplinar e aceite humano do lote. Não adicionar ID a `approvedStoryIds` nem avançar `stepsCompleted` nesta entrega.

### Auditoria documental integral — 23–24/10/2026

Revisão/revalidação focal `ses_e949c48d7ffe9cZqN60z6Bjjof`: **6 Stories / 24 AC / 14 RF principais**, todos os AC-01–04 examinados individualmente; sem residual dos sete achados abaixo. CL-01–05 aplicáveis; coordenador único editor. Resultado global no índice; aceite humano pendente.

| ID / severidade | Epic/Story/AC | Evidência original | Fonte normativa | Correção persistida | Resultado | Revalidação |
|---|---|---|---|---|---|---|
| A1 MAJOR | 11.1 AC-01 | Só cinco filtros explícitos | RF-RELATORIOS-004 | Dez filtros e composição na mesma consulta | Corrigido | Sessão acima; sem residual |
| A2 MAJOR | 11.2 AC-01 | Resumo omitia três indicadores | RF-RELATORIOS-005 | Seis indicadores e ocorrências componentes | Corrigido | Idem |
| A3 MAJOR | 11.3 AC-01 | Temática genérica | RF-RELATORIOS-008 | Reuniões temáticas/título/partilhador/presenças | Corrigido | Idem |
| A4 MAJOR | 11.4 AC-01 | Encerramentos históricos não explícitos | RF-RELATORIOS-010 | Conclusão/entrega/destituição e fatos temporais | Corrigido | Idem |
| A5 MAJOR | 11.3 AC-01/03 | Contagem de detalhes podia substituir total | Arquitetura §9; RF-AUTOMACOES-013 | Totais declarados, detalhes e quantidades distintos; exemplo 3≠1 | Corrigido | Idem |
| A8 MINOR | 11.1 AC-01/03 | Gráficos acessíveis sem alternativa verificável | UX §§4/8/9 | Tabela por gráfico/eixos/unidades/estados e teclado | Corrigido | Idem |
| A9 MINOR | 11.5 dependências | Exigia todas as páginas para exportar uma | Macro Epic 11 | Produtor só da página exportada; aceite integral ainda composto | Corrigido | Idem |

**Contratos por Story, AC-01–04:** 11.1 → RNF-03/05/06/14/17, RB-01/02/10/20, CA-06/14, UX-DR23/39/41/51; 11.2 → RNF-03/05/06/17, RB-01–04/20, CA-06/14, UX-DR39/41; 11.3 → RNF-03/05/06/17, RB-02/03/10/20, CA-14/22, UX-DR39/41/58; 11.4 → RNF-03/05/06/17, RB-11/15/16, CA-08/19/26, UX-DR40/41; 11.5 → RNF-03/05/06/16/17, RB-10/17/20, CA-10/22, UX-DR17/23/51/52; 11.6 → RNF-03/05/06/09/11, RB-17/22, CA-24/38, UX-DR62. Gates de fórmulas/Looker, modelo RSG, editorial de serviço, D2/D4 e ACL/restore preservados; seis incrementos reutilizam consultas e PDF, sem recriar os domínios.
