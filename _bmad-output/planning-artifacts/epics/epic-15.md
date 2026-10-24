## Epic 15: Migrar o acervo e realizar a virada assistida com reconciliação
> **Estado vigente — APROVADO COMO PLANEJAMENTO CONDICIONADO em 24/10/2026.** Aceite humano expresso após auditoria/revalidação. Stories e AC abaixo preservados; expressões de proposta/revisão/aceite pendente são proveniência histórica. Gates institucionais e operacionais continuam abertos; sem autorização de build, dados reais, sprint ou deploy. Conclusão documental não encerra formalmente os passos 03/04 nem comprova readiness.

> **Fontes canônicas:** [índice global](../epics.md) · [PRD](../PRD.md) · [Product Brief](../ProductBrief.md) · [Arquitetura](../Arquitetura.md) · [UX Design](../UX-Design.md). **Detalhamento exclusivo deste Epic:** Stories, critérios de aceite, gates e matriz RF → Story → AC.

## Escopo aprovado no passo 02
**Objetivo:** responsáveis incorporam todo o legado ativo aplicável, comprovam paridade e desligam cada ferramenta antiga somente após aceite, com retorno seguro.

**Resultado verificável:** cargas idempotentes por domínio, origem/hash/lote/linha, ambiguidades revistas por servidores antigos, totais/nomes/histórico preservados e publicação em triagem até liberação humana. Demonstrar equivalência Apps Script/n8n/Docs/Looker e reprocessamento; backup, ensaio de restore, aceite por rotina/fonte e cutover assistido com contingência.

**RFs responsáveis (12):** RF-MIGRACAO-001–005, RF-MIGRACAO-010–011, RF-MIGRACAO-013–016; RF-AUTOMACOES-015.

**Dependências — início:** diagnóstico/mapas/autorização da fonte em 3, capacidade do destino daquele lote e backup/restore do escopo em 14. Não exige todos os módulos de 1/2/4–13 nem conclusão integral de 14. **Por capacidade:** carga de atas depende apenas do destino ata, Kit de destino Kit, financeiro de livro/categorias/saldo e serviço de suas entidades/documentos; paridade usa consultas específicas e indicadores de 11 somente quando necessários à comparação. **Incremental:** ampliar fontes, vínculos e rotinas conforme destinos reais, com decisões humanas e idempotência por lote; IA nunca é pré-requisito. **Produção/virada:** parâmetros 05/09/11, recuperação/retorno do escopo, equivalência funcional de todas as funções ativas abrangidas e aceite por responsável antes de desligar cada legado. Para o encerramento integral de 15, toda fonte/rotina ativa aplicável deve estar conciliada ou formalmente descontinuada, não apenas o primeiro lote.

**Limites:** reutilizar regras/rotinas/importação diagnóstica e projeções anteriores, sem reimplementar cada domínio. Não transportar scripts inseguros, criar autor fictício, inferir identidade ou transformar totais desconhecidos em zero. Fluxos descontinuados precisam de aprovação explícita; IA/Telegram desativados não são presumidos ativos. A numeração final deste Epic não adia inventário (Epic 3), prevenção de perda ou ensaios pequenos por domínio; formaliza a consolidação e o aceite, nunca impõe big-bang.
## Contratos de elaboração e revisão
- **Fonte e estado:** o PRD rege texto integral dos RF; o índice rege a titularidade do Epic e os contratos compartilhados. Uma referência de RF não comprova sua implementação nem aceite ponta a ponta.
- **Dependências por capacidade:** cada Story usa somente baseline ou produtores anteriores mencionados. Referências externas para integração, dados reais ou consumidores posteriores são *gates de aceite/ativação*, não autorização para antecipar tabelas, criar rotas falsas ou bloquear uma capacidade manual já utilizável.
- **Segurança e governança:** capacidades pelo mandato vigente/Policy no backend e na tela; exceção root somente por sessão pessoal elevada, justificada, temporal e auditada. Evento/alteração/auditoria e intenção outbox dentro da mesma transação quando aplicáveis, nunca chamadas HTTP externas antes do commit.
- **Dados e precisão:** preservar NULL distinto de zero; datas civis em `America/Bahia`, instantes em UTC; valores monetários decimais; filtros e exports usam mesma Policy. Não inferir pessoas a partir do Zoom nem mesclar homônimos.
- **Validação posterior:** testes com fixtures sintéticas, cenários positivos/negativos, sessão dormente, falhas, repetição, concorrência, PostgreSQL real, classificação de dados, mobile/teclado/leitor e comportamento degradado pertinentes. Contrato técnico de provedor exige homologação real, não apenas mock.
- **Limite da rodada:** não executar fluxo instalado, migrations, testes Laravel, comandos de importação, integração real, sprint, commit ou deploy. A revisão independente e correção adversarial ficam para o OpenCode no checkpoint humano.

## Stories propostas

<a id="story-15-1"></a>
### Story 15.1: Criar lote de migração rastreável com reprocessamento controlado

**Como** responsável pela migração autorizado,

**quero** executar lote com identidade de origem e linha,

**para** evitar perda, mistura de fontes e importação oculta.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 3.1–3.4 diagnóstico e 14.1–14.3 recuperação do escopo. **RF principais:** `RF-MIGRACAO-010`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não publicar dado importado automaticamente; operação real requer gate de fonte/backup/autorização.

**Acceptance Criteria:**

**AC-01.** **Given** pacote diagnóstico de 3 e destino existente; **When** CLI autorizada abrir lote por fonte; **Then** validar contrato E3-H/T: schema/versões compatíveis, manifesto/hashes, cadeia de decisões, referências e candidatos selecionados inequívocos, autorização vigente por classe, pendências e destinos físicos homologados. Negar pacote adulterado/incompatível/órfão ou decisão inválida sem conversão silenciosa; registrar lote/linha/arquivo/hash, transformações, resultados, contagens, avisos e decisões. Posse do shell não é autorização e não há tela de lotes.

**AC-02.** **Given** lote interrompido, arquivo sobreposto/reordenado, novo hash/mapa ou dois lotes concorrentes da mesma fonte/negócio; **When** executar/retomar; **Then** deduplicar pela identidade estável de domínio independente de lote/posição/hash do arquivo e manter toda proveniência. Rejeitar colisão incerta para revisão humana, nunca unir por nome; revisão do mapa não autoriza segundo efeito nem sobrescreve correção posterior. Testar todas as cargas pertinentes entre lotes distintos e duas conexões PostgreSQL, além do replay idêntico.

**AC-03.** **Given** linha inválida, origem incerta ou ambiguidade não revista; **When** processar; **Then** reter em quarentena de lote e contabilizar erro sem inventar identidade ou valor zero.

**AC-04.** **Given** relatório final com linhas importadas/erros/avisos/duplicatas; **When** reconciliar; **Then** conciliar total de entrada com cada resultado e registrar assinatura/aceite do responsável.

**Evidência futura e gate humano:** Autorizações, retorno/retensão de arquivos e critérios de reconciliação por lote. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-15-2"></a>
### Story 15.2: Migrar atas regulares preservando registros e versões

**Como** responsável autorizado pelo acervo de reuniões,

**quero** carregar histórico de atas a partir de origem validada,

**para** manter memória institucional sem duplicação ou exposição.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 15.1 e destino oficial do Epic 5. **RF principais:** `RF-MIGRACAO-001`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Sem repetir workflow de rascunho oficial como usuário fictício; proveniência distingue importação.

**Acceptance Criteria:**

**AC-01.** **Given** planilha Responses inventariada, mapa e campo histórico completo; **When** importar lote de atas; **Then** criar registros em triagem por ocorrência, preservar texto/nome original, valores/contagens e origem/linha/hash sem autor fictício.

**AC-02.** **Given** data/horário ambíguos, mesmas grafias ou referência financeira declarativa; **When** transformar; **Then** manter exceção para revisão humana; não fundir pessoas nem gerar lançamento contábil/Kit por simples carga.

**AC-03.** **Given** ata histórica já importada ou lote repetido; **When** reprocessar; **Then** upsert apenas quando identidade estável e versão compatível, sem sobrepor correção autorizada mais recente.

**AC-04.** **Given** lote não foi liberado editorialmente; **When** abrir relatório público/resumo; **Then** manter em_triagem, sem publicação nominal ou disparos externos.

**Evidência futura e gate humano:** Aceite de esquema/campos/paridade por servidores antigos antes de publicar acervo. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-15-3"></a>
### Story 15.3: Migrar histórico Kit sob segregação e sem contato indevido

**Como** Coordenador Kit vigente, com execução técnica sob autorização específica da classe,

**quero** conciliar dados de ingressos e remessas,

**para** preservar expedições sem revelar endereço fora do Kit.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 15.1 e capacidades Kit de 6.1/6.2/6.4/6.5 para estados/endereço/remessas; persistência de eventos de 6.6 somente quando a fonte contiver rastreio, e recuperação do escopo em 14. **RF principais:** `RF-MIGRACAO-002`. **Fatia vertical:** carga do Kit existente com segregação; automação externa só é necessária ao cutover da rotina que a utiliza, não à carga histórica manual.

**Limites:** Não alterar serviço postal externo nem reconstituir endereço ausente de forma inferida.

**Acceptance Criteria:**

**AC-01.** **Given** fonte Kit autorizada e mapa de ingresso/remessa conferido; **When** importar lote; **Then** preservar estado, contato cifrado, código protegido, eventos e origem/linha sob Policy Kit.

**AC-02.** **Given** homônimo, endereço divergente ou ingresso sem vínculo confiável; **When** mapear; **Then** registrar sugestão/pendência para o Coordenador Kit; servidores antigos recebem apenas contexto legitimamente autorizado e sanitizado, nunca PII Kit por integrarem a equipe. Não fundir automaticamente nem conceder acesso operacional ao executor técnico por posse do shell; aplicar E3-A/P e exclusividade da fonte.

**AC-03.** **Given** remessa histórica expedida e evento de ata reprocessado; **When** integrar; **Then** não criar novo Kit ou reenviar solicitação/endereço/código de rastreio.

**AC-04.** **Given** CG/SG, público ou arquivo de relatório geral; **When** consultar resultado da migração; **Then** não revelar payload bruto, telefone, endereço nem código individual.

**Evidência futura e gate humano:** Política 03/05/09, autorização Kit e recuperação da classe antes de carga real. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-15-4"></a>
### Story 15.4: Migrar tesouraria e reserva prudente com saldo conciliado

**Como** Tesoureiro vigente e responsável técnico autorizado,

**quero** importar lançamentos legados financeiros aprovados,

**para** preservar saldo sem dobrar valores do ASAAS.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 15.1 e livro do Epic 7 com saldo inicial/categorias aprovados. **RF principais:** `RF-MIGRACAO-003`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não atribuir lançamento a CG/SG nem inventar aprovação do Tesoureiro a dado antigo.

**Acceptance Criteria:**

**AC-01.** **Given** planilha de tesouraria/reserva validada e marco de corte explícito; **When** importar lote; **Then** registrar origem, categoria, valores decimais, datas e tipo de movimento com autoria histórica conhecida ou desconhecida honesta.

**AC-02.** **Given** movimento legado coincide com evento ASAAS já aplicado; **When** reconciliar; **Then** detectar identidade do efeito e impedir dupla entrada no saldo, mantendo evento original bruto.

**AC-03.** **Given** categoria incompatível, reserva divergente ou saldo desconhecido; **When** analisar; **Then** apontar diferenças e exigir decisão do Tesoureiro sem transformar ausência em zero.

**AC-04.** **Given** relatório de paridade do período e saldo inicial; **When** comparar contra fonte; **Then** demonstrar conciliação por categoria/movimento e manter lote pendente quando houver divergência não aceita.

**Evidência futura e gate humano:** Parâmetro 07/09 e assinatura de saldo inicial/recuperação antes da carga real. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-15-5"></a>
### Story 15.5: Migrar encargos, escalas, atas de serviço e documentos

**Como** SG/CG e responsáveis antigos autorizados,

**quero** preservar histórico institucional em fontes normalizadas,

**para** reconstruir quadro e acervo sem criar presenças fictícias.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 15.1 e recuperação 14 do escopo selecionado; mandatos/escalas usam somente 1/2, documentos/atas de serviço usam somente os produtores pertinentes de 8. **RF principais:** `RF-MIGRACAO-004`, `RF-MIGRACAO-005`. **Fatia vertical:** adaptadores estreitos do mesmo mecanismo de carga/proveniência de 15.1, não reconstrução dos dois domínios. Carga útil de mandatos não espera Drive; aceite integral desta Story exige ambos os adaptadores aplicáveis. Reconferir tamanho com formatos reais; se exceder um agente, dividir minimamente antes do build, mantendo rastreabilidade e ratificação humana.

**Limites:** Uma carga não concede mandato atual sem validação de vigência e autoridade.

**Acceptance Criteria:**

**AC-01.** **Given** fonte de servidores com datas e nomes históricos; **When** migrar mandatos/escalas; **Then** preservar estado/vigência e origem; vincular pessoa apenas após revisão humana de homônimos.

**AC-02.** **Given** documento ou PDF histórico de ata de serviço; **When** importar/referenciar; **Then** registrar identificador/versão/ACL Drive sem sobrescrever original, preservando agregados conhecidos.

**AC-03.** **Given** acervo contém só presentes_total/faltas_total/justificadas_total; **When** conciliar; **Then** não gerar lista nominal fictícia nem converter NULL em zero.

**AC-04.** **Given** falta documento ou encargo com intervalo inconsistente; **When** reconciliar; **Then** registrar divergência e bloquear liberação daquele item/lote até decisão competente.

**Evidência futura e gate humano:** Parâmetro 02/05/09 e autorização de acesso documental antes da migração. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-15-6"></a>
### Story 15.6: Comprovar equivalência de Apps Script, n8n, Docs e Looker

**Como** responsável pelas rotinas e domínio correspondente,

**quero** comparar execução antiga com funções Laravel,

**para** evitar desligar serviço ativo sem equivalente.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 3 inventário e módulos de destino reais de 1–14 conforme escopo. **RF principais:** `RF-MIGRACAO-013`, `RF-MIGRACAO-014`, `RF-MIGRACAO-015`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não duplicar handlers/cálculos existentes; cada dono implementa a rotina no seu Epic.

**Acceptance Criteria:**

**AC-01.** **Given** ata-resumo, ata-kitingresso, kit-ingresso ou servico-terminio confirmado ativo; **When** executar casos de comportamento sintéticos/autorizados; **Then** demonstrar equivalência de gatilho, regras, erro/idempotência e resultado sem copiar script inseguro.

**AC-02.** **Given** workflow n8n inventariado com origem/destino e credencial referenciada; **When** classificar função; **Then** relacionar implementação Laravel ou descontinuação formal humana, nunca presumir ativo ou obsoleto só pelo inventário antigo.

**AC-03.** **Given** visão VAGO/Relação de Servidores/Looker ou documento operacional; **When** comparar dados e fórmula; **Then** usar PostgreSQL e consultas existentes, registrando divergência de totais/critério/período.

**AC-04.** **Given** função depende de módulo não implementado ou parâmetro não homologado; **When** avaliar aceite; **Then** marcar cobertura parcial e impedir desligamento apenas dessa função, sem criar requisito de big-bang.

**Evidência futura e gate humano:** Responsável por rotina/fonte aceita equivalência e eventual descontinuação. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-15-7"></a>
### Story 15.7: Resolver divergências humanas e paridade de dados e relatórios

**Como** equipe de servidores antigos e responsáveis de domínio,

**quero** revisar conflitos e conferir totais antes da liberação,

**para** evitar fusão de homônimos e indicadores falsos.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 15.1, carga/equivalência pertinente ao lote ou rotina em avaliação dentre 15.2–15.6 e mapa/decisões de 3.3/3.4; não todas as cargas em conjunto. **RF principais:** `RF-MIGRACAO-011`, `RF-MIGRACAO-016`. **Fatia vertical:** reconciliação humana de escopo existente; encerramento integral exige todas as fontes aplicáveis, não impede paridade parcial útil.

**Limites:** Nunca aceitar colapsar homônimos apenas por nome ou deixar entrada sem origem rastreável.

**Acceptance Criteria:**

**AC-01.** **Given** nomes/ingressos/fichas/serviço com grafia semelhante; **When** abrir revisão; **Then** apresentar sugestões com contexto/evidência e permitir confirmar/rejeitar/adiar com justificativa humana.

**AC-02.** **Given** planilha/Looker e destino no mesmo período/definição; **When** comparar totais; **Then** documentar fonte, fórmula, campos excluídos, diferenças e aceitação pelo dono do dado.

**AC-03.** **Given** fusão rejeitada, dado desconhecido ou divergência ainda aberta; **When** tentar liberar lote; **Then** não consolidar vínculo nem publicar número que depende da decisão pendente.

**AC-04.** **Given** reprocessamento depois de nova decisão humana; **When** executar novamente; **Then** preservar decisão/autor/versão e atualizar somente efeitos vinculados, sem alterar acervo original. Após saneamento editorial humano do escopo, a CLI permite liberação explícita `em_triagem → liberado` por autoridade editorial homologada, com motivo, versão esperada, trilha e ausência de bloqueios; aceite técnico/financeiro não a substitui. Negar autoridade ausente/decisão obsoleta/pendência e preservar opt-out no replay/recarga, sem consentimento individual novo por linha ou publicação de Kit/Zoom/pagador.

**Evidência futura e gate humano:** Parâmetros 05/09 e critérios de tolerância/paridade definidos pelos responsáveis. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-15-8"></a>
### Story 15.8: Realizar virada assistida por rotina e testar retorno

**Como** responsável institucional pela transição,

**quero** autorizar desligamento gradual de legados aprovados,

**para** operar a nova fonte de verdade sem perder reversibilidade.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 15.1 e carga, equivalência e reconciliação de 15.2–15.7 pertinentes à fonte/rotina selecionada, backup/restore 14 e capacidades reais usadas por esse escopo. Não exige cargas de outros domínios para cutover parcial; encerramento integral exige todas as fontes/rotinas aplicáveis. **RF principais:** `RF-AUTOMACOES-015`. **Fatia vertical:** procedimento de virada assistida do escopo aceito, sem big-bang ou reconstrução de produtores.

**Limites:** Virada incremental por domínio, jamais big-bang obrigatório.

**Acceptance Criteria:**

**AC-01.** **Given** fonte/rotina com equivalência, backup, paridade e aceite específico; **When** autorizar cutover de escopo; **Then** registrar responsável, data, versão, checklist e reversão; somente então desabilitar gatilho legado correspondente.

**AC-02.** **Given** reprocessamento, webhook duplicado ou execução simultânea antiga/nova; **When** realizar janela de virada; **Then** garantir um efeito por identidade de negócio e detectar duplicidade sem processar silenciosamente em duas fontes.

**AC-03.** **Given** divergência, indisponibilidade ou recuperação falha após cutover; **When** acionar contingência; **Then** reverter escopo com procedimento ensaiado, registro de decisão e preservação da trilha sem perda de dados.

**AC-04.** **Given** parte do acervo ainda não inventariada ou ativo desconhecido; **When** tentar declarar migração integral concluída; **Then** negar aceite global até classificar toda fonte ativa/aplicável ou descontinuá-la formalmente.

**Evidência futura e gate humano:** Autorização humana de cutover, backup/restore, rollback e homologaçāo por rotina antes de qualquer desligamento. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

### Rastreabilidade proposta do Epic 15 — RF → Story → AC
Os IDs e a titularidade principal são os do mapa global aprovado. ACs indicam contratos **propostos**, não testes executados; RFs compostos dependem das integrações/gates explicitados no escopo.

| RF principal | Story e critérios propostos | Objeto de cobertura | Situação |
|---|---|---|---|
| RF-MIGRACAO-010 | 15.1 AC-01–04; [Story 15.1](#story-15-1) | Relatório de importação | Proposto; execução/aceite condicionados ao RF integral |
| RF-MIGRACAO-001 | 15.2 AC-01–04; [Story 15.2](#story-15-2) | Importar planilha de atas | Proposto; execução/aceite condicionados ao RF integral |
| RF-MIGRACAO-002 | 15.3 AC-01–04; [Story 15.3](#story-15-3) | Importar kit ingresso | Proposto; execução/aceite condicionados ao RF integral |
| RF-MIGRACAO-003 | 15.4 AC-01–04; [Story 15.4](#story-15-4) | Importar tesouraria | Proposto; execução/aceite condicionados ao RF integral |
| RF-MIGRACAO-004 | 15.5 AC-01–04; [Story 15.5](#story-15-5) | Importar quadro de serviços | Proposto; execução/aceite condicionados ao RF integral |
| RF-MIGRACAO-005 | 15.5 AC-01–04; [Story 15.5](#story-15-5) | Importar atas de serviço | Proposto; execução/aceite condicionados ao RF integral |
| RF-MIGRACAO-013 | 15.6 AC-01–04; [Story 15.6](#story-15-6) | Migração de scripts Google Apps Script | Proposto; execução/aceite condicionados ao RF integral |
| RF-MIGRACAO-014 | 15.6 AC-01–04; [Story 15.6](#story-15-6) | Migração de workflows n8n | Proposto; execução/aceite condicionados ao RF integral |
| RF-MIGRACAO-015 | 15.6 AC-01–04; [Story 15.6](#story-15-6) | Substituição de visões Google Docs/Looker | Proposto; execução/aceite condicionados ao RF integral |
| RF-MIGRACAO-011 | 15.7 AC-01–04; [Story 15.7](#story-15-7) | Validação contra relatórios atuais | Proposto; execução/aceite condicionados ao RF integral |
| RF-MIGRACAO-016 | 15.7 AC-01–04; [Story 15.7](#story-15-7) | Reconciliar históricos de ingressos, fichas, servidores e pessoas por sugestões cruzadas, revisadas por equipe de servidores antigos. | Proposto; execução/aceite condicionados ao RF integral |
| RF-AUTOMACOES-015 | 15.8 AC-01–04; [Story 15.8](#story-15-8) | Desativação segura | Proposto; execução/aceite condicionados ao RF integral |

## Dependências, gates e aceite do Epic
- **Início e ordem:** implementar somente Stories autorizadas, precedidas pelos produtores reais necessários; a ordem editorial não substitui a verificação de capacidade já entregue. Qualquer dependência de componente futuro deve ser sinalizada e o consumidor adiado, sem simular funcionalidade.
- **Decisões humanas:** permanecem abertas as do índice e da cláusula de cada Story. Ausência de decisão não pode virar default normativo silencioso. Segurança, finalidade, canais, fornecedores, documentação e política de dados por classe antecedem a habilitação afetada.
- **Critério de encerramento:** revisar **todos** os RF principais acima, seus negativos e os contratos RNF/RB/CA/UX pertinentes no texto integral do PRD/UX. Não concluir Epic enquanto faltar funcionalidade composta exigida, homologação externa ou gate explicitamente aplicável.
- **Ponto de parada BMAD:** aguardar revisão independente individual/global, conciliação multidisciplinar e aceite humano do lote. Não adicionar ID a `approvedStoryIds` nem avançar `stepsCompleted` nesta entrega.

### Auditoria documental integral — 23–24/10/2026

Revisão/revalidação focal `ses_e949bfb7effeMsV1as7jHml9pw`, com regressão cruzada `ses_e949cc042ffelQAdv6ld6a1cFg`: **8 Stories / 32 AC / 12 RF principais**, cada AC-01–04 examinado individualmente; quatro achados corrigidos sem residual focal. CL-01–05 aplicáveis; coordenador único editor. Resultado global no índice; nada importado, publicado ou desligado.

| ID / severidade | Epic/Story/AC | Evidência original | Fonte normativa | Correção persistida | Resultado | Revalidação |
|---|---|---|---|---|---|---|
| M-F1 MAJOR | 15.1 AC-01 | Handoff só citado, não validado integralmente | 3.4 AC-07; E3-H/T | Validação executável CLI de versões/hashes/cadeia/candidatos/autoridade/bloqueios | Corrigido | Sessão acima; sem residual |
| M-F2 MAJOR | 15.1 AC-02; cargas | Dedup explícito só mesmo lote/hash | RNF-08/09/12; Arquitetura §9 | Identidade de domínio independente de lote/linha/hash, conflitos humanos | Corrigido | Idem |
| M-F6 MINOR | 15.5/15.7/15.8 | Intervalos exigiam cargas de outros domínios | Macro incremental; BMAD 03/04 | Dependências por escopo; mandatos sem Drive; integral separado | Corrigido | Idem; tamanho 15.5 condicionado aos formatos |
| M-F7 MAJOR | 15.7 AC-04 | Só bloqueio editorial, sem liberação positiva | RB-20; Arquitetura §6.3; Epic 10 | CLI editorial explícita com autoridade/versão/trilha/opt-out | Corrigido | Idem |

**Contratos por Story, AC-01–04:** 15.1 → RNF-03–05/08/09/12/16, RB-05/12–14/17/20/21, CA-09/14/17/18, UX-DR50/63; 15.2 → RNF-03–05/08/09/12/17, RB-01–05/12/20/21, CA-09/14/18, UX-DR28/50/58/63; 15.3 → RNF-03–05/08/09/12/17, RB-07/12/17/21, CA-07/09/16/22/36, UX-DR34–36/50/63; 15.4 → RNF-03–05/08/09/12/17, RB-08/12/17/19, CA-04/10/14/21/28, UX-DR37/38/50/63; 15.5 → RNF-03–05/08/09/12, RB-04/05/11/12/15/16/22, CA-09/19/24/38, UX-DR40/47/48/50/63; 15.6 → RNF-03–05/08–10/12/16, RB-12/13/23, CA-17/18, UX-DR50/63; 15.7 → RNF-03–05/09/12/17, RB-10/12–14/20/21, CA-09/14/18/22/30/37, UX-DR50/54/58/63; 15.8 → RNF-01/03–05/08/09/11/12/16/17, RB-12/13/17/20, CA-12/14/17/18/35, UX-DR49/50/53/63. E3-A/F/P/T/N/H, autorização editorial/por fonte, tolerâncias, saldo inicial, paridade, retorno e aceite por rotina permanecem gates humanos, sem big-bang.
