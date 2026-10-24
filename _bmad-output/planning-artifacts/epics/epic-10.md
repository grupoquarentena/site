## Epic 10: Publicar com privacidade e retificar informações públicas
> **Estado vigente — APROVADO COMO PLANEJAMENTO CONDICIONADO em 24/10/2026.** Aceite humano expresso após auditoria/revalidação. Stories e AC abaixo preservados; expressões de proposta/revisão/aceite pendente são proveniência histórica. Gates institucionais e operacionais continuam abertos; sem autorização de build, dados reais, sprint ou deploy. Conclusão documental não encerra formalmente os passos 03/04 nem comprova readiness.

> **Fontes canônicas:** [índice global](../epics.md) · [PRD](../PRD.md) · [Product Brief](../ProductBrief.md) · [Arquitetura](../Arquitetura.md) · [UX Design](../UX-Design.md). **Detalhamento exclusivo deste Epic:** Stories, critérios de aceite, gates e matriz RF → Story → AC.

## Escopo aprovado no passo 02
**Objetivo:** disponibilizar projeções públicas autorizadas e aplicar decisões editoriais de ocultação/correção com anonimato, opt-out e histórico, reutilizando o atendimento já entregue em 2.

**Resultado verificável:** projeção de ingresso/conquista com nome mínimo opt-out, sem PII restrita; decisão humana correlacionada ao protocolo atualiza fonte/projeção/cache/export autorizado com trilha, sem ocultação automática. Registro histórico permanece em triagem até liberação humana. Não criar outra caixa de atendimento.

**RFs responsáveis (5):** RF-ACESSO-010; RF-RELATORIOS-015–016, RF-RELATORIOS-020; RF-COMUNICACAO-001.

**Dependências — início:** triagem/protocolo/correlação de atendimento em 2 e autorização/trilha necessários de 1. **Por capacidade:** cada projeção exige apenas sua fonte existente e regras editoriais; ingresso/conquista requer registro oficial de 5, não remessas de 6, livro de 7 ou dashboards de 11. **Incremental:** novos tipos de fonte se registram pela allowlist/Policy com seus donos, sem antecipar schema/PII. **Produção:** parâmetros 03/05/12 aplicáveis, fonte liberada, opt-out/correção e invalidação de cache/export testados; histórico só após aceite do lote em 15.

**Limites:** publicação só sobre dados existentes e permitidos; não precisa do dashboard melhorado para oferecer projeções mínimas. Reutilizar protocolo/triagem/encaminhamento de 2 e a regra de retificação do dono da fonte, sem segunda caixa de entrada ou herança de acesso ao Kit. O Epic 11 reutiliza publicação/opt-out, não cria outro. Histórico importado permanece em triagem até liberação humana no lote de 15.
## Contratos de elaboração e revisão
- **Fonte e estado:** o PRD rege texto integral dos RF; o índice rege a titularidade do Epic e os contratos compartilhados. Uma referência de RF não comprova sua implementação nem aceite ponta a ponta.
- **Dependências por capacidade:** cada Story usa somente baseline ou produtores anteriores mencionados. Referências externas para integração, dados reais ou consumidores posteriores são *gates de aceite/ativação*, não autorização para antecipar tabelas, criar rotas falsas ou bloquear uma capacidade manual já utilizável.
- **Segurança e governança:** capacidades pelo mandato vigente/Policy no backend e na tela; exceção root somente por sessão pessoal elevada, justificada, temporal e auditada. Evento/alteração/auditoria e intenção outbox dentro da mesma transação quando aplicáveis, nunca chamadas HTTP externas antes do commit.
- **Dados e precisão:** preservar NULL distinto de zero; datas civis em `America/Bahia`, instantes em UTC; valores monetários decimais; filtros e exports usam mesma Policy. Não inferir pessoas a partir do Zoom nem mesclar homônimos.
- **Validação posterior:** testes com fixtures sintéticas, cenários positivos/negativos, sessão dormente, falhas, repetição, concorrência, PostgreSQL real, classificação de dados, mobile/teclado/leitor e comportamento degradado pertinentes. Contrato técnico de provedor exige homologação real, não apenas mock.
- **Limite da rodada:** não executar fluxo instalado, migrations, testes Laravel, comandos de importação, integração real, sprint, commit ou deploy. A revisão independente e correção adversarial ficam para o OpenCode no checkpoint humano.

## Stories propostas

<a id="story-10-1"></a>
### Story 10.1: Servir projeções públicas autorizadas com barreira de privacidade

**Como** responsável editorial autorizado,

**quero** publicar somente indicadores e referências aprovados,

**para** dar transparência sem revelar dados restritos.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 2.4 portal; fontes oficiais existentes e contrato editorial. **RF principais:** `RF-ACESSO-010`, `RF-RELATORIOS-015`, `RF-RELATORIOS-016`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não recriar dashboards do Epic 11 nem publicar acervo antes do aceite de migração.

**Acceptance Criteria:**

**AC-01.** **Given** fonte oficial com campos classificados como públicos; **When** publicar projeção/URL sem login; **Then** entregar somente allowlist de campos por finalidade, sem contato/endereço, logs, Zoom nominal, Kit ou dados bancários.

**AC-02.** **Given** fonte inexistente, não homologada ou em triagem; **When** acessar link publicado; **Then** exibir ausência/indisponibilidade sem endpoint fictício nem dado histórico não liberado.

**AC-03.** **Given** classificação de fonte muda ou publicação é revogada; **When** refazer consulta pública/cache/export; **Then** invalidar versões e impedir vazamento de registro antigo.

**AC-04.** **Given** visitante manipula parâmetros de API/filtros; **When** tentar acessar rota interna via link público; **Then** negar escalada por ID, contagem pequena identificável e projeção fora do contrato.

**Evidência futura e gate humano:** Parâmetros 03/05/12 e classificação editorial antes de divulgar dados reais. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-10-2"></a>
### Story 10.2: Publicar nome mínimo de ingresso/conquista com opt-out

**Como** responsável autorizado pela publicação,

**quero** liberar primeiro nome ou apelido mínimo quando permitido,

**para** preservar acolhimento e direito de ocultação.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 10.1; ingresso/conquista oficial de 5; triagem em 2. **RF principais:** `RF-RELATORIOS-020`, `RF-COMUNICACAO-001`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não vincular ingresso automaticamente a pessoa/conta nem transportar contato do Kit.

**Acceptance Criteria:**

**AC-01.** **Given** registro novo oficial sem opt-out e com nome mínimo editorialmente permitido; **When** gerar projeção nominal; **Then** publicar somente primeiro nome/apelido conforme política, sem sobrenome completo nem contato Kit.

**AC-02.** **Given** registro migrado ou importado sem revisão de privacidade; **When** consultar publicação; **Then** permanecer em_triagem até decisão de liberação humana por lote em 15.

**AC-03.** **Given** pedido de ocultação pelo Fale Conosco com protocolo; **When** registrar pedido; **Then** preservar publicação até decisão humana competente, sem exclusão automática pelo visitante.

**AC-04.** **Given** decisão de ocultar/retificar registrada; **When** republicar pela Policy da fonte; **Then** ocultação suprime a referência nominal pública, enquanto retificação substitui a forma anterior pela corrigida autorizada. Eliminar a forma pública obsoleta de HTML, cache, busca e exportações sob controle da plataforma, preservando histórico/trilha restritos e sem alterar retrospectivamente texto original por mera mudança cadastral.

**Evidência futura e gate humano:** Política opt-out/finalidade e procedimento de tratamento de pedido precisam ser homologados. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-10-3"></a>
### Story 10.3: Aplicar retificação editorial correlacionada ao Fale Conosco

**Como** responsável pela fonte com autorização vigente,

**quero** cumprir decisão humana sobre ocultação/correção,

**para** manter fonte e projeções coerentes sem acesso adicional.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 2.5/2.6 protocolo/triagem; 10.1/10.2 publicação. **RF principais (contribuição, sem novo ownership):** `RF-RELATORIOS-020`, `RF-COMUNICACAO-001`; consumidor de `RF-ATENDIMENTO-002` do Epic 2. **Fatia vertical:** ação editorial do dono da fonte na mesma caixa, correlacionada à decisão humana, sem mecanismo de UPDATE genérico ou segunda triagem.

**Limites:** Não recriar a caixa de atendimento nem uma API genérica de UPDATE de entidades.

**Acceptance Criteria:**

**AC-01.** **Given** protocolo existente, alvo permitido e decisão humana justificada; **When** corrigir/ocultar na fonte por sua Policy; **Then** registrar correlação, antes/depois redigidos e autor; atualizar projeções públicas a partir da fonte correta.

**AC-02.** **Given** pedido genérico sem alvo ou só com suspeita; **When** receber atendimento; **Then** manter triagem humana e não editar tabela arbitrária nem inferir registro.

**AC-03.** **Given** pedido relativo a dados privados Kit; **When** CG/SG encaminhar; **Then** usar percurso sanitizado e delegar ao Coordenador Kit sem revelar original privado aos administradores.

**AC-04.** **Given** corrigir fonte falha ou cache fica desatualizado; **When** processar revisão; **Then** não declarar pedido concluído até confirmar atualização/reconciliação, com retry idempotente.

**Evidência futura e gate humano:** E2-K e matriz de responsabilidades dos dados condicionam fluxo real. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-10-4"></a>
### Story 10.4: Garantir propagação, histórico e revogação de publicação

**Como** responsável editorial,

**quero** verificar rastreabilidade e revogação das informações públicas,

**para** evitar reaparecimento após cache, exportação ou migração.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 10.1–10.3. **RF principais (contribuição, sem novo ownership):** `RF-RELATORIOS-016`, `RF-RELATORIOS-020`, `RF-COMUNICACAO-001`; RNF-09/17, RB-20, CA-30/37. **Fatia vertical:** reconciliação operacional protegida de projeções após interrupção/reconstrução/restore, com pendências e resultado verificáveis; não transfere para esta Story a proteção básica já obrigatória em 10.1–10.3.

**Limites:** Exportações já retiradas por terceiros exigem política/resposta institucional específica; não prometer controle externo retroativo.

**Acceptance Criteria:**

**AC-01.** **Given** ocultação/correção autorizada e interrupção/reconstrução deixa projeção pendente; **When** operador autorizado executar reconciliação por fonte/versão; **Then** recompor HTML/JSON/CSV e índice de pesquisa controlados pela plataforma, registrar resultado/pendências e garantir que toda leitura pública revalide a fonte/visibilidade antes de servir. Nunca expor versão revogada enquanto a recomposição estiver pendente; falha mantém saída afetada indisponível, não pública obsoleta.

**AC-02.** **Given** evento repetido ou alteração concorrente da fonte; **When** reprocessar projeção; **Then** deduplicar por entidade/versão/efeito e impedir publicação de versão anterior.

**AC-03.** **Given** dados históricos aguardando avaliação; **When** consultar relatório ou reconstruir cache; **Then** nenhuma reconstrução libera automaticamente material em_triagem.

**AC-04.** **Given** visitante solicita ocultação e revisão ainda não concluída; **When** operador competente preparar retorno privado autorizado pelo atendimento 2.6; **Then** informar somente o estado real permitido, sem alegar resolução antecipada. Não criar consulta pública por protocolo, segredo de acompanhamento ou nova caixa; protocolo/recibo não concedem leitura do pedido.

**Evidência futura e gate humano:** Gates de retenção, publicação, notificação e homologação humana por lote. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

### Rastreabilidade proposta do Epic 10 — RF → Story → AC
Os IDs e a titularidade principal são os do mapa global aprovado. ACs indicam contratos **propostos**, não testes executados; RFs compostos dependem das integrações/gates explicitados no escopo.

| RF principal | Story e critérios propostos | Objeto de cobertura | Situação |
|---|---|---|---|
| RF-ACESSO-010 | 10.1 AC-01–04; [Story 10.1](#story-10-1) | Relatórios públicos | Proposto; execução/aceite condicionados ao RF integral |
| RF-RELATORIOS-015 | 10.1 AC-01–04; [Story 10.1](#story-10-1) | Relatórios públicos | Proposto; execução/aceite condicionados ao RF integral |
| RF-RELATORIOS-016 | 10.1 AC-01–04; 10.3 AC-01/03/04; 10.4 AC-01–03; [Story 10.1](#story-10-1) | Projeção mínima, retificação e reconciliação sem exposição obsoleta | Composto; proteção desde a primeira publicação |
| RF-RELATORIOS-020 | 10.2 AC-01–04; 10.3 AC-01–04; 10.4 AC-01–04; [Story 10.2](#story-10-2) | Opt-out, decisão humana correlacionada, efeitos e reconciliação | Histórico exige liberação editorial do lote; pedido não oculta automaticamente |
| RF-COMUNICACAO-001 | 10.2 AC-01–04; 10.3 AC-01–04; 10.4 AC-01–04; [Story 10.2](#story-10-2) | Nome mínimo novo opt-out, histórico triado, ocultação/correção e propagação | Composto; sem PII Kit e sem nova consulta pública de protocolo |

## Dependências, gates e aceite do Epic
- **Início e ordem:** implementar somente Stories autorizadas, precedidas pelos produtores reais necessários; a ordem editorial não substitui a verificação de capacidade já entregue. Qualquer dependência de componente futuro deve ser sinalizada e o consumidor adiado, sem simular funcionalidade.
- **Decisões humanas:** permanecem abertas as do índice e da cláusula de cada Story. Ausência de decisão não pode virar default normativo silencioso. Segurança, finalidade, canais, fornecedores, documentação e política de dados por classe antecedem a habilitação afetada.
- **Critério de encerramento:** revisar **todos** os RF principais acima, seus negativos e os contratos RNF/RB/CA/UX pertinentes no texto integral do PRD/UX. Não concluir Epic enquanto faltar funcionalidade composta exigida, homologação externa ou gate explicitamente aplicável.
- **Ponto de parada BMAD:** aguardar revisão independente individual/global, conciliação multidisciplinar e aceite humano do lote. Não adicionar ID a `approvedStoryIds` nem avançar `stepsCompleted` nesta entrega.

### Auditoria documental integral — 23–24/10/2026

Revisão/revalidação focal `ses_e949c48d7ffe9cZqN60z6Bjjof`, com regressão cruzada `ses_e949cc042ffelQAdv6ld6a1cFg`: **4 Stories / 16 AC / 5 RF principais**, cada AC-01–04 examinado individualmente. Sem residual dos três achados; CL-01–05 aplicáveis; coordenador único editor. Resultado global no índice; aprovação humana pendente.

| ID / severidade | Epic/Story/AC | Evidência original | Fonte normativa | Correção persistida | Resultado | Revalidação |
|---|---|---|---|---|---|---|
| A6 MAJOR | 10.4 AC-04 | Consulta pública de protocolo não autorizada | Limites 2.5/2.6; RF-ATENDIMENTO-001/002 | Retorno privado existente, sem endpoint público | Corrigido | Sessão acima; sem residual |
| A7 MINOR | 10.3/10.4; matriz | Contribuição RF/slicing ambíguos | BMAD 03/04; RNF-17 | RFs compostos ligados; reconciliação recuperável como valor próprio | Corrigido | Idem |
| A10 MINOR | 10.2 AC-04 | Retificar obrigava remover nome | RF-COMUNICACAO-001; RB-20 | Separadas supressão e substituição da forma pública | Corrigido | Idem |

**Contratos por Story, AC-01–04:** 10.1 → RNF-03/05–07/17, RB-10/17/20, CA-22/30/37, UX-DR38/52/58; 10.2 → RNF-03/05/17, RB-10/20, CA-22/30/37, UX-DR58; 10.3 → RNF-03–05/09/17, RB-07/10/17/20, CA-30/37, UX-DR57/58; 10.4 → RNF-03–05/09/17, RB-10/20, CA-30/37, UX-DR58. E2-K, política editorial por classe e liberação histórica continuam gates; protocolo nunca é credencial de consulta.
