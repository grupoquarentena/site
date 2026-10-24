## Epic 14: Manter o serviço recuperável, documentado e replicável
> **Estado vigente — APROVADO COMO PLANEJAMENTO CONDICIONADO em 24/10/2026.** Aceite humano expresso após auditoria/revalidação. Stories e AC abaixo preservados; expressões de proposta/revisão/aceite pendente são proveniência histórica. Gates institucionais e operacionais continuam abertos; sem autorização de build, dados reais, sprint ou deploy. Conclusão documental não encerra formalmente os passos 03/04 nem comprova readiness.

> **Fontes canônicas:** [índice global](../epics.md) · [PRD](../PRD.md) · [Product Brief](../ProductBrief.md) · [Arquitetura](../Arquitetura.md) · [UX Design](../UX-Design.md). **Detalhamento exclusivo deste Epic:** Stories, critérios de aceite, gates e matriz RF → Story → AC.

## Escopo aprovado no passo 02
**Objetivo:** responsáveis operam os fluxos publicados, recuperam incidentes e preparam instalação segura no domínio oficial ou fork limpo, sem depender do conhecimento de uma única pessoa.

**Resultado verificável:** material de tarefa por módulo e administração, instalação parametrizada, backup de dados/arquivos e recuperação isolada conferida, contingência e teste de fork sem dados/segredos herdados. Validar acesso HTTPS via Tunnel/Swarm, hosts/proxies/cookies/cache, webhooks e ausência de portas públicas de origem, mediante autorização específica de implantação.

**RFs responsáveis (11):** RF-BACKUP-001–006; RF-DOCUMENTACAO-001–005.

**Dependências — início:** apenas fundação #33/PR #34, sem exigir 1–13 completos. **Por capacidade:** backup/restore real inicial de dados de teste, integridade e recuperação de chaves/arquivos necessários antecedem o primeiro dado operacional; ampliação cobre documentos/PII/chaves de cada módulo antes de seu uso real. Instruções acompanham os incrementos. **Incremental:** homologação da origem/Tunnel usa landing implementada em 2, não todos os módulos; documentação consolidada/fork e ensaios de release exigem exatamente os componentes incluídos no release. **Produção:** parâmetros 03/04/10/11, #30 para marca final pública, autorização de implantação, restore do escopo exposto e gates de segurança por rota; release parcial não equivale a produto completo. Infra fornece evidência para RF-PUBLICO-001, cujo responsável principal passa a 2.

**Limites:** snapshot estrutural e Swarm dev da #33 são reaproveitados, mas não substituem restore real de dados/documentos/chaves, fallback de Tunnel e treinamento observado. Validação da origem/domínio pode ocorrer em implantação controlada sem liberar dados históricos ainda não reconciliados. Publicar imagem, configurar rede, executar migration e promover produção exigem as autorizações próprias; esta proposta não as concede.
## Contratos de elaboração e revisão
- **Fonte e estado:** o PRD rege texto integral dos RF; o índice rege a titularidade do Epic e os contratos compartilhados. Uma referência de RF não comprova sua implementação nem aceite ponta a ponta.
- **Dependências por capacidade:** cada Story usa somente baseline ou produtores anteriores mencionados. Referências externas para integração, dados reais ou consumidores posteriores são *gates de aceite/ativação*, não autorização para antecipar tabelas, criar rotas falsas ou bloquear uma capacidade manual já utilizável.
- **Segurança e governança:** capacidades pelo mandato vigente/Policy no backend e na tela; exceção root somente por sessão pessoal elevada, justificada, temporal e auditada. Evento/alteração/auditoria e intenção outbox dentro da mesma transação quando aplicáveis, nunca chamadas HTTP externas antes do commit.
- **Dados e precisão:** preservar NULL distinto de zero; datas civis em `America/Bahia`, instantes em UTC; valores monetários decimais; filtros e exports usam mesma Policy. Não inferir pessoas a partir do Zoom nem mesclar homônimos.
- **Validação posterior:** testes com fixtures sintéticas, cenários positivos/negativos, sessão dormente, falhas, repetição, concorrência, PostgreSQL real, classificação de dados, mobile/teclado/leitor e comportamento degradado pertinentes. Contrato técnico de provedor exige homologação real, não apenas mock.
- **Limite da rodada:** não executar fluxo instalado, migrations, testes Laravel, comandos de importação, integração real, sprint, commit ou deploy. A revisão independente e correção adversarial ficam para o OpenCode no checkpoint humano.

## Stories propostas

<a id="story-14-1"></a>
### Story 14.1: Produzir backup consistente do PostgreSQL e verificar integridade

**Como** responsável técnico autorizado,

**quero** obter backup restaurável de dados críticos,

**para** não depender de um volume ou snapshot estrutural.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** BASE PostgreSQL 17 e ambiente autorizado. **RF principais:** `RF-BACKUP-001`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não confundir schema snapshot da fundação com backup de dados operacionais.

**Acceptance Criteria:**

**AC-01.** **Given** banco de teste com registros e alterações concorrentes e dependências do escopo identificadas; **When** executar procedimento de backup consistente; **Then** produzir conjunto recuperável com manifesto de data/versão/hash, ponto consistente do banco, arquivos privados/documentos Drive e referências às versões das chaves necessárias à decifragem. Proteger cópias e custódia fora do volume único, sem colocar chave em claro junto ao dump; incluir documentos produzidos cotidianamente, não somente snapshots de migração. Antes do primeiro dado operacional e de cada ampliação de classe, comprovar o conjunto correspondente, sem exigir módulos ainda inexistentes.

**AC-02.** **Given** backup truncado, processo falha ou disco indisponível; **When** verificar integridade; **Then** marcar falha e não publicar cópia como recuperável; nunca apagar último backup íntegro.

**AC-03.** **Given** segredo ou PII nos dumps; **When** armazenar/transferir; **Then** aplicar cifra/ACL e excluir segredos dos logs, sem versionar dump em Git.

**AC-04.** **Given** operador sem autoridade e credenciais inadequadas; **When** tentar backup; **Then** negar acesso e preservar evidência mínima da execução.

**Evidência futura e gate humano:** Definir destino, retenção, custódia e RTO/RPO sem números presumidos. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-14-2"></a>
### Story 14.2: Restaurar banco e dependências em ambiente isolado

**Como** responsável técnico de recuperação,

**quero** ensaiar restore completo sem afetar produção,

**para** demonstrar possibilidade real de retomada.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 14.1; escopo de arquivos/chaves identificado. **RF principais:** `RF-BACKUP-002`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Inclui dependências necessárias ao dado; snapshots/migrations não comprovam restore operacional.

**Acceptance Criteria:**

**AC-01.** **Given** conjunto de backup íntegro, ambiente isolado e custódia autorizada das chaves; **When** restaurar PostgreSQL, arquivos/documentos e dependências do manifesto; **Then** conferir contagens/amostras/hashes, abrir documento recuperado e decifrar dado sintético sob Policy autorizada, sem alterar origem nem revelar chaves. Reaplicar revogações/expurgos e política por classe antes de disponibilizar consultas; restore não ressuscita PII descartada, sessões privilegiadas ou bootstrap consumido.

**AC-02.** **Given** chave ausente, arquivo externo faltante ou versão incompatível; **When** validar restore; **Then** indicar escopo não recuperado, sem declarar resultado PASS parcial como completo.

**AC-03.** **Given** fork ou ambiente restaurado, inclusive backup anterior a efeitos externos já realizados; **When** inicializar serviços; **Then** manter workers de saída, rotinas, webhooks públicos e credenciais da origem desabilitados/quarentenados. Antes da retomada produtiva especificamente autorizada, reconciliar efeitos externos posteriores ao ponto do backup contra evidência confiável do provedor/registro independente; resultado não comprovável permanece bloqueado para intervenção. Restaurar marcas locais antigas não autoriza reenviar mensagem, duplicar movimento ou reabrir obrigação já satisfeita.

**AC-04.** **Given** restauração repetida ou falha intermediária; **When** retomar ensaio; **Then** preservar registro da execução e caminho reversível sem apagar backup comprovado.

**Evidência futura e gate humano:** Recuperação real ensaiada do escopo antes de primeiros dados operacionais. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-14-3"></a>
### Story 14.3: Preservar exportações e arquivos antes de cada lote de migração

**Como** responsável pela migração autorizada,

**quero** criar snapshot recuperável do acervo envolvido,

**para** possibilitar comparação e retorno antes de virada.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 14.1/14.2, fontes autorizadas conforme Epic 3. **RF principais:** `RF-BACKUP-003`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Não executar migração operacional nesta Story; entregar contrato e prova de recuperação por escopo.

**Acceptance Criteria:**

**AC-01.** **Given** fonte legada e destino de lote definidos; **When** autorizar etapa pré-carga; **Then** exigir backup dos dados importados e originais com hash/escopo/proveniência e restore de amostra.

**AC-02.** **Given** backup incompleto ou arquivo histórico indisponível; **When** tentar liberar carga/cutover; **Then** bloquear lote afetado, sem alegar preservação integral.

**AC-03.** **Given** novo lote acrescenta PDFs/Kit/chaves; **When** ampliar snapshot; **Then** incluir componentes novos e acesso segregado conforme a classe, sem copiar ao repositório.

**AC-04.** **Given** falha na carga ou rollback solicitado; **When** restaurar ensaio do conjunto; **Then** provar recuperabilidade e reconciliar estado de origem/destino sem perda silenciosa.

**Evidência futura e gate humano:** Parâmetros 03/09/11 e autorização institucional por fonte/lote. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-14-4"></a>
### Story 14.4: Documentar instalação Docker e validar origem HTTPS isolada

**Como** responsável técnico por operação,

**quero** reproduzir ambiente e conferir entrada segura,

**para** evitar host exposto e configurações implícitas.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** BASE Docker; landing 2 para homologar domínio de produção. **RF principais:** `RF-BACKUP-004`, `RF-BACKUP-006`, `RF-DOCUMENTACAO-004`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Instalação de referência não é permissão de produção nem atualização do Proxmox/Swarm.

**Acceptance Criteria:**

**AC-01.** **Given** checkout aprovado e configuração sem segredos no Git; **When** seguir instruções versionadas de instalação Docker; **Then** reproduzir ambiente controlado com Laravel/PHP/PostgreSQL/worker/scheduler quando necessários, lockfiles e variáveis documentadas. Material técnico existente cobre arquitetura, ambiente, jobs, webhooks, integrações, backup/restore, deploy/rollback e diagnóstico do escopo efetivo; outro responsável reproduz uma tarefa técnica apenas com esse material, sem depender de instrução oral ou manual paralelo por Story.

**AC-02.** **Given** Cloudflare Tunnel/domínio/HTTPS e proxy configurados com autorização; **When** testar origem da landing/admin; **Then** verificar trusted hosts/proxies, cookies, headers, cache, webhook e ausência de portas privadas expostas.

**AC-03.** **Given** origem offline, proxy mal configurado ou segredo ausente; **When** executar preflight; **Then** falhar fechado, sem anunciar release autorizado nem abrir porta pública como atalho.

**AC-04.** **Given** autorização específica para ensaio de infraestrutura/release; **When** exercitar deploy/rollback por imagem imutável e perda/retomada de Tunnel/DNS, rotação/revogação de token e falha/retorno do backend; **Then** demonstrar recuperação do escopo com procedimento versionado, origem privada e sem abrir portas como atalho ou revelar credenciais. Mock/preflight não comprova recuperação real; esta Story não autoriza implantação nem migração de banco real por inferência.

**Evidência futura e gate humano:** Parâmetros 10/11, domínio/ownership, #30 e autorização específica de deploy. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-14-5"></a>
### Story 14.5: Publicar orientação operacional curta e administração segura

**Como** servidor voluntário ou gestor autorizado,

**quero** realizar tarefa e recuperação cotidiana por documentação clara,

**para** reduzir dependência de conhecimento individual.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** fluxos efetivamente existentes por Epic, não documentação antecipatória fictícia. **RF principais:** `RF-BACKUP-005`, `RF-DOCUMENTACAO-001`, `RF-DOCUMENTACAO-002`, `RF-DOCUMENTACAO-003`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Manual vivo por fluxo, sem arquivo histórico duplicado por alteração menor.

**Acceptance Criteria:**

**AC-01.** **Given** servidor novo precisa usar painel, ata regular/de serviço, serviço/encargos, relatórios, Kit, tesouraria ou comandos conforme função; **When** abrir guia contextual; **Then** ver passos e telas reais por audiência, com erro/retomada e acessibilidade no celular, cobrindo todos os módulos entregues sem antecipar fluxo fictício.

**AC-02.** **Given** gestor precisa administrar pessoas, mandatos, auditoria, migração autorizada e correções; **When** abrir documentação administrativa; **Then** receber política de acesso, gates e procedimento sem segredos ou dados privados em capturas; distinguir revisão institucional e operação CLI extraordinária de migração, sem criar tela de lotes.

**AC-03.** **Given** módulo ainda não implementado ou integração desabilitada; **When** consultar guia; **Then** declarar pendência/inaplicabilidade sem instruir ação que não existe.

**AC-04.** **Given** servidor leigo elegível executa tarefa observada apenas com o material publicado; **When** seguir roteiro; **Then** registrar resultado e corrigir lacunas de orientação, repetindo o ensaio afetado antes do aceite. Existência do texto ou ajuda oral do autor não comprova treinamento; evidência fica no registro operacional existente, não relatório permanente paralelo.

**Evidência futura e gate humano:** Aceite depende de ensaio observado por pessoa elegível e estado real da funcionalidade. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

<a id="story-14-6"></a>
### Story 14.6: Preparar fork limpo sem herdar dados, sessões ou segredos

**Como** responsável técnico por reutilização da plataforma,

**quero** instalar uma instância de outro grupo,

**para** reproduzir produto sem vazamento do QuarenteNA.

**Estado:** proposta para revisão. **Dependências diretas/capacidades:** 14.4 e 14.2; identidade de instalação/bootstrap 1.1/1.11. **RF principais:** `RF-DOCUMENTACAO-005`. **Fatia vertical:** resultado funcional indicado no título, persistência/controle/interface apropriados somente quando necessários; não criar arquitetura vazia ou segunda fonte de verdade.

**Limites:** Fork é uma instância por grupo, não SaaS/multi-tenancy.

**Acceptance Criteria:**

**AC-01.** **Given** fork em ambiente isolado com dados sintéticos ou instalação nova; **When** parametrizar nome, domínio, e-mail, Zoom, ASAAS, Evolution, textos, marca e configurações; **Then** gerar instância própria sem usuários, dados, tokens, sessões ou chaves da origem.

**AC-02.** **Given** cópia acidental de volume/dump/segredo antigo; **When** executar verificação de fork; **Then** impedir bootstrap/replay e exigir identidade/chaves novas com prova de isolamento.

**AC-03.** **Given** customizações ou módulos opcionais desabilitados; **When** seguir instalação; **Then** manter funcionamento essencial sem Redis/MinIO ou IA obrigatórios.

**AC-04.** **Given** fork tenta acessar rede/túnel/webhook do grupo de origem; **When** verificar endpoints; **Then** bloquear destinos/segredos herdados e registrar teste de não envio.

**Evidência futura e gate humano:** Ensaiar isolamento, brand e custódia de chaves antes de distribuir a solução. Demonstrar cenários com dados sintéticos, autorização positiva/negativa, rollback/repetição/conflito e interface acessível quando aplicáveis; nenhuma evidência de runtime foi executada nesta elaboração.

### Rastreabilidade proposta do Epic 14 — RF → Story → AC
Os IDs e a titularidade principal são os do mapa global aprovado. ACs indicam contratos **propostos**, não testes executados; RFs compostos dependem das integrações/gates explicitados no escopo.

| RF principal | Story e critérios propostos | Objeto de cobertura | Situação |
|---|---|---|---|
| RF-BACKUP-001 | 14.1 AC-01–04; [Story 14.1](#story-14-1) | Backup do banco | Proposto; execução/aceite condicionados ao RF integral |
| RF-BACKUP-002 | 14.2 AC-01–04; [Story 14.2](#story-14-2) | Restauração | Proposto; execução/aceite condicionados ao RF integral |
| RF-BACKUP-003 | 14.3 AC-01–04; [Story 14.3](#story-14-3) | Backup antes da migração | Proposto; execução/aceite condicionados ao RF integral |
| RF-BACKUP-004 | 14.4 AC-01–04; [Story 14.4](#story-14-4) | Documentação de instalação | Proposto; execução/aceite condicionados ao RF integral |
| RF-BACKUP-006 | 14.4 AC-01–04; [Story 14.4](#story-14-4) | Documentação técnica | Proposto; execução/aceite condicionados ao RF integral |
| RF-DOCUMENTACAO-004 | 14.4 AC-01–04; [Story 14.4](#story-14-4) | Documentação técnica | Proposto; execução/aceite condicionados ao RF integral |
| RF-BACKUP-005 | 14.5 AC-01–04; [Story 14.5](#story-14-5) | Documentação operacional | Proposto; execução/aceite condicionados ao RF integral |
| RF-DOCUMENTACAO-001 | 14.5 AC-01–04; [Story 14.5](#story-14-5) | Documentação para iniciantes | Proposto; execução/aceite condicionados ao RF integral |
| RF-DOCUMENTACAO-002 | 14.5 AC-01–04; [Story 14.5](#story-14-5) | Documentação por módulo | Proposto; execução/aceite condicionados ao RF integral |
| RF-DOCUMENTACAO-003 | 14.5 AC-01–04; [Story 14.5](#story-14-5) | Documentação administrativa | Proposto; execução/aceite condicionados ao RF integral |
| RF-DOCUMENTACAO-005 | 14.6 AC-01–04; [Story 14.6](#story-14-6) | Adaptação para outro grupo | Proposto; execução/aceite condicionados ao RF integral |

## Dependências, gates e aceite do Epic
- **Início e ordem:** implementar somente Stories autorizadas, precedidas pelos produtores reais necessários; a ordem editorial não substitui a verificação de capacidade já entregue. Qualquer dependência de componente futuro deve ser sinalizada e o consumidor adiado, sem simular funcionalidade.
- **Decisões humanas:** permanecem abertas as do índice e da cláusula de cada Story. Ausência de decisão não pode virar default normativo silencioso. Segurança, finalidade, canais, fornecedores, documentação e política de dados por classe antecedem a habilitação afetada.
- **Critério de encerramento:** revisar **todos** os RF principais acima, seus negativos e os contratos RNF/RB/CA/UX pertinentes no texto integral do PRD/UX. Não concluir Epic enquanto faltar funcionalidade composta exigida, homologação externa ou gate explicitamente aplicável.
- **Ponto de parada BMAD:** aguardar revisão independente individual/global, conciliação multidisciplinar e aceite humano do lote. Não adicionar ID a `approvedStoryIds` nem avançar `stepsCompleted` nesta entrega.

### Auditoria documental integral — 23–24/10/2026

Revisão/revalidação focal `ses_e949bfb7effeMsV1as7jHml9pw`: **6 Stories / 24 AC / 11 RF principais**, todos os AC-01–04 individualmente examinados; F3–F5 resolvidos sem residual focal. CL-01–05 aplicáveis; coordenador único editor. Resultado global no índice; nenhum backup/restore/Tunnel/fork ou treinamento foi executado nesta auditoria documental.

| ID / severidade | Epic/Story/AC | Evidência original | Fonte normativa | Correção persistida | Resultado | Revalidação |
|---|---|---|---|---|---|---|
| M-F3 MAJOR | 14.1/14.2 AC-01 | Recuperação positiva só do banco | RNF-11; CA-12; Arquitetura §§10/12 | Conjunto manifesto/banco/arquivos/Drive/chaves e prova positiva | Corrigido | Sessão acima; sem residual |
| M-F4 MINOR | 14.4 AC-04 | Falha fechada não provava recuperação Tunnel | Arquitetura §10 | Ensaio autorizado DNS/Tunnel/token/backend e retorno | Corrigido | Idem |
| M-F5 MINOR | 14.4/14.5 | Temas técnicos/módulos omitidos | RF-BACKUP-005/006; RF-DOCUMENTACAO-002–004 | Checklist completo, tarefa técnica e leiga reproduzíveis | Corrigido | Idem |

**Contratos por Story, AC-01–04:** 14.1 → RNF-01/03–05/11/16, RB-17, CA-12, UX-DR49/52; 14.2 → RNF-01/03–05/09/11/16, RB-06/17/18, CA-12, UX-DR49/52; 14.3 → RNF-03–05/11/12/16, RB-12/17, CA-12/14/17, UX-DR50/63; 14.4 → RNF-01/03/07/09/11/16/18, RB-17/23, CA-01/12/35, UX-DR19/49/52; 14.5 → RNF-06/13/16, RB-13/17, CA-13, UX-DR25/49/51/53; 14.6 → RNF-01/03–05/11/18, RB-05/06/17/18, CA-15, UX-DR52. Custódia, retenção, destino, RTO/RPO após medição e autorização de infraestrutura continuam humanos; recuperação inicial antecede dados reais, não espera produto inteiro.
