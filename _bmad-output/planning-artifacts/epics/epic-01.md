## Epic 1: Organizar pessoas, mandatos e acesso responsável

> **Estado vigente — Epic 1: APROVADO COMO PLANEJAMENTO CONDICIONADO pelo responsável em 23/10/2026.** A aprovação do lote abrange a revisão documental das Stories e ACs, sem homologar as decisões institucionais pendentes, autorizar implementação, dados reais, integrações, deploy ou concluir o passo 03 global. As expressões anteriores “proposta”, “não aprovada”, “apreciação humana” e registros de sessões abaixo documentam o estado **histórico pré-aceite** e não revogam este checkpoint.

> **Fonte canônica global:** [índice de Epics](../epics.md), [PRD](../PRD.md), [Product Brief](../ProductBrief.md), [Arquitetura](../Arquitetura.md) e [UX Design](../UX-Design.md). **Detalhamento exclusivo:** Epic 1 — Stories, critérios e matriz RF → Story → AC.


CG/SG encontram pessoas existentes, administram contas e responsabilidades vigentes; cada servidor acessa apenas suas funções e a contingência root mantém autoria individual. Preservar os 38 RF atribuídos no mapa aprovado, sem duplicar login/logout/provisionamento da fundação. RNF-01–05/13/15, RB-05–06/11/14–18/21, CAs de identidade/mandato/root e UX-DR4–5/15/45/49–55 são aplicáveis conforme o incremento; a atribuição detalhada por Story será conferida no aceite de cada proposta.

**Estado de elaboração:** o responsável autorizou elaboração e revisão independente em lote exclusivamente do Epic 1, adaptando os checkpoints colaborativos do passo 03. As seções abaixo são **propostas**, não Stories aprovadas. `approvedStoryIds` permanece vazio; os passos 03/04 não estão concluídos. Diretrizes de auditoria e bootstrap estão aprovadas; o mecanismo técnico de prova externa não está. Nenhuma Story implementada/homologada. Não iniciar Epic 2 sem nova instrução expressa.

O procedimento fica estritamente limitado a reautenticação pessoal, prova de autorização institucional independente, primeira designação CG/SG, trilha e marca permanente atômicas com concorrência/idempotência PostgreSQL. Não inclui interface administrativa, delegações, recuperação de contas, concessões posteriores ou root. Só materializar entidades necessárias quando houver futura implementação autorizada; esta tarefa continua apenas documental. A posse de shell e a ausência de CG/SG vigente não autorizam iniciar/repetir a concessão. Aprovar o contrato não autoriza designar uma pessoa real ou executar o bootstrap em produção.

O meio técnico verificável da prova externa será explicitamente proposto na revisão da Story, sem tratar referência autodeclarada como aprovação. Os demais parâmetros operacionais e conflitos continuam nos seus checkpoints; não se cria dependência de Story futura para executar a primeira designação.

### Contratos comuns das propostas do Epic 1

- **Baseline conferida:** `routes/web.php`, `AutenticacaoController`, `UsuarioAtivo`, `CriarUsuario`, migrations de pessoas/usuarios e testes existentes. Login/logout/hash/vínculo único existem; `nome_busca` é apenas coluna indexada, tokens de reset não são recuperação implementada. Nenhuma Policy por encargo, auditoria de negócio ou root existe na baseline. Reutilizar mecanismos e regressões, sem reescrever migrations aplicadas.
- **Ordem proposta:** 1.1 bootstrap → 1.2 pessoas → 1.3 contas → 1.4 recuperação → 1.5 encargos → 1.6 designações → 1.7 encerramentos → 1.8 manutenção/regras confirmadas → 1.9 delegações → 1.10 consulta da trilha → 1.11 custódia da contingência → 1.12 elevação pessoal. Os vínculos diretos serão explicitados em cada Story; ordem editorial não implica dependência de todas as anteriores.
- **CT-01 — Autorização/autoria:** todas as entradas de negócio entregues (web, CLI, job e escrita fora do ORM) têm controle explícito no servidor e ator real. CG/SG vigentes administram pessoas/contas/mandatos; não ganham Kit/edição financeira. Exclusivamente 1.1 usa autorização inaugural independente + reautenticação pessoal, sem exigir mandato prévio nem introduzir Gate global de bypass. Contas/pessoas inativas não operam. Nenhum consumidor futuro é antecipado; testes de contrato não comprovam rotas Kit/Zoom/finanças inexistentes. Root só se integra após 1.12; antes disso nenhum bypass root é necessário.
- **CT-02 — Transação e concorrência:** mutação de domínio + trilha redigida na mesma transação; falha reverte tudo e não anuncia sucesso. Revalidar autorização e estado sob bloqueio antes do commit para evitar concessão concorrente a revogação. FKs/NOT NULL/UNIQUE/CHECK efetivos no PostgreSQL nas invariantes do incremento; conflitos informam recarga, sem sobrescrita silenciosa. Nenhum `migrate:fresh` em banco persistente.
- **CT-03 — Auditoria:** RF-AUDITORIA-001/002/005/006/007/009 e Arquitetura §§6.8/síntese 2: ator `usuario` com vínculo pessoal ou `job`/`integracao`/`sistema` com origem, correlação, entidade, ação, antes/depois redigidos, motivo aplicável e instante UTC; exibição America/Bahia. CLI não inventa IP/agente; alegação de identidade não vira autor autenticado. Sem senha/hash/token/chave/documento integral/PII desnecessária. Trilha append-only no fluxo comum; nenhum endpoint normal de edição/remoção. Escritas mecânicas e a trilha não se auditam recursivamente. Eventos de segurança próprios distinguem tentativa negada de negócio efetivado.
- **CT-04 — Privacidade e ciclo de vida:** versionar/excluir logicamente registros institucionais, preservando razão/autoria e referências. Anonimização/expurgo legal de PII é processo distinto, condicionado à política por classe do parâmetro 03; não inventar retenção nem substituir expurgo por soft delete. Nenhuma coleta real/expurgo real autorizado nesta tarefa.
- **CT-05 — UX/testes futuros:** nas telas, UX-DR4–5/13–15/17–18/25/49–55: menu por capacidade, backend igualmente protegido, estados vazio/carregando/erro/sucesso após commit, labels/erros associados, confirmação sensível, teclado/leitor, mobile 360/375px e zoom 200%; PII só na resposta mínima de detalhe/edição ao destinatário autorizado, nunca em HTML/JSON público ou não autorizado, campos ocultos desnecessários, analytics/toasts/cache comum. Testes positivos, negativos, cross-user, relógio/cache/sessão, rollback e PostgreSQL/concorrência quando aplicáveis, com dados sintéticos. Instrução curta acompanha o fluxo existente, sem relatório/arquivo individual de Story. Ensaio observado e WCAG são validações futuras, não realizados por aprovação documental.
- **CT-06 — Limites de cobertura:** Epic 1 entrega e testa contratos reutilizáveis de autorização, trilha, justificativa oficial e leitura sensível usando seus próprios fluxos e fixtures sintéticas. Cada Epic consumidor deve aplicar/testar tais contratos no seu domínio; RF transversal e CA ponta a ponta não são declarados integralmente implementados só com Epic 1. Agenda/reunião/dupla são integrações posteriores do Epic 2, não dependências para designações de Mesa deste Epic.

**Precisões verificáveis dos contratos comuns (integram os AC aplicáveis, sem novos AC):**

- **CT-02, estado esperado:** a primeira edição concorrente de pessoas em 1.2 define uma convenção reutilizável de revisão esperada por registro: a revisão apresentada ao operador deve ser comparada atomicamente à atual, avançada em cada mutação e rejeitada quando obsoleta. Bloquear somente depois de carregar dados antigos não basta. As Stories seguintes aplicam a mesma convenção às entidades que materializam, sem criar tabelas futuras; a solução técnica deve demonstrar ausência de atualização perdida com duas conexões PostgreSQL. Não confundir revisão de registro com invalidação de autenticação.
- **CT-01/05, autenticação versus capacidade:** 1.2 entrega um único mecanismo persistente de invalidação da autenticação pessoal, integrado ao guard/middleware existente; 1.3 (atividade da conta), 1.4 (senha) e 1.12 (elevação vinculada) o consomem, sem mecanismos paralelos. Desativar pessoa/conta ou redefinir senha torna inutilizáveis todas as autenticações anteriores, inclusive cookies dormentes e regravações tardias; reativação não desfaz a invalidação. Encerrar mandato/delegação ou revogar root retira somente a fonte de capacidade correspondente: não obriga logout pessoal nem elimina outro mandato legítimo. A estratégia concreta deve ser escolhida e registrada no contrato de implementação de 1.2, com estado durável e verificação no servidor, sem impor coluna física. 1.1 já verifica vigência na fonte; 1.6 consolida esse contrato para demais encargos. Nenhuma decisão positiva antiga em sessão/cache substitui revalidação da fonte vigente por operação; se houver cache auxiliar, sua versão/invalidade e fronteira temporal precisam ser verificadas antes de autorizar. Testar cache aquecido mesmo sem cache específico de autorização, sem inventar Redis.
- **CT-05, ensaios de sessão e navegação:** configurações versionadas `config/session.php` e `config/cache.php` usam `database` por padrão; `phpunit.xml` usa `array` nos testes. Confirmar os drivers efetivos no futuro ambiente autorizado, sem ler segredos neste planejamento. A prova de persistência exige driver de sessão/cache homologado (inicialmente database/PostgreSQL), dois clientes independentes, um dormente durante a revogação, conta/pessoa posteriormente reativadas, cache aquecido, scheduler parado e corrida operação/revogação. Testes com `actingAs`/array isoladamente não bastam. Menu reflete capacidades vigentes na próxima renderização/operação; URL direta continua protegida mesmo com tela antiga ou falha de atualização visual. 1.3 AC-06 e 1.10 AC-07 exercitam consumidores representativos; 1.12 amplia-os apenas quando a elevação existir.

<a id="story-1-1"></a>

### Story 1.1: Registrar a primeira designação da Mesa com autoria auditável

**Como** responsável institucional autorizado pela inicialização,
**quero** registrar a primeira designação CG ou SG para conta pessoal existente mediante CLI controlado,
**para** estabelecer autoridade legítima e rastreável sem root compartilhado ou bypass reutilizável.

**Condição:** REVISADA — PRONTA PARA AVALIAÇÃO HUMANA, com D1/D2/guarda explicitadas; não aprovada. Reutiliza a [candidata da Issue #35](https://github.com/grupoquarentena/site/issues/35#issuecomment-6334453298), preservando seus 13 controles e reduzindo prescrição técnica não aprovada.

**Dependências:** somente baseline #33/#34; contas e pessoas válidas previamente provisionadas, autorização externa independente e vigência homologadas antes da execução real. **Referências:** RF-ACESSO-005/007/009/011/013, RF-SERVIDORES-001/004/005/007/015/017 e RF-AUDITORIA-001/002/006/007 (contribuições); regressão RF-ACESSO-001/002 e RF-IDENTIDADE-004; RNF-01–05/15, RB-05/11/13/16–18, CA-19/26/33 parciais, UX-DR52; Arquitetura §4 bootstrap e síntese 2.

**Escopo/tamanho:** um procedimento inaugural end-to-end e adaptação mínima dos caminhos já existentes à primeira trilha: fechar provisionamento após bootstrap e instrumentar login/logout, sem reconstruí-los. Não inclui UI de gestão, catálogo completo, delegações, reset, root, jobs ou outbox. Só `encargos` (CG/SG mínimos), `designacoes_servico`, `auditorias` e marca de inicialização necessária; sem FK para sessão root/agenda futura. Designação tem FKs pessoa/encargo NOT NULL, CHECK temporal e CHECK exclusivo `ativo`/`concluido`/`entregue`/`destituido`, sem estado VAGO/revogado. Os 13 controles são cenários do procedimento e fechamento de suas entradas existentes, não 13 funcionalidades. Modelo de ator técnico fica extensível sem usuários fictícios.

**Decisão D1 — prova independente, antes de implementar este CLI:** recomendação submetida ao responsável: assinatura destacada de comprovante com material sintético nos testes e âncora pública previamente homologada/protegida, sem PKI, emissor ou portal de chaves. Vincular instalação, operador, beneficiário, CG/SG, vigência, motivo, aprovador e referência/hash. Alternativa: conferência por aprovador independente em canal autenticado externo com atestado verificável vinculado à mesma operação e guarda protegida; exige definir protocolo/evidência de autenticação e resistência à autodeclaração. Assinatura facilita verificação offline, mas exige custódia/rotação da âncora; conferência externa reduz ferramenta criptográfica, mas exige prova de autoria e procedimento verificável. Referência livre, arquivo autoemitido ou chave passada pelo próprio solicitante não são alternativas válidas. Não implementar ambos por precaução. D1 bloqueia o verificador específico de 1.1, não autoriza enfraquecer a diretriz nem depende de outra Story.

**Fechamento de D1 e identidade de instalação:** a decisão deve identificar autoridade/aprovador independente, vínculo da prova à operação exata, custódia e alteração da âncora ou equivalente, validade/replay e critérios positivos/negativos da verificação. Deve também homologar um único contrato de identidade da instalação e de evidência positiva de estado nunca inicializado, distinto da mera ausência da marca: origem confiável dessa identidade/evidência, integridade, vínculo à prova e tratamento de restore/fork. Bootstrap e custódia 1.11 usam o mesmo contrato, não identificadores independentes. Banco restaurado não se torna instalação nova; fork limpo não herda prova, consumo ou material de contingência. A decisão deve fornecer exemplos verificáveis de instalação nova, restauração e marca ausente/inconsistente; até isso existir, o caminho positivo não está pronto para implementar. Não escolher aqui UUID, segredo, emissor ou tabela.

**Entidade lógica mínima da marca:** registro persistente único por instalação, com identidade da instalação, consumo definitivo, instante UTC, operador e beneficiário pessoais, designação inaugural, correlação/trilha e aprovador/referência/hash mínimo da evidência. A chave global não pode ser escolhida pelo solicitante para criar outro espaço de inicialização; vínculos e unicidade devem impedir segunda concessão também com CG/SG ou operadores distintos. O armazenamento físico será harmonizado na seção existente da Arquitetura no mesmo PR da implementação, antes de aceitar o schema; nomes citados na candidata #35 continuam propostas. Unicidade/FKs não substituem proteção contra alteração/remoção/rearmamento.

**Fronteira de ameaça e entrega coesa:** o CLI oficial deve negar shell sem autorização D1; acesso ao shell não é prova institucional. Isso não promete impedir quem controla código/segredos do host de executar SQL arbitrário e forjar negócio; a implementação deve explicitar credenciais/privilégios efetivos de execução e demonstrar a proteção da marca/trilha sob o contexto ordinário da aplicação, sem alegar isolamento inexistente ou impor nova infraestrutura. Manter 13 AC e uma Story: checkpoints internos são persistência/prova sintética → CLI e corrida com provisionamento → regressão de login/logout. Só habilitar o procedimento e a primeira UI de 1.2 após todos passarem; nenhum checkpoint parcial constitui entrega operacional. Mover fechamento do CLI para 1.3 abriria janela sem autoridade; mover instrumentação deixaria a tela de 1.2 sem eventos de autenticação. Após D1/D2, confirmar tamanho para um agente sobre a solução concreta, sem plataforma genérica.

**Acceptance Criteria:**

**AC-01 — Concessão inaugural.** **Given** instalação comprovadamente não inicializada, contas/pessoas existentes e ativas e prova independente válida; **When** operador se reautentica por senha interativa oculta e confirma exatamente a operação autorizada; **Then** uma designação CG/SG com vigência, trilha e consumo permanente da marca são efetivados atomicamente; **And** sucesso/correlação só aparecem após commit, sem flag permanente de poder.

**AC-02 — Reautenticação.** **Given** senha ausente/incorreta, operador/pessoa inativos ou execução sem prompt seguro; **When** solicitar o procedimento; **Then** negar sem concessão/consumo e registrar evento próprio de reautenticação recusada; **And** registrar também reautenticação aceita quando ocorrer, sem confundi-la com concessão, não aceitar senha em argumento/variável de ambiente/log nem atribuir autoria autenticada à identidade alegada.

**AC-03 — Prova vinculada.** **Given** posse de shell e conta autenticada; **When** faltar prova verificável ou houver adulteração, emissor não confiável/não independente, instalação ou dados divergentes; **Then** negar e registrar rejeição sanitizada; **And** aplicar os testes de autenticidade/replay do mecanismo escolhido em D1, não um mock que aceite qualquer referência.

**AC-04 — Autoconcessão.** **Given** operador igual ao beneficiário; **When** a combinação não estiver explicitamente autorizada por aprovador independente comprovado; **Then** negar; **And** se autorizada, aplicar os mesmos controles sem exceção tácita.

**AC-05 — Vínculo e corrida de inativação.** **Given** pessoa/usuário inexistente, inativo ou vínculo inconsistente; **When** revalidar sob bloqueios pertinentes dentro da região crítica; **Then** negar inclusive inativação anterior à revalidação final; **And** não criar conta/pessoa substituta. O consumo da marca fecha imediatamente `usuario:criar` para qualquer criação posterior por shell: testar `--nome`/`--pessoa` e corrida CLI/bootstrap sob a mesma serialização global, para que nenhuma criação pós-consumo confirme sem autoridade/trilha. Preservar apenas provisionamento pré-inaugural necessário da fundação; a proteção não espera 1.2 nem reconstrói gestão de contas.

**AC-06 — Encargo e datas.** **Given** encargo fora de CG/SG, período divergente da prova, data inválida, fim ausente ou início posterior ao fim; **When** validar concessão; **Then** negar sem persistência parcial; **And** não inventar prazo/padrão silencioso. Instantes de fronteira seguem contrato temporal explícito e testado do PRD/Arquitetura, com apresentação America/Bahia; convenção não definida fica em D2 antes da implementação.

**AC-07 — Consumo único.** **Given** marca já consumida; **When** repetir operação idêntica ou mudar prova/pessoa/encargo; **Then** recusar nova concessão e preservar o registro original; **And** ausência de CG/SG vigente nunca reabilita bootstrap.

**AC-08 — Encerramento não rearma.** **Given** mandato inaugural expirado/concluido/entregue/destituido em fixture; **When** repetir comando, recuperar senha ou reativar conta; **Then** marca continua consumida; **And** recuperação extraordinária exige autorização separada, fora deste CLI.

**AC-09 — Corrida global.** **Given** duas conexões/processos PostgreSQL independentes, com operações válidas diferentes; **When** disputarem inicialização; **Then** lock transacional global independente dos argumentos e unicidade real permitem no máximo um commit de designação/trilha/marca; **And** perdedor revalida e não deixa efeito órfão nem sucesso.

**AC-10 — Rollback.** **Given** falha em designação, trilha, marca ou criação mínima auditada de encargo; **When** persistir; **Then** reverter toda a mutação e não consumir marca; **And** eventual evento de falha não afirma concessão efetivada, e retry exige novamente todas as condições válidas.

**AC-11 — Autoridade limitada.** **Given** concessão concluída; **When** inspecionar conta/sessão ou consultar a fonte de autoridade fora da vigência; **Then** não existir root, papel comercial ou credencial de bootstrap reutilizável como poder; **And** a designação só confere capacidade enquanto vigente, sem alegar Policies de módulos inexistentes.

**AC-12 — Marca permanente.** **Given** inicialização consumida ou evidência de instalação/histórico inconsistente; **When** repetir o comando após expiração, restauração de sessão ou tentativa ordinária de alterar/remover/rearmar/trocar a marca; **Then** negar nova concessão e preservar o consumo e seus vínculos; **And** ausência/inconsistência nunca é reconstruída automaticamente, indicando recuperação institucional extraordinária. Comprovar no PostgreSQL real, usando as credenciais/privilégios ordinários da aplicação, que UPDATE de consumo/instalação/vínculos, DELETE e TRUNCATE não removem nem rearmam a marca e que inserção alternativa não contorna sua unicidade. Exigir mecanismo efetivo no banco e testes negativos, não apenas proteção do ORM, UNIQUE ou FK; escolha técnica deve cobrir também privilégios de alteração estrutural disponíveis nesse contexto. Não prometer resistência a superusuário/host comprometido. O caso positivo de instalação nova exige a evidência homologada em D1, não tabela vazia; restore e marca ausente são casos negativos próprios.

**AC-13 — Sigilo e trilha.** **Given** sucesso, negação ou falha; **When** emitir saída/eventos; **Then** aplicar CT-03 sem documento integral/segredos em stdout/stderr, histórico de shell ou logs; **And** guardar só aprovador/referência/hash mínimos, sem auditoria recursiva, com ator/origem/correlação reais. A primeira disponibilidade da trilha nesta Story já instrumenta login aceito/recusado e logout existentes na baseline, sem esperar gestão de contas 1.3: evento sanitizado com módulo/origem/correlação/IP/agente quando disponíveis; falha do evento obrigatório de login aceito não deixa qualquer sessão autenticada utilizável. Logout sempre invalida autenticação mesmo se o evento falhar, sinalizando falha sanitizada sem restaurar acesso. Reutilizar controller/guard/CSRF/rate limit; não reconstruir login. Testar antes de habilitar a gestão de pessoas 1.2.

**Verificação futura:** CLI positivo/negativo, identidade/login regressivos, D1 com prova sintética real, fronteiras temporais, constraints/imutabilidade/rollback/duas conexões em PostgreSQL direto e snapshot após evolução autorizada. D1/D2/guarda da evidência impedem execução real sem decisão; nenhum teste foi executado neste planejamento.

**D2 — Convenção temporal, antes da implementação afetada:** responsável/Mesa + técnico devem homologar conversão de datas de mandato em fronteiras efetivas America/Bahia/UTC, inclusividade do término e cálculo de meses/ano civil. Preservar seis meses/um ano, sem convertê-los silenciosamente em 180/365 dias nem alterar contrato. Testar instante anterior/exato/posterior ao início/término; a pendência não é Story futura.

**Revisão independente:** sessão `ses_e99aa39d6ffeWUfWRSxx2wB1bN`; MAJOR sobre eventos de reautenticação corrigido e revalidado; três MINOR corrigidos. Sem BLOCKER/MAJOR técnico residual identificado; D1/D2/guarda continuam humanas.

<a id="story-1-2"></a>

### Story 1.2: Encontrar e manter pessoas sem criar logins desnecessários

**Como** CG ou SG vigente,
**quero** buscar pessoas, confirmar sua identidade e manter o cadastro mínimo,
**para** reutilizar identidades estáveis sem fundir homônimos nem exigir conta.

**Condição:** PROPOSTA EM REVISÃO; não aprovada. **Dependência direta:** 1.1 (autoridade inaugural e trilha); sem root/UI futura. Este incremento entrega sua própria tela e Policies de pessoas, consultando designação vigente da autoridade inaugural.

**Referências:** RF-IDENTIDADE-001/002, RF-ACESSO-004, RF-AUDITORIA-001/002/007/009; RNF-01/03–06/13/15, RB-05/14/21, CA-29, UX-DR4–5/13–15/17–18/49–55; Arquitetura §§6.1/6.10 e CT-01–06.

**Escopo:** busca/cadastro/edição/inativação lógica auditados em `pessoas`; nova migration apenas para contatos opcionais, localização e controles mínimos de revisão/invalidação de sessões necessários. Não inclui gestão de login, fusão de históricos, importação, Kit ou cadastro Zoom. Nome de busca NFKC/trim/colapso de espaços/casefold não único; contatos cifrados, blind index HMAC separado só se busca autorizada por contato for necessária (não obrigatória nesta Story). Após bootstrap, `usuario:criar` permanece indisponível para provisionamento ordinário; a gestão web autorizada de contas será entregue em 1.3, sem bloquear este cadastro de pessoas. Qualquer CLI futuro exigiria decisão de escopo/autorização própria, sem rearmar bootstrap; nenhum comando alternativo é autorizado implicitamente e nenhum é criado aqui. A exceção pré-inaugural serve apenas às contas pessoais necessárias ao bootstrap e não reabre após consumo.

**Acceptance Criteria:**

**AC-01 — Busca antes do cadastro.** **Given** gestor vigente autenticado; **When** iniciar cadastro de pessoa/servidor; **Then** exigir busca e apresentar semelhantes com contexto permitido e ID estável para conferência; **And** cadastro novo só é oferecido após não localizar a pessoa e confirmar isso, inclusive quando os semelhantes são homônimos distintos. Normalização sugere, não atribui identidade. Pessoas já provisionadas na baseline com `nome_busca` nula devem ser encontradas na primeira busca por adaptação mínima (normalização/backfill seguro ou fallback equivalente), sem trocar PK/duplicar identidade; se persistir alteração de identidade, aplicar trilha/redação e rollback pertinentes, não tratar chave nula como pessoa inexistente.

**AC-02 — Pessoa sem conta.** **Given** busca conferida sem pessoa correspondente; **When** confirmar nome/apelido e opcionalmente WhatsApp/e-mail/estado/país; **Then** criar pessoa sem `usuario`, preservando grafia de exibição e gerando chave de busca; **And** não impor contato/login, criar senha ou fundir pessoas por nome/contato iguais. Campos inválidos exibem erro sem gravação parcial.

**AC-03 — Edição e preservação.** **Given** pessoa existente; **When** confirmar edição cadastral ordinária ou inativação lógica (esta exige motivo); **Then** manter PK e referências históricas, versionar mudança/trilha redigida atomicamente; **And** inativação impede atuação da conta vinculada e invalida persistentemente todas as sessões anteriores, sem apagar designações ou reescrever nome histórico de ocorrências futuras consumidoras. Implementar controle mínimo de invalidação junto ao middleware já existente, reutilizável em 1.3, não depender de 1.3 para fechar essa garantia. Testar pessoa ativa→inativa→reativada com duas sessões dormentes e requisição concorrente: sessões antigas não ressuscitam; 1.12 deve consumir a mesma invalidação para elevação. Edição cadastral ordinária não é retificação de registro oficial enviado: motivo não é obrigatório salvo regra específica aplicável, mas autoria/antes/depois redigidos sempre são.

Inativar a pessoa do próprio operador ou da última autoridade exige o mesmo aviso de perda concreta e confirmação previstos para contas/mandatos; aplica-se o gate de continuidade do conjunto antes do uso real, sem veto institucional inventado, rearmamento de bootstrap ou extensão de mandato. Ensaiar essa via cadastral para que não contorne os avisos/encaminhamento de 1.3 AC-03 e 1.7 AC-06.

**AC-04 — Acesso mínimo.** **Given** anônimo, conta sem CG/SG vigente ou gestor expirado; **When** buscar, ler contatos ou alterar cadastro diretamente; **Then** Policy nega e evento de segurança sanitizado é registrado; **And** manipular IDs não amplia escopo permitido, menu oculto não é controle e contato de pessoa não permite ler Kit. **Given** gestor autorizado revela contatos privados no detalhe/edição; **When** ler; **Then** registrar evento próprio com ator/pessoa/origem/correlação sem os valores; **And** não entregar se o evento obrigatório falhar, nem expor contatos em sugestões/cache comum/respostas não autorizadas/logs.

`notas_restritas` já existe e tem cast `encrypted` em `Pessoa`, mas não integra consulta/edição deste incremento: nenhum ator recebe acesso ao campo por esta UI/API, inclusive gestor. Rejeitar payload de escrita, omitir da seleção/serialização, busca, listagens e respostas e não copiar seus valores para auditoria. Testar valor sintético preexistente e acesso por ID/payload; preservar o conteúdo cifrado sem criar CRUD de notas. Leitura futura exige funcionalidade e autorização específicas homologadas; cifra em repouso não autoriza leitura nem protege serialização após descriptografar.

**AC-05 — Integridade e disputa.** **Given** duas edições ou edição/inativação concorrentes da mesma pessoa; **When** uma confirmar primeiro; **Then** a outra detecta versão desatualizada por comparação atômica e exige recarga, sem perder atualização; **And** criação concorrente de homônimos não funde nem usa índice único de nome. Em falha da trilha reverter a mutação; parâmetros manipulados não permitem atribuir conta/permissão. Tentativa pós-bootstrap pelo CLI legado é negada sem criar pessoa/conta e gera evento sanitizado, inclusive uso de `--nome` ou `--pessoa`.

**AC-06 — Uso acessível e guarda.** **Given** celular/teclado/leitor ou falha de consulta; **When** buscar/selecionar/salvar; **Then** estados e erro ficam compreensíveis conforme CT-05, sem sucesso antes do commit; **And** contatos ficam cifrados no banco e fora da trilha, com instrução curta de busca/homônimos. Política de finalidade/guarda do parâmetro 03 deve ser homologada antes da coleta real, sem prazo inventado.

Antes da coleta real, explicitar custódia/acesso mínimo, recuperação e rotação das chaves usadas na cifra; testar rotação/leitura de valores anteriores e falha de decifragem sem exposição nem perda silenciosa. Se houver blind index, sua chave HMAC separada e reconstrução segura entram no mesmo ensaio. Reutilizar recursos de criptografia/segredos existentes; não impor envelope encryption, cofre novo ou busca por contato. O responsável homologa a custódia operacional junto à política por classe; o incremento testa o mecanismo técnico escolhido com material sintético.

**Verificação futura:** testes de normalização/homônimos e confirmação, cadastros sem contato/conta, acesso direto/cross-user/mandato expirado, cifra e saída redigida, concorrência/rollback PostgreSQL, regressão `UsuarioAtivo` e ensaio UX. Não requer Epic 2 nem fusão de dados históricos.

**Revisão independente:** `ses_e99a820b0ffeTtSZshibqgsnCo`; dois MAJOR (CLI paralelo e leitura privada sem evento) e três MINOR corrigidos/revalidados; sem residual conhecido. **Condição final:** REVISADA — PRONTA PARA AVALIAÇÃO HUMANA; coleta real condicionada ao parâmetro 03.

<a id="story-1-3"></a>

### Story 1.3: Administrar contas pessoais vinculadas sem privilégios implícitos

**Como** CG ou SG vigente,
**quero** criar, ativar e desativar o acesso de uma pessoa já conferida,
**para** que cada operador use identidade própria sem duplicar contas ou herdar poderes.

**Condição:** PROPOSTA EM REVISÃO. **Dependência direta:** 1.2 (busca/seleção/Policy/trilha); 1.1 transitiva. **Referências:** RF-ACESSO-001/002 (regressão), 005/007/009/011/013; RF-IDENTIDADE-004; RF-AUDITORIA-001/002/007/009; RNF-01–06/13, RB-05/16–18, CA-26/33 parciais, UX-DR4–5/18/49–55, Arquitetura §§4/6.1; CT-01–06.

**Escopo:** tela de listagem/busca e criação/ativação/desativação de `usuarios` para pessoa existente ativa; reutilizar guard/hash/constraints e mecanismo persistente entregue em 1.2. Senha inicial por formulário protegido com política da fundação, sujeita ao fechamento de D3, sem e-mail obrigatório de entrega nem geração em ingresso/Zoom. Não inclui reset (1.4), mandatos, root, papéis comerciais ou contas técnicas. `usuario:criar` permanece fechado após bootstrap; nenhum CLI equivalente é autorizado por esta Story. Instrumentação de login/logout foi entregue em 1.1; esta Story a amplia/regrede no contexto das contas, sem janela anterior sem auditoria.

**Acceptance Criteria:**

**AC-01 — Conta vinculada.** **Given** pessoa ativa selecionada por busca e ainda sem conta; **When** gestor confirma e-mail pessoal e senha inicial conforme política existente; **Then** criar uma conta ativa vinculada ao mesmo ID e auditar atomicamente sem senha/hash; **And** nenhuma capacidade decorre da criação, nenhuma outra pessoa é criada silenciosamente. Pessoa com conta existente apenas encaminha à conta, sem duplicar, trocar vínculo, sobrescrever e-mail/senha ou reativar implicitamente.

**AC-02 — Unicidade concorrente.** **Given** duas criações para a mesma pessoa ou variantes de caixa do mesmo e-mail; **When** concorrerem; **Then** constraints NOT NULL/UNIQUE/FK e índice `lower(email)` existentes permitem no máximo uma conta válida; **And** erro de conflito é seguro e não deixa conta/trilha de sucesso órfã. Recusar pessoa ausente/inativa, e-mail inválido ou senha fora da política.

**AC-03 — Ativar/desativar.** **Given** gestor vigente e conta alvo; **When** confirmar alteração de atividade com motivo na desativação; **Then** persistir status, invalidação de todas as sessões autenticadas anteriores e trilha juntos, sem apagar pessoa/histórico; **And** pessoa inativa não ganha acesso por ativação da conta, e reativar não restaura sessões/tokens antigos nem mandato encerrado/elevação antiga. Consumir obrigatoriamente o mecanismo de 1.2 conforme CT-01/05, sem adiar sua entrega nem criar versão paralela. Provar duas sessões independentes, uma dormente durante a inativação, posterior reativação e corrida login/desativação: nenhuma autenticação anterior ao desligamento ressuscita depois. Disputa ativação/desativação detecta revisão desatualizada conforme CT-02 e exige recarga sem sucesso do perdedor. Autodesativação/última autoridade exige aviso explícito de perda do próprio acesso e de eventual ausência de gestor; não inventar veto institucional nem reabrir bootstrap. O encaminhamento de continuidade pendente no gate global deve estar homologado antes do uso real desta operação.

**AC-04 — Autorização real.** **Given** anônimo, conta pessoal sem encargo, RSG/Tesoureiro/Kit ou CG/SG fora da vigência; **When** chamar qualquer endpoint de gestão ou adulterar pessoa/status/capacidades; **Then** negar sem alteração e registrar evento sanitizado; **And** parâmetros de papel/flags/permissões são rejeitados, sem conceder Kit ou edição financeira a CG/SG. Revalidar autoridade e atividade em disputa com revogação/inativação conforme CT-02.

**AC-05 — Login/logout preservados e instrumentados.** **Given** conta/pessoa ativa, inclusive conta sem mandato; **When** entrar/sair pelo fluxo existente; **Then** preservar hash, proteção CSRF/rate limit, renovação/invalidação da sessão e manter login independente do mandato; **And** registrar eventos próprios sanitizados de login aceito/recusado e logout, sem eventos por escrita mecânica da sessão, senha em flash/log/HTML de retorno nem enumeração desnecessária. Falha do registro obrigatório de login aceito não deixa qualquer sessão autenticada utilizável, mesmo sem mandato. No logout a falha do registro é tratada sem restaurar/manter autenticação: invalidar a sessão sempre e sinalizar falha sanitizada no mecanismo próprio de segurança. Testar esses comportamentos separadamente.

**AC-06 — Interface e falhas.** **Given** uso móvel/teclado ou falha de trilha/banco; **When** criar/alterar conta; **Then** aplicar CT-05 e rollback integral sem sucesso falso; **And** não retornar senha persistida, hash, token ou contato privado indevido na lista/recibo, com instrução de acesso pessoal e confirmação da identidade antes de fornecer credencial por canal institucional autorizado.

**Given** CG/SG vigente e conta sem gestão; **When** renderizar o painel de contas e acessar URL direta; **Then** somente o autorizado vê ações de gestão e o backend nega o outro; **And** expiração do mandato ou desativação da conta elimina operações na próxima tentativa mesmo com menu antigo/cache aquecido. Antes de 1.12 não há menu root nem dependência dessa Story; ela acrescentará o cenário de elevação temporária.

**Pendência D3:** responsável/Mesa definir entrega pessoal segura, confirmação do destinatário e tratamento do conhecimento da senha pelo gestor em 1.3/1.4. Alternativas: credencial temporária com troca pessoal obrigatória no primeiro acesso; definição pessoal por mecanismo de ativação de uso único; ou entrega pessoal confirmada com aceitação expressa do risco residual de senha conhecida. Recomenda-se eliminar o conhecimento continuado do gestor por uma das duas primeiras, por RNF-02 e autoria pessoal de RF-ACESSO-007; não são obrigações já homologadas. Troca pessoal/ativação adicionaria capacidade, testes e eventual estado persistente a 1.3/1.4: exige decisão de escopo e harmonização das fontes antes de implementar essa parte, não endpoint/migration automático nem Story futura implícita. A alternativa escolhida deve caber no incremento que provisiona/recupera, sem janela de uso real com política indefinida. Não enviar por grupo/logs nem presumir e-mail configurado; sem D3 o fluxo positivo de credencial permanece condicionado, não plenamente implementável. Preservar mínimo de 12 caracteres e máximo de 72 bytes UTF-8 já verificados no CLI/login da fundação; ensaiar limites ASCII/multibyte também no formulário, sem confundir bytes com caracteres.

**Verificação futura:** regressões `AutenticacaoTest`, `IdentidadeTest`, `CriarUsuarioTest`, `CsrfTest`; testes web positivos/negativos, criação concorrente, desativação de sessão já aberta, falha da trilha e instrumentação de autenticação com credenciais sintéticas. Só evolução mínima necessária; nenhuma tabela de módulo futuro.

**Revisão independente:** `ses_e99a5d23affedCdekHc32Crus4`; dois MAJOR e dois MINOR corrigidos/revalidados, sem residual técnico conhecido. **Condição final:** REVISADA — PRONTA PARA AVALIAÇÃO HUMANA; D3 permanece.

<a id="story-1-4"></a>

### Story 1.4: Redefinir senha pessoal por recuperação administrativa auditada

**Como** CG ou SG vigente,
**quero** recuperar o acesso de uma conta pessoal após conferir o destinatário,
**para** restabelecer o serviço sem conhecer a senha anterior nem preservar sessões comprometidas.

**Condição:** PROPOSTA EM REVISÃO. **Dependência direta:** 1.3 (gestão, invalidação de sessões e autenticação instrumentada). **Referências:** RF-ACESSO-003/005/007, RF-AUDITORIA-001/002/007; RNF-01–05/13, RB-05/16/18, UX-DR18/49/52/55; CT-01–06 e Arquitetura §6.1. D3 define entrega/confirmação do destinatário antes do uso real; recuperação por e-mail é futura e condicional à configuração, não necessária para este fluxo administrativo.

**Escopo:** ação de redefinição na conta existente, sem alterar pessoa, vínculo, atividade, designação ou privilégios. Reutilizar hash/política, não construir provedor de e-mail ou reset público. Não revelar senha anterior nem recuperar segredo/MFA root por este fluxo.

**Acceptance Criteria:**

**AC-01 — Recuperação identificada.** **Given** gestor CG/SG vigente e conta alvo identificada por pessoa/ID conferidos; **When** confirmar destinatário conforme D3, motivo e nova senha válida em formulário protegido; **Then** gravar hash e trilha de recuperação atomicamente com autor/alvo/motivo, sem valor/hash da senha; **And** mostrar apenas confirmação após commit e entregar a credencial pelo canal aprovado, nunca grupo/log/recibo persistido.

**AC-02 — Sessões antigas.** **Given** duas sessões independentes do alvo, uma dormente, e eventual token de recuperação anterior; **When** redefinição confirmar; **Then** o mecanismo persistente de 1.2 torna todas as sessões e credenciais de recuperação anteriores inutilizáveis inclusive após reativação da conta, conforme CT-01/05; **And** login novo exige nova senha e cumpre a política escolhida em D3, senha antiga falha, não se concede mandato nem restaura atividade/elevação. Token técnico existente não é fluxo de e-mail homologado.

Autorredefinição do gestor invalida inclusive a única sessão que executou a ação; confirmação não preserva sessão antiga. Testar também requisição antiga que termina após reset, para que não recrie autenticação válida. Preservar mínimo de 12 caracteres e máximo de 72 bytes da política existente.

**AC-03 — Negativas.** **Given** anônimo, conta sem gestão vigente, destinatário não confirmado, motivo ausente, alvo inválido ou senha fora da política; **When** tentar redefinir inclusive por URL/ID manipulado; **Then** negar sem efeito parcial, evento próprio de segurança quando pertinente e mensagens sem enumeração/segredos desnecessários; **And** CG/SG não pode recuperar credencial institucional root/MFA por esse endpoint.

**AC-04 — Disputa/rollback.** **Given** duas redefinições ou recuperação concorrente a inativação/login/revogação da autoridade; **When** disputar o alvo; **Then** serializar/revalidar versões e autoridade, rejeitar estado desatualizado e não ressuscitar sessão anterior; **And** falha da trilha reverte hash/invalidação sem afirmar sucesso, não envia nem entrega senha de operação abortada.

**AC-05 — Interface protegida.** **Given** celular/teclado, retorno de validação ou falha; **When** abrir/confirmar ação; **Then** aplicar CT-05 com confirmação sensível, sem preencher senha de volta em HTML/flash, analytics ou estado persistente do navegador; **And** instrução distingue recuperação da conta pessoal de contingência root e explica perda das sessões anteriores.

**Verificação futura:** senha antiga/nova, autorização/CSRF, duas sessões, tokens antigos, reset concorrente e rollback PostgreSQL, ausência de segredo nas saídas e regressão da autenticação. Não executados agora.

**Revisão independente:** `ses_e99a3df07ffe8Jt2rEyOoentyH`; sem BLOCKER/MAJOR, MINOR de autorredefinição incorporado. **Condição final:** REVISADA — PRONTA PARA AVALIAÇÃO HUMANA; D3 permanece.

<a id="story-1-5"></a>

### Story 1.5: Gerir o catálogo institucional de encargos e suas responsabilidades

**Como** CG ou SG vigente,
**quero** consultar e manter encargos, responsabilidades e duração aprovada,
**para** organizar o serviço preservando os seis cargos da Mesa e a consciência coletiva.

**Condição:** PROPOSTA EM REVISÃO. **Dependências diretas:** 1.2 (gestão protegida/trilha/pessoas); 1.1 (encargos mínimos existentes). **Referências:** RF-SERVIDORES-001/003/005/015, RF-ACESSO-007/009/013, RF-AUDITORIA-001/002/007/009; RNF-03–06/13, RB-11/13/15–17, UX-DR5/13–14/17–18/40/55; Arquitetura §§4/6.1/6.4 (serviço), CT-01–06.

**Escopo:** completar catálogo mínimo com Coordenador Geral, Secretário Geral, Tesoureiro, RSG, RSG Suplente e Coordenador Kit Ingresso como encargos distintos; responsabilidades conforme Brief/Arquitetura. Função moderador é única, não duas funções permanentes fala/chat. Parametrizar duração aprovada, padrões de um ano Mesa/seis meses moderadores; D2 impede conversão arbitrária em dias. Nenhuma pessoa/designação é semeada. Sem reunião/escala do Epic 2 e sem editor arbitrário de permissões.

**Acceptance Criteria:**

**AC-01 — Catálogo íntegro.** **Given** catálogo com CG/SG inaugural; **When** gestor abrir gestão e completar encargos; **Then** seis cargos distintos e função moderação ficam disponíveis com responsabilidades/duração corretas, sem duplicar CG/SG ou criar titulares; **And** rótulos de voz/chat não viram cargos da Mesa nem novas fontes de privilégio.

D2 deve resolver representação/validação da duração antes de implementar essa parte: preservar a unidade civil homologada, com cálculo/exibição verificáveis, sem converter seis meses/um ano silenciosamente em 180/365 dias. A solução pode usar quantidade/unidade, datas efetivas ou configuração versionada; não está escolhida aqui. Reconciliar formalmente `duracao_dias` e fronteiras do dicionário §6.4 da Arquitetura com D2 no mesmo PR da implementação afetada, antes de aceitar schema/validação; divergência não pode ser considerada resolvida apenas por esta nota. Parâmetro 02 também precede ativação real; a mesma precisão integra AC-02/04.

Responsabilidades precisam de representação persistida ou derivada de fonte versionada verificável, associada à identidade estável do encargo e à decisão vigente; consulta e edição devem reconstruir antes/depois sem perder a versão institucional anterior. A escolha técnica mínima deve integrar §6.4 no mesmo PR de implementação de 1.5; não impor coluna física nem cadastro documental completo. Textos iniciais seguem Brief/UX aprovados, sem converter descrição em autorização executável.

**AC-02 — Mudança institucional.** **Given** nova função ou alteração de responsabilidade/duração/atividade; **When** gestor informar decisão institucional registrada, motivo e confirmar; **Then** versionar configuração/trilha atomicamente e preservar o núcleo dos seis cargos e referências; **And** sem decisão registrada negar a mudança. Remover logicamente função não apaga histórico nem revoga/concede silenciosamente mandatos existentes; impacto deve ser explicitado antes da confirmação e usar a fonte de vigência, não flags permanentes.

Alterar duração padrão não recalcula mandatos existentes. Atividade do catálogo controla disponibilidade para novas designações, não redefine silenciosamente vigência das existentes. Na ausência de decisão específica sobre efeito nos mandatos, negar inativação de encargo com designações existentes afetadas, preservando tudo; catálogo sem tais referências pode ser inativado logicamente. Essa negativa é segura e funcional agora, não depende de implementar encerramento futuro. Testar inativação do próprio encargo autorizador e corrida com alteração de designação, sem ampliar poder.

**AC-03 — Sem ampliação por nome.** **Given** função nova ou renomeada; **When** cadastrar ou mudar descrição; **Then** não criar capacidades executáveis novas ou atribuir acesso Kit/finanças por semelhança de nome; **And** mapeamento normativo dos seis cargos permanece explícito, sem converter RSG Suplente em administrador ou representante ativo sem substituição autorizada.

Função nova nasce sem capacidades executáveis; rejeitar payload de tipo/papel/capacidades, inclusive associação a capacidades existentes. Mapeamento normativo usa identidade estável explícita do cargo, não nome editável; aplicar a negativa também em AC-04.

**AC-04 — Negativas/integridade.** **Given** usuário sem gestão vigente, nome conflitante, duração inválida ou referência institucional ausente; **When** salvar ou chamar endpoint direto; **Then** negar conforme validação/Policy, com evento sanitizado pertinente; **And** nomes únicos/FKs/versionamento são efetivos, concorrência exige recarga e falha de auditoria reverte alteração.

Testar duas edições da mesma versão, criação simultânea de nome conflitante e perda de autoridade entre abertura e commit, com PostgreSQL real; anônimo, pessoa/conta inativa e demais encargos são negados. Não há sucesso/trilha de efetivação do perdedor. Aplicam-se as precisões de duração e payload dos AC-01/03.

**AC-05 — Experiência.** **Given** uso móvel/teclado ou catálogo vazio/parcial; **When** consultar/editar; **Then** mostrar responsabilidades, unidade de duração, padrões e decisão associada conforme CT-05; **And** confirmação não inventa renovação, mandato real ou decisão institucional ainda ausente.

O menu/ações do catálogo usam o mesmo contrato de capacidade exercitado em 1.3 AC-06; testar usuário sem gestão por URL direta e perda de capacidade por vigência, sem copiar controles de UI como fonte de autorização.

**Verificação futura:** seis cargos e moderação, configuração autorizada/negada, preservação de referências e ausência de privilégios por nome, versionamento/rollback/concorrência PostgreSQL e UX. D2 precede implementação afetada; parâmetro 02 precede ativação real.

**Revisão independente:** `ses_e99a27cfeffeNoeLVniP63wIE8`; dois MAJOR e dois MINOR corrigidos/revalidados; nenhum residual técnico conhecido. **Condição final:** REVISADA — PRONTA PARA AVALIAÇÃO HUMANA; D2 e parâmetro 02 permanecem.

<a id="story-1-6"></a>

### Story 1.6: Designar titulares com capacidades limitadas à vigência

**Como** CG ou SG vigente,
**quero** registrar designações e substituições autorizadas com titular e período,
**para** conceder só as capacidades corretas durante o serviço e preservar o histórico.

**Condição:** PROPOSTA EM REVISÃO. **Dependências diretas:** 1.3 (contas), 1.5 (catálogo); pessoas/trilha transitivas. Não depende de root, reunião, escala ou encerramento futuro. **Referências:** RF-SERVIDORES-004/005/006/007/008/015/017, RF-ACESSO-005/007/008/009/011/013, RF-IDENTIDADE-004, RF-AUDITORIA-001/002/007/009; RNF-01/03–06/13, RB-05/11/15–17, CA-05/19/26/33 (consumidores completos depois), UX-DR4–5/18/40/49/55; Arquitetura §§4/6.1 e designações; CT-01–06.

**Escopo:** designações de Mesa/serviço sem reunião, UI de designar/consultar histórico e vagas mínimas de encargos; Policies verificam estado `ativo`, titular, atividade de pessoa/conta e período em cada operação. Não atribuir poderes de módulos inexistentes nem construir suas telas; fornecer decisões de capacidade testáveis para matriz normativa. Associação a dia/horário/reunião e dupla será integrada no Epic 2, não FK antecipada. Conta não é exigida a pessoa histórica sem operação; operador atual exige conta pessoal ativa.

**Acceptance Criteria:**

**AC-01 — Designação válida.** **Given** gestor vigente, pessoa confirmada e encargo ativo; **When** registrar titular, início/fim, motivo/referência institucional e confirmar; **Then** persistir designação e trilha atomicamente, sem mudar conta/vínculo; **And** padrões seis meses/um ano são sugestões conferíveis conforme parâmetro 02/D2, nunca datas inventadas nem concessão por simples criação de conta. Titular e encargo têm FKs obrigatórias, datas/estados com constraints.

Término é obrigatório no novo fluxo; legado sem término só por futuro contrato de migração, não concessão indefinida implícita.

**AC-02 — Ativação e fronteiras.** **Given** designação futura, vigente ou expirada e scheduler parado; **When** a mesma conta/sessão solicitar capacidade antes/no/depois das fronteiras homologadas em D2; **Then** conceder só quando `ativo` e dentro da vigência, negando imediatamente fora dela; **And** cache/sessão antigos nunca prolongam poder, login pessoal ativo continua possível e mudança de vigência é auditada com invalidação das decisões privilegiadas.

Alterar administrativamente datas exige trilha transacional; passagem natural do relógio não precisa de escrita para a Policy negar, nem gera auditoria por consulta mecânica. Evento de término/manutenção é próprio do incremento correspondente.

Para 1.1 e 1.6–1.8, D2 deve tornar testáveis separadamente datas civis previstas, fronteiras efetivas de autorização, encerramento antecipado e registro/processamento tardio. `date` não implica meia-noite UTC nem último segundo do dia: uma convenção de início inclusivo/fim exclusivo após o último dia civil é alternativa técnica para D2, não decisão tomada. Testar instante anterior/exato/posterior em America/Bahia e UTC, virada de mês/ano, meses desiguais e ano bissexto; o resultado deve coincidir em UI, Policy e job. Preservar período originalmente aprovado e suas alterações versionadas. A representação lógica deve permitir consultar instante efetivo do encerramento e instante de registro/processamento separadamente, sem sobrescrever término previsto para simular entrega antecipada; harmonizar §6.4 no PR afetado sem impor `terminado_em` ou trocar automaticamente todo `date` por timestamp.

**AC-03 — Matriz e segregação.** **Given** cada um dos seis cargos/moderação e conta sem encargo; **When** resolver capacidades em testes de contrato e usar rotas existentes; **Then** CG/SG gerem contas/mandatos sem acesso operacional Kit/escrita financeira, Tesoureiro e Kit só tenham capacidades normativas próprias e demais não ganhem gestão por padrão; **And** RSG Suplente só atua em substituição autorizada, fala/chat não criam mandatos separados. Consumidores futuros devem provar isso em seus endpoints, não são simulados como entregues.

Testar cada encargo isoladamente e CG/SG no modo ordinário sem outra fonte legítima. Exclusividade de capacidade por encargo não decide se uma pessoa pode acumular dois encargos: isso permanece em D4, sem constraint automática proibindo Tesouraria + Kit e sem permissão implícita de acumulação. RF-SERVIDORES-004 continua parcial até agenda/dia/horário do Epic 2; testes desta Story não autorizam aceite integral do RF composto.

**AC-04 — Substituição e disputa.** **Given** substituição institucional com vigência explícita; **When** registrar nova designação e relacionar substituição; **Then** não inventar quinto estado e impedir sobreposição de autoridade incompatível segundo regra homologada D4; **And** concorrência real de duas concessões para o mesmo encargo/período revalida sob bloqueio e constraint PostgreSQL correspondente, sem dupla titularidade não autorizada. Não encerrar implicitamente o titular anterior por sobrescrita; se for necessária transição conjunta, ela é atomicamente justificada neste incremento.

**AC-05 — Histórico/vaga.** **Given** designações históricas de pessoas sem conta e mandatos atuais/futuros/expirados; **When** gestor consultar encargo/período; **Then** preservar titulares/versões e derivar VAGO da ausência de designação ativa vigente, sem linha/pessoa VAGO; **And** lista mínima restrita não depende de comando WhatsApp, calendário ou política de publicação nominal futura.

**AC-06 — Negativas e falha.** **Given** não gestor, gestor expirado, pessoa inválida/inativa, operador atual sem conta, datas inválidas, status não canônico ou estado desatualizado; **When** designar/alterar por UI/API; **Then** negar sem efeito parcial, registrar segurança pertinente e aplicar CT-02 em corrida com revogação/inativação do gestor; **And** falha de trilha reverte concessão/transição/invalidação sem sucesso aparente, mantendo bootstrap consumido.

**AC-07 — Tela compreensível.** **Given** uso móvel/teclado; **When** conferir designação; **Then** mostrar pessoa/encargo, vigência/fuso, capacidades e efeitos da substituição antes de confirmar conforme CT-05; **And** não expor fatos íntimos nem apresentar dado futuro como mandato vigente.

**D4 — Antes de implementar substituições/constraints de sobreposição:** responsável/Mesa homologar compatibilidades de acumulação/titularidade por encargo, intervalo e substituição (inclusive RSG Suplente). Fontes não autorizam impor unicidade universal de uma pessoa/cargo nem acumulação livre; sem essa decisão negar conflitos potencialmente incompatíveis, sem substituir titular automaticamente. A regra e os testes de PostgreSQL escolhidos devem corresponder à decisão, não a uma convenção inventada.

**Verificação futura:** fronteiras/relógio, sessão/cache aquecidos com scheduler parado, matriz permitida/negada, conta histórica sem login, substituição/corrida de concessão/rollback PostgreSQL e histórico/vaga restritos. D2/D4 são gates de implementação afetada, não dependências de Stories futuras.

**Revisão independente:** `ses_e99a04653ffeh29wp2dDaGi34r`; nenhum BLOCKER/MAJOR, MINOR temporal esclarecido. **Condição final:** REVISADA — PRONTA PARA AVALIAÇÃO HUMANA; D2/D4 permanecem.

<a id="story-1-7"></a>

### Story 1.7: Registrar encerramento, entrega e destituição com revogação imediata

**Como** CG ou SG vigente,
**quero** registrar a conclusão, entrega ou destituição autorizada de uma designação,
**para** retirar capacidades imediatamente, atualizar vagas e preservar o histórico sem exposição íntima.

**Condição:** PROPOSTA EM REVISÃO. **Dependência direta:** 1.6 (designações/Policies/histórico/vagas). **Referências:** RF-SERVIDORES-006/007/008/012/017, RF-ACESSO-005/009/011/013, RF-AUDITORIA-001/002/007/009; RNF-03–06/13, RB-11/13/16/17, CA-19/26/33 e CA-08 parcial, UX-DR18/40/49/52/55; Arquitetura §4/designações, CT-01–06.

**Escopo:** transição manual `ativo`→`concluido`/`entregue`/`destituido`, sem estado suspenso/revogado/VAGO; fim do termo para concluído, renúncia para entregue, fato/deliberação autorizado para destituído. Sem inferência Zoom, formulário de saúde, apagar histórico, automação de condição ainda não confirmada ou reabrir bootstrap. Não bloquear conta apenas porque mandato acabou.

**Acceptance Criteria:**

**AC-01 — Encerramento legítimo.** **Given** designação existente e gestor vigente; **When** confirmar estado final compatível, instante/data, motivo mínimo e referência institucional quando exigida; **Then** atualizar estado/trilha/histórico atomicamente com autor e instante; **And** preservar designação original/versão e invalidar imediatamente as decisões privilegiadas derivadas do mandato, sem conceder outra função.

Distinguir instante do registro UTC da data/instante efetivo do encerramento, preservando período original; conclusão só após fim conforme D2. Validar registro tardio sem reabrir autorização histórica e rejeitar data futura como agendamento implícito deste fluxo. Entrega/destituição confirmada revoga imediatamente; retroatividade institucional adicional não é presumida.

Persistir ou derivar de histórico íntegro os fatos temporais distintos definidos em 1.6 AC-02, incluindo instante efetivo antecipado e instante de registro; testar consulta posterior sem substituir o período previsto. Corrida entre encerramento antecipado, extensão e operação privilegiada usa CT-02; job tardio não reescreve o fato institucional nem desloca a fronteira de autorização.

**AC-02 — Revogação em sessões.** **Given** servidor com sessões abertas e caches aquecidos; **When** término/entrega/destituição confirmar; **Then** próxima operação protegida nega capacidades encerradas em todos os dispositivos sem aguardar job/novo login; **And** conta pessoal ativa continua autenticável e outras capacidades legitimamente vigentes não são retiradas por engano. Requisição concorrente só confirma mutação se autoridade ainda válida no ponto de revalidação sob bloqueio.

Ensaiar dois clientes independentes, um sem requisições durante o encerramento, e eventual inativação/reativação posterior: capacidade encerrada não retorna por cookie/cache antigo, mas outro mandato ainda vigente permanece. Aplicar a distinção autenticação/capacidade de CT-01/05, não logout indiscriminado por fim de mandato.

**AC-03 — Destituição sem vigilância.** **Given** deliberação ou confirmação autorizada da hipótese institucional de duas faltas injustificadas ou recaída; **When** gestor registrar destituição manual; **Then** exigir referência/fundamento mínimo restrito e motivo suficiente, sem copiar detalhes íntimos; **And** ausência de confirmação/autorização nega, Zoom/ausência de eventos nunca prova condição pessoal. Grupo/público não recebe o fundamento íntimo. D5 já condiciona a implementação desta destituição manual: distinguir quem tem autoridade institucional para confirmar o fato/deliberação, quem registra como gestor e quem executa tecnicamente; não pressupor que CG/SG pode confirmar por ser operador, nem esperar a Story 1.8 resolver a decisão.

**AC-04 — Histórico e vaga imediatos.** **Given** transição confirmada; **When** consultar encargo/histórico; **Then** vaga é derivada imediatamente da ausência de outro titular ativo vigente e histórico mantém razão/autoria; **And** não criar linha VAGO, duplicar titular nem atribuir pessoa fictícia, sem depender de calendário ou comandos de outros Epics.

**AC-05 — Repetição, disputa e falha.** **Given** mesmo pedido repetido ou duas transições concorrentes sobre a mesma versão; **When** processar; **Then** no máximo uma transição efetiva, repetição não duplica efeitos e conflito divergente exige recarga sem substituir razão anterior; **And** autorização/dados revalidados em PostgreSQL, falha de trilha reverte estado/invalidação e não afirma revogação efetivada inexistente.

**AC-06 — Negativas e confirmação.** **Given** anônimo, gestor expirado/inativo, alvo inexistente, motivo ausente ou estado incompatível; **When** chamar UI/API; **Then** negar sem efeito parcial com evento sanitizado pertinente; **And** CT-05 mostra consequências e confirmação inclusive autoencerramento/última autoridade, sem veto inventado, restauração implícita, inativação obrigatória de login ou rearmamento do bootstrap.

**Verificação futura:** matriz de transições/negativas, autoria e sigilo do fundamento, relógio/scheduler desligado, duas sessões/cache, manutenção de outro mandato legítimo, corrida encerramento/mutação e encerramento/encerramento, rollback PostgreSQL e histórico/vaga. D2/D4 aplicam-se a transições temporais/substituição; nenhuma automação de alerta/envio faz parte desta Story.

**Revisão independente:** `ses_e999eb664ffetTGRmSvud2z3dp`; sem BLOCKER/MAJOR; MINOR de instante efetivo/registro esclarecido. **Condição final:** REVISADA — PRONTA PARA AVALIAÇÃO HUMANA; gates D2/D4 preservados.

<a id="story-1-8"></a>

### Story 1.8: Conciliar términos e aplicar consequências de fatos confirmados sem duplicação

**Como** CG ou SG vigente,
**quero** que a manutenção conclua mandatos vencidos e aplique regras institucionais habilitadas a fatos já confirmados,
**para** manter histórico e vagas coerentes sem scheduler prolongar poderes ou automatizar julgamentos pessoais.

**Condição:** PROPOSTA EM REVISÃO. **Dependência direta:** 1.7 (transições auditadas/revogação). **Referências:** RF-AUTOMACOES-008, RF-SERVIDORES-006/007/008/012/017, RF-ACESSO-011, RF-AUDITORIA-001/002/005/007/009; RNF-03–05/09/15, RB-11/13/16/23, CA-19/26/33 e CA-08 parcial; UX-DR49/52/55, Arquitetura §4 e designações/síntese 2, CT-01–06.

**Escopo:** job Laravel complementar de conclusão por término e aplicação idempotente de regra aprovada após confirmação humana autorizada, com tela/ação mínima de confirmação e habilitação da regra própria deste fluxo. Não depende de editor/engine do Epic 12, mensageria/n8n/Zoom ou ata de serviço futura. Não constrói cadastro de saúde/frequência: guarda somente referência restrita da decisão/fato confirmado e correlação, sem detalhes íntimos. Flags/regras começam sem efeitos de destituição até habilitação institucional explícita; conclusão temporal segue regra aprovada, não depende de decisão de destituição.

**Acceptance Criteria:**

**AC-01 — Manutenção de término.** **Given** mandato vencido e ainda marcado ativo; **When** job executar; **Then** revalidar período e transicionar uma única vez para concluido com motivo de término/ator técnico/origem/correlação, trilha e limpeza complementar atômicas; **And** manter histórico e VAGO derivado, sem criar designação/linha extra ou inventar usuário humano executor.

**AC-02 — Scheduler não autoriza.** **Given** scheduler parado, fila atrasada ou job falhando; **When** mandato expirar; **Then** Policy já nega por data em cada operação e cache não concede poder; **And** histórico distingue término efetivo da execução tardia, reprocessamento retoma sem duplicar efeito nem confundir ausência de job com mandato vigente.

**AC-03 — Confirmação e regra.** **Given** hipótese de duas faltas injustificadas ou recaída abrangida por regra institucional aprovada/habilitada e confirmação emitida pela autoridade legitimada em D5; **When** CG/SG vigente autorizado registrar essa confirmação, referência verificável mínima restrita e motivo; **Then** preservar confirmação auditada vinculada à designação e versão da regra e aplicar consequência idempotente por job; **And** distinguir autoridade confirmadora, operador que registrou e executor técnico, sem presumir autoridade de confirmação pelo acesso administrativo. Testar confirmação válida por autoridade distinta do registrador e negativa de CG/SG sem autoridade D5 tentando autoconfirmar; se D5 legitimar essa coincidência, exigir sua prova explícita. Zoom, silêncio de webhook, IA/heurística ou declaração não confirmada nunca disparam destituição. Sem regra habilitada não há destituição automática; 1.7 mantém seu fluxo manual sujeito à parcela pertinente de D5.

**AC-04 — Revalidação e revogação.** **Given** consequência pendente; **When** worker iniciar transação; **Then** revalidar regra habilitada, confirmação válida e autoridade legitimada conforme D5, versão/estado da designação e elegibilidade, invalidando capacidades junto à destituição; **And** preservar separadamente confirmador institucional, operador pessoal registrador e `job` executor com origem/correlação, sem atribuir execução ao login de qualquer dos humanos. Instantes de fato efetivo, registro e processamento seguem 1.6 AC-02/1.7 AC-01 e não alteram o período previsto.

**AC-05 — Concorrência/retomada.** **Given** dois workers ou job concorrente a encerramento manual, extensão autorizada de término ou cancelamento da confirmação antes da aplicação; **When** disputarem; **Then** bloqueios e chave de efeito/versão asseguram no máximo uma transição compatível e não aplicam consequência cancelada/obsoleta; **And** retry após falha da trilha reverte toda a tentativa e pode retomar sem duplicar, preservando transição já confirmada.

Confirmação commitada sem despacho de job (falha/reinício entre commit e enfileiramento) permanece identificável/recuperável por manutenção/reprocessamento autorizado de AC-07, com as mesmas revalidações. Testar recuperação única e cancelamento antes dela, sem impor outbox/infraestrutura adicional como solução obrigatória.

**AC-06 — Controle e sigilo.** **Given** operador sem gestão vigente ou payload manipulando fato/regra; **When** confirmar/habilitar/cancelar pendência ou consultar fundamento; **Then** negar via Policy e registrar segurança pertinente, sem dados íntimos na resposta; **And** gestor só vê referência mínima necessária, leitura sensível é auditada sem conteúdo e fail-closed, alterações de regra/confirmação são versionadas/auditadas. Cancelamento antes da aplicação não restaura mandato já encerrado.

**AC-07 — Operação verificável.** **Given** falha/atraso de manutenção; **When** gestor consultar a designação; **Then** mostrar estado efetivo, registro técnico sanitizado e possibilidade de reprocessar pelo mecanismo autorizado sem duplicação, conforme CT-05; **And** homologar worker/scheduler/locks no runtime existente antes de habilitar efeitos reais, sem exigir Redis ou alterar infraestrutura neste planejamento.

**Pendência D5 (alcança 1.7 e 1.8):** Mesa/responsável homologar quem pode confirmar institucionalmente e qual referência mínima comprova deliberação/confirmação, já antes da destituição manual de 1.7 (CG/SG registram, não ganham autoridade institucional por suposição). Para 1.8, homologar também regra/habilitação da consequência automática. Antes de implementar cada parte afetada, resolver a parcela pertinente de D5 e política de guarda do fundamento; não inventar quórum, elegibilidade ou prazo. Automação de término usa D2 já exigida na predecessora; não depende da decisão de destituição.

**Verificação futura:** relógio com scheduler desligado, jobs duplicados e duas conexões, disputa com transição/extensão/cancelamento, flags/regra/versão, rollback/retry e atores distintos. Nenhum evento externo ou módulo futuro necessário; RF-AUDITORIA-005 é exercitado com job real de serviço, integração só se comprova nos consumidores.

**Revisão independente:** `ses_e999cc595ffeTDgVz1y1YZyhXA`; nenhum BLOCKER/MAJOR, MINOR de recuperação entre commit/despacho incorporado. **Condição final:** REVISADA — PRONTA PARA AVALIAÇÃO HUMANA; D5 e guarda permanecem.

<a id="story-1-9"></a>

### Story 1.9: Delegar capacidades excepcionais com prazo e segregação

**Como** CG ou SG vigente,
**quero** conceder e revogar delegações excepcionais justificadas e temporárias,
**para** atender necessidade limitada sem transformar exceção em perfil permanente ou romper exclusividades.

**Condição:** PROPOSTA EM REVISÃO. **Dependências diretas:** 1.6 (resolução de capacidades), 1.7 (revogação/histórico), 1.3 (contas). **Referências:** RF-ACESSO-008/009/011/013, RF-AUDITORIA-001/002/007/009; RNF-03–06/13, RB-16–18, CA-05/19 parciais, UX-DR5/18/49/55; Arquitetura §§4/6.1 (`permissoes`/`permissoes_usuario`), CT-01–06.

**Escopo:** catálogo fechado somente de capacidades existentes/delegáveis homologadas e tela de conceder/revogar com início/fim/motivo/autor/destinatário. Somente entidades/constraints necessárias, sem editor de código, atribuição genérica por nome, capacidade de módulos futuros ou root. D6 define allowlist institucional antes de implementar concessão afetada; não presumir toda gestão delegável.

**Acceptance Criteria:**

**AC-01 — Delegação delimitada.** **Given** gestor vigente autorizado em D6, destinatário com `usuario` ativo vinculado a `pessoa` ativa e capacidade existente explicitamente delegável; **When** confirmar escopo, início/fim e motivo; **Then** vincular concessão à conta pessoal correta (`usuario` titular, conforme §6.1), persistindo delegação/trilha atomicamente e concedendo só no escopo/período aprovado; **And** manter origem excepcional identificável, sem copiar privilégio permanente à conta nem atribuir delegação executável a pessoa sem login. Negar pessoa sem conta, vínculo inválido e tentativa de transferir titular silenciosamente; criar conta posteriormente não ativa concessão que não foi legitimamente realizada.

**AC-02 — Barreiras ordinárias.** **Given** solicitação de delegar acesso operacional/PII Kit, edição financeira, elevação root ou capacidade não homologada/inexistente; **When** tentar por UI/API/payload adulterado; **Then** negar sem efeitos e registrar evento sanitizado; **And** concessor CG/SG não usa delegação para dar a si ou a terceiros exclusividades reservadas a outro encargo. Nenhuma cadeia/subdelegação é permitida por posse da própria delegação; exigiria autorização explícita inexistente neste incremento.

**AC-03 — Vigência e desligamento.** **Given** delegação futura/vigente/expirada/revogada, conta/pessoa inativa ou autoridade vinculada à origem encerrada conforme D6; **When** resolver a mesma capacidade com sessão/cache aquecidos e scheduler parado; **Then** respeitar período, escopo e regra de término homologada, negando após expiração/revogação imediatamente; **And** revogar uma delegação não remove outra fonte legítima da capacidade nem desativa login pessoal.

Testar antes/no/depois do início e término segundo convenção temporal homologada (D2 quando aplicável), persistindo instantes UTC e exibindo America/Bahia; não presumir inclusividade nem prazo máximo. Campos opcionais do dicionário arquitetural são propostas físicas, não dispensam autor, prazo, escopo e motivo obrigatórios aqui; alinhar seção existente sob autorização na implementação afetada.

Consumir CT-01/05 em duas sessões independentes, uma dormente, com cache aquecido, scheduler parado e corrida uso/revogação. Criar/reativar conta não reativa delegação revogada/expirada ou inválida segundo D6; uma fonte legitimamente vigente não é removida junto à revogada. D6 decide efeitos adicionais de inativação e fim da autoridade concedente, sem herança automática por `pessoa`. Antes de aceitar o schema de 1.9, harmonizar §6.1 no mesmo PR pós-D6: titular `usuario`, concedente, prazo, motivo e escopo exigidos pelo fluxo positivo não podem ser dispensados por colunas opcionais do dicionário de referência.

**AC-04 — Revogação e trilha.** **Given** delegação existente; **When** gestor autorizado confirmar revogação com motivo; **Then** registrar instante/autor/razão e invalidar decisões privilegiadas atomicamente, sem apagar histórico; **And** conceder novamente é novo registro legítimo, não reativação silenciosa da revogada.

**AC-05 — Integridade e corrida.** **Given** período inválido/fim ausente, alvo inválido ou duas concessões/revogação e uso concorrentes; **When** validar/processar; **Then** constraints e bloqueios da fonte de autorização impedem estado contraditório, efeito duplicado e uso com autoridade já revogada no ponto de revalidação; **And** falha de trilha reverte concessão/revogação/invalidação e conflito divergente exige recarga, com parâmetros desconhecidos rejeitados.

**AC-06 — Gestão clara.** **Given** operador autorizado ou não; **When** listar/abrir delegações; **Then** mostrar apenas registros no escopo permitido com destinatário, capacidade, prazo e motivo redigido, controles protegidos no backend e CT-05; **And** não transformar listagem em exposição de fundamento íntimo nem criar privilégios para outros encargos por associação de nome.

**D6 — Antes da implementação afetada:** Mesa/responsável homologar capacidades existentes delegáveis, destinatários/escopos elegíveis, quem concede/revoga e efeito de término do mandato do concessor/origem (inclusive delegação autocentrada). Não delegar Kit/escrita financeira/root no ordinário; nenhuma decisão pode ampliar isso implicitamente. Enquanto D6 não resolvida, nenhuma concessão real é habilitada e a parte afetada não é considerada pronta para implementar.

**Verificação futura:** allowlist positiva real definida em D6, negativas de exclusividade/autoampliação/subdelegação, fronteiras sem scheduler, cache/sessão, fontes múltiplas, concorrência/rollback PostgreSQL. Não contar allowlist vazia ou apenas negações como entrega completa do RF-ACESSO-008.

**Revisão independente:** `ses_e999ab7c4ffesGwo8qOd9hOnwH`; nenhum BLOCKER/MAJOR, MINOR temporal incorporado. **Condição final:** REVISADA — PRONTA PARA AVALIAÇÃO HUMANA; D6 permanece.

<a id="story-1-10"></a>

### Story 1.10: Consultar a trilha institucional sem revelar conteúdo de outros serviços

**Como** CG ou SG vigente,
**quero** consultar ações institucionais e suas versões/correlações,
**para** reconstruir responsabilidades sem editar evidências ou acessar conteúdo reservado ao Kit e à Tesouraria.

**Condição:** PROPOSTA EM REVISÃO. **Dependência direta:** 1.3 (gestão protegida e eventos de identidade; trilha/leitura privada transitivas de 1.1/1.2). Eventos de 1.7/1.8/1.9 ampliam filtros/regressões quando disponíveis, não bloqueiam consulta nem custódia/root por D5/D6. **Referências:** RF-AUDITORIA-001–009, RF-ACESSO-009/013; RNF-03–06/13/15, RB-05/11/17, CA-05/06/19/23/33 parciais, UX-DR5/17/49/51–52/55; Arquitetura §§6.8/síntese 2 e matriz 6.1, CT-01–06.

**Escopo:** tela restrita de consulta/paginação/filtros por período/ator/origem/entidade/ação/correlação sobre eventos reais já produzidos; detalhe redigido e versões de registros institucionais existentes. Contratos reutilizáveis de justificativa para registro oficial enviado e de leitura/revelação sensível com testes sintéticos, sem atas, Kit, segredo Zoom, livro financeiro, exportação ou root futuros. Não gerar falso registro oficial só para demonstrar tela.

**Acceptance Criteria:**

**AC-01 — Consulta institucional.** **Given** CG/SG vigente; **When** filtrar/listar/detalhar eventos institucionais de pessoas/contas/encargos/designações/delegações/jobs; **Then** mostrar ator humano ou técnico real, origem, instante UTC com exibição local, entidade/ID, ação, correlação, motivo aplicável e antes/depois redigidos; **And** filtros/paginação não escapam do escopo e dados indisponíveis não são inventados, nem IP obrigatório para CLI/job.

CT-03 inclui módulo e captura de IP/user-agent quando disponíveis e pertinentes, sem inventar IP de CLI/job nem exibir metadados além do escopo autorizado. Produtores adicionais integram a consulta quando entregues, sem impedir listar eventos já existentes.

**AC-02 — Consulta segregada.** **Given** anônimo, conta sem consulta institucional ou operador de outro serviço; **When** manipular IDs/filtros/URLs; **Then** negar escopo não autorizado sem revelar presença de dados sensíveis; **And** CG/SG não recebe PII Kit ou conteúdo financeiro reservado pela consulta de auditoria. Contrato de projeções distingue institucional CG/SG, financeiro pertinente Tesoureiro, Kit privativo e integral somente root elevado quando 1.12 existir; testar matriz com fixtures sintéticas, sem criar tabelas dos domínios futuros.

**AC-03 — Imutabilidade normal.** **Given** qualquer usuário do fluxo comum, inclusive administrador institucional; **When** tentar editar/excluir log por endpoint/payload; **Then** não haver rota normal de mutação da trilha e tentativa não altera evidência; **And** testar proteção de escrita pelo contexto da aplicação, sem prometer imutabilidade contra superusuário de banco ou host comprometido. Registros institucionais retificados/excluídos logicamente preservam versões/razão/autor consultáveis no escopo permitido.

A proteção append-only nasce com a trilha em 1.1 e é regredida aqui: testar SQL direto UPDATE/DELETE/TRUNCATE no PostgreSQL real com privilégios ordinários da aplicação, inclusive caminhos fora do ORM e alteração estrutural disponível nesse contexto; as tentativas não podem alterar/remover evidência. A solução deve demonstrar proteção efetiva, não apenas callback Eloquent ou rota ausente; não se prescreve trigger/role específica. Processo legal extraordinário de anonimização/expurgo não é edição normal: requer política por classe, autoridade e mecanismo separado auditável, sem afirmar esse processo operacional entregue neste incremento.

**AC-04 — Contrato de retificação oficial.** **Given** estado sintético de registro oficial enviado e ator autorizado pelo dono da fonte; **When** aplicar retificação pelo contrato reutilizável; **Then** motivo vazio é rejeitado e motivo válido produz versão/antes/depois redigidos e trilha atômicos, revertendo junto se falhar; **And** não conceder autorização genérica para editar atas e não declarar RF-AUDITORIA-003 ponta a ponta concluído antes dos Epics 5/8 e demais fontes oficiais.

**AC-05 — Contrato de leitura sensível.** **Given** fonte sensível sintética e Policy autorizando escopo; **When** revelar/ler pelo contrato; **Then** registrar ator/referência/tipo de acesso/origem/correlação sem valor secreto antes de entregar resposta; **And** falha no registro impede revelação, negativa não copia conteúdo e acessar detalhe privado da própria trilha é também leitura sensível auditável sem cascata recursiva. Gravação do evento não gera outro evento de si mesma.

**AC-06 — Cobertura de entradas.** **Given** mutações reais existentes por HTTP/CLI/job e eventual escrita fora do ORM; **When** executar testes de cobertura de auditoria; **Then** toda entrada entregue possui ator/contexto/redação/transação, ou escrita direta proibida pelo contexto da aplicação; **And** confirmar insert/update/exclusão lógica/permissão/configuração e eventos relevantes de login/negação/contato/término com dados sintéticos, sem auditar por linha sessão/cache/fila ou afirmar integrações externas ainda inexistentes.

Enumerar leitura de contato privado, encerramento de designação, login aceito/recusado, logout e eventos pertinentes de autorização/negação. É conferência/regressão da instrumentação já entregue, reutilizando o mecanismo de leitura de 1.2, não sua reconstrução. Cobertura de job/delegação/encerramento incorpora-se quando esses produtores estiverem disponíveis, sem torná-los pré-requisitos funcionais da consulta inicial.

**AC-07 — Privacidade/UX e ciclo de vida.** **Given** resultado vazio, erro ou consulta móvel/teclado; **When** usar filtros/detalhe; **Then** aplicar CT-05 e não persistir conteúdo privado em cache público/analytics/toast/export oculto; **And** guarda/anonimização de PII na trilha depende de política por classe (parâmetro 03), sem expurgo destrutivo do histórico institucional no fluxo comum nem prazo novo. **Given** política legal por classe homologada e fonte sintética; **When** testar contrato de descarte autorizado; **Then** distinguir anonimização/expurgo real de exclusão lógica, selecionar só dados abrangidos, preservar autoria/referências institucionais mínimas lícitas e emitir evidência redigida do resultado sem reter a própria PII descartada. A execução real é responsabilidade de cada módulo sobre sua PII e da capacidade existente `E14-recuperacao` para trilhas, retenção/rotação de backups/chaves e restauração sem ressuscitar dado sujeito a descarte. Esse aceite futuro exige inventário por classe, autoridade/fundamento legal, execução verificável também em réplicas/caches/logs e manejo dos backups conforme política, e teste de restore/consulta que não reintroduza PII expurgada. Sem esse mecanismo operacional homologado, RF-AUDITORIA-009 fica parcial; política aprovada e soft delete sozinhos não o completam. Não criar Story de outro Epic agora nem condicionar a consulta desta Story à implementação futura do descarte.

**Verificação futura:** filtros/cross-user e IDs diretos, matriz sintética de projeções, ausência de endpoints de edição, leituras fail-closed, retificação justificada com rollback, enumeração de entradas reais e prova de ausência de recursão. Consumidores precisam de ensaios próprios; mocks de Kit/Zoom/finanças não completam seus RF/CA.

No AC-07, ensaiar também navegação: **Given** CG/SG vigente e usuário sem consulta institucional; **When** renderizar menu e acessar URL direta da trilha; **Then** exibir somente ações pertinentes e negar backend não autorizado; **And** expiração/desativação perde acesso na próxima operação apesar de menu antigo. 1.12 adiciona elevação/deselevação ao mesmo teste quando existir, não é dependência desta consulta.

**Revisão independente:** `ses_e9998b398ffe1eVzKBRReYNbnh`; nenhum BLOCKER/MAJOR, dois MINOR de enumeração esclarecidos. **Condição final:** REVISADA — PRONTA PARA AVALIAÇÃO HUMANA; política por classe continua pendente.

<a id="story-1-11"></a>

### Story 1.11: Preparar a custódia e recuperação segura da contingência institucional

**Como** responsável institucional pela contingência identificado pessoalmente,
**quero** configurar e exercitar guarda, MFA, rotação e recuperação da credencial de elevação,
**para** permitir futura contingência sem login compartilhado, segredo exposto ou bypass de autenticação pessoal.

**Condição:** PROPOSTA EM REVISÃO. **Dependências diretas:** 1.3/1.4 (conta pessoal/recuperação), 1.10 (trilha/leituras/consulta). **Referências:** RF-ACESSO-006/007, RF-ROOT-001 (preparação), RF-AUDITORIA-001/002/007/008; RNF-01–05/13/15, RB-06/17/18, CA-23/27 parciais, UX-DR18/45/49/52; Arquitetura §§4/6.1/síntese 1–2; CT-01–06.

**Escopo:** fluxo controlado para material de contingência e MFA externo/pessoal conforme decisão D7, sem dar poder root nesta Story; ensaio de rotação/recuperação com material sintético, configuração de TTL/inatividade/custódia e verificação de fatores. Nenhuma PKI, provedor obrigatório, dados reais ou mudança automática de Docker/segredos. Persistir só referências/verificadores e metadados mínimos necessários; segredos institucionais em cofre protegido conforme contrato. Não presumir que CG/SG pode substituir fatores de root pela gestão de contas.

**Acceptance Criteria:**

**AC-01 — Preparação autorizada.** **Given** política D7 homologada, conta/pessoa ativa e autoridade de custódia comprovada; **When** responsável se reautenticar e seguir procedimento controlado aprovado de provisionar credencial/fator; **Then** vincular configuração à instalação, identidade e política/versão com trilha redigida atômica dos metadados; **And** a preparação não cria conta root, designação, capacidade permanente nem sessão elevada.

D7 deve distinguir elegibilidade institucional, prova verificável e executor pessoal autenticado da custódia: declarar se a autoridade é de mandato já existente ou procedimento independente homologado. Não exigir root para preparar root; shell, flag autodeclarada e referência livre não provam autorização. CT-01 admite procedimento independente de custódia exclusivamente para preparar/recuperar material, não outro bootstrap de designação, bypass global ou concessão de poder de negócio. A exceção inaugural de designar CG/SG segue exclusiva de 1.1; aplicar também a AC-05.

O vínculo à instalação consome exclusivamente identidade/evidência homologadas em D1 e materializadas em 1.1; não gerar segundo identificador independente para custódia. Ensaiar configuração de outra instalação, fork limpo e restore com inconsistência: negar habilitação automática, preservar histórico e encaminhar recuperação autorizada conforme D7. Recuperar não rearma marca inaugural nem copia segredos para fork. A parcela de D7 relativa a restore deve ser coerente com D1 e a política por classe; sem esse contrato o caminho positivo da custódia permanece condicionado.

**AC-02 — Segredo e fator.** **Given** segredo/MFA configurados; **When** verificar fatores em ensaio sintético; **Then** comprovar fator independente adequado ao operador pessoal e rejeitar fator ausente/incorreto/reutilizado conforme método homologado; **And** não expor segredo/chave/código de recuperação em repositório, HTML de listagem, logs, auditoria, parâmetros CLI ou mensagens. Eventual exibição única estritamente necessária ao enrolamento usa canal pessoal protegido definido em D7, nunca resposta comum persistida.

Enrolamento/retirada/revelação de material gera evento próprio redigido com operador, destinatário se distinto, versão, resultado e correlação; sem segredo/código/chave. Se evento obrigatório falhar, não revelar/entregar material. Registrar quem retirou não é copiar o segredo. Testar, também em AC-04/05, que senha/reset pessoal, sessão ou segredo institucional isolados não substituem fator/prova independente homologados; fator de outra pessoa e recuperação autodeclarada falham.

**AC-03 — Rotação.** **Given** credencial/fator configurado e responsável autorizado; **When** rotacionar conforme D7; **Then** registrar autor/razão/versão sem valores, rejeitar material anterior e preservar referências de auditoria; **And** uma rotação não restaura privilégio, vínculo ou fator revogado. Definir transição consistente entre cofre e metadados, sem afirmar transação distribuída inexistente: falha deixa contingência desabilitada ou versão anterior comprovadamente íntegra, nunca mistura aceita de versões.

Consistência cofre–metadados vale também no provisionamento (AC-01) e recuperação (AC-04): versão anterior comprovadamente íntegra ou contingência desabilitada, nunca mistura aceita. Testar timeout/resultado externo desconhecido, falha de trilha e retry após interrupção, sem sucesso aparente. Nenhuma plataforma genérica/múltiplos provedores integra o tamanho desta Story.

**AC-04 — Recuperação segura.** **Given** perda sintética de fator/segredo e conta pessoal válida; **When** seguir recuperação institucional autorizada em D7; **Then** exigir prova independente estabelecida pela política e nova validação pessoal, revogar material comprometido e registrar autoria/referência/motivo sem revelar segredos; **And** reset pessoal 1.4 não recupera root, não dispensa MFA nem autoriza login root direto. Conta pessoal perdida deve ser recuperada pelo fluxo próprio antes de qualquer elevação.

**AC-05 — Negativas/replay.** **Given** shell sem autoridade, operador inativo/anônimo, política ausente, fator inválido ou solicitação repetida/concorrente; **When** provisionar/rotacionar/recuperar; **Then** negar ou revalidar versão/autoridade sem duplicação/autoatribuição, com eventos próprios sanitizados; **And** alteração de metadados/trilha falha junta, chamadas externas após preparo seguro não imprimem sucesso falso nem habilitam configuração incompleta.

**AC-06 — Prontidão de contingência.** **Given** configuração não homologada/incompleta, TTL/inatividade ausentes ou ensaio de recuperação não demonstrado; **When** consultar prontidão; **Then** indicar contingência desabilitada e pendência específica, sem aceitar valores arbitrários por default; **And** publicar instrução operacional protegida de custódia/rotação/recuperação no local existente apropriado, sem nomes reais/segredos ou novo relatório por Story. Habilitação funcional de elevação só em 1.12.

**D7 — Antes de implementar autenticação de contingência afetada:** Mesa/responsável + segurança/técnico homologar custodiantes/elegíveis, cofre/método de MFA e vínculo pessoal, enrolamento inicial, canal/prova independente de recuperação, limites de tentativas, TTL curto/inatividade, rotação/revogação e revisão posterior. Não inventar números, aprovador adicional universal ou fornecedor. Prova de bootstrap D1 não é automaticamente método de MFA/root. Caso política exija confirmação externa, seu procedimento verificável integra este incremento, sem exigir outra Story futura.

**Verificação futura:** provisionamento/segunda prova sintéticos, MFA real do método escolhido, rotação/recuperação e falhas entre cofre/metadados, concorrência/replay, negativas e logs redigidos; nenhum exercício com credencial real agora. Tamanho: um procedimento vertical de custódia/verificação, não gerenciamento completo de chaves nem implementação de elevação.

**Revisão independente:** `ses_e99970dd8ffesiGJZdlGBw0kWw`; dois MAJOR e dois MINOR corrigidos/revalidados; nenhum residual técnico conhecido. **Condição final:** REVISADA — PRONTA PARA AVALIAÇÃO HUMANA; D7 permanece.

<a id="story-1-12"></a>

### Story 1.12: Elevar e encerrar sessão pessoal root excepcional com autoria integral

**Como** usuário pessoal autorizado para contingência, inclusive sem encargo vigente,
**quero** elevar temporariamente minha própria sessão mediante segunda autenticação forte e encerrá-la com segurança,
**para** recuperar/configurar o serviço com poderes integrais excepcionais sem perder identidade ou perpetuar privilégio.

**Condição:** PROPOSTA EM REVISÃO. **Dependências diretas:** 1.11 (custódia/MFA/política), 1.10 (consulta/trilha/leituras), 1.6 (resolução ordinária temporal); autenticação/invalidação pessoal de 1.2/1.3/1.4 transitivas. Encerramentos de 1.7 ampliam regressões quando disponíveis, não são requisito para o ciclo de elevação. **Referências:** RF-ROOT-001/002/003, RF-ACESSO-005/006/007/008/009/011/012/013, RF-AUDITORIA-001/002/004/007/008; RNF-01–06/13/15, RB-06/17/18, CA-05/23/27 e CA-26/33 parciais, UX-DR4–5/18/45/49/52; Arquitetura §§4/6.1/síntese 1–2, CT-01–06.

**Escopo/tamanho:** um ciclo completo de elevação pessoal, uso e encerramento automático/explícito/admin; `sessoes_elevadas` e vínculo opcional de auditoria só agora, constraints estritamente necessárias. Reutilizar verificação de fatores preparada em 1.11 e contratos de 1.10; não reconstruir cofre, login ou módulos. Poder integral nas operações existentes e contrato testado para Kit/Tesouraria/segredo Zoom; integração real exige consumidores posteriores, sem criá-los aqui.

**Acceptance Criteria:**

**AC-01 — Segunda autenticação pessoal.** **Given** sessão pessoal ativa, conta/pessoa ativa, contingência habilitada e elegibilidade conforme D7; **When** operador confirmar identidade individual, reautenticar com segredo institucional protegido, comprovar MFA pessoal e informar justificativa não vazia; **Then** criar elevação vinculada exclusivamente àquela sessão/usuário e política/versão, com início/expiração/inatividade finitos e trilha; **And** conta sem mandato pode elevar se elegível, mas não há login root direto nem nova identidade/papel permanente. Senha pessoal isolada não é segredo root/MFA.

**AC-02 — Poder excepcional e aviso.** **Given** elevação válida; **When** usar qualquer operação existente; **Then** resolver poder integral excepcional, mostrando aviso persistente de identidade, poderes, motivo/tempo restante e saída explícita; **And** registrar identidade pessoal, modo elevado, justificativa e sessão/correlação em alterações/leituras sensíveis. O poder excepcional não altera matriz ordinária nem permite editar a trilha pelo fluxo comum; constraints de integridade continuam obrigatórias.

**AC-03 — Segregação e projeções.** **Given** mesma conta CG/SG ou sem encargo, com e sem elevação; **When** testar capacidades e projeções institucional/financeira/Kit; **Then** sem elevação manter negações ordinárias e com elevação válida permitir exceção integral identificada; **And** exercitar telas reais existentes e contratos sintéticos de leitura/alteração restrita, sem afirmar endpoints Kit/Tesouraria/Zoom entregues. Consulta integral da auditoria só ocorre durante elevação válida; leitura sensível falha se sua trilha falhar, sem segredo copiado.

**AC-04 — Expiração, inatividade e saída.** **Given** sessão elevada com cache aquecido, inclusive scheduler parado; **When** atingir TTL ou limite de inatividade homologados, sair da elevação, fazer logout ou perder atividade de conta/pessoa; **Then** encerrar capacidade elevada no servidor antes da próxima autorização, registrar razão/instante e invalidar cache/contexto elevado; **And** sair da elevação preserva só o login pessoal válido, logout elimina ambos, nenhum dado elevado volta via cache/navegador/restauração de sessão. Atividade não estende TTL absoluto silenciosamente.

Homologar em D7 os eventos que contam como atividade usando relógio do servidor; polling/requests automáticos não renovam indevidamente inatividade. Testar inativação/reativação da pessoa, não apenas da conta, com duas sessões dormentes: a invalidação persistente pessoal entregue em 1.2 deve impedir ressurgimento de qualquer elevação antiga.

**AC-05 — Revogação administrativa e rotação.** **Given** elevação ativa; **When** autoridade de revogação definida em D7 revogar sessão ou credencial/fator/política de origem for revogado/rotacionado; **Then** negar elevação antiga imediatamente e registrar autoria/razão, sem esperar job; **And** reset de senha/desativação pessoal também a invalidam, reativar/novo login nunca a restauram. Encerramento do mandato ordinário não é elevação permanente nem decisão automática sobre elegibilidade D7; revalidar a política vigente.

**AC-06 — Replay e compartilhamento.** **Given** tentativa anônima, conta inativa, motivo ausente, segredo/MFA inválidos, desafio usado/expirado ou credencial de outra sessão/pessoa; **When** elevar ou reutilizar contexto elevado; **Then** negar com evento sanitizado e limites de tentativas homologados, sem transferir elevação entre sessões; **And** renovar identificador de sessão na elevação/saída conforme proteção contra fixação, consumir desafio de forma única/atômica e não aceitar replay de token/contexto após logout/rotação. Sessão emprestada sem confirmação individual/fatores não eleva; não prometer impedir roubo integral de host/dispositivo comprometido apenas por cookie.

Após regenerar o ID contra fixation, o vínculo persistido de elevação deve corresponder exclusivamente à sessão pessoal válida resultante; nunca resolver elevação apenas por `usuario_id`. O ID anterior não reutiliza nem transfere poder. Na saída, revogar o vínculo elevado anterior e renovar o ID, retornando somente ao login pessoal ainda válido; outra sessão do mesmo usuário não herda elevação. Testar IDs/cookies anteriores após entrada e saída, duas sessões, abas/requisições concorrentes e falha entre rotação, persistência, auditoria e entrega de cookie: nenhuma falha parcial deixa elevação utilizável sem vínculo/trilha válidos, nem regravação tardia ressuscita o anterior. Não pressupor atomicidade distribuída de cookie e banco; a propriedade exigida é falhar sem poder utilizável.

**AC-07 — Corridas e rollback.** **Given** duas tentativas de elevação com mesmo desafio ou uso elevado concorrente com revogação/rotação; **When** disputar no PostgreSQL; **Then** no máximo um consumo válido e nenhuma mutação privilegiada confirma após perder autoridade no ponto de revalidação sob bloqueio; **And** criação de elevação/trilha/vínculo e mutação de negócio/trilha falham atomicamente, sem sessão elevada utilizável após falha. Encerrar/logout deve sempre retirar poder mesmo se registro de segurança de saída falhar, sinalizando falha sanitizada sem restaurar acesso.

Testar leitura sensível concorrente com revogação/rotação/expiração: revalidar antes da entrega e negar se a autoridade tiver sido perdida antes desse ponto. Não prometer recolher informação já entregue nem impedir toda exfiltração em dispositivo comprometido.

**AC-08 — Experiência e recuperação.** **Given** interrupção, erro de fator/cofre ou falha da trilha; **When** solicitar/usar/encerrar elevação; **Then** estado de elevação é inequívoco conforme CT-05, sem sucesso falso, segredo/código em HTML de retorno/log/analytics/localStorage ou consulta privilegiada em cache comum; **And** contingência indisponível não vira login compartilhado/bypass e usa recuperação homologada de 1.11, com instrução protegida de uso excepcional/revisão posterior. Não habilitar root real sem D7 e ensaio dos consumidores aplicáveis.

Ampliar testes de menu de 1.3 AC-06/1.10 AC-07: elevação válida mostra temporariamente ações disponíveis nas operações existentes; saída/TTL/revogação as retiram na próxima renderização, mantendo verificação independente no backend mesmo com UI obsoleta. Revogar somente a elevação preserva login pessoal válido e outra fonte ordinária vigente; desativação/reset pessoal usa a invalidação persistente comum e nunca restaura a elevação após reativação.

**Verificação futura:** autenticação dupla/MFA do método homologado, permissão positiva/negativa de conta sem mandato/CGSG, TTL/inatividade exatos, logout/saída/revogação/reset/rotação, duas sessões/usuários e desafios concorrentes, cache/histórico do navegador, rollback PostgreSQL e logs sem segredo. CA-27/ROOT-002 integrais nos dados Kit/Tesouraria só se concluem com Epics 6/7; contratos sintéticos não substituem essa evidência.

**Revisão independente:** `ses_e99949d26ffeXIop2kLEmGZ1v2`; nenhum BLOCKER/MAJOR, dois MINOR (atividade e corrida de leitura) esclarecidos. **Condição final:** REVISADA — PRONTA PARA AVALIAÇÃO HUMANA; D7 permanece.

### Rastreabilidade do Epic 1 — cobertura planejada, não implementação

Todos os **38 RF de responsabilidade principal** mantêm Epic 1 no mapa macro. Os AC abaixo são asserções de aceite propostas. Regressão reutiliza baseline; “contrato + consumidores” não equivale a teste ponta a ponta entregue. CT-01–06 compõem os AC de cada Story referenciada, sem dispensar seus cenários específicos.

**Estados de cobertura (eixos complementares, não progresso):** reuso da fundação = RF-ACESSO-001/002 e vínculo mínimo de RF-IDENTIDADE-004; o restante deste último é planejado no Epic. Cobertura completa planejada no Epic para o núcleo próprio = RF-ACESSO-003/004/005/006/007/012, RF-AUDITORIA-006, RF-SERVIDORES-001/003/005/006/007/008/012/015/017, RF-AUTOMACOES-008, RF-IDENTIDADE-001/002/004, RF-ROOT-001/003. Esses núcleos não estão implementados por este documento. Cobertura parcial/transversal = RF-ACESSO-008/009/011/013, RF-AUDITORIA-001/002/004/005/007/008/009, RF-SERVIDORES-004 e RF-ROOT-002: exigem consumidores e evidências abaixo. RF-AUDITORIA-003 é somente contrato neste Epic. O ciclo de elevação de RF-ACESSO-012/ROOT-001 é próprio; demonstração de poder nos módulos futuros pertence à parcialidade de RF-ROOT-002, sem fingir módulos entregues.

**Condição humana por RF:** D1→RF-ACESSO-005 e bootstrap; D2/parâmetro 02→RF-SERVIDORES-005 e fronteiras dos RF-SERVIDORES-004/006/007/008/012/017, RF-ACESSO-008/011 e RF-AUTOMACOES-008; D3→RF-ACESSO-003/005/007 e provisionamento de RF-IDENTIDADE-004; D4→RF-SERVIDORES-004/006/015 e substituição; D5→RF-SERVIDORES-006/012 e RF-AUTOMACOES-008 na destituição; D6→RF-ACESSO-008 e delegações de RF-ACESSO-011; D7→RF-ACESSO-005/006/007/009/012 e RF-ROOT-001/002/003 na parcela elevada. Política por classe condiciona dados/evidências de RF-IDENTIDADE-001, RF-AUDITORIA-001–009 e demais produtores. Essa marcação sobrepõe-se a cobertura completa/parcial: “completa planejada” não significa decisão resolvida, aceite ou autorização de implementação.

| RF principal | Stories e critérios de aceite | Alcance / limite |
|---|---|---|
| RF-ACESSO-001 | 1.1 AC-13; 1.3 AC-01/05/06; 1.4 AC-02 | Instrumentação inicial e regressão do login existente, não reconstrução. |
| RF-ACESSO-002 | 1.1 AC-13; 1.3 AC-05; 1.12 AC-04/07 | Instrumentação/regressão logout e término elevado. |
| RF-ACESSO-003 | 1.4 AC-01–05 | Reset administrativo; e-mail só futuramente se configurado. D3. |
| RF-ACESSO-004 | 1.2 AC-01–03/05; 1.3 AC-01 | Pessoa sem conta, sem login por ingresso/Zoom; consumidores propagam. |
| RF-ACESSO-005 | 1.1 AC-01–05; 1.3 AC-01/03/04; 1.6 AC-01/06; 1.12 AC-01 | Bootstrap legítimo, gestão CG/SG e elevação pessoal, não perfil permanente. |
| RF-ACESSO-006 | 1.11 AC-01–06; 1.12 AC-01/04–08 | Custódia/MFA/recuperação/TTL; D7. |
| RF-ACESSO-007 | 1.3 AC-01/03–05; 1.4 AC-01–04; 1.6 AC-02/03; 1.11 AC-03/04; 1.12 AC-01/02/05 | Identidade pessoal, capacidades, autoria da recuperação/rotação. |
| RF-ACESSO-008 | 1.6 AC-02/03; 1.9 AC-01–06; 1.12 AC-02/03 | Origem por encargo, delegação excepcional D6 e root distinto. |
| RF-ACESSO-009 | 1.2 AC-04; 1.3 AC-04; 1.6 AC-02/03/06; 1.10 AC-02/06; 1.12 AC-02/03 | Policies em entradas existentes; consumidores completam por módulo. |
| RF-ACESSO-011 | 1.2 AC-03; 1.3 AC-03; 1.4 AC-02/04; 1.6 AC-02/06; 1.7 AC-01/02/05; 1.8 AC-01/02; 1.9 AC-03–05; 1.12 AC-04–07 | Parcial/transversal: núcleo de vigência/revogação e autenticação distinta de capacidade; cada futuro consumidor prova negação temporal, revogação e corrida nos seus endpoints, não só contrato sintético. |
| RF-ACESSO-012 | 1.12 AC-01–08 | Elevação pessoal integral excepcional e temporária; consumidores completam dados reais. |
| RF-ACESSO-013 | 1.3 AC-04; 1.6 AC-03; 1.9 AC-02; 1.10 AC-02; 1.12 AC-03 | Administração não herda Kit/escrita financeira; integração/configuração efetiva no Epic 4. |
| RF-AUDITORIA-001 | 1.1 AC-01/10/13; 1.3 AC-05; 1.8 AC-01/03–06; 1.10 AC-06 | Todas as mutações entregues e segurança própria; CT-02/03 propagado aos consumidores. |
| RF-AUDITORIA-002 | 1.1 AC-01/13; 1.8 AC-04; 1.10 AC-01/06 | Campos mínimos, módulo/IP/agente quando disponíveis, ator real, redigidos. |
| RF-AUDITORIA-003 | 1.10 AC-04 | Contrato de justificativa oficial; fontes reais oficiais Epics 5/8/devidos consumidores. |
| RF-AUDITORIA-004 | 1.10 AC-01/02/05; 1.12 AC-02/03 | Consulta institucional e integral elevada; financeiro/Kit real nos consumidores 7/6. |
| RF-AUDITORIA-005 | 1.8 AC-01/03–05; 1.10 AC-01/06 | Job de serviço real identificado; webhooks ASAAS/Zoom/Evolution nos seus Epics. |
| RF-AUDITORIA-006 | 1.1 AC-13 + CT-03; 1.10 AC-03; 1.12 AC-02 | Trilha não editável por interface/fluxo comum, inclusive root. |
| RF-AUDITORIA-007 | 1.1 AC-01/09/10/13; 1.7 AC-01/05; 1.8 AC-01/04/05; 1.10 AC-01/06 | Transação/ator/correlação/UTC/redação em mutações e controles, CT-02/03. |
| RF-AUDITORIA-008 | 1.2 AC-04; 1.10 AC-05; 1.11 AC-02; 1.12 AC-01–03/06 | Leitura privada/contrato sensível/elevação; segredo Zoom e PII Kit reais nos consumidores 9/12 e 6. |
| RF-AUDITORIA-009 | 1.2 AC-03; 1.7 AC-01/04/05; 1.10 AC-03/04/07 | Versão/exclusão lógica e contrato de expurgo; execução por dono da PII + E14-recuperacao (backups/restore), com aceite verificável descrito em 1.10 AC-07. Parcial até essa comprovação. |
| RF-SERVIDORES-001 | 1.1 AC-01/10; 1.5 AC-01–05 | Catálogo mínimo inaugural e gestão funcional posterior. |
| RF-SERVIDORES-003 | 1.5 AC-01–04 | Seis cargos preservados, alteração por decisão registrada. |
| RF-SERVIDORES-004 | 1.6 AC-01/03/05/06 | Parcial: titular/pessoa/conta operador; reunião/dia/horário integrada no Epic 2 sem alterar dono do RF. Vedado aceite integral antes dessa integração e seus testes. |
| RF-SERVIDORES-005 | 1.1 AC-06; 1.5 AC-01/02/04; 1.6 AC-01/02 | Datas/padrões seis meses/um ano configuráveis; D2/parâmetro 02. |
| RF-SERVIDORES-006 | 1.6 AC-01/04/05; 1.7 AC-01/03–06; 1.8 AC-03–06 | Quatro estados, substituição com período, VAGO derivado, fato confirmado D5. |
| RF-SERVIDORES-007 | 1.6 AC-05; 1.7 AC-01/04; 1.8 AC-01/02 | Histórico por encargo preservado e instante efetivo separado do registro. |
| RF-SERVIDORES-008 | 1.6 AC-05; 1.7 AC-04; 1.8 AC-01/02 | Vagas derivadas em tempo real, sem pessoa/linha fictícia. |
| RF-SERVIDORES-012 | 1.7 AC-01/03–06; 1.8 AC-01/03–05 | Motivo/autor/instante/histórico em fim/entrega/destituição. |
| RF-SERVIDORES-015 | 1.5 AC-01–03; 1.6 AC-01/03 | Seis cargos e responsabilidades/mandatos distintos. |
| RF-SERVIDORES-017 | 1.6 AC-01–03; 1.7 AC-01/02/05; 1.8 AC-01/02/04 | Concessão na vigência e revogação atômica/por relógio, sem scheduler como autoridade. |
| RF-AUTOMACOES-008 | 1.8 AC-01–07 | Manutenção/regras confirmadas idempotentes e VAGO sem duplicação. |
| RF-IDENTIDADE-001 | 1.2 AC-01–06 | PK estável, pessoa sem login, contatos/localização opcionais protegidos. |
| RF-IDENTIDADE-002 | 1.2 AC-01/02/05; 1.3 AC-01 | Busca obrigatória, semelhantes e confirmação humana, homônimos distintos. |
| RF-IDENTIDADE-004 | 1.1 AC-05/11; 1.3 AC-01–05; 1.6 AC-05/06; 1.8 AC-04 | Vínculo NOT NULL UNIQUE reaproveitado, conta não depende de mandato, ator técnico não é login. |
| RF-ROOT-001 | 1.11 AC-01/02/04–06; 1.12 AC-01/06/07 | Sessão pessoal, segunda autenticação, MFA/motivo/TTL, D7. |
| RF-ROOT-002 | 1.12 AC-02/03/07/08 | Poder integral temporário com autoria; Kit/Tesouraria reais só nos consumidores 6/7. |
| RF-ROOT-003 | 1.12 AC-04–08 | TTL/inatividade/logout/revogação/rotação e replay/sessão pessoal vinculada. |

**Complemento exaustivo dos produtores de RF-AUDITORIA-001/002/007:** as linhas acima apontam AC principais, não excluem os demais produtores abaixo. Em cada linha, CT-02/03 define condição de aceite: ator/contexto/redação/UTC e mutação+trilha atômicas; no evento sem mutação, a regra de falha própria de segurança/leitura. A mesma conferência deve ser repetida nos futuros consumidores sem alterar responsável principal.

| Story produtora | AC que exercitam os três RF de auditoria | Condição/limite do produtor |
|---|---|---|
| 1.1 | AC-01/09/10/13 | Bootstrap e eventos de autenticação; marca/trilha atômicas. |
| 1.2 | AC-01/03–06 | Pessoas, leitura de contato e negações; notas não são expostas. |
| 1.3 | AC-01/03–06 | Conta/atividade, sessão e autenticação. |
| 1.4 | AC-01–04 | Reset/invalidação sem credencial na trilha. |
| 1.5 | AC-02–04 | Configuração institucional versionada. |
| 1.6 | AC-01–04/06 | Designação/alteração/substituição; passagem do relógio não gera escrita por consulta. |
| 1.7 | AC-01–03/05/06 | Encerramento e fundamento mínimo. |
| 1.8 | AC-01/03–06 | Regra/confirmação e ator técnico do job. |
| 1.9 | AC-01–05 | Delegação/revogação/negações. |
| 1.10 | AC-03–07 | Leitura sensível, contratos e regressão de entradas já existentes; não cria produtores futuros. |
| 1.11 | AC-01–05 | Metadados de custódia/rotação/recuperação; sem material secreto. |
| 1.12 | AC-01–07 | Elevação, uso e revogação vinculados ao operador/sessão. |

**Aceite semântico:** para cada RF, validar o enunciado integral do PRD, os AC referidos (incluindo este complemento), resultado permitido/negado e dependência de decisão/consumidor. Os 38 vínculos nominais não encerram os 38 RF. RF-AUDITORIA-003 exige retificação real das fontes oficiais 5/8; 004 exige projeções privadas dos domínios e root; 008 exige leituras Kit/Zoom efetivas nos donos/12; 009 exige guarda/descarte/backup/restore operacional pelos donos e E14-recuperacao; RF-ROOT-002 exige módulos reais 6/7 e demais consumidores. Nenhum fixture ou política aprovada substitui essa evidência.

### Dependências diretas e gates humanos do conjunto

| Story | Predecessoras diretas (sempre menores) | Decisões/checkpoints relevantes |
|---|---|---|
| 1.1 | BASE | D1 prova externa; D2 vigência; guarda da evidência. |
| 1.2 | 1.1 | Política por classe/finalidade antes de coleta real (parâmetro 03). |
| 1.3 | 1.2 | D3 entrega pessoal segura antes do uso real. |
| 1.4 | 1.3 | D3; recuperação pessoal não recupera root. |
| 1.5 | 1.1, 1.2 | D2 representação da duração antes de implementar; parâmetro 02/decisões de catálogo. |
| 1.6 | 1.3, 1.5 | D2/fronteiras e D4 compatibilidade/sobreposição antes da parte afetada. |
| 1.7 | 1.6 | D2/D4; D5 já antes de implementar confirmação institucional/destituição manual, sem fato íntimo inferido. |
| 1.8 | 1.7 | D5 regra/confirmador/referência e guarda; runtime worker/scheduler antes dos efeitos reais. |
| 1.9 | 1.3, 1.6, 1.7 | D6 delegações e D2 temporal antes de implementar parte afetada. |
| 1.10 | 1.3 | Política por classe e aceite operacional de descarte nos donos/E14-recuperacao; 1.7–1.9 apenas ampliam regressões, não bloqueiam consulta. |
| 1.11 | 1.3, 1.4, 1.10 | D7 política completa de contingência antes da implementação afetada. |
| 1.12 | 1.6, 1.10, 1.11 | D7 habilitação e ensaios; consumidores completam poderes nos dados respectivos. |

**Pendências externas comuns:** política de guarda/finalidade/expurgo pessoal por classe (parâmetro 03, inclusive provas/logs/backups); identidade/constraint canônica pessoa–usuário já inequívoca, mas correção editorial do diagrama CREDENCIAL permanece sob confirmação; campo temporal proposto pode exigir alinhamento autorizado dos documentos existentes após D2. Runtime no checkout escolhido e recuperação do escopo do Epic 14 antecedem implementação/primeiro dado real conforme gate próprio, não tornam Stories dependentes de Stories futuras. Não se cria Story de outro Epic nem se exige conclusão integral de 14.

**Fronteiras obrigatórias de integração posterior:** Epic 2 associa designações à agenda/dupla e completa componente reunião/dia/horário de RF-SERVIDORES-004; Epics 5/8 aplicam justificativa oficial; 6/7 completam exclusividade e root nos dados Kit/financeiros; 4/9/12 aplicam auditoria/segredo/atores de integração nas suas entradas. RFs principais não são redistribuídos. Essa propagação não impede executar incrementos deste Epic com fundação e predecessoras; impede apenas declarar atendimento global dos RFs compostos antes dos respectivos consumidores.

### Gate global do Epic 1

**Estado: REVISÃO HUMANA COM PENDÊNCIAS. Histórico anterior à rodada de 23/10/2026:** as 12 propostas foram revisadas individualmente e o conjunto foi auditado pela sessão `ses_e9991ed0fffeINJ55bF2WW2sla`. Essa auditoria encontrou quatro MAJOR e quatro MINOR: fechamento tardio do CLI, instrumentação tardia da autenticação, gate da destituição manual, responsabilidade/aceite do expurgo; dependências artificiais da consulta, busca da baseline, invalidação pessoal e complementos de AC apartados. O marco #6355521600 registra correções/revalidação anteriores. IDs e pareceres ao fim das Stories são proveniência histórica, não prova reproduzível desta rodada, nem garantia de ausência de defeitos. Os três relatórios posteriores motivaram nova conferência e não foram alterados.

**Rodada atual (23/10/2026):** preservadas 12 Stories e 82 AC numerados, sem novas funcionalidades automáticas. Precisados marca/instalação, CLI, autenticação versus capacidade, notas indisponíveis, responsabilidades/duração, fronteiras, autoridade D5, titular D6, menu, D3 e vínculo de rotação root. A matriz distingue núcleo, contrato, transversalidade, reuso e condicionantes humanos. Revisores independentes em contextos isolados: A requisitos/BMAD/produto `ses_e9911b47cffeMHWQsH6UrElwLV` (lente de verificabilidade) e B Laravel/PostgreSQL/segurança `ses_e9911b46cffevW1B60nXlUPELl` (lente de caminhos de borda). Ambos receberam caminhos dos documentos normativos e conteúdo integral revisado, sem edição, e retornaram `[]` (nenhum novo achado). Na segunda conferência do principal, acrescentado aviso/gate de continuidade também à inativação de pessoa em 1.2 AC-03; ambos releram esse ajuste e retornaram novamente `[]`. Essa evidência pertence à rodada atual, não valida retroativamente sessões antigas nem equivale a aprovação humana ou teste de código.

**Cobertura/dependências:** 38/38 RF principais ligados a Story/AC, 236 RF/15 Epics macro preservados. Sem ciclo ou dependência obrigatória de Story futura; root/consulta não ficam bloqueados por automação/delegação. Contratos sintéticos, integrações posteriores e execução operacional do descarte continuam explicitamente parciais até prova nos consumidores. Não declarar RF composto ou Epic implementado/concluído apenas pela elaboração.

**Pendências humanas separadas do aceite do lote:** D1 prova independente do bootstrap (assinatura destacada recomendada, alternativa autenticada delimitada, sem PKI imposta); D2 fronteiras/representação temporal; D3 entrega e confirmação de credenciais pessoais; D4 compatibilidades/sobreposições; D5 confirmação institucional manual e regras automáticas; D6 allowlist/autoridade/ciclo das delegações; D7 custódia/MFA/recuperação/TTL/inatividade/revogação root; política por classe/finalidade/guarda/descarte, inclusive evidência, trilha e backups. Aplicam-se antes da implementação afetada ou uso real conforme cada gate, nunca por suposição. Perda de todos os logins pessoais elegíveis exige encaminhamento extraordinário humano protegido com recuperação operacional; não rearma bootstrap nem cria login root direto. Correções editoriais canônicas permanecem sob confirmação, sem alterar constraint normativa.

**Continuidade administrativa — decisão humana adicional, não veto automático:** homologar antes de usar desativação/encerramento da última autoridade (1.3 AC-03/1.7 AC-06) o encaminhamento quando não houver CG/SG vigente ou nenhum login pessoal elegível, inclusive por expiração natural. Alternativas: sucessão/segunda autoridade legitimamente designada antes da saída; saída permitida com aviso e recuperação extraordinária independente; ou restrição institucional específica à saída manual, com exceção autorizada. Recomenda-se sucessão preventiva e recuperação independente ensaiada, sem prorrogar mandato vencido, impedir renúncia por inferência ou presumir root disponível antes de 1.11/1.12. A decisão precisa dizer quem autoriza, qual prova, como recuperar primeiro uma conta pessoal e depois uma autoridade legítima, qual trilha e ensaio; até lá nenhuma recuperação está entregue. Não rearma bootstrap nem autoriza SQL manual como bypass. Aviso mostra perda concreta e canal/procedimento homologado quando existir; falta desse procedimento é gate de uso real, não Story futura obrigatória.

**Propostas exatas de harmonização arquitetural para revisão humana (Arquitetura não alterada nesta rodada):** §4/6.8: documentar entidade lógica de consumo único/identidade de instalação e proteção efetiva da marca/trilha conforme 1.1 AC-12/CT-03, sem nome físico antecipado; §6.1: registrar exclusão de notas da UI genérica, contrato persistente de invalidação pessoal e titular `usuario`/dados obrigatórios da delegação pós-D6; §6.4: representar responsabilidades versionáveis, duração civil homologada pós-D2 e fatos temporais distintos (previsto/efetivo/registro/processamento) sem coluna imposta; §6.1 sessões elevadas: esclarecer vínculo exclusivo ao ID resultante da rotação. Justificativa: o dicionário é referência proposta e ainda não representa integralmente esses contratos já exigidos ou condicionados. Atualização das seções afetadas no mesmo PR da implementação e antes do aceite de schema/testes é obrigatória; mudanças normativas/parâmetros só após homologação, nunca por inferência desta proposta.

**Parecer documental da rodada: CONCERNS — APROVAÇÃO HUMANA COM RESSALVAS.** Não identificado defeito documental impeditivo residual nas duas revisões, mas D1 (prova/identidade de instalação), D2 e D3 ainda determinam solução/tamanho e, em D3, eventual ampliação de capacidade; D4–D7, continuidade e política por classe mantêm seus gates. O lote pode ser apreciado como proposta condicionada, não como autorização de build/habilitação. Recomendações físicas não sustentadas das auditorias não foram incorporadas como decisões; em particular, não foram impostos endpoint de senha, coluna de sessão/encerramento, nome de tabela de bootstrap, veto à última autoridade ou proibição de acumulação pessoal Tesouraria/Kit. Limite de senha já existe no CLI/login da baseline (72 bytes; mínimo de 12 caracteres no provisionamento), ao contrário de GLM-F010. Consolidação dos 41 achados dos três relatórios é apresentada na resposta desta rodada, não em relatório novo.

**Dimensionamento:** incrementos 1.1/1.2 têm risco alto e médio/alto, respectivamente, conforme auditoria global; seguem coesos para um agente reutilizando a fundação e um só mecanismo de invalidação. Não separar bootstrap/fechamento concorrente/instrumentação em entregas inseguras. D1/D2 e D7 devem delimitar uma solução concreta, não plataforma genérica. 1.8 reutiliza transição de 1.7 e regra fixa homologada, não engine; 1.11 não constrói gestão completa de chaves. Não há estimativa ou promessa de uma sessão antes de resolver decisões que determinam o tamanho efetivo.

**Verificações documentais da rodada:** BMAD instalado 6.12.4, passo 03/template e passo 04 somente como referência; fontes integrais/relatórios e leitura dirigida do código/histórico #35/#33/PR #34; 12 Stories/82 AC, 38 RF principais, 236 RF/15 Epics e revisão 2 preservados; grafo macro de 44 capacidades/83 vínculos e dependências de Stories sem ciclos/futuras obrigatórias; referências RF/Story/AC, Markdown/links locais e whitespace inclusive arquivo não rastreado. A resposta final registra hash/tamanho e comandos efetivamente conferidos. Hashes de Brief/PRD/Arquitetura/UX/CHANGELOG e dos três relatórios preservados; nenhum desses arquivos foi editado nesta rodada. Sem atualização de Issue/PR ou relatório extenso novo. Testes Laravel/PostgreSQL/concorrência/runtime/MCP e homologações UX dos AC são **futuros, não executados**. Somente este `epics.md` foi alterado no projeto; temporários operacionais próprios são removidos após conferência.

Aceite humano não concedido, `approvedStoryIds: []`, passo 03 incompleto, passo 04 não executado e nenhum PASS de readiness. Revisões são documentais intermediárias, **não** revisão do diff completo para PR. Nenhum commit/push/PR, implementação, sprint planning ou operação institucional real. **Parada obrigatória no Epic 1; Epic 2 não iniciado.**

**Marco registrado:** [consolidação, auditoria e verificações na Issue #35](https://github.com/grupoquarentena/site/issues/35#issuecomment-6355521600). O artefato permanece local/não publicado na branch; o comentário não afirma push nem aprovação.
