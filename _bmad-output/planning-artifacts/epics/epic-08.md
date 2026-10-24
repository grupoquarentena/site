## Epic 8: Formalizar reuniões de serviço e preservar decisões e documentos
> **Estado vigente — APROVADO COMO PLANEJAMENTO CONDICIONADO em 24/10/2026.** Aceite humano expresso após auditoria/revalidação. Stories e AC abaixo preservados; expressões de proposta/revisão/aceite pendente são proveniência histórica. Gates institucionais e operacionais continuam abertos; sem autorização de build, dados reais, sprint ou deploy. Conclusão documental não encerra formalmente os passos 03/04 nem comprova readiness.

> **Fontes canônicas:** [índice global](../epics.md) · [PRD](../PRD.md) · [Product Brief](../ProductBrief.md) · [Arquitetura](../Arquitetura.md) · [UX Design](../UX-Design.md). **Detalhamento exclusivo deste Epic:** Stories, critérios de aceite, gates e matriz RF → Story → AC.

## Escopo aprovado no passo 02
**Objetivo:** SG mantém modelo, redige/finaliza atas de serviço e preserva decisões/PDFs com consulta apropriada.

**Resultado verificável:** template versionado, pré-preenchimento, rascunho retomável, finalização oficial sem aprovação adicional, PDF privado no Drive, histórico e correção/anulação justificada com versões anteriores preservadas. Relatório próprio usa totais históricos mesmo sem presenças nominativas.

**RFs responsáveis (12):** RF-ATASERVICO-001–010; RF-RELATORIOS-011; RF-ADMINISTRACAO-009.

**Dependências — início:** autorização SG/trilha/pessoa vigentes de 1, sem exigir todos os RF de 1/2/4. **Por capacidade:** template/rascunho requerem persistência e versão; finalização/PDF/arquivo oficial exige integração Drive privada funcional, entregue no próprio incremento. **Incremental:** pré-preenchimento por agenda de 2 quando disponível; notificações usam transporte mínimo de 4 somente se previstas/habilitadas. **Produção:** parâmetro 06, credencial/ACL Drive, recuperação dos documentos/chaves e preservação de revisões comprovadas. Falha Drive não permite declarar arquivamento definitivo bem-sucedido nem aceitar RF-ATASERVICO-005.

**Limites:** desconhecido continua nulo; não inventar listas/zeros na migração. Versionar documento, template e metadados sem transformar Drive em banco. Publicação só de decisões autorizadas; faltas/justificativas permanecem restritas. O acervo do RSG aproveitará essa capacidade no Epic 11.
## Contratos de elaboração e revisão
- **Fonte e estado:** o PRD rege texto integral dos RF; o índice rege a titularidade do Epic e os contratos compartilhados. Uma referência de RF não comprova sua implementação nem aceite ponta a ponta.
- **Dependências por capacidade:** cada Story usa somente baseline ou produtores anteriores mencionados. Referências externas para integração, dados reais ou consumidores posteriores são *gates de aceite/ativação*, não autorização para antecipar tabelas, criar rotas falsas ou bloquear uma capacidade manual já utilizável.
- **Segurança e governança:** capacidades pelo mandato vigente/Policy no backend e na tela; exceção root somente por sessão pessoal elevada, justificada, temporal e auditada. Evento/alteração/auditoria e intenção outbox dentro da mesma transação quando aplicáveis, nunca chamadas HTTP externas antes do commit.
- **Dados e precisão:** preservar NULL distinto de zero; datas civis em `America/Bahia`, instantes em UTC; valores monetários decimais; filtros e exports usam mesma Policy. Não inferir pessoas a partir do Zoom nem mesclar homônimos.
- **Validação posterior:** testes com fixtures sintéticas, cenários positivos/negativos, sessão dormente, falhas, repetição, concorrência, PostgreSQL real, classificação de dados, mobile/teclado/leitor e comportamento degradado pertinentes. Contrato técnico de provedor exige homologação real, não apenas mock.
- **Limite da rodada:** não executar fluxo instalado, migrations, testes Laravel, comandos de importação, integração real, sprint, commit ou deploy. A revisão independente e correção adversarial ficam para o OpenCode no checkpoint humano.

## Stories propostas

<a id="story-8-1"></a>
### Story 8.1: Versionar o modelo institucional de ata de serviço

**Como** Secretário Geral vigente,

**quero** manter o modelo das atas de serviço com prévia,

**para** preservar o padrão utilizado por cada reunião.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 1.6 SG e auditoria. **RF principais:** `RF-ATASERVICO-002`, `RF-ATASERVICO-010`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não armazenar documento institucional somente no Drive; metadados e revisão no PostgreSQL.

**Acceptance Criteria:**

**AC-01.** **Given** SG com capacidade e modelo institucional homologado, inclusive primeira configuração; **When** criar/editar seções, textos/pauta e conferir prévia local; **Then** gravar versão auditada com uma única versão vigente por chave e manter versões anteriores imutáveis para atas já abertas, sem depender de ata/PDF futuro para usar o editor.

**AC-02.** **Given** duas edições concorrentes ou template inválido; **When** salvar; **Then** recusar revisão obsoleta/injeção e manter última versão confirmada sem perder histórico.

**AC-03.** **Given** CG, moderador ou conta sem SG vigente; **When** editar modelo por endpoint; **Then** negar mutação, sem conceder permissão pelo simples acesso ao painel.

**AC-04.** **Given** novo modelo e reunião histórica; **When** abrir prévia; **Then** identificar versão e distinguir conteúdo padrão vigente de cópia usada pela ata histórica.

**Evidência futura e gate humano:** Definir modelo oficial e acesso documental antes de usar dados reais. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-8-2"></a>
### Story 8.2: Abrir e retomar rascunho de ata de serviço

**Como** Secretário Geral vigente,

**quero** criar ata do encontro a partir do modelo escolhido,

**para** registrar decisões sem planilhas nem segundo aceite obrigatório.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 8.1; agenda 2 somente quando existir ocorrência apropriada. **RF principais:** `RF-ATASERVICO-001`, `RF-ATASERVICO-003`, `RF-ATASERVICO-004`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não exigir segunda aprovação do CG; campo ausente de reunião não pode virar fato inventado.

**Acceptance Criteria:**

**AC-01.** **Given** SG ativo, reunião de serviço identificada e template vigente; **When** abrir ata; **Then** copiar versão imutável do modelo, sugerir data/tipo/servidores/pauta quando disponíveis e criar rascunho retomável no servidor.

**AC-02.** **Given** salvamento sucessivo, desconexão celular ou dois editores; **When** salvar rascunho por versão esperada; **Then** confirmar persistência no servidor e detectar conflito sem apagar texto confirmado.

**AC-03.** **Given** rascunho não finalizado; **When** consultar indicadores/publicações/PDF oficial; **Then** não contabilizar documento como oficial nem emitir versão definitiva.

**AC-04.** **Given** SG termina mandato ou navegador tenta finalizar indevidamente; **When** abrir/editar por rota direta; **Then** revalidar capacidade na requisição e negar operação sem autoridade atual.

**Evidência futura e gate humano:** Parâmetro 06 e regras de salvamento/retomada precisam de ensaio em dispositivo móvel. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-8-3"></a>
### Story 8.3: Registrar presenças, ausências, justificativas e totais históricos

**Como** Secretário Geral vigente,

**quero** registrar dados de reunião de serviço sem inventar nomes,

**para** preservar números mesmo quando legado só contém totais.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 8.2. **RF principais:** `RF-ATASERVICO-006`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não inferir presenças a partir do Zoom nem criar identidade por nome solto.

**Acceptance Criteria:**

**AC-01.** **Given** reunião com dados disponíveis; **When** SG registrar presença, ausência, justificativa, interessados, contexto, links/documentos relacionados e totais próprios; **Then** persistir valores inteiros não negativos e referências autorizadas sem somar a lista uma segunda vez. Registrar também decisões/consciências, inclusive restritas, com texto, data, contexto e vínculo à ata; registro não autoriza publicação nem concede mandato automaticamente.

**AC-02.** **Given** reunião histórica só possui totais agregados; **When** registrar presentes_total, faltas_total e justificadas_total; **Then** aceitar totais desacompanhados de listas, mantendo identidade desconhecida sem criar pessoa fictícia.

**AC-03.** **Given** total desconhecido, lista parcial ou inconsistência numérica; **When** validar/editar; **Then** preservar NULL distinto de zero e validar `justificadas_total <= faltas_total` quando ambos conhecidos; rejeitar negativos, frações e violação dessa relação, sem dispensá-la por justificativa. Divergência entre lista parcial e total declarado é identificada separadamente para conferência humana; desconhecido lícito não vira erro automático, nome fictício ou zero.

**AC-04.** **Given** justificativa pessoal sensível; **When** consultar/exportar relatório público; **Then** proteger detalhes individuais e não os incluir em agregados ou logs.

**Evidência futura e gate humano:** Política de visibilidade e retenção de justificativas por classe. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-8-4"></a>
### Story 8.4: Finalizar ata e arquivar PDF privado no Google Drive

**Como** Secretário Geral vigente,

**quero** finalizar oficialmente e recuperar o PDF institucional,

**para** manter o acervo versionado e acessível somente aos autorizados.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 8.1–8.3; integração Drive com ACL própria. **RF principais:** `RF-ATASERVICO-005`, `RF-ADMINISTRACAO-009`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** PostgreSQL é fonte de estado e relação; Drive é armazenamento documental, não banco de decisões.

**Acceptance Criteria:**

**AC-01.** **Given** ata conferida com dados e autoridade válidos; **When** SG solicitar finalização; **Then** gerar versão oficial identificada e PDF, registrar metadados PostgreSQL e arquivar em pasta privada Drive com ACL verificada.

**AC-02.** **Given** Drive indisponível, credencial inválida ou upload incerto; **When** finalizar; **Then** não afirmar PDF arquivado sem confirmação; manter estado recuperável/reconciliação, sem duplicar arquivo nem perder a versão da ata.

**AC-03.** **Given** repetição, duas finalizações concorrentes ou upload concluído após timeout; **When** retentar; **Then** deduplicar operação por versão/efeito e reconciliar ID/hashes do objeto com metadados sem substituir arquivo anterior.

**AC-04.** **Given** visitante, servidor comum ou link público manipulado; **When** abrir PDF/listar IDs; **Then** negar acesso ao documento e às justificativas, aplicando ACL Drive e Policy Laravel coerentes.

**Evidência futura e gate humano:** Credencial/ACL, recuperação de PDFs/chaves e política de acesso exigem homologação para uso real. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-8-5"></a>
### Story 8.5: Conferir, corrigir ou anular ata de serviço mantendo versões

**Como** Secretário Geral vigente ou usuário em sessão root pessoal elevada válida,

**quero** registrar correção justificada após finalização,

**para** proteger histórico e documentos oficiais anteriores.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 8.4. **RF principais:** `RF-ATASERVICO-007`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não usar DELETE do Drive para representar anulação institucional; não criar aprovação extra.

**Acceptance Criteria:**

**AC-01.** **Given** ata oficial existente e responsável autorizado; **When** conferir sem alterar conteúdo; **Then** registrar conferência distinguindo revisão de correção material, com autoria e instante.

**AC-02.** **Given** correção autorizada com motivo ou anulação justificada por SG vigente/root pessoal elevado; **When** confirmar mudança; **Then** criar revisão imutável e nova versão documental/PDF privado correspondente à correção/anulação, mantendo versões, PDFs e motivos anteriores acessíveis ao autorizado. Conferência sem alteração registra seu ato sem recriar conteúdo. Nenhuma segunda aprovação é exigida; finalização/correção/anulação e outbox são transacionais, upload posterior aplica o mesmo mecanismo de reconciliação de 8.4.

**AC-03.** **Given** versão desatualizada, conflito simultâneo ou upload incompleto; **When** corrigir/anular; **Then** bloquear sobrescrita ou inconsistência entre estado e arquivo, fornecendo retomada segura.

**AC-04.** **Given** visitante ou servidor sem poder de retificação; **When** enviar PATCH ou tentar arquivo Drive anterior; **Then** negar mutação/leitura e não apagar original.

**Evidência futura e gate humano:** Critérios de correção e retenção histórica da Secretaria Geral homologados. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-8-6"></a>
### Story 8.6: Consultar histórico e publicar somente decisões autorizadas

**Como** servidor autorizado ou visitante sob regra editorial,

**quero** consultar reuniões de serviço e decisões por período,

**para** encontrar acervo sem expor faltas e justificativas pessoais.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 8.3–8.5. **RF principais:** `RF-ATASERVICO-008`, `RF-ATASERVICO-009`, `RF-RELATORIOS-011`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não criar dashboard geral do Epic 11; consulta da reunião de serviço é domínio próprio.

**Acceptance Criteria:**

**AC-01.** **Given** atas oficiais e seus totais próprios; **When** filtrar relatório por data/tipo/presenças/faltas/justificativas/decisões; **Then** retornar agregados e versões oficiais corretos, excluindo rascunhos/anuladas dos indicadores e aplicando correções vigentes. Ao autorizado, oferecer participantes, faltas e justificativas individuais existentes e PDFs privados com Policy própria; quando lista histórica não existir, totais continuam consultáveis/filtráveis sem fabricar indivíduos.

**AC-02.** **Given** decisão/consciência registrada em 8.3 com referência de ata, data/contexto e classificação publicável; **When** operador competente registrar a autorização institucional de divulgação homologada; **Then** mostrar somente síntese autorizada, data/contexto/registro, sem PDF privado ou justificativas pessoais. Ser SG ou cadastrar a decisão não substitui a autoridade editorial ainda sujeita a homologação; decisão restrita permanece no acervo privado.

**AC-03.** **Given** decisão restrita, correção ou anulação posterior; **When** abrir rota pública/cache/exportação; **Then** negar ou invalidar versão pública afetada, preservando rastro privado.

**AC-04.** **Given** servidor não autorizado a detalhes pessoais; **When** acessar filtros/CSV/documentos; **Then** mostrar somente projeção permitida; documentos privados requerem permissão própria.

**Evidência futura e gate humano:** Política de divulgação/retenção e testes de documento/link Drive antes de uso real. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

### Rastreabilidade proposta do Epic 8 — RF → Story → AC
Os IDs e a titularidade principal são os do mapa global aprovado. ACs indicam contratos **propostos**, não testes executados; RFs compostos dependem das integrações/gates explicitados no escopo.

| RF principal | Story e critérios propostos | Objeto de cobertura | Situação |
|---|---|---|---|
| RF-ATASERVICO-002 | 8.1 AC-01–04; [Story 8.1](#story-8-1) | Template padrão | Proposto; execução/aceite condicionados ao RF integral |
| RF-ATASERVICO-010 | 8.1 AC-01–04; 8.4 AC-01–04; [Story 8.1](#story-8-1) | Template/versões/prévia e PDF/Drive privado | Composto; editor local não conclui geração/arquivamento |
| RF-ATASERVICO-001 | 8.2 AC-01–04; [Story 8.2](#story-8-2) | Criar ata de serviço | Proposto; execução/aceite condicionados ao RF integral |
| RF-ATASERVICO-003 | 8.2 AC-01–04; [Story 8.2](#story-8-2) | Pré-preenchimento | Proposto; execução/aceite condicionados ao RF integral |
| RF-ATASERVICO-004 | 8.2 AC-01–04; 8.4 AC-01–04; [Story 8.2](#story-8-2) | Rascunho persistido e finalização oficial SG sem segunda aprovação | Composto; rascunho isolado não conclui finalização |
| RF-ATASERVICO-006 | 8.3 AC-01–04; [Story 8.3](#story-8-3) | Dados da reunião de serviço | Proposto; execução/aceite condicionados ao RF integral |
| RF-ATASERVICO-005 | 8.4 AC-01–04; [Story 8.4](#story-8-4) | Geração de PDF | Proposto; execução/aceite condicionados ao RF integral |
| RF-ADMINISTRACAO-009 | 8.4 AC-01–04; [Story 8.4](#story-8-4) | Armazenamento Google Drive | Proposto; execução/aceite condicionados ao RF integral |
| RF-ATASERVICO-007 | 8.5 AC-01–04; 8.6 AC-01/03/04; [Story 8.5](#story-8-5) | Histórico/filtros, correções/anulações e relatórios | Composto; exige consulta e ciclo documental |
| RF-ATASERVICO-008 | 8.3 AC-01; 8.6 AC-02/03; [Story 8.6](#story-8-6) | Registro de decisões/consciências e divulgação somente autorizada | Registro e publicação têm autoridades/aceites distintos |
| RF-ATASERVICO-009 | 8.6 AC-01–04; [Story 8.6](#story-8-6) | Consulta pública ou restrita | Proposto; execução/aceite condicionados ao RF integral |
| RF-RELATORIOS-011 | 8.6 AC-01–04; [Story 8.6](#story-8-6) | Página Reuniões de Serviço | Proposto; execução/aceite condicionados ao RF integral |

## Dependências, gates e aceite do Epic
- **Início e ordem:** implementar somente Stories autorizadas, precedidas pelos produtores reais necessários; a ordem editorial não substitui a verificação de capacidade já entregue. Qualquer dependência de componente futuro deve ser sinalizada e o consumidor adiado, sem simular funcionalidade.
- **Decisões humanas:** permanecem abertas as do índice e da cláusula de cada Story. Ausência de decisão não pode virar default normativo silencioso. Segurança, finalidade, canais, fornecedores, documentação e política de dados por classe antecedem a habilitação afetada.
- **Critério de encerramento:** revisar **todos** os RF principais acima, seus negativos e os contratos RNF/RB/CA/UX pertinentes no texto integral do PRD/UX. Não concluir Epic enquanto faltar funcionalidade composta exigida, homologação externa ou gate explicitamente aplicável.
- **Ponto de parada BMAD:** aguardar revisão independente individual/global, conciliação multidisciplinar e aceite humano do lote. Não adicionar ID a `approvedStoryIds` nem avançar `stepsCompleted` nesta entrega.

### Auditoria documental integral — 23–24/10/2026

Revisão e revalidação focal somente leitura `ses_e949c70b4ffeP3CPTRSZfPhilp`: **6 Stories / 24 AC / 12 RF principais**, todos os AC-01–04 individualmente examinados; sem residual dos três achados abaixo. Coordenador único editor; CL-01–05 aplicáveis. Parecer global/evidências no índice; aceite humano e homologação Drive pendentes.

| ID / severidade | Epic/Story/AC | Evidência original | Fonte normativa | Correção persistida | Resultado | Revalidação |
|---|---|---|---|---|---|---|
| E8-01 MAJOR | 8.3 AC-01; 8.6 AC-02 | Dados complementares/registro de decisões faltavam | RF-ATASERVICO-006/008 | Interessados/contexto/documentos e decisões inclusive restritas | Corrigido | Sessão acima; sem residual |
| E8-02 MAJOR | 8.6 AC-01 | Consulta positiva só agregada | RF-ATASERVICO-007; RF-RELATORIOS-011 | Nominais autorizados/PDFs quando disponíveis; agregados sem lista | Corrigido | Idem |
| E8-03 MAJOR | 8.3 AC-03 | Justificativa podia dispensar invariante numérica | Arquitetura §6.4 | Inteiros, NULL e justificadas ≤ faltas quando conhecidos | Corrigido | Idem |

**Contratos por Story, AC-01–04:** 8.1 → RNF-03–06/09, RB-17/22, CA-24, UX-DR46; 8.2 → RNF-03–06/09, RB-01/04/22, CA-24/38, UX-DR47; 8.3 → RNF-03–06, RB-04/05/13/22, CA-38, UX-DR47/48; 8.4 → RNF-01/03–05/08/09/11, RB-04/22, CA-24/38, UX-DR47; 8.5 → RNF-03–05/08/09, RB-04/22, CA-38, UX-DR48; 8.6 → RNF-03/05/06/17, RB-04/10/22, CA-38, UX-DR48/52. Autoridade editorial, justificativas privadas, custódia/ACL Drive e recuperação seguem gates, sem segunda aprovação de finalização.
