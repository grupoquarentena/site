---
title: "Arquitetura — Grupo QuarenteNA"
project: Grupo QuarenteNA — Plataforma Web
artifact: architecture
method: "BMAD 6.12.4 — Architecture Spine + detalhamento de dados"
status: "APROVADO PELO RESPONSÁVEL DO PROJETO — fundação mínima integrada; demais domínios propostos"
date: "2026-10-18"
updated: "2026-10-24"
revision: "Issue #35: 15 Epics e 101 Stories aprovados como planejamento condicionado; readiness com gates abertos"
---


# Arquitetura — Grupo QuarenteNA


> O **Brief** e o **PRD** estão **APROVADOS**; o inventário QuarenteNA é evidência histórica, não fonte normativa. Esta arquitetura **foi aprovada pelo responsável do projeto**, com os ajustes de governança e integrações descritos aqui. O dicionário completo abaixo permanece **proposta lógica/física de referência**, não prova de implementação de todos os domínios nem homologação de produção. A fundação Laravel mínima foi incorporada a `grupoquarentena/site` pela Issue #33 / PR #34, integrada à `dev` no commit `4ad7814bca49b89ffbb39f19b60c6c389f68ccd8`; seu subconjunto físico está nas migrations, em `database/schema/pgsql-schema.sql` e em `docs/banco/db.md`.


## 1. Objetivos, restrições e princípios


| Decisão de produto | Diretriz arquitetural |
|---|---|
| Site público e `/admin` | Mesma aplicação Laravel, com fronteira explícita de autorização e dados publicados |
| PHP + PostgreSQL + Docker | **Laravel 13 / PHP 8.5 / PostgreSQL 17** conforme fundação atual do template; upgrades sujeitos a testes |
| BMAD 6.12.4 | Brief/PRD aprovados → UX/Architecture → Specs/Epics → readiness → build |
| Laravel Boost | Documentação version-aware e inspeção do runtime do Laravel durante a implementação |
| Migração integral | Scripts, planilhas, n8n, Apps Script e Looker substituídos por funcionalidades versionadas e testáveis |
| Integrações externas | Zoom, ASAAS, Evolution API e rastreio Correios isolados por adaptadores e filas |
| Um grupo, não SaaS | **Um banco PostgreSQL institucional**, com esquema de domínio; fork parametrizável, sem multi-tenant |
| Anonimato | Menor coleta necessária, isolamento dos dados pessoais e nenhuma vigilância individual de presença Zoom |
| Continuidade | Fonte de verdade única, reconciliação, rollback, backup e restore testado |
| Responsabilidade humana | Permissões derivadas de encargo vigente, auditoria e prestação de contas; decisões coletivas continuam humanas |


### Fronteira de evidência


**Referência de fundação:** PHP `^8.5`, Laravel 13, PostgreSQL 17, PHPUnit e Laravel Boost no ambiente de desenvolvimento. O projeto **não importa** clientes, pivots comerciais, permissões comerciais nem Sanctum sem uma Story de API. A fundação cria `pessoas`, `usuarios` pessoais e tabelas técnicas de sessão/cache/fila; os demais domínios seguem este planejamento e serão concretizados por Story.

**Baseline homologada (#33 / PR #34):** autenticação pessoal, logout, inativação de pessoa/usuário, vínculo `usuarios.pessoa_id NOT NULL UNIQUE`, provisionamento CLI, três migrations e dez tabelas no snapshot (incluindo `migrations`), Docker, lockfile, gates e CI. Os resultados registrados na #33 são evidências históricas, não reexecução na #35. Não há ainda Policies por encargo, root elevado, auditoria de negócio, atas, Kit, Tesouraria, integrações funcionais ou migração. A restauração do snapshot estrutural nos testes não equivale a restore operacional de dados/arquivos. Workers e scheduler precisam de homologação antes dos primeiros jobs reais.

**Checkout de continuidade (#35):** o responsável escolheu `C:\backup\Documentos\repositorios\Sites\quarentena`, equivalente a `/mnt/c/backup/Documentos/repositorios/Sites/quarentena` no WSL, e removeu o checkout Linux anterior. Validar caminhos Docker/MCP, mounts, permissões, arquivos ignorados e preservação dos recursos locais antes da futura implementação; não presumir que a homologação no checkout anterior comprova o runtime neste caminho. Essa verificação não bloqueia a elaboração de `epics.md` nem autoriza recriar o clone removido ou reinstalar dependências.


**Confirmado no rastreador escolhido:** `opastorello/correios-rastreamento` implementa serviço Python/FastAPI que disponibiliza REST e MCP; rotas `POST /rastreamento/objeto`, `/rastreamento/multiplos`, `/health` e histórico interno. Usa scraping e resolução de CAPTCHA, portanto sua disponibilidade depende de mudanças dos Correios e requer monitoramento e alternativa de consulta manual. Não incorporar o Python ao processo PHP.


Referências: [fundação Laravel de inspiração](https://github.com/cirinho/template-laravel-bmad), [documentação Laravel](https://laravel.com/docs) e [README Correios](https://github.com/opastorello/correios-rastreamento/blob/master/README.md). O contrato efetivo é o código adaptado neste repositório, não o estado remoto do template.


## 2. Diagramas de arquitetura


### C4 — Contexto


```mermaid
flowchart LR
  PUB[Visitante] --> SITE[Site público]
  SERV[Servidor do grupo] --> ADM[Admin QuarenteNA]
  SITE --> APP[Laravel]
  ADM --> APP
  APP --> PG[(PostgreSQL)]
  APP <--> EV[Evolution API]
  APP <--> ASA[ASAAS]
  APP <--> Z[Zoom]
  APP <--> C[Correios Rastreamento REST]
  APP --> B[Backup seguro / exportações]
```


### Containers de aplicação


```mermaid
flowchart TB
  EXT[Internet HTTPS] --> REV[Proxy / TLS existente]
  REV --> PHP[Laravel + Apache / container app]
  PHP --> PG[(PostgreSQL persistente)]
  PHP --> Q[(Fila PostgreSQL inicial)]
  WORK[Laravel queue worker] --> Q
  SCHED[Laravel scheduler] --> Q
  WORK --> PG
  SCHED --> PG
  WORK <--> COR[Serviço Python Correios REST - rede privada]
  WORK <--> APIs[ASAAS / Zoom / Evolution API]
  PHP --> ST[Storage documentos / anexos]
  PG --> BACK[pg_dump + restore exercitado]
  COR --> HC[Health-check / logs sem PII]
```


**Escolha inicial recomendada:** PostgreSQL como fila (`QUEUE_CONNECTION=database`) para simplificar o início; Redis permanece **opcional** conforme a carga medida. A fundação adota `QUEUE_CONNECTION=database`; antes de usar jobs reais, ativar e testar workers, idempotência e tratamento de falhas. Em Swarm, ativar réplicas para worker e scheduler; este último deve garantir execução única ou coordenação distribuída.


### Limites por funcionalidade


```mermaid
flowchart TD
  APP[Laravel] --> PUB[Publico]
  APP --> IDENT[Identidade / autorização]
  APP --> REUN[Reunioes e atas]
  APP --> SERV[Serviço]
  APP --> KIT[Kit ingresso]
  APP --> TES[Tesouraria]
  APP --> COM[Comunicação e webhooks]
  APP --> REP[Relatórios]
  APP --> MIG[Migração e auditoria]
  REUN --> CORE[Serviços de domínio / eventos]
  SERV --> CORE
  KIT --> CORE
  TES --> CORE
  COM --> CORE
  CORE --> DB[(PostgreSQL)]
```


## 3. Contratos arquiteturais entre módulos


| Regra transversal | Implementação orientadora | Não permitir |
|---|---|---|
| Fonte de dados | PostgreSQL autoritativo após cutover; escrita via serviços de domínio | Planilhas/Docs editados como banco paralelo |
| Eventos | Evento interno/outbox **persistido na mesma transação** que altera o domínio; entrega externa só pelo worker após commit | Disparar WhatsApp dentro da transação ou inserir outbox somente depois do commit |
| Webhooks | Autenticar/verificar origem, salvar identificador, deduplicar, enfileirar processamento | Duplicar lançamento ASAAS após retry |
| Autorizações | Policies/Gates sobre recurso, finalidade e papel | Autorizar apenas por menu ou atributo de tela |
| Auditoria | Snapshot mínimo redigido de antes/depois, autor, horário, ação, motivo | Salvar tokens, telefones/endereços em logs técnicos genéricos |
| Contagens Zoom | Eventos efêmeros em área restrita; somente totais sugeridos para ata | Vincular evento automaticamente ao cadastro de pessoa |
| Agendamentos | Definidos por ocorrência e fuso do grupo | Offset fixo ou horário duplicado em views |
| Financeiro | Lançamento automático imediato por evento ASAAS válido; manual imediato apenas pelo tesoureiro; edição/exclusão lógica com rastro | Rascunho ou aprovação intermediária como requisito para lançar; alteração física sem histórico |
| Kit | Máquina de estados com histórico e rastreio deduplicado, acesso exclusivo ao Coordenador Kit em mandato vigente | Expor contato/endereço à mesa ou outros servidores por herança de admin |
| Documentos | Google Drive institucional como storage documentário principal; OAuth/conta de serviço e permissões mínimas, versionamento e metadados PostgreSQL | Planilha ou arquivo Google como banco relacional; link público de ata restrita |
| Integrações | Adaptadores com timeout, retry e circuit breaking básico | Tratar HTTP 200 como prova de mensagem recebida pelo destinatário |
| Migração | Snapshot imutável, batch, origem e reconciliação | Apagar dados históricos antes do aceite |


### Estrutura definida para a fundação Laravel no mesmo repositório (preservar convenções do framework)


**Decisão de arquitetura:** fundação Laravel integrada **no próprio repositório `grupoquarentena/site`**, pela branch histórica `chore/issue-33-fundacao-laravel` originada de `dev` e PR #34; manter `main` congelada e preservar o template como referência externa. A árvore abaixo é a estrutura alvo, não inventário de diretórios já implementados: criar cada diretório **quando sua primeira classe/feature existir**; não gerar pastas vazias, arquivos placeholder ou “um documento por componente”.


```text
grupoquarentena/site/
├── AGENTS.md                      # uma regra canônica curta para agentes
├── .ai/guidelines/                # somente convenções do projeto necessárias ao Boost
├── _bmad-output/planning-artifacts/
│   ├── QuarenteNA.md              # AS-IS, mapeamento de dados e corte
│   ├── ProductBrief.md            # visão aprovada
│   ├── PRD.md                     # requisitos aprovados e IDs estáveis
│   ├── UX-Design.md               # DESIGN + EXPERIENCE aprovados num só documento
│   ├── Arquitetura.md             # decisões + dicionário lógico/relacional
│   ├── epics.md                   # estado e catálogo global do backlog BMAD
│   └── epics/                     # arquivos epic-NN.md, criados por Epic detalhado
├── app/
│   ├── Funcionalidades/           # domínio (regras, consultas, eventos e serviços)
│   │   ├── Identidade/            # usuários, mandatos, permissões, root excepcional
│   │   ├── Reunioes/              # agenda, ocorrências, moderadores, atas regulares
│   │   ├── Servico/               # mesa, designações, atas e consciência coletiva
│   │   ├── KitIngresso/           # ingressos, contato, remessa, Correios
│   │   ├── Tesouraria/            # lançamentos, ASAAS, estorno e saldo
│   │   ├── Comunicacao/           # Evolution, comandos, templates e jobs
│   │   ├── Relatorios/            # agregações seguras e projeções públicas
│   │   └── Integracoes/           # adaptadores Zoom, Correios, Drive e config
│   ├── Http/Controllers/          # entrada HTTP; autenticação inicial sem API própria
│   ├── Models/                    # Pessoa/Usuario no núcleo; demais modelos por Story
│   ├── Policies/                  # autorização em toda ação e registro
│   ├── Jobs/                      # filas (atividades assíncronas quando necessárias)
│   └── Console/Commands/          # importação, verificação e rotinas operacionais
├── bootstrap/                      # manter o esqueleto original Laravel
├── config/                         # drivers/storage/integrations não secretos
├── database/
│   ├── migrations/                 # fonte executável única do schema; nunca prune automático
│   ├── seeders/                    # catálogos e encargos sem contas/PII reais
│   └── schema/pgsql-schema.sql     # snapshot GERADO do PostgreSQL real
├── resources/views/               # Blade público, painel, e-mails/PDFs
├── routes/                        # web.php, api.php, console.php conforme necessário
├── public/                        # assets versionados após homologação da logo
├── tests/Unit/                    # regras puras
├── tests/Feature/                 # fluxos HTTP, autorização e webhooks
├── docs/banco/db.md               # catálogo GERADO do PostgreSQL real
├── docker/                         # Compose/Swarm herdados e adaptados
└── scripts/                        # comandos existentes do template (reutilizar)
```


**Não criar** um diretório próprio `Integracoes/` *e* uma segunda hierarquia duplicada de conectores em `app/Http`; a lógica externa vive num só lugar e os controllers apenas recebem/validam/encaminham. Não criar `app/Events/Listeners/Repositories/Services/` vazios por padrão. `app/Funcionalidades/` é a propriedade dos contratos de domínio; Eloquent permanece no `app/Models/` do framework, evitando duplicidade de modelos. Documentação de API só é adicionada se uma API própria, estável e publicada realmente precisar de contrato distinto.


**Regras de atualização do projeto (`grupoquarentena/site`):**


| Mudança | Atualizar no mesmo PR/commit, sem arquivo histórico paralelo |
|---|---|
| Requisito/escopo | `PRD.md` e, somente se afetados, `ProductBrief.md`, UX e arquitetura |
| Fluxo/tela | `UX-Design.md`, testes e requisito correspondente no `PRD.md` |
| Decisão de domínio/segurança | `Arquitetura.md`, regras em `AGENTS.md` e testes |
| Banco estrutural | Nova migration + testes PG + `./scripts/banco-atualizar.sh` → `database/schema/pgsql-schema.sql` + `docs/banco/db.md` + **Arquitetura.md** |
| Fonte importada/automações | `QuarenteNA.md` (mapa origem/destino), PRD (se alterar comportamento), testes |
| Implementação de Story | Código, testes e **Story ativa** de BMAD; não reescrever stories históricas |


**Artefatos BMAD mínimos:** os documentos acima preservam seus papéis de fontes aprovadas e inventário histórico. A #35 organiza o backlog como **índice global em `_bmad-output/planning-artifacts/epics.md`** (inventário, mapa e 15 Epics macro) e **detalhamento autoritativo por Epic em `_bmad-output/planning-artifacts/epics/epic-NN.md`** (Stories, AC, matriz RF → Story → AC e gates). Não duplicar Stories no índice. Os arquivos individuais de Story para implementação continuam **just in time**, somente quando o fluxo de implementação exigir. O BMAD 6.12.4 passo 03 normalmente escreve no monolítico; a convenção exige orientação/customização versionada e validação dos consumidores antes da próxima geração. Specs individuais só quando o BMAD exigir contrato que não possa ser expresso pela Story. `sprint-status.yaml` apenas após aprovação do backlog e gate de readiness permitido. É proibido criar versões concorrentes `-atualizado`, `-detalhado` ou múltiplos relatórios repetindo decisões. Não descartar artefatos ativos necessários à continuidade/review.


## 4. Adaptação correta do template Laravel BMAD


| Recurso do template `main` | Estado inspecionado | Tratamento no QuarenteNA |
|---|---|---|
| `usuarios` | Conta pessoal do QuarenteNA, sem `papel` comercial | **Fundação**: migration própria com `pessoa_id FK NOT NULL UNIQUE`, e-mail, senha com hash, `ativo` e timestampsTz, sem perfis comerciais; credenciais e permissões submetidas a Policies do QuarenteNA |
| `password_reset_tokens` | Token de recuperação por email | **Reutilizar**, controlar expiração e mensagens sem vazamento |
| `clientes` | Exemplo comercial do template, não adotado | **Não importar**: não existe no schema do QuarenteNA |
| `cliente_usuario` | Exemplo comercial não adotado | **Não importar** |
| `personal_access_tokens` | Recurso futuro opcional para APIs humanas | **Não incluir na fundação mínima**; adicionar Sanctum com escopo/validade por Story de API, sem usuários técnicos fictícios |
| `jobs`, `job_batches`, `failed_jobs` | Tabelas do driver de fila | **Reutilizar** para jobs; não armazenar payload com PII desnecessária |
| `database/schema/pgsql-schema.sql` | Snapshot real criado por script do template | **Gerar no projeto executável**, jamais substituir pela proposta deste documento |
| `docs/banco/db.md` | Catálogo do banco regenerado do PG | **Gerar após migrations** e versionar junto com snapshot |
| `./scripts/banco-atualizar.sh` | Concilia dump PG com documentação | Executar após cada alteração estrutural testada |
| `./scripts/testar.sh --postgres` | Gate PostgreSQL | Obrigatório para migrations/consultas específicas do PG e antes de release |
| Docker Compose / Swarm | app Apache, PG, profiles de filas e scheduler | Usar volumes e segredos; sem publicar porta PG externa |
| Laravel Boost | Dependência de desenvolvimento; MCP via script | Instalar/ativar via scripts do template e consultar documentação na implementação |
| BMAD | Já instalado no ambiente do projeto | **Preservar** BMAD 6.12.4, sem executar instalador; versionar apenas artefatos `_bmad-output/` |


### Adaptação de identidade e permissões


A fundação **não importa** papéis comerciais `super_admin`, `admin` ou `cliente`; eles não são papéis de negócio do QuarenteNA. `pessoas` contém a identidade operacional, com ou sem conta; `usuarios` contém somente logins humanos e requer `pessoa_id NOT NULL UNIQUE`. Todos os servidores que operam o sistema usam seu login pessoal. Jobs, ASAAS, Zoom, Evolution e outras integrações são **atores técnicos em auditoria**, sem login humano artificial. Um usuário logado pode executar **segunda autenticação institucional root** para elevar apenas sua sessão pessoal e obter todos os poderes sem nova identidade anônima. **A fonte de permissão efetiva é a designação de encargo ativa**, complementada por uma exceção autorizada e auditada: o sistema pode materializar flags para performance, mas Policies devem sempre invalidá-las quando o mandato expirar ou for revogado.


- **Coordenador Geral e Secretário Geral:** gestão técnica/administrativa, usuários, escalas, modelos documentais, integrações e auditoria; **sem permissão de edição financeira nem acesso operacional ao Kit Ingresso**.
- **Tesoureiro:** único encargo de edição dos lançamentos e relatórios financeiros privados; eventos ASAAS processados sob identidade de sistema, não sob sessão humana.
- **Coordenador Kit Ingresso:** único encargo com acesso ao módulo e contatos/endereços/remessas.
- **RSG e RSG Suplente:** acesso às atividades de representação, sem privilégios de gestão financeira/kit.
- **Moderadores do dia:** um único tipo de serviço de moderação com duas pessoas escaladas por ocorrência; papéis de voz (coordenador do dia) e chat/ata (secretário do dia) são substituíveis, sem confusão com cargos da Mesa.
- **Root institucional:** elevação excepcional de sessão de usuário pessoal, com acesso integral inclusive aos módulos restritos, nunca login direto. MFA, segredo em cofre, justificativa, identidade do operador vinculada, logs e revisão posterior. Root não remove o requisito de um login pessoal do servidor para operações cotidianas.


**Acesso:** na gravação da designação, concessão automática; no fim da vigência, policy nega **mesmo se o scheduler estiver atrasado**, e o job remove cache/flags/tokens; entrega ou destituição revoga instantaneamente, termina sessões privilegiadas quando necessário e registra motivo, responsável e instante. Evitar dar permissão perpetuamente em `permissoes_usuario` se derivada de `designacoes_servico`.


**Migrações iniciais:** as migrations de `pessoas`, `usuarios` e runtime foram criadas e homologadas na #33, antes da integração pelo PR #34. Reutilizar esse subconjunto; evoluções exigem novas migrations, mantendo o histórico aplicado (não reescrever as existentes), como exigem `AGENTS.md` e o README da fundação.

**Bootstrap inaugural aprovado na #35:** somente a primeira designação institucional de CG ou SG pode usar CLI excepcional. A conta pessoal do operador e a conta beneficiária precisam existir, estar ativas e vinculadas a pessoas válidas; reautenticar explicitamente no CLI com senha interativa oculta, reaproveitando o mecanismo de verificação de credenciais, sem presumir sessão web ou autorização pelo shell. Comprovar aprovação institucional externa independente, vinculada à operação e com aprovador/referência rastreáveis; referência digitada ou evidência autodeclarada não bastam. Operador pode ser beneficiário somente com autorização independente comprovada. Meio técnico dessa prova é proposta sujeita ao aceite específico da Story, não um serviço de aprovação já existente.

Na execução inaugural, persistir **designação + auditoria + consumo irreversível da marca de inicialização** na mesma transação PostgreSQL. Exigir exclusão mútua efetiva no banco, unicidade da marca e proteção contra escrita concorrente/replay, com revalidação sob bloqueio antes de conceder. A marca é independente da vigência/status do mandato e não pode ser removida/rearmada pelo fluxo administrativo comum; ausência inesperada, inconsistência ou evidência inválida falham fechadas, sem reconstruí-la automaticamente no comando. Inicialização/migrations estruturais não designam pessoas nem concedem privilégios. Operações extraordinárias de recuperação/restore não autorizam reabrir o bootstrap automaticamente.

Não criar root compartilhado, papel comercial, ID humano privilegiado fixo ou bypass permanente. Registrar operador, beneficiário, encargo CG/SG, vigência, motivo, aprovador/referência e correlação, sem segredos. A concessão decorre exclusivamente da designação vigente e das Policies que forem implementadas/testadas; executar o comando não eleva a sessão nem substitui as Policies futuras. Após o bootstrap, designações usam o fluxo institucional ordinário. A aprovação deste contrato não autoriza execução em produção ou uso de dados reais. Materializar somente as entidades/constraints do procedimento quando sua Story for aprovada para implementação; a marca técnica não implica gerar o dicionário completo nem alterar o snapshot agora.


## 5. Estratégia de PostgreSQL e modelagem


### Uma instância lógica de dados; vários domínios


Usar **um banco PostgreSQL** por ambiente (`quarentena_dev`, `quarentena_prod` são exemplos de identificação operacional, não nomes obrigatórios), com tabelas separadas por função **dentro do schema `public` por padrão**, permitindo migrations convencionais do Laravel. Separar ambientes e credenciais, nunca criar bancos diferentes por módulo sem necessidade concreta.


```mermaid
flowchart LR
  A[APP Laravel] --> B[(PostgreSQL / public)]
  B --> I[Identidade e acesso]
  B --> R[Reuniões e atas]
  B --> S[Serviço]
  B --> K[Kit e remessas]
  B --> T[Tesouraria]
  B --> C[Mensagens e integrações]
  B --> M[Migração e auditoria]
```


### Convenções transversais propostas


| Tema | Convenção recomendada | Verificação |
|---|---|---|
| Chaves | `id bigint GENERATED ...` via `$table->id()`; UUID de evento externo como string quando couber | FK valida; sem IDs de planilha como PK |
| Tempo | `timestamptz` para eventos; timezone `America/Bahia` em aplicação, com instantes persistidos corretamente | Comparações em DST/timezones e bordas |
| Data de ocorrência | `date` + horário local, e ocorrência com início/fim efetivos | Calendário sem ambiguidades |
| Dinheiro | `numeric(14,2)` ou precisão adequada ao domínio | Proibir float para saldo/valor |
| Dados externos | `external_id`, `provider`, `source_hash` e idempotency key únicos por origem | Replays não duplicam |
| Status | `varchar` validado por constraint/cadastro e enum lógico no domínio | Não salvar texto livre arbitrário |
| Auditoria | Antes/depois redigido, usuário, data, request/correlation id, motivo | Testes de edição/remoção |
| PII | Criptografia de aplicação em campos sensíveis, mascaramento e acesso mínimo | Busca por hash determinístico separado quando estritamente necessário |
| Eliminação | Retenção definida por classe de dado, soft delete com cuidado; preservação financeira/auditável segundo política | Nenhum apagamento destrutivo sem autorização legal |
| Imports | Snapshot de origem com hash, linha e lote | Reexecutável sem duplicação |
| Comentários | Comentários PT-BR em tabelas/colunas próprias | Snapshot + `docs/banco/db.md` sincronizados |
| Integridade | NOT NULL, UNIQUE, FK, CHECK e índices necessários, além da validação Laravel | Tests `--postgres` obrigatórios |


**Classificação do esquema:** `EXISTENTE NO TEMPLATE` (migrations comprovadas) versus `PROPOSTO PELO QUARENTENA` (entidades de domínio). O dicionário a seguir especifica tabelas candidatas e deve evoluir para migrations com testes antes de ser declarado físico/canônico.




## 6. Dicionário de dados — tabelas de domínio propostas


**Leitura:** nomes e tipos são **propostas arquiteturais**; o modelo físico aprovado será o gerado por migrations e documentado pelo banco real. Todo registro mutável recebe `id` e timestamps (`created_at`/`updated_at`) salvo exceção documentada. `FK` aponta uma relação; `?` significa nullable; `cipher` significa valor criptografado no domínio da aplicação, com gestão de chaves fora do banco. Valores de status precisam de CHECK/tabela de domínio/testes, não de texto livre.


### Panorama: quantas tabelas por domínio?


| Domínio | Tabelas propostas | Esforço relativo de modelagem (não é prazo) |
|---|---:|---|
| Identidade e permissões | 4 | Baixo |
| Agenda e ocorrências | 4 | Baixo |
| Atas e indicadores | 10 | Alto |
| Servidores e reuniões de serviço | 11 | Alto |
| Kit ingresso e Correios | 6 | Médio |
| Tesouraria e ASAAS | 5 | Médio |
| Comunicação e eventos de integração | 16 | Alto |
| Migração, governança e atendimento | 7 | Médio |
| **Total de entidades candidatas** | **63** | Não equivale a 62 migrations obrigatórias; concretizar por Story, excluídas as tabelas do template |


```mermaid
pie showData
  title Entidades candidatas por dominio - 63
  "Identidade e permissões" : 4
  "Agenda e ocorrências" : 4
  "Atas e indicadores" : 10
  "Servidores e reuniões de serviço" : 11
  "Kit ingresso e Correios" : 6
  "Tesouraria e ASAAS" : 5
  "Comunicação e eventos de integração" : 16
  "Migração, governança e atendimento" : 7
```


### 6.1. Identidade e permissões


**Matriz efetiva de autorização aprovada** (leitura de indicadores agregados públicos é uma projeção separada; acesso à auditoria não permite editar o dado de negócio):


| Papel/encargo ativo | Usuários e permissões | Ata regular do dia | Ata de serviço e templates | Tesouraria: editar | Kit/PII: acessar | Integrações/configurações |
|---|---|---|---|---|---|---|
| Coordenador Geral | Sim | **Editar enviada e apoiar rascunho** | Gestão administrativa | **Não** | **Não** | Sim |
| Secretário Geral | Sim | **Editar enviada e apoiar rascunho** | **Editar template e atas** | **Não** | **Não** | Sim |
| Tesoureiro | Não por padrão | Se escalado | Consulta autorizada | **Sim, exclusivo** | **Não** | Não |
| RSG / Suplente | Não por padrão | Se escalado | Consulta/representação | **Não** | **Não** | Não |
| Coordenador Kit Ingresso | Não por padrão | Se escalado | Consulta autorizada | **Não** | **Sim, exclusivo** | Não |
| Moderador escalado | Não | **Criar/editar rascunho; depois do envio só se for remetente** | Não por padrão | Não | Não | Não |
| Root por elevação de sessão pessoal | Poder integral excepcional com usuário identificável | Sim enquanto elevado | Sim enquanto elevado | **Sim, excepcional auditado** | **Sim, excepcional auditado** | Sim, excepcional |


A matriz diferencia **edição de ata regular já enviada** (remetente do envio, Coordenador Geral, Secretário Geral ou root pessoalmente elevado) de **preenchimento de ata em rascunho** (moderadores escalados), sem autorização genérica a toda a Mesa. A matriz é implementada por Laravel Gates/Policies com testes negativos. Sessões root registram também quem retirou/operou a credencial; não existe login root direto: a elevação requer login pessoal prévio, segunda autenticação, MFA, justificativa, TTL e correlação na auditoria.




| Tabela | Finalidade | Relações principais | Privacidade | Fonte da necessidade |
|---|---|---|---|---|
| `pessoas` | Cadastro-base operacional, **podendo existir sem login** | `usuarios.pessoa_id NOT NULL UNIQUE` quando houver conta humana; `designacoes_servico.pessoa_id NOT NULL` | restrito | Cadastros e relações de servidores |
| `permissoes` | Catálogo explícito de capacidades | permissoes_usuario.permissao_id | restrito | Permissões do PRD |
| `permissoes_usuario` | Delegação de permissão extra | usuarios, permissoes | restrito | Permissões extras e serviço |
| `sessoes_elevadas` | Elevação root temporária de sessão pessoal | usuarios; auditorias | muito restrito | RF-ROOT-001…003 |


**Campos e tipos de referência:**


| Tabela | Campo / tipo sugerido | Significado / regra |
|---|---|---|
| `pessoas` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `nome_exibicao text` | Primeiro nome/apelido, preservando grafia exibida |
| ↳ | `nome_busca varchar?` | Chave NFKC + trim/colapso de espaços + casefold para **sugestões**, não identidade única |
| ↳ | `ativo bool` | Estado |
| ↳ | `notas_restritas text?` | Notas com acesso mínimo |
| ↳ | `telefone_cipher text?` | WhatsApp/telefone opcional para avisos privados de servidores, separado do contato do Kit |
| ↳ | `telefone_hash char(64)?` | Blind index HMAC de telefone normalizado, chave separada da APP_KEY |
| ↳ | `email_cipher text?` | Contato opcional privado |
| ↳ | `estado char(2)?` | Localização geral, quando informada |
| ↳ | `pais char(2)?` | Código ISO, quando informado |
| `permissoes` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `chave varchar UNIQUE` | Ex.: tesouraria.editar |
| ↳ | `descricao text` | Finalidade |
| ↳ | `dominio varchar` | Área de negócio |
| `permissoes_usuario` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `usuario_id FK` | Titular |
| ↳ | `permissao_id FK` | Capacidade |
| ↳ | `concedida_por FK?` | Autor |
| ↳ | `validade timestamptz?` | Fim |
| ↳ | `motivo text?` | Justificativa |
| `sessoes_elevadas` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `usuario_id FK NOT NULL` | Usuário **pessoal**, autenticado antes de elevar; sem login direto root |
| ↳ | `sessao_id varchar UNIQUE` | Sessão pessoal vinculada ao evento de elevação; não transferível |
| ↳ | `verificado_em timestamptz` | MFA, reautenticação e segredo institucional comprovados |
| ↳ | `mfa_metodo varchar?` | Método utilizado, sem gravar código/segredo |
| ↳ | `aberto_em timestamptz` | Início |
| ↳ | `expira_em timestamptz` | Fim |
| ↳ | `justificativa text` | Motivo |
| ↳ | `encerrado_em timestamptz?` | Logout, expiração, inatividade ou revogação administrativa |
| ↳ | `aprovado_por FK?` | Responsável adicional quando exigido por política |


### 6.2. Agenda e ocorrências


| Tabela | Finalidade | Relações principais | Privacidade | Fonte da necessidade |
|---|---|---|---|---|
| `tipos_reuniao` | Tipos e formatos aprovados | reunioes.tipo_padrao_id | público | Agenda e tipos |
| `reunioes` | Definições estáveis de encontros | tipos_reuniao; horarios_reuniao | misto | Agenda atual |
| `horarios_reuniao` | Regra semanal recorrente | reunioes, ocorrencias_reuniao | público | Form/brief agenda |
| `ocorrencias_reuniao` | Ocorrência específica com instante inequívoco | reunioes; atas; eventos_zoom | público parcial | Data + horário da ata |


**Campos e tipos de referência:**


| Tabela | Campo / tipo sugerido | Significado / regra |
|---|---|---|
| `tipos_reuniao` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `nome varchar UNIQUE` | Abertas/fechadas etc. |
| ↳ | `descricao_publica text` | Elegibilidade |
| ↳ | `ativo bool` | Visibilidade |
| `reunioes` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `titulo varchar` | Nome breve |
| ↳ | `tipo_padrao_id FK` | Tipo |
| ↳ | `zoom_url text` | Link validado HTTPS |
| ↳ | `zoom_meeting_id varchar?` | ID |
| ↳ | `zoom_senha_cipher text?` | Senha protegida |
| `horarios_reuniao` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `reuniao_id FK` | Reunião |
| ↳ | `dia_semana smallint` | 0…6 |
| ↳ | `hora_inicio time` | Hora local |
| ↳ | `hora_fim time?` | Hora local |
| ↳ | `ativo bool` | Ativo |
| `ocorrencias_reuniao` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `reuniao_id FK` | Modelo |
| ↳ | `inicio_em timestamptz` | Início efetivo |
| ↳ | `fim_em timestamptz?` | Fim |
| ↳ | `status varchar` | Prevista/ocorrida/cancelada |
| ↳ | `horario_id FK?` | Regra origem |


### 6.3. Atas e indicadores


| Tabela | Finalidade | Relações principais | Privacidade | Fonte da necessidade |
|---|---|---|---|---|
| `atas` | Registro oficial de uma ocorrência | ocorrencias_reuniao, pessoas, revisoes_ata | restrito; agregados públicos | Form Responses 1 |
| `revisoes_ata` | Histórico imutável de retificações | atas, usuarios | restrito | Correções do formulário |
| `origens_visita` | Dimensão de origem de visitantes | visitas.origem_id | público se agregado | Visitas Looker |
| `visitas` | Contagens de visitantes por ata e origem | atas, origens_visita | restrito; agregado público | Responses/Looker |
| `ingressos` | Registro mínimo do ingresso na reunião | atas; kits_ingresso | sensível | Respostas I/Z, Kit |
| `tipos_conquista` | Catálogo de conquistas/tempo limpo | conquistas.tipo_id | público se agregado | Indicadores de conquistas |
| `conquistas` | Conquistas registradas em reunião | atas, tipos_conquista | restrito; agregado público | Form / Looker Conquistas |
| `tematicas` | Biblioteca de assuntos de reuniões | atas_tematicas.tematica_id | público | Looker Temáticas |
| `atas_tematicas` | Associação de ata e temática | atas, tematicas | restrito | Temáticas e partilhador |
| `resumos_ata` | Texto de resumo e estado de divulgação | atas, mensagens_saida | restrito/publicável após revisão | Script ata-resumo |


**Campos e tipos de referência:**


| Tabela | Campo / tipo sugerido | Significado / regra |
|---|---|---|
| `atas` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `ocorrencia_id FK UNIQUE` | Uma ata oficial lógica por ocorrência |
| ↳ | `secretario_id FK?` | Responsável pela secretaria da ocorrência (não determina autoria do envio) |
| ↳ | `enviada_por_usuario_id FK?` | **Usuário pessoal que executou envio oficial**; `NULL` enquanto rascunho/legado sem identificação; obrigatório para novos envios, sustenta a Policy de retificação |
| ↳ | `coordenador_id FK?` | Responsável |
| ↳ | `membros_total integer? CHECK >= 0` | Contagem oficial não negativa; nulo apenas enquanto rascunho |
| ↳ | `partilhas_total integer? CHECK >= 0` | Contagem oficial não negativa; nulo apenas enquanto rascunho |
| ↳ | `enviada_em timestamptz?` | Data/hora do envio oficial; preenchida na transição para enviada |
| ↳ | `visitas_total integer? CHECK >= 0` | Total declarado da ata; não somar novamente sobre `visitas` |
| ↳ | `ingressos_total integer? CHECK >= 0` | Total declarado mesmo se nomes não forem informados |
| ↳ | `conquistas_total integer? CHECK >= 0` | Total declarado mesmo sem nomes; origem histórica preservada |
| ↳ | `setima_tradicao_valor numeric(14,2)? CHECK >= 0` | Informação declarativa da reunião, **não gera movimentação contábil** |
| ↳ | `setima_tradicao_atualizada_em date?` | Data de referência histórica/operacional |
| ↳ | `despesas_valor numeric(14,2)? CHECK >= 0` | Despesa informativa da ata; não lança automaticamente em Tesouraria |
| ↳ | `despesas_descricao text?` | Discriminação histórica de despesas |
| ↳ | `eleicoes text?` | Texto de eleição de encargo reportado |
| ↳ | `informacoes_adicionais text?` | Conteúdo complementar institucional |
| ↳ | `observacoes text?` | Observações do preenchimento |
| ↳ | `status varchar` | Rascunho/enviada/revisada/corrigida/anulada |
| `revisoes_ata` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `ata_id FK` | Registro |
| ↳ | `revisao integer` | Sequência; `UNIQUE(ata_id, revisao)` |
| ↳ | `alterado_por FK` | Usuário |
| ↳ | `antes jsonb` | Valores redigidos |
| ↳ | `depois jsonb` | Valores redigidos |
| ↳ | `motivo text` | Justificativa |
| `origens_visita` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `nome varchar UNIQUE` | Origem normalizada |
| ↳ | `ativo bool` | Disponível |
| `visitas` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `ata_id FK` | Ata |
| ↳ | `origem_id FK?` | Origem |
| ↳ | `quantidade integer CHECK >= 0` | Total de visitas não negativo; uma linha por origem/ata conforme regra |
| ↳ | `observacao text?` | Contexto aprovado |
| `ingressos` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `ata_id FK` | Ata de origem |
| ↳ | `pessoa_id FK?` | Referência opcional a `pessoas`, somente após confirmação humana |
| ↳ | `nome_exibicao varchar?` | Primeiro nome/apelido mínimo informado para divulgação |
| ↳ | `nome_historico varchar?` | Nome original da ata/snapshot; nunca sobrescrito por atualização cadastral |
| ↳ | `oculto_publicamente bool DEFAULT false` | Opt-out após decisão humana; só aplicável **após liberação de publicação** do registro |
| ↳ | `publicacao_status varchar` | `em_triagem` (importação ainda não publicável) ou `liberado` (registros novos oficiais ou histórico saneado); `CHECK IN`, sem publicar `em_triagem` |
| ↳ | `retificado_em timestamptz?` | Última retificação editorial |
| ↳ | `confirmado_em timestamptz?` | Data |
| ↳ | `status varchar` | Estado |
| ↳ | `origem_legada varchar?` | Rastreio migração |
| `tipos_conquista` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `nome varchar UNIQUE` | Rótulo |
| ↳ | `ordem integer` | Ordenação |
| ↳ | `ativo bool` | Disponível |
| `conquistas` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `ata_id FK` | Ata |
| ↳ | `tipo_id FK?` | Tipo |
| ↳ | `quantidade integer CHECK >= 0` | Conquistas não negativas |
| ↳ | `pessoa_id FK?` | Vínculo opcional à pessoa, sem login e sem alterar texto original |
| ↳ | `referencia_minima varchar?` | Texto público mínimo sujeito a opt-out e revisão de histórico |
| ↳ | `nome_historico varchar?` | Forma textual original da ficha/ata |
| ↳ | `oculto_publicamente bool DEFAULT false` | Opt-out após decisão humana, não publica se histórico estiver em triagem |
| ↳ | `publicacao_status varchar` | `em_triagem` ou `liberado`; registros novos válidos liberados, legado somente após reconciliação humana |
| `tematicas` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `nome varchar UNIQUE` | Tema |
| ↳ | `descricao text?` | Descrição pública |
| ↳ | `ativo bool` | Status |
| `atas_tematicas` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `ata_id FK` | Ata |
| ↳ | `tematica_id FK` | Tema |
| ↳ | `partilhador_pessoa_id FK?` | Referência opcional `pessoas.id`; não requer login |
| ↳ | `partilhador_exibicao varchar?` | Nome/apelido capturado na ata, separado do perfil cadastral |
| `resumos_ata` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `ata_id FK` | Ata |
| ↳ | `texto_revisado text` | Corpo publicado |
| ↳ | `status varchar` | Rascunho/revisado/enviado |
| ↳ | `validado_por FK?` | Responsável |


### 6.4. Servidores e reuniões de serviço

```mermaid
stateDiagram-v2
  [*] --> Ativo: designação de titular com vigência
  Ativo --> Concluido: fim do período
  Ativo --> Entregue: renúncia do encargo
  Ativo --> Destituido: deliberação ou hipótese institucional confirmada
```

**VAGO não aparece na máquina de estados**: é resultado de consulta por encargo/dia/horário sem designação `ativo` vigente. A decisão de destituição por faltas ou recaída nunca é inferida automaticamente por presença Zoom, e as evidências íntimas não integram relatórios ou tabelas de serviço publicáveis.




| Tabela | Finalidade | Relações principais | Privacidade | Fonte da necessidade |
|---|---|---|---|---|
| `encargos` | Funções de serviço configuráveis | designacoes_servico.encargo_id | público condicionado | Serviço Grupo Quarentena |
| `designacoes_servico` | Mandato e titular de encargo | pessoas, encargos, reunioes | restrito; publicação parcial | Serviço/terminio |
| `escalas_reuniao` | Serviço previsto por ocorrência | ocorrencias_reuniao, designacoes_servico | restrito | Relação de servidores |
| `reunioes_servico` | Reuniões de serviço agendadas | atas_servico, presencas_servico | restrito | Atas de Serviço |
| `atas_servico` | Ata de reunião de serviço | reunioes_servico, revisoes_ata_servico, arquivos | restrito | Google Docs/PDF e Sheets |
| `revisoes_ata_servico` | Snapshots imutáveis, correções e anulações de atas finalizadas | atas_servico, usuarios, arquivos | muito restrito | RF-ATASERVICO-007 e RB-04 |
| `templates_ata_servico` | Versões editáveis do modelo de ata mantidas pela Secretaria Geral | atas_servico, usuarios | restrito | RF-ATASERVICO-010 |
| `presencas_servico` | Presenças/ausências por reunião de serviço | reunioes_servico, pessoas | restrito | Atas de Serviço |
| `decisoes_coletivas` | Deliberações e regras vigentes | reunioes_servico, documentos | misto | Consciências Coletivas |
| `documentos` | Registro de documentos operacionais | arquivos, decisoes_coletivas | misto | VAGO, Relação, PDFs |
| `arquivos` | Metadados do armazenamento privado | atas_servico, documentos, usuarios | restrito | PDFs e anexos históricos |


**Campos e tipos de referência:**


| Tabela | Campo / tipo sugerido | Significado / regra |
|---|---|---|
| `encargos` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `nome varchar UNIQUE` | Encargo |
| ↳ | `tipo varchar` | Escopo |
| ↳ | `duracao_dias integer?` | Duração aprovada |
| ↳ | `ativo bool` | Válido |
| `designacoes_servico` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `pessoa_id FK NOT NULL` | Titular real **sempre obrigatório** em toda designação, sem linha fictícia para vaga |
| ↳ | `encargo_id FK` | Encargo |
| ↳ | `reuniao_id FK?` | Reunião ou série, quando existente |
| ↳ | `horario_reuniao_id FK?` | Dia/horário recorrente da dupla; derivação das ocorrências futuras |
| ↳ | `inicio date` | Data |
| ↳ | `termino date?` | Data |
| ↳ | `status varchar CHECK (status IN ('ativo','concluido','entregue','destituido'))` | Somente quatro estados: ativo, concluído (fim do termo), entregue (renúncia), destituído (deliberação ou regra institucional confirmada). `VAGO` é projeção por **ausência de designação ativa, com titular e período vigente**, não estado persistido. Duas faltas injustificadas ou recaída exigem confirmação autorizada da condição; após confirmação o workflow **pode destituir automaticamente** conforme a regra institucional, auditando estado/motivo mínimo, sem armazenar dados íntimos ou inferir recaída por Zoom. |
| ↳ | `terminado_por_usuario_id FK?` | Administrador que encerrou manualmente |
| ↳ | `motivo_termino text?` | Entrega, destituição, fim automático e justificativa |
| `escalas_reuniao` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `ocorrencia_id FK` | Data/horário |
| ↳ | `designacao_id FK?` | Mandato |
| ↳ | `pessoa_id FK?` | Substituto |
| ↳ | `funcao varchar` | `fala` ou `chat`, **função por ocorrência**, nunca encargo permanente |
| ↳ | `substituiu_escala_id FK?` | Troca de função/substituição auditável; `UNIQUE(ocorrencia_id, funcao)` para escala vigente, considerando substituições |
| `reunioes_servico` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `titulo varchar` | Reunião |
| ↳ | `realizada_em timestamptz` | Instante |
| ↳ | `interessados_total integer? CHECK >= 0` | Pessoas interessadas no serviço conforme registro histórico |
| ↳ | `presentes_total integer? CHECK >= 0` | Total agregado oficial: inclui acervo legado sem lista individual; `NULL` = não informado |
| ↳ | `faltas_total integer? CHECK >= 0` | Ausências declaradas, não deduzir de cadastro incompleto |
| ↳ | `justificadas_total integer? CHECK >= 0` | Faltas com justificativa, conforme fonte; verificar `justificadas_total <= faltas_total` quando ambos existirem |
| ↳ | `tipo varchar` | Ordinária/extra etc. |
| ↳ | `status varchar` | Estado |
| `atas_servico` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `reuniao_servico_id FK UNIQUE` | Reunião |
| ↳ | `conteudo text` | Conteúdo da ata de serviço montado pelo template vigente |
| ↳ | `template_id FK?` | Referência à versão imutável de `templates_ata_servico` |
| ↳ | `modelo_versao integer?` | Snapshot de versão para importação histórica/consulta |
| ↳ | `status varchar` | `rascunho`, `finalizada`, `revisada`, `corrigida`, `anulada`; validar CHECK e transições; rascunho nunca publicado |
| ↳ | `enviada_por_usuario_id FK?` | Autor que **finalizou**, nulo enquanto rascunho/legado inconclusivo |
| ↳ | `finalizada_em timestamptz?` | Momento do primeiro envio oficial |
| ↳ | `revisao_atual integer CHECK >= 0` | Número incremental; nova revisão em retificação e anulação, sem sobrescrever histórico |
| ↳ | `pdf_arquivo_id FK?` | Documento |
| `revisoes_ata_servico` | `id bigint PK` | Snapshot imutável de conteúdo oficial, retificação ou anulação |
| ↳ | `ata_servico_id FK NOT NULL` | Ata de origem; `UNIQUE(ata_servico_id, revisao)` |
| ↳ | `revisao integer CHECK > 0` | Sequência da versão oficial |
| ↳ | `conteudo_snapshot text` | Conteúdo íntegro **restrito**, necessário para reconstruir versão anterior, com acesso mínimo |
| ↳ | `alterado_por_usuario_id FK NOT NULL` | Autor pessoal da finalização/retificação |
| ↳ | `motivo text?` | Obrigatório para corrigir/anular; auditoria separada e redigida |
| ↳ | `tipo varchar` | `finalizacao`, `conferencia`, `correcao`, `anulacao` |
| ↳ | `pdf_arquivo_id FK?` | Referência à versão PDF no Drive, preservada |
| ↳ | `registrada_em timestamptz` | Quando o evento ocorreu |
| `templates_ata_servico` | `id bigint PK` | Identificador da **versão imutável** do template |
| ↳ | `chave varchar` | Modelo lógico institucional (único com versão) |
| ↳ | `versao integer CHECK > 0` | `UNIQUE(chave, versao)`; não sobrescrever versão usada |
| ↳ | `conteudo text` | Pauta e texto padrão, com variáveis autorizadas |
| ↳ | `ativo bool` | Apenas uma versão ativa por chave |
| ↳ | `editado_por FK` | Secretário Geral autenticado e autorizado |
| ↳ | `publicado_em timestamptz?` | Início da vigência do template |
| `presencas_servico` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `reuniao_servico_id FK` | Reunião |
| ↳ | `pessoa_id FK?` | Pessoa |
| ↳ | `situacao varchar` | Presente/ausente/justificada |
| ↳ | `justificativa text?` | Texto restrito |
| `decisoes_coletivas` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `reuniao_servico_id FK?` | Reunião origem |
| ↳ | `assunto varchar` | Tema |
| ↳ | `deliberacao text` | Texto revisado |
| ↳ | `aprovada_em date?` | Data |
| ↳ | `visibilidade varchar` | Privada/pública |
| `documentos` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `titulo varchar` | Descrição |
| ↳ | `categoria varchar` | Tipo |
| ↳ | `versao integer` | Revisão |
| ↳ | `visibilidade varchar` | Controle |
| ↳ | `arquivo_id FK?` | Anexo |
| `arquivos` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `caminho_privado text?` | Caminho lógico local/privado quando não houver objeto no Drive |
| ↳ | `provider varchar` | `google_drive` por padrão para acervo institucional; S3/MinIO opcionais |
| ↳ | `external_file_id varchar?` | ID remoto de arquivo no Google Drive, sob conta institucional |
| ↳ | `external_folder_id varchar?` | Referência lógica ao diretório remoto |
| ↳ | `versao_externa varchar?` | Revisão para reconciliação sem sobrescrita indevida |
| ↳ | `visibilidade varchar` | ACL: público editorial autorizado ou restrito; privado como padrão |
| ↳ | `mime varchar` | Tipo |
| ↳ | `bytes bigint` | Tamanho |
| ↳ | `sha256 char(64)` | Integridade |
| ↳ | `criado_por FK?` | Autor |


### 6.5. Kit ingresso e Correios


| Tabela | Finalidade | Relações principais | Privacidade | Fonte da necessidade |
|---|---|---|---|---|
| `kits_ingresso` | Processo de atendimento de ingresso acessível só pelo Coordenador Kit | ingressos; enderecos_kit; remessas | sensível | Kit Ingresso Quarentena |
| `enderecos_kit` | Endereço usado somente para entrega | kits_ingresso | muito restrito | Kit + AwesomeAPI CEP |
| `contatos_kit` | Contato para comunicação da entrega | kits_ingresso | muito restrito | Telefone Forms/Kit |
| `remessas` | Expedições efetivas de kits | kits_ingresso, eventos_rastreio | sensível | Planilha Kit / rastreio |
| `eventos_rastreio` | Eventos normalizados dos Correios | remessas | restrito | Serviço correios-rastreamento |
| `historico_kit` | Histórico de transições | kits_ingresso, usuarios | restrito | Status da planilha |


**Campos e tipos de referência:**


| Tabela | Campo / tipo sugerido | Significado / regra |
|---|---|---|
| `kits_ingresso` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `ingresso_id FK UNIQUE` | Origem |
| ↳ | `status varchar` | Verificar/falta_endereco/falta_enviar/enviado/recebido/devolvido/cancelado |
| ↳ | `atribuido_a FK?` | Responsável |
| ↳ | `criado_em timestamptz` | Início |
| ↳ | `observacoes text?` | Anotações operacionais privativas do Coordenador Kit, **cifradas na aplicação** (coluna PostgreSQL TEXT com cast criptográfico); nunca incluídas no resumo público ou em logs |
| `enderecos_kit` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `kit_id FK` | Kit |
| ↳ | `cep_cipher text` | CEP protegido |
| ↳ | `logradouro_cipher text` | Logradouro |
| ↳ | `numero_cipher text` | Número |
| ↳ | `complemento_cipher text?` | Complemento |
| ↳ | `cidade varchar` | Cidade |
| ↳ | `uf char(2)` | Estado |
| ↳ | `atualizado_em timestamptz` | Atualização |
| `contatos_kit` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `kit_id FK` | Kit |
| ↳ | `telefone_cipher text?` | Telefone |
| ↳ | `telefone_hash char(64)?` | Blind index HMAC da forma normalizada; nunca exposto |
| ↳ | `canal varchar` | WhatsApp/outro |
| ↳ | `consentimento_em timestamptz?` | Autorização registrada |
| `remessas` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `kit_id FK` | Kit |
| ↳ | `codigo_rastreio varchar UNIQUE?` | Código |
| ↳ | `expedido_em timestamptz?` | Postagem |
| ↳ | `status varchar` | Estado |
| ↳ | `ultima_consulta timestamptz?` | Consulta |
| `eventos_rastreio` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `remessa_id FK` | Remessa |
| ↳ | `codigo_evento varchar?` | Código externo |
| ↳ | `ocorrido_em timestamptz` | Data |
| ↳ | `descricao text` | Evento |
| ↳ | `cidade varchar?` | Local |
| ↳ | `uf char(2)?` | UF |
| ↳ | `hash_evento char(64)` | Deduplicação |
| `historico_kit` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `kit_id FK` | Kit |
| ↳ | `estado_anterior varchar?` | Antes |
| ↳ | `estado_novo varchar` | Depois |
| ↳ | `alterado_por FK?` | Usuário ou serviço |
| ↳ | `origem varchar` | Manual/automática |
| ↳ | `instante timestamptz` | Quando |


### 6.6. Tesouraria e ASAAS


| Tabela | Finalidade | Relações principais | Privacidade | Fonte da necessidade |
|---|---|---|---|---|
| `contas_financeiras` | Contas e reservas do grupo | lancamentos_financeiros | muito restrito | Planilha Tesouraria |
| `categorias_financeiras` | Categorias de despesas e entradas | lancamentos_financeiros | restrito/misto | Planilha / Looker |
| `lancamentos_financeiros` | Livro transacional do grupo | contas_financeiras, categorias_financeiras, eventos_asaas | muito restrito | Tesouraria/ASAAS |
| `eventos_asaas` | Inbox idempotente de ASAAS | lancamentos_financeiros | muito restrito | Webhook ASAAS n8n |
| `conciliacoes_financeiras` | Verificação do livro/ASAAS | usuarios, lancamentos_financeiros | muito restrito | Tesouraria / prestação |


**Campos e tipos de referência:**


| Tabela | Campo / tipo sugerido | Significado / regra |
|---|---|---|
| `contas_financeiras` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `nome varchar UNIQUE` | Nome |
| ↳ | `tipo varchar` | Corrente/reserva |
| ↳ | `ativa bool` | Vigente |
| ↳ | `saldo_inicial numeric(14,2)` | Saldo inicial auditado de abertura |
| `categorias_financeiras` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `nome varchar UNIQUE` | 7ª tradição, Zoom etc. |
| ↳ | `natureza varchar` | Entrada/saída/ambos |
| ↳ | `publicavel bool` | Se aceita no relatório |
| `lancamentos_financeiros` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `conta_id FK` | Conta |
| ↳ | `categoria_id FK` | Categoria |
| ↳ | `tipo varchar` | Entrada/saída |
| ↳ | `valor numeric(14,2) CHECK > 0` | Quantia positiva; sinal definido por `tipo`, não float |
| ↳ | `ocorrido_em timestamptz` | Data |
| ↳ | `descricao text NOT NULL` | Entrada PIX ASAAS: padrão **“7ª Tradição - PIX recebido”**; despesa: descrição sanitizada extraída da mensagem do evento quando disponível, caso contrário texto de fallback explícito. Tesoureiro pode retificar com auditoria; descrições restritas sem nome do contribuinte em relatórios públicos |
| ↳ | `asaas_evento_id FK?` | Evento de origem; não equivale à identidade única de transação financeira |
| ↳ | `chave_efeito varchar UNIQUE?` | Chave de efeito financeiro idempotente por transação/tipo de efeito; replay/estado sucessivo não duplica saldo |
| ↳ | `status varchar` | Efetivo/estornado/excluído logicamente; não há rascunho ou pendente de aprovação do lançamento |
| ↳ | `origem varchar` | ASAAS ou manual; ambos lançados imediatamente após validação |
| ↳ | `corrigido_por FK?` | Somente tesoureiro; histórico em `auditorias` |
| ↳ | `excluido_em timestamptz?` | Exclusão lógica, sem sumir com o evento original |
| `eventos_asaas` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `evento_externo_id varchar UNIQUE` | Identificador idempotente do evento entregue |
| ↳ | `transacao_externa_id varchar?` | ID do recurso/lançamento de origem distinto do ID do evento |
| ↳ | `tipo varchar` | Tipo |
| ↳ | `recebido_em timestamptz` | Instante |
| ↳ | `hash_payload char(64)` | Integridade |
| ↳ | `status varchar` | Processado/erro |
| ↳ | `payload_minimizado jsonb` | Somente campos necessários |
| `conciliacoes_financeiras` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `periodo_inicio date` | Inicial |
| ↳ | `periodo_fim date` | Final |
| ↳ | `conciliado_por FK` | Tesouraria |
| ↳ | `divergencias jsonb` | Ocorrências |
| ↳ | `aprovado_em timestamptz?` | Fechamento |


### 6.7. Comunicação e eventos de integração


| Tabela | Finalidade | Relações principais | Privacidade | Fonte da necessidade |
|---|---|---|---|---|
| `grupos_mensagem` | Destinos autorizados Evolution API | comandos_recebidos, mensagens_saida | muito restrito | n8n Robô / grupos |
| `modelos_mensagem` | Textos versionados de notificação | mensagens_saida | restrito | Apps Script / Docs |
| `mensagens_saida` | Outbox de notificações | modelos_mensagem, grupos_mensagem | muito restrito | n8n e Evolution API |
| `comandos_recebidos` | Comandos WhatsApp processados | grupos_mensagem | restrito | n8n Robô / !vago |
| `eventos_webhook` | Inbox genérica de integrações | eventos_asaas, eventos_zoom | restrito | Endpoints/webhooks |
| `execucoes_rotinas` | Observabilidade de jobs migrados | mensagens_saida, eventos_webhook | restrito | n8n / Apps Script |
| `eventos_zoom` | Eventos temporários de reunião | ocorrencias_reuniao, resumos_zoom | muito restrito/temporário | Webhook Zoom |
| `resumos_zoom` | Indicadores sugeridos para ocorrência | ocorrencias_reuniao, atas | restrito | Contagem Zoom |


| Tabela | Finalidade | Relações principais | Privacidade | Fonte da necessidade |
|---|---|---|---|---|
| `rotinas_programadas` | Ativação, frequência, próxima execução e dono | gatilhos_automacao | restrito | RF-AUTOMACOES-017/018 |
| `gatilhos_automacao` | Eventos/agendas permitidos para rotina | rotinas_programadas | restrito | Editor declarativo |
| `condicoes_automacao` | Predicados de catálogo, sem código executável | rotinas_programadas | restrito | Editor declarativo |
| `acoes_automacao` | Handlers Laravel permitidos e destinos | rotinas_programadas | restrito | Editor declarativo |
| `destinos_mensagem` | Destinos por escopo com referência cifrada | mensagens_saida, grupos_mensagem | sensível | RF-AUTOMACOES-018 |
| `credenciais_integracao` | Referência a segredo protegido, não segredo em claro | configuracoes | muito restrito | RF-ADMINISTRACAO-008 |
| `eventos_outbox` | Efeitos externos persistidos na transação de origem | entidades de domínio | restrito | Idempotência transacional |
| `notebooks_ia` | Registro de notebooks aprovados e finalidade | configuracoes | restrito | RF-IA-002 |

**Campos e tipos de referência:**


| Tabela | Campo / tipo sugerido | Significado / regra |
|---|---|---|
| `grupos_mensagem` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `nome varchar` | Apelido |
| ↳ | `identificador_cipher text` | Grupo privado |
| ↳ | `identificador_hash char(64)?` | Blind index HMAC para localização exata autorizada |
| ↳ | `finalidade varchar` | Serviço/membros |
| ↳ | `ativo bool` | Status |
| `modelos_mensagem` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `chave varchar UNIQUE` | Identificador |
| ↳ | `conteudo text` | Template de mensagem com variáveis validadas |
| ↳ | `ativo bool` | Habilitar/desabilitar uso do modelo |
| ↳ | `aprovado_em timestamptz?` | Aprovação |
| ↳ | `versao integer` | Revisão |
| `mensagens_saida` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `modelo_id FK?` | Template |
| ↳ | `grupo_id FK?` | Grupo |
| ↳ | `destinatario_ref varchar?` | Referência protegida |
| ↳ | `status varchar` | Fila/enviando/enviado/falha |
| ↳ | `chave_idempotencia varchar UNIQUE` | Unicidade |
| `comandos_recebidos` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `grupo_id FK` | Origem |
| ↳ | `mensagem_externa_id varchar UNIQUE` | Dedup |
| ↳ | `comando varchar` | Nome |
| ↳ | `recebido_em timestamptz` | Momento |
| ↳ | `resultado varchar` | Estado |
| `eventos_webhook` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `provedor varchar` | Zoom/ASAAS/Evolution |
| ↳ | `identificador varchar` | ID externo; `UNIQUE(provedor, identificador)` para envelope genérico |
| ↳ | `recebido_em timestamptz` | Data |
| ↳ | `status varchar` | Novo/processado/erro |
| ↳ | `payload_minimizado jsonb` | Campos permitidos |
| `execucoes_rotinas` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `rotina varchar` | Identificador |
| ↳ | `iniciada_em timestamptz` | Início |
| ↳ | `terminada_em timestamptz?` | Fim |
| ↳ | `status varchar` | Sucesso/erro |
| ↳ | `correlacao varchar` | ID |
| ↳ | `erro_redigido text?` | Erro sem PII |
| `eventos_zoom` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `ocorrencia_id FK?` | Relação |
| ↳ | `external_event_id varchar UNIQUE` | Dedup |
| ↳ | `tipo varchar` | Evento |
| ↳ | `ocorreu_em timestamptz` | Momento |
| ↳ | `participante_ref_hash char(64)?` | Referência temporária |
| ↳ | `nome_exibicao_cipher text?` | Nome temporário criptografado para conferir presença, nunca relatório público |
| ↳ | `expira_em timestamptz` | Prazo configurável; padrão inicial de 365 dias com expurgo real |
| `resumos_zoom` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `ocorrencia_id FK UNIQUE` | Reunião |
| ↳ | `participantes_sugeridos integer? CHECK >= 0` | `NULL` = contagem indisponível; **0** = contagem válida de zero pessoas (não confundir indisponibilidade com zero) |
| ↳ | `calculado_em timestamptz` | Momento |
| ↳ | `fonte varchar` | Algoritmo/relatório |


| Tabela | Campo / tipo sugerido | Significado / regra |
|---|---|---|
| `rotinas_programadas` | `id bigint PK` | Rotina institucional identificável |
| ↳ | `chave varchar UNIQUE` | Tipo e finalidade do fluxo |
| ↳ | `ativo bool DEFAULT false` | Flag obrigatória verificada antes do disparo |
| ↳ | `agenda jsonb?` | Recorrência IANA, frequência e horário; validar schema |
| ↳ | `dono_modulo varchar` | Responsável autorizado (Tesouraria, Kit ou gestão) |
| ↳ | `proxima_execucao timestamptz?` | Idempotência para agendamento por ocorrência |
| `gatilhos_automacao` | `id bigint PK` | Gatilho de catálogo, pertencente à rotina |
| ↳ | `rotina_id FK` | Rotina dona |
| ↳ | `tipo varchar` | Evento, webhook, calendário, nunca SQL/PHP livre |
| ↳ | `parametros jsonb` | Esquema tipado/validado pelo handler |
| `condicoes_automacao` | `id bigint PK` | Expressão declarativa permitida |
| ↳ | `rotina_id FK` | Rotina dona |
| ↳ | `operador varchar` | Predicado permitido, nunca `eval` |
| ↳ | `parametros jsonb` | Campos em allowlist |
| `acoes_automacao` | `id bigint PK` | Ação programada por handler conhecido |
| ↳ | `rotina_id FK` | Rotina dona |
| ↳ | `handler_chave varchar` | Identificador versionado do código Laravel permitido |
| ↳ | `ordem integer CHECK >= 0` | Ordem da ação na rotina |
| ↳ | `parametros jsonb` | Validados e sem segredos literais |
| `destinos_mensagem` | `id bigint PK` | Canal/destino autorizado |
| ↳ | `tipo varchar` | Grupo ou conversa privada autorizada |
| ↳ | `identificador_cipher text` | ID do destino criptografado |
| ↳ | `identificador_hash char(64)?` | Blind index HMAC, sem revelar destinatário |
| ↳ | `grupo_id FK?` | Grupo da Evolution, se aplicável |
| `credenciais_integracao` | `id bigint PK` | Referência protegida a credencial |
| ↳ | `provedor varchar` | Cloudflare, ASAAS, Zoom, Evolution, IA etc. |
| ↳ | `segredo_ref varchar` | **Referência** a Docker Secret/cofre ou armazenamento cifrado próprio; nunca token em claro em UI/log |
| ↳ | `rotacionado_em timestamptz?` | Trilha de rotação |
| `eventos_outbox` | `id bigint PK` | Efeito de negócio externo aguardando dispatch |
| ↳ | `chave_idempotencia varchar UNIQUE` | Proíbe reenviar a mesma consequência involuntariamente |
| ↳ | `evento_tipo varchar` | Evento de domínio publicável |
| ↳ | `entidade varchar` | Origem do efeito |
| ↳ | `entidade_id bigint` | PK da origem |
| ↳ | `status varchar` | Pendente/enviando/confirmado/erro |
| ↳ | `payload_minimizado jsonb` | Apenas dados autorizados do canal |
| `notebooks_ia` | `id bigint PK` | Registro dos notebooks habilitáveis |
| ↳ | `notebook_externo_id varchar UNIQUE` | ID externo por notebook, nunca token |
| ↳ | `finalidade varchar` | Literatura, treinamento, meditação |
| ↳ | `ativo bool DEFAULT false` | Desabilitado por padrão |
| ↳ | `fontes_geridas_manualmente bool DEFAULT true` | Responsabilidade de curadoria humana |


### 6.8. Migração, governança e operação


| Tabela | Finalidade | Relações principais | Privacidade | Fonte da necessidade |
|---|---|---|---|---|
| `lotes_importacao` | Auditoria da importação por fonte | registros_importados, usuarios | muito restrito | Exports Sheets/Docs/n8n |
| `registros_importados` | Rastreio por registro migrado | lotes_importacao, entidades de destino | restrito | Planilhas históricas |
| `conflitos_importacao` | Casos de homônimo/erro/dúvida | lotes_importacao, usuarios | sensível | Saneamento e dedup |
| `auditorias` | Trilha append-only de escrita e acesso sensível por usuário/job/integração | usuarios, sessoes_elevadas | muito restrito | RF-AUDITORIA-002/007/008 |
| `configuracoes` | Configuração institucional sem segredos | usuarios | restrito | Docs e scripts legado |
| `solicitacoes_atendimento` | **Fale Conosco geral** e pedidos de retificação sob revisão humana | auditorias, usuarios; alvo opcional | sensível | RF-ATENDIMENTO-001/002 |
| `registros_backup` | Evidência dos backups e restores | auditorias | muito restrito | Operação Docker/PG |


**Campos e tipos de referência:**


| Tabela | Campo / tipo sugerido | Significado / regra |
|---|---|---|
| `lotes_importacao` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `fonte varchar` | Origem |
| ↳ | `arquivo_sha256 char(64)` | Hash |
| ↳ | `iniciado_em timestamptz` | Início |
| ↳ | `finalizado_em timestamptz?` | Fim |
| ↳ | `estado varchar` | Em andamento/validado |
| ↳ | `responsavel_id FK` | Quem aprovou |
| `registros_importados` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `lote_id FK` | Lote |
| ↳ | `linha_origem varchar` | ID/linha |
| ↳ | `tabela_destino varchar` | Entidade |
| ↳ | `registro_destino_id bigint?` | ID |
| ↳ | `hash_origem char(64)` | Dedup |
| ↳ | `resultado varchar` | Importado/ignorado/erro |
| `conflitos_importacao` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `lote_id FK` | Lote |
| ↳ | `categoria varchar` | Tipo |
| ↳ | `descricao text` | Motivo |
| ↳ | `decisao text?` | Solução |
| ↳ | `decidido_por FK?` | Humano |
| ↳ | `estado varchar` | Pendente/solucionado |
| `auditorias` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `usuario_id FK?` | Autor humano autenticado, quando houver |
| ↳ | `ator_tipo varchar` | `usuario`, `integracao`, `job`, `sistema` |
| ↳ | `ator_referencia varchar?` | Identificador do serviço/job integrado, não segredo |
| ↳ | `sessao_elevada_id FK?` | Identifica elevação excepcional da sessão |
| ↳ | `correlacao_id varchar` | ID de requisição/fluxo para rastreabilidade |
| ↳ | `origem varchar?` | Canal/tipo da ação; IP não é identidade da pessoa |
| ↳ | `ip_origem inet?` | IP, quando disponível, respeitada retenção |
| ↳ | `user_agent text?` | Navegador/agente, quando disponível |
| ↳ | `modulo varchar` | Domínio funcional da operação |
| ↳ | `justificativa text?` | Motivo de retificação/acesso quando exigido |
| ↳ | `tipo_acesso varchar` | `escrita`, `leitura_sensivel`, `revelacao_segredo`, `elevacao` |
| ↳ | `acao varchar` | Operação |
| ↳ | `entidade varchar` | Tabela |
| ↳ | `entidade_id bigint` | Registro |
| ↳ | `antes_redigido jsonb?` | Antes |
| ↳ | `depois_redigido jsonb?` | Depois |
| ↳ | `ocorrido_em timestamptz` | Quando |
| `configuracoes` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `chave varchar UNIQUE` | Nome |
| ↳ | `valor jsonb` | **Somente configuração não secreta**, nunca token/API key em claro |
| ↳ | `editavel_por varchar` | Perfil administrativo autorizado; controle real via Policy |
| ↳ | `escopo varchar` | Institucional |
| ↳ | `modificado_por FK?` | Responsável |
| `solicitacoes_atendimento` | `id bigint PK` | Protocolo interno institucional |
| ↳ | `protocolo varchar UNIQUE` | Identificador apresentado ao solicitante |
| ↳ | `categoria varchar` | `duvida`, `sugestao`, `problema`, `ocultacao`, `retificacao`, `outro`; sempre notifica CG e SG, com encaminhamento restrito quando envolver Kit |
| ↳ | `assunto varchar?` | Título resumido opcional, não usado como conteúdo em notificações públicas |
| ↳ | `mensagem_cipher text` | Corpo minimizado protegido por permissão, não notificado em grupo |
| ↳ | `contato_retorno_cipher text?` | WhatsApp/e-mail opcional para resposta individual |
| ↳ | `status varchar` | Recebida/em_analise/atendida/indeferida |
| ↳ | `responsavel_id FK?` | Atribuição para CG/SG na triagem; caso de Kit encaminhado pelo fluxo seguro ao Coordenador Kit sem expor seus dados |
| ↳ | `decisao text?` | Motivo da análise; não copiar PII em logs |
| ↳ | `tipo_alvo varchar?` | Tipo do registro eventualmente questionado, validado por **allowlist**; nulo para atendimento geral |
| ↳ | `registro_alvo_id bigint?` | ID de registro identificado na triagem, opcional; ambos os campos de alvo preenchidos ou ambos nulos, com acesso sempre pela Policy |
| ↳ | `correlacao_auditoria varchar?` | Identificador compartilhado com a retificação efetiva quando houver, para rastreabilidade sem copiar o corpo do chamado |
| ↳ | `resolvida_em timestamptz?` | Encerramento e resposta ao solicitante |
| `registros_backup` | `id bigint PK` | Identificador interno estável; nunca igualar à linha da planilha |
| ↳ | `inicio timestamptz` | Data |
| ↳ | `escopo varchar` | Banco/arquivos |
| ↳ | `hash_arquivo char(64)?` | Integridade |
| ↳ | `armazenamento_ref varchar` | Destino |
| ↳ | `restaurado_em timestamptz?` | Ensaio |


### 6.9. Tabelas técnicas herdadas do template e tratamento


| Tabela (existente no template) | Conteúdo existente confirmado | Diretriz na derivação |
|---|---|---|
| `usuarios` | Identidade de login humano, email, hash da senha e ativo | **Adaptar o template** e tornar `pessoa_id FK NOT NULL UNIQUE` para contas humanas; sem cadastro automático por Zoom; substituir perfis comerciais por autorização derivada do encargo |
| `password_reset_tokens` | Tokens temporários de recuperação | Reutilizar com proteção contra enumeração de contas |
| `personal_access_tokens` | Tokens Sanctum / API v1 | Somente escopos ligados a usuário humano pessoal; provedores/jobs autenticam-se por integrações assinadas/segredos próprios sem criar login fictício |
| `jobs`, `job_batches`, `failed_jobs` | Fila e erros do Laravel | Usar filas sem payload integral de dados pessoais |
| `migrations` | Lotes/histórico de schema Laravel | Não podar ou reescrever histórico aplicado |
| `clientes`, `cliente_usuario` | Estruturas comerciais de referência | Não importar para o QuarenteNA |


### 6.10. Normalização dos nomes e correspondência com as planilhas


**Não existe identificador civil ou de membro de NA que justifique criar cadastro público nominal geral.** O sistema distingue `usuarios.id` (identidade de login), `pessoas.id` (servidor operacional), `ingressos.id` (ocorrência/registro de ingresso) e `contatos_kit.id` (contato protegido de entrega). Não criar unicidade global em primeiro nome/apelido.


| Entrada legada | Exibição preservada | Chave de busca técnica | Efeito correto |
|---|---|---|---|
| `Ciro`, `ciro`, `CIRO`, `Ciro   ` | `Ciro` conforme revisão | `ciro` | Sugerir correspondência com registro existente; não duplicar por variação de caixa/espaço |
| `Círo`, `Ciro` | Forma informada autorizada | `ciro` em chave auxiliar sem acentos | Sugerir possível correspondência, **sem fusão automática** |
| Dois participantes distintos chamados `Ciro` | Primeiro nome/apelido de cada um | `ciro` | Manter dois identificadores estáveis e contextos distintos |
| `Ciro` digitado junto a ingresso novo | Texto do ingresso separado do cadastro operacional | `ciro` | Registrar ingresso; só vincular pessoa com confirmação humana e finalidade |


**Pipeline:** Unicode NFKC → trim Unicode → reduzir espaços internos → Unicode casefold para `nome_busca`; opcionalmente remover diacríticos **somente na chave auxiliar de busca**. Indexar chaves normalizadas de pesquisa, mas sem índice `UNIQUE` sobre nomes de pessoas. Para identidades de login, conservar `usuarios.email` único com canonização adequada e validação de colisão; índices funcionais/collations do PostgreSQL escolhidos e testados no schema real. A documentação PostgreSQL lembra que `citext` depende de locale e que collations ICU não determinísticas podem tratar Unicode/case melhor; decidir pelo PostgreSQL efetivamente instalado, não por heurística.


### 6.11. Mapeamento dos campos reais da ata e da planilha


| Origem no Form / Sheets | Campo relacional alvo | Regra de tratamento e histórico |
|---|---|---|
| Timestamp da resposta (A) | `atas.enviada_em` + `registros_importados` | Preservar timestamp e origem da importação |
| Data (B), horário (C), tipo (W) | `ocorrencias_reuniao` + `reunioes` | Horário local 20h–22h como agenda atual confirmada; tipo de reunião pode variar |
| Total membros (D), partilhas (E) | `atas.membros_total`, `atas.partilhas_total` | Inteiros não negativos, nulo distinto de zero na migração quando a fonte era vazia |
| Visitas (F) e origem (X) | `visitas`, `origens_visita` | Contagem e origens separadas, múltiplas origens estruturadas |
| Ingressos (G), nomes (I) | `atas.ingressos_total` + `ingressos` | Conferir total/linhas; público mostra somente apelido/primeiro nome autorizado |
| Contato do ingressante (Z) | `contatos_kit` | Criptografia, acesso exclusivo ao Coordenador Kit |
| Conquistas (H), nome/tempo (J) | `conquistas`, `tipos_conquista` | Contagem separada de referência nominal mínima autorizada |
| Temática (K), partilhador (L) | `atas_tematicas`, `tematicas` | Normalizar catálogo; texto de referência permitido |
| Observações (M) e informações adicionais (T) | `atas.observacoes`, `atas.informacoes_adicionais` | Campo livre higienizado, com privacidade e histórico |
| Secretário (N), coordenador (O) | `escalas_reuniao`, `atas` | Resolver por usuário/escala real, sem duplicar servidor por grafia; funções alternáveis |
| 7ª Tradição (P), data (Q), despesa (R), discriminação (S) | `lancamentos_financeiros` quando lançamento real; `atas` para informação declarativa | **Não criar transação financeira automaticamente só por total/saldo informado na ata**; distinguir saldo de entrada/saída e conciliar com ASAAS; direitos exclusivos tesoureiro |
| Ata pendente (U) e e-mail (V) | `atas.status`, `usuarios` quando autorizado | Não importar e-mail do formulário como conta com login automaticamente |
| Eleição de encargo (Y) | `designacoes_servico`, `decisoes_coletivas` | Criar mandato com prazo e responsável somente após confirmação de deliberação |
| Google Sheets Atas de Serviço A–G | `atas_servico`, `reunioes_servico`, `presencas_servico`, `arquivos` | PDF no Drive privado; números de presença/falta preservados |
| Google Sheets Serviço Grupo Quarentena | `encargos`, `designacoes_servico`, `escalas_reuniao` | Uma designação por titular/vigência, sem replicar linha histórica “VAGO” |
| Google Sheets Kit A–K | `kits_ingresso`, `contatos_kit`, `enderecos_kit`, `remessas`, `eventos_rastreio`, `historico_kit` | Histórico eventos em linhas, não texto concatenado; PII isolada |
| Google Sheets Tesouraria | `contas_financeiras`, `categorias_financeiras`, `lancamentos_financeiros`, `eventos_asaas` | Valores DECIMAL; guardar origem/estorno e conciliar totals históricos |


**Campos de ata obrigatórios preservados no produto:** data, horário, formato/tipo, dupla (chat e fala), presenças, partilhas, visitas, ingressos, nomes públicos permitidos dos ingressantes, conquistas e tempos informados, temática/partilhador, 7ª Tradição e sua data de referência, despesas/discriminação quando existirem, informações adicionais, eleições e observações. Não misturar telefone/endereço com conteúdo da ata que pode ser exportado/publicado. Dados pessoais de kit entram no fluxo privado vinculado ao ingresso.


**Consultas de relatório:** somente atas oficiais válidas entram em agregações; correção/anulação exige recálculo, e snapshot de importação permite conferir números do Looker antigo.


### Regras verificáveis antes de criar migrations

| Invariante | Prova técnica exigida |
|---|---|
| Contagens de ata | Rascunho admite nulo, ata oficial exige totais inteiros `>= 0`; `visitas_total`/`ingressos_total`/`conquistas_total` são valores declarados, não somados novamente aos detalhes |
| Ata enviada: autorização | Para `status != rascunho`, `enviada_por_usuario_id` é obrigatório em novo envio; correção somente remetente, CG, SG ou root elevado. Se autoria legada for desconhecida, somente CG/SG/root podem corrigir, sem inventar autor |
| Serviço: totais históricos | `presentes_total`, `faltas_total`, `justificadas_total` admitem NULL=desconhecido; `justificadas_total <= faltas_total` quando ambos existem; ausência de listas não impede filtros por totais |
| Publicação nominal | Registro novo oficial liberado por padrão se sem opt-out; importação legada entra `em_triagem` até reconciliação humana em lote; projeções públicas exigem `liberado` e `oculto_publicamente=false`, ata válida e minimização de nomes |
| Revisões e escala | `UNIQUE(ata_id, revisao)`; escala vigente limita uma pessoa por função e ocorrência, registrando substituição sem apagar vínculo anterior |
| Pessoa e login | **`usuarios.pessoa_id FK NOT NULL UNIQUE`** para cada conta humana; `pessoas` pode existir sem conta. Bot/job/ASAAS = `auditorias.ator_tipo` técnico, sem login humano fictício; nomes normalizados não são chaves de identidade |
| Template de serviço | Versão de template referenciada por ata é imutável; edições geram nova versão |
| Livro financeiro | Idempotência por **efeito financeiro** (identidade da transação e tipo de efeito), além de idempotência por evento externo; conciliação não bloqueia lançamento efetivo |
| Eventos externos | Inbox recebe/valida; outbox é gravado com transação de domínio e auditoria; workers aplicam retries com chaves únicas |
| Segurança | Tesoureiro/Kit exclusivos no acesso normal; root só como sessão pessoal elevada de curta duração, com MFA e auditoria de leitura sensível |
| Comunicação | `!kit` descontinuado, não recebe rota; notificações do Kit não incluem PII em grupos; Fale Conosco notifica Coordenação/Secretaria Geral com protocolo |

As **63 entidades são candidatas lógicas, não 63 migrations a executar em bloco**. A implementação deve materializar somente as tabelas usadas na Story e validar os relacionamentos no PostgreSQL real.


## 7. Diagramas entidade–relacionamento (ERD)


### Reunião, ata, serviço e pessoas


```mermaid
erDiagram
  PESSOAS ||--o| USUARIOS : "pode ter login"
  TIPOS_REUNIAO ||--o{ REUNIOES : define
  REUNIOES ||--o{ HORARIOS_REUNIAO : possui
  REUNIOES ||--o{ OCORRENCIAS_REUNIAO : ocorre
  OCORRENCIAS_REUNIAO ||--o| ATAS : possui
  ATAS ||--o{ REVISOES_ATA : historico
  ATAS ||--o{ VISITAS : agrega
  ORIGENS_VISITA ||--o{ VISITAS : classifica
  ATAS ||--o{ INGRESSOS : registra
  ATAS ||--o{ CONQUISTAS : contabiliza
  ATAS ||--o{ ATAS_TEMATICAS : aborda
  TEMATICAS ||--o{ ATAS_TEMATICAS : classifica
  PESSOAS ||--o{ DESIGNACOES_SERVICO : exerce
  ENCARGOS ||--o{ DESIGNACOES_SERVICO : define
  REUNIOES ||--o{ DESIGNACOES_SERVICO : relacionado
  REUNIOES_SERVICO ||--o| ATAS_SERVICO : registra
  ATAS_SERVICO ||--o{ REVISOES_ATA_SERVICO : versiona
```


> Cardinalidade de `ATAS` por ocorrência precisa tratar cancelamentos e importações antigas; recomendação de **um registro lógico por ocorrência**, com revisões separadas e histórico de origem imutável.


### Kit, remessa e Correios


```mermaid
erDiagram
  ATAS ||--o{ INGRESSOS : registra
  INGRESSOS ||--o| KITS_INGRESSO : origina
  KITS_INGRESSO ||--o| ENDERECOS_KIT : endereca
  KITS_INGRESSO ||--o| CONTATOS_KIT : comunica
  KITS_INGRESSO ||--o{ REMESSAS : expede
  REMESSAS ||--o{ EVENTOS_RASTREIO : possui
  KITS_INGRESSO ||--o{ HISTORICO_KIT : audita_estado
```


**Por que várias tabelas?** O ingresso identifica o acontecimento da reunião; o kit registra a obrigação operacional; a remessa pode precisar de reenvio após devolução; eventos de rastreio formam linha do tempo; endereço e contato possuem restrição mais rigorosa. Uma planilha única misturava essas informações e dificultava auditoria.


### Tesouraria, mensagens e integrações


```mermaid
erDiagram
  CONTAS_FINANCEIRAS ||--o{ LANCAMENTOS_FINANCEIROS : recebe
  CATEGORIAS_FINANCEIRAS ||--o{ LANCAMENTOS_FINANCEIROS : categoriza
  EVENTOS_ASAAS ||--o| LANCAMENTOS_FINANCEIROS : origina
  GRUPOS_MENSAGEM ||--o{ COMANDOS_RECEBIDOS : recebe
  GRUPOS_MENSAGEM ||--o{ MENSAGENS_SAIDA : recebe
  MODELOS_MENSAGEM ||--o{ MENSAGENS_SAIDA : formata
  OCORRENCIAS_REUNIAO ||--o{ EVENTOS_ZOOM : registra
  OCORRENCIAS_REUNIAO ||--o| RESUMOS_ZOOM : resume
  LOTES_IMPORTACAO ||--o{ REGISTROS_IMPORTADOS : contem
  LOTES_IMPORTACAO ||--o{ CONFLITOS_IMPORTACAO : observa
```


## 8. Contratos de integração e tratamento de falhas


### 8.1. Correios: serviço aprovado para o Kit Ingresso


**Fonte selecionada pelo projeto:** [`opastorello/correios-rastreamento`](https://github.com/opastorello/correios-rastreamento). O serviço é **Python/FastAPI**, não uma biblioteca nativa Laravel; deve ser executado separadamente no Docker e integrado por HTTP autenticado **apenas na rede privada**.


| Contrato | Valor/diretriz extraído do repositório | Regra QuarenteNA |
|---|---|---|
| Objeto único | `POST /rastreamento/objeto` com `{"codigo":"AA000000000BR"}` | Laravel chama com código da remessa permitida |
| Múltiplos | `POST /rastreamento/multiplos`, até 20 códigos por consulta segundo README | Worker agrupa consultas, respeitando limites e sem misturar autorização entre kits |
| Health | `GET /health` | Observabilidade técnica; falha não remove dados existentes |
| Segurança | `API_TOKEN` Bearer opcional; sem token, proteção é desabilitada | Token **obrigatório em produção** + rede privada, segredos Docker |
| Histórico auxiliar | Histórico global em arquivo do serviço | Não usar como fonte oficial; histórico institucional fica em `eventos_rastreio` |
| Limites | README indica 20/min por IP objeto; 10/min múltiplos | Job aplica backoff, circuit breaker, jitter e limite inferior ao necessário |
| Retenção auxiliar | `HISTORY_RETENTION_DAYS`, padrão 90 no README | Isolar/limitar a persistência auxiliar; política final de PII a aprovar operacionalmente |
| Timezone do serviço | `APP_TIMEZONE=America/Sao_Paulo` por padrão | Configurar `America/Bahia` para consistência de exibição; salvar instantes sem ambiguidades |
| Tecnologia | Scraping de Correios + CAPTCHA por rede neural | Dependência frágil frente a mudanças externas; oferecer reconsulta manual e alerta |
| Interface/MCP | Interface web pública e MCP possíveis | Não publicar as rotas do serviço na Internet; usar REST interno e bloquear acesso externo |


#### Sequência idempotente de rastreamento


```mermaid
sequenceDiagram
  participant S as Scheduler Laravel
  participant J as Job de Rastreio
  participant DB as PostgreSQL
  participant API as Correios Rastreamento
  participant M as Evolution API
  S->>J: Enfileirar remessas pendentes
  J->>DB: Carregar remessa e última consulta
  J->>API: POST /rastreamento/objeto (Bearer)
  alt Resposta válida
    API-->>J: Eventos e status
    J->>DB: Transação: upsert por hash_evento
    J->>DB: Atualizar estado do kit/remessa
    J->>DB: Criar aviso outbox único
    J->>M: Enviar aviso autorizado
  else Timeout/erro/limite
    API-->>J: Erro
    J->>DB: Registrar falha sem PII
    J->>J: Retry com backoff ou fila de intervenção
  end
```


**Salvaguardas:** não realizar consulta em massa desnecessária; avaliar termos dos Correios e estabilidade do scraper; não exibir CPF, telefone, endereço completo ou dados privados no serviço auxiliar; não confiar em `situacao` sem normalizar eventos e datas; registrar `hash_evento` único por remessa e ocorrência.


### Consistência entre atas, ingressos e remessas

```mermaid
sequenceDiagram
  participant U as Servidor autorizado
  participant A as Atas
  participant DB as PostgreSQL
  participant K as Coordenador Kit
  U->>A: Corrige ou anula ata oficial
  A->>DB: Grava versão + auditoria + outbox na mesma transação
  DB-->>K: Evento assíncrono de revisão de Kit (sem PII)
  K->>DB: Verifica kit e remessa com permissão vigente
  K->>DB: Registra decisão e providência; mantém rastreio expedido
```

Corrigir ou anular uma ata modifica projeções oficiais, mas **não apaga/remete/estorna automaticamente** um kit já aberto ou despachado. O Coordenador Kit é notificado de forma privada e decide a ação necessária; o grupo geral recebe no máximo indicadores não identificáveis. Reexecução do evento de outbox é idempotente.


### 8.2. ASAAS — escrituração imediata e correção posterior


```mermaid
sequenceDiagram
  participant ASAAS
  participant API as Webhook Laravel
  participant DB as PostgreSQL
  participant T as Tesoureiro
  ASAAS->>API: Evento autenticado
  API->>DB: Inbox com chave de idempotência
  API->>DB: Cria lançamento efetivo se evento financeiro elegível
  DB-->>T: Lançamento disponível imediatamente
  T->>DB: Se houver divergência, corrige/estorna/exclui logicamente
  DB->>DB: Preserva evento, autor, antes/depois e motivo
```


A mensagem recebida não corresponde necessariamente a receita final: mapear tipos de eventos ASAAS que confirmam movimentação, reversão ou estorno, evitando duplicar a mesma transação em eventos de estado sucessivos. Lançamento manual do Tesoureiro também é efetivado no `save`, sem etapa rascunho/aprovação. Processamento técnico/retry no inbox pode estar pendente, **mas o lançamento de negócio não possui estágio “pendente de conferência”**. Correções e exclusões por UI preservam trilha e integridade do livro; para reconciliação externa preferir reversão/retificação em vez de apagar eventos.



### Configuração web de integrações e rotinas


Root excepcional e administração do Coordenador Geral/Secretário Geral podem manter **URLs, nomes de integração, endpoint de webhook, referências de credenciais, rotas, templates, horários, ativação/desativação e parâmetros não secretos** no `/admin`. Secretos devem ficar criptografados via infraestrutura de segredos/driver próprio; UI oferece troca e teste de conectividade, nunca retorno do segredo em claro, log ou export. Todo ajuste identifica conta, operador, hora, campos alterados e teste executado. Habilitar/desabilitar por integração e por rotina; job de cron verifica flag persistida antes de cada envio para evitar mensagens após desligamento. Previews dos templates devem sanitizar dados privados. **Não implementar uma plataforma visual de workflows equivalente ao n8n:** para `!ajuda`, `!vago` e outros, usar roteamento explícito PHP para handlers simples e auditáveis; cron do Apps Script vira `Schedule` + Jobs/Notifications Laravel.


### Google Drive e armazenamento opcional


O **Google Drive é a integração documental principal** para atas de serviço, PDFs, manuais e registros institucionais quando a política do grupo permitir. `documentos`/`arquivos` guardam metadados, hash, versão, `provider=google_drive`, `external_file_id`, localização lógica e visibilidade; o Laravel autentica na Drive API com credencial institucional com escopos mínimos e fornece links de acesso controlado, sem ACL pública por padrão. Drive não guarda tabelas operacionais nem substitui PostgreSQL. **MinIO/S3** é apenas opção posterior para binários privados, se volume/custo/disponibilidade justificar; não subir MinIO obrigatório. **Redis** idem: só habilitar quando fila/cache PostgreSQL demonstrar gargalo e houver definição de backup, HA e operação.


### 8.3. Evolution API: comandos e mensagens


| Operação | Contrato | Proteção |
|---|---|---|
| Receber mensagem | Webhook + ID externo + grupo | Verificação de origem/segredo; dedup por mensagem |
| Identificar comando | `!` + nome + contexto de grupo | Allowlist, escopo do comando, rate limit |
| Ler dados do grupo | Query por serviço interno com Policy | Sem usar respostas públicas para dados do kit/PII |
| Enviar mensagem | `mensagens_saida` (outbox) + job | Sem envio dentro de transação de banco; idempotência |
| Término de mandato | scheduler e regras de data | Notificações por política; evitar mensagem duplicada |
| Resumo de ata | `resumos_ata` aprovado | Não divulgar identidades/lista Zoom, contatos ou dados privados; primeiro nome/apelido só quando autorizado pela política editorial de publicação mínima e opt-out, após revisão aplicável |
| Erro permanente | Status falha + painel de reenvio | Trilha e intervenção manual |


### 8.4. Zoom: somente apoio à ata


| Dado de Zoom | Uso | Regra |
|---|---|---|
| ID de reunião e event ID | Correlacionar e deduplicar | Indexar por identificador de integração |
| Eventos de entrada/saída | Calcular sugestão agregada | Reter somente pelo período tecnicamente necessário |
| Identificadores de participantes | Deduplicação transitória quando imprescindível | Não migrar para `pessoas` nem exibir individualmente a servidores comuns |
| Contagem sugerida | Pré-preenchimento da ata | Secretário pode corrigir com justificativa |
| Falha de evento | Campo de conferência manual | Ata continua utilizável |


## 9. Plano técnico para migrar todas as fontes


### Inventário de origem para destino


| Fonte | Exemplo de dado / função | Tabela ou serviço de destino | Validação de paridade |
|---|---|---|---|
| Google Forms / `Responses` | Data, horário, secretário, coordenador e contagens | `ocorrencias_reuniao`, `atas` | Total por data/horário, checagem de tipos e números |
| Respostas de visitas | Origens, quantidades | `visitas`, `origens_visita` | Soma por período = relatório legado, com exceções justificadas |
| Respostas de ingressos | Nome, contato, data | `ingressos`, `kits_ingresso`, `contatos_kit` | 1 entrada válida = registro coerente, sem obrigar login |
| Respostas de conquistas e temáticas | Tipo, quantidade e tema | `conquistas`, `tipos_conquista`, `tematicas` | Número de conquistas/temas por ocorrência |
| Planilha Kit Ingresso | Contato, CEP, endereço, status e tracking | `kits_ingresso`, `enderecos_kit`, `remessas`, `eventos_rastreio` | Estados e remessas em aberto reconciliados |
| Planilha de Serviço | Titulares, início/fim, vagas | `encargos`, `designacoes_servico`, `escalas_reuniao` | Encarregados, vagas e vencimentos por reunião |
| Atas de Serviço / PDFs | Histórico de reuniões, decisões e anexos | `reunioes_servico`, `atas_servico`, `decisoes_coletivas`, `arquivos` | Documento preservado e vinculado |
| Tesouraria Sheets + ASAAS | Datas, valores, categorias, IDs externos | `lancamentos_financeiros`, `eventos_asaas`, `contas_financeiras` | Totais por período e saldo de abertura/fechamento |
| Docs VAGO / Relação | Visão textual de encargos | Consulta/view sobre `designacoes_servico` | Resultado equivalente sem cópia manual |
| Docs Consciências | Texto aprovado e histórico | `decisoes_coletivas`, `documentos`, `arquivos` | Integridade e visibilidade preservadas |
| Looker Studio | Resumo, Temáticas, Visitas, Kit, Quadro | Consultas/views PostgreSQL + dashboards Laravel | Filtros e fórmulas comparados com captura de referência |
| Apps Script `ata-resumo` | Geração/disparo de resumo | Eventos Laravel + `resumos_ata` + outbox | Mesmo significado e texto com revisão |
| Apps Script `ata-kitingresso` | Criação automática de kit | Service `CriarKitPorIngresso` | 1 kit por ingresso confirmado, sem duplicação |
| Apps Script `kit-ingresso` | CEP, status, mensagem e rastreio | Jobs Kit + Correios REST | Testes da máquina de estados e mensagens |
| Apps Script `servico-terminio` | Avisos 30/15/7/3/2/1 e encerramento | Scheduler Mandatos + Outbox | Datas/avisos/estados equivalentes |
| n8n `Robô` | Comandos WhatsApp | Handler de comandos Laravel | Comandos e permissões por grupo comparados |
| n8n divulgação, resumos e servidores | Notificações periódicas | Jobs + templates + audiência | Envio único, segurança, rastreabilidade |
| n8n tesouraria | Webhook financeiro → planilha | Inbox ASAAS + classificação + reconciliação | Mesmo evento não duplica transação |


### Etapas de importação e virada


```mermaid
flowchart TD
  A[Exportações imutáveis + hashes] --> B[Inventário completo de arquivos / abas / scripts]
  B --> C[Normalização e importações de teste]
  C --> D[Relatório por lote: importado, ignorado, erro]
  D --> E{Divergências?}
  E -->|Sim| F[Revisão humana / correção de mapa]
  F --> C
  E -->|Não| G[Testes de fluxos e automações]
  G --> H[Restore + teste de contingência]
  H --> I{Aceite pelo responsável?}
  I -->|Não| F
  I -->|Sim| J[Cutover assistido por módulo]
  J --> K[Legado read-only e backup conservado]
```


**Invariantes de migração:** `UNIQUE(fonte,linha/hash)` para idempotência por lote; numeração original preservada em tabela de proveniência; normalização não apaga o texto original em snapshot seguro; mesclagem de homônimos precisa de aprovação; relatórios não devem misturar atas em rascunho/anuladas com registros oficiais sem critério expresso.


### Conversão de fórmulas do Looker em métricas explícitas


| Métrica | Definição inicial | Cuidados |
|---|---|---|
| Reuniões | Contagem de atas válidas conforme status | Não confundir reunião agendada com ata enviada |
| Membros por reunião | Média de `membros_total` em atas válidas | Não criar histórico nominal |
| Visitas | Soma de `atas.visitas_total` nas atas oficiais; detalhes por origem em `visitas` | Não adicionar o mesmo visitante duas vezes nem perder visitas sem origem detalhada |
| Ingressos | Soma de `atas.ingressos_total` nas atas oficiais; `ingressos` fornece detalhes e vínculo do Kit | Total existe mesmo se a pessoa preferiu não registrar nome |
| Conquistas | Soma de `atas.conquistas_total` nas atas oficiais; `conquistas` discrimina nomes/tipos quando informados | Não exigir cadastro nominal nem somar o declarado outra vez |
| Mandatos vagos | Encargos/escalas sem titular ativo conforme data | Não depender de documento `VAGO` |
| Saldo | **Saldo inicial + entradas efetivas − saídas efetivas ± estornos/retificações efetivas** | Conciliação identifica divergências a posteriori; não retém entrada válida em estado preliminar |


As fórmulas concretas do Looker exigem comparação com os relatórios originais durante a migração; estas regras são um **modelo inicial**, não alegação de equivalência já verificada.


## 10. Infraestrutura Docker, Swarm e segurança operacional


| Serviço | Ambiente local | Produção Docker/Swarm | Persistência |
|---|---|---|---|
| Laravel/Apache | Compose `app` | Imagem compilada e versionada imutável | Efêmera; storage compartilhado/externo se necessário |
| PostgreSQL 17 | Compose `postgres` sem porta host por padrão | Serviço persistente / infraestrutura externa já aprovada | Volume cifrado/backup externo testado |
| Queue worker | Compose profile `filas` | Réplica(s) após validação | Jobs no PG; idempotência |
| Scheduler | Compose profile `scheduler` | Coordenar instância responsável | Lock/sem repetição de tarefas |
| Redis | Opcional | Opcional conforme métricas | Não é fonte de negócio |
| NotebookLM-py REST/CLI/MCP | Adaptador de desenvolvimento e testes | Serviço privado no Swarm, perfil autenticado protegido, versionado | Credenciais/perfil em volume e segredo separados |
| Correios Rastreamento | Container auxiliar em rede privada | Serviço privado separado, com API_TOKEN e health check | Histórico auxiliar descartável/controlado |
| **Cloudflare Tunnel (`cloudflared`)** | Opcional em dev | **Entrada pública obrigatória da produção**, serviço sem portas ingress publicadas, token via Docker Secret | Token não entra na imagem/Stack em claro |
| Proxy / TLS | Proxy local opcional | HTTPS termina na borda Cloudflare; salto interno Tunnel → Laravel/proxy pela rede privada, com TLS interno opcional validado | Cabeçalhos confiáveis configurados |
| Arquivos privados | Volume local protegido | Armazenamento persistente privado | Backup e controle de acesso |
| Backup | Teste `pg_dump` + arquivos | Rotina independente com verificação e ensaio de restore | Fora do volume único e fora da rede pública |


### Topologia de produção: GrupoQuarenteNA.org e Cloudflare Tunnel

```mermaid
flowchart LR
  I[Internet: visitantes e servidores] --> CF[Cloudflare DNS + HTTPS + WAF]
  CF --> H[Hostname GrupoQuarenteNA.org]
  H --> TU[Túnel autenticado: cloudflared]
  subgraph SW[Docker Swarm - overlay privada]
    TU --> APP[Laravel / Apache]
    APP --> DB[(PostgreSQL 17)]
    WORK[Workers] --> DB
    SCHED[Scheduler unico/lock] --> DB
    WORK --> EX[Integracoes privadas Correios / NotebookLM]
  end
```

| Controle | Contrato e verificação de implantação |
|---|---|
| DNS e hostname | Zona Cloudflare do **GrupoQuarenteNA.org** e publicação do hostname de produção no Tunnel; rota `www` apenas se optar por redirecionamento para o domínio canônico |
| Modo de túnel | Recomenda-se túnel **remotamente gerenciado**; `cloudflared` compatível com `--token-file /run/secrets/<secret>` e serviço Swarm ligado à mesma rede overlay do Laravel/proxy |
| Segredos | Token do Tunnel em Docker Secret/cofre externo; nunca em `.env` commitado, logs, `docker service inspect` com variável em claro ou argumentos expostos |
| Origem | Endpoint interno do serviço Laravel/Apache ou proxy privado por DNS da rede Swarm (ex.: `http://app:80`), **não `localhost` do container cloudflared**; se HTTPS interno, verificar certificado e hostname |
| Exposição | Nenhuma porta publicada para Laravel, PostgreSQL, workers, scheduler, IA, rastreamento, Portainer ou painel de infraestrutura; conexões externas iniciadas pelo Tunnel |
| Headers e sessão | Laravel atrás de proxy confiável, `APP_URL=https://grupoquarentena.org`, cookie `Secure`, HTTPS redirecionado, hosts permitidos, cabeçalhos `X-Forwarded-*` confiados somente a proxies designados |
| Superfícies | Home, agenda, Zoom e Fale Conosco públicos; `/admin` exige autenticação e Policies internas; regra Cloudflare Access **não deve bloquear todo o site público** |
| Webhooks | Caminhos ASAAS/Zoom/Evolution acessíveis sem desafio interativo de bot quando apropriado, com validação de autenticidade no backend, rate limit, deduplicação e filtros de origem |
| Cache | Nunca armazenar em CDN respostas de `/admin`, sessão, Fale Conosco, webhooks, dados pessoais ou respostas privadas; testar regras de cache |
| Disponibilidade | Health checks da aplicação e `cloudflared`; duas réplicas do conector onde houver infraestrutura de nós e necessidade de HA, sem confundir réplicas no mesmo host com resiliência de hardware |
| Recuperação | Ensaio de perda de Tunnel/DNS, rotação de token, falha de backend, backup/restore PostgreSQL; registrar procedimento sem expor credenciais |

O Tunnel publica **HTTP(S)**, não dá acesso remoto automaticamente ao banco ou SSH. Mudanças em DNS/Cloudflare ou rotas de rede não devem ser presumidas operantes apenas por estarem especificadas neste planejamento.

**Referências oficiais:** [Publicar aplicações](https://developers.cloudflare.com/cloudflare-one/networks/connectors/cloudflare-tunnel/routing-to-tunnel/), [parâmetro `token-file`](https://developers.cloudflare.com/cloudflare-one/networks/connectors/cloudflare-tunnel/configure-tunnels/run-parameters/), [alta disponibilidade de Tunnel](https://developers.cloudflare.com/tunnel/configuration/).

### Regras do template a respeitar


**Diretriz documental:** documentação **permanente** de implementação vive em `AGENTS.md` e `docs/banco/db.md` (gerado), decisões do produto em apenas cinco Markdown. Não criar pastas `docs/research`, `docs/decisions`, `docs/status`, duplicatas de arquitectura, nem dezenas de logs narrativos por agente. Histórico exato das mudanças já existe no Git/PRs e nas migrations. Os workflows BMAD podem usar arquivos temporários operacionais durante execução, mas devem manter como canônico apenas o material indispensável ao handoff.




- Manter `America/Bahia` como timezone IANA em PHP, Laravel, testes e containers; usar timestamps adequados no PostgreSQL, nunca offset fixo em configuração de regra de negócio.
- `postgres:5432` via DNS interno Compose; **não publicar 5432** em rede pública.
- `database/schema/pgsql-schema.sql` e `docs/banco/db.md` são **gerados do PostgreSQL real** após migrations, por `./scripts/banco-atualizar.sh`, não copiados deste documento.
- Nunca executar `schema:dump --prune` automaticamente e nunca alterar uma migration já aplicada para esconder evolução.
- Gates: `./scripts/testar.sh` (Pint, Larastan/PHPStan, Unit, Feature); `./scripts/testar.sh --postgres` em mudanças de banco e release; `git diff --check`.
- Laravel Boost: usar o wrapper oficial do template em ambiente compatível, manter guidelines `.ai/` e `AGENTS.md`, sem duplicar instruções conflitantes.
- BMAD: arquivos derivados em `_bmad-output/`, instalação do pacote em `_bmad/` recriável e não versionada; não tratar saída textual desta conversa como execução dos workflows.
- Separar dev/prod, proteger `.env` e Docker Secrets; logs não carregam segredos, dados de contato, código de acesso financeiro ou eventos Zoom não necessários.


## 11. Segurança, ameaças e controles


| Ameaça | Limite afetado | Controle verificável |
|---|---|---|
| Exposição de telefone/endereço | Kit ingresso | Colunas criptografadas, Policies, logs redigidos e teste de acesso negado |
| Reuso de token API | Integrações | Segredo separado por ambiente, rotação e identidade do chamador |
| Eventos ASAAS repetidos | Tesouraria | `UNIQUE` externo + transação única + replay test |
| Open redirect pelo domínio público | Links externos | HTTPS/host autorizado; não aceitar URL arbitrária do usuário em redirect |
| Falsa identificação Zoom | Atas/privacidade | Proibir merge Zoom→pessoa; contagem apenas sugerida |
| Falha ou mudança no scraper Correios | Kit | Timeout, retries, monitoramento e consulta manual |
| Conta técnica usada indevidamente | Administração | Break-glass temporal, 2º fator conforme solução, auditoria e revisão de concessões |
| Alteração financeira sem justificativa | Tesouraria | Registros imutáveis de ajuste, histórico, segregação de função |
| Job duplicado | Mensagens/mandatos/kit | Chave de idempotência, lock e transação para estado oficial |
| Planilha de origem alterada após export | Migração | Hash do snapshot e metadados do lote |
| Exposição pública por consulta/cache | Relatórios | Projeções públicas explícitas, views aprovadas, testes de dados ausentes |
| Backup inacessível | Continuidade | Restore periódico em ambiente isolado, inventário dos artefatos |


A base normativa da proteção de dados pessoais inclui a **LGPD** (Lei 13.709/2018) e os princípios de anonimato do grupo. É necessário documentar finalidade, acesso, duração de guarda e descarte por classe de informação, especialmente dados de ingressantes e eventos Zoom. O documento não inventa período de retenção sem decisão de governança.


## 12. Estratégia de testes e aceitação


| Nível | Exemplos | Evidência |
|---|---|---|
| Unit | Status de ata, cálculo de prazo, composição de resumos, mapeamento de evento Correios | Tests Unit |
| Feature | Login, permissão, edição, formulário de ata, painel/relatórios | Tests Feature |
| Integration PG | FK/UNIQUE/CHECK, retries, concorrência de webhook, idempotência de kit | Teste PostgreSQL descartável |
| Contrato API | Respostas simuladas Zoom/ASAAS/Evolution/Correios e timeout | Fixtures anonimizadas |
| Segurança | 403, cache, cross-user, escopo de kit e finanças | Testes positivos/negativos |
| Migração | CSV/JSON de legado com homônimos e dados faltantes | Comparação de totais e hashes |
| E2E/UX | Ata no smartphone, correção, relatório e integração indisponível | Ensaio observado com servidores |
| Recuperação | Restore de PG + storage e recálculo de indicadores | Relatório de exercício |
| Release | `testar.sh` + `--postgres`, migração staged, exportação e contingência | Checklist de readiness |


### Critérios de saída por domínio


| Domínio | Gate mínimo |
|---|---|
| Público | Zoom encontrado sem JS e sem login; links confiáveis; acessibilidade ensaiada |
| Atas | Revisão + envio único + correção/auditoria + rascunho persistido |
| Kit | Dados privados restritos; rastreio via Correios integrado; falhas recuperáveis |
| Tesouraria | Sem duplicidade financeira, conciliação e agregados sem contribuinte |
| Serviço | Vagas, mandatos e alertas equivalentes ao legado |
| Comunicação | Comandos autorizados, mensagens idempotentes, sem exposição de contatos |
| Migração | Todas as fontes ativas inventariadas, perdas explicadas, paridade aprovada |
| Operação | Restore, backup, segredos e health checks documentados e testados |


## 13. Decisões registradas e pontos de confirmação técnica


| Decisão | Estado | Referência |
|---|---|---|
| Laravel adaptado do template fornecido | **Definida pelo usuário** | Repositório `cirinho/template-laravel-bmad` |
| PostgreSQL como fonte de verdade | **Definida pelo usuário** | Brief/PRD aprovados |
| Docker para produção | **Definida pelo usuário** | Brief/PRD aprovados |
| BMAD 6.12.4 e Laravel Boost | **Definida pelo usuário** | Template e método |
| Correios `opastorello/correios-rastreamento` | **Definida pelo usuário** | Repositório do serviço |
| PHP 8.5, Laravel 13, PostgreSQL 17 | **Fundação homologada na #33 / PR #34** | Baseline `4ad7814`; versões travadas no lock, runtime e evidências da #33; revalidar ambiente local antes da implementação |
| Modular monolith e banco único | **Recomendação técnica** | Evita complexidade distribuída precoce |
| Queue via PostgreSQL inicialmente | **Recomendação técnica** | Reavaliar por volume e confiabilidade |
| **63 entidades de domínio candidatas** | **Modelo lógico aprovado, sujeito a materialização por Story** | Tabelas físicas só existem após migrations executadas e testes PostgreSQL; não gerar todas antecipadamente |
| Paleta visual/estrutura de navegação | **UX aprovado** | Logo original preservada; exportações vetoriais requerem revisão de fidelidade |
| Permissões de encargos/fluxos financeiros e do kit | **Aprovadas pelo usuário** | Políticas de retenção e detalhes de contingência continuam configuráveis |


## 14. Próximo passo BMAD


`Arquitetura.md` constitui o **spine aprovado** e catálogo de dados para alimentar os próximos contratos de implementação, mas **não é resultado de executar `bmad-architecture` dentro do repositório**. Em implementação, a saída oficial do workflow deve herdar as decisões transversais aqui registradas, fazer inspeção do commit do template efetivamente adotado e citar as migrations geradas.


Continuidade após a fundação: conferência cruzada dirigida na #35 → entrada local explícita `quarentena-backlog`, com critérios metodológicos de `bmad-create-epics-and-stories`, índice `epics.md` e detalhamento em `epics/epic-NN.md`, conforme [contrato operacional B](../../docs/git-workflow-bmad.md#backlog-b). Os overrides oficiais carregam o contrato, mas não substituem os passos monolíticos do pacote; não invocar o criador original para regenerar o índice. Os 15 Epics e 101 Stories estão aprovados como planejamento condicionado (1–5 em 23/10 e 6–15 em 24/10/2026); a elaboração documental está encerrada, sem declarar prontidão global enquanto os gates pertinentes permanecem abertos. A avaliação de prontidão utiliza `bmad-sprint-planning`, na intenção exclusivamente de readiness: avaliar, apresentar o parecer e encerrar sem gerar `sprint-status.yaml`, Stories de implementação ou iniciar build. Sprint planning e geração de tracking dependem de decisão posterior e do gate aplicável. Homologar runtime/Boost do checkout escolhido antes da implementação; aceite documental não resolve decisões institucionais pendentes. `bmad-spec` é opcional quando necessário ao contrato de execução, não um documento obrigatório por Epic. Não iniciar deploy nem migração definitiva só pela aprovação documental.


**Referências:** [BMAD 6.12.4 — arquitetura e UX](https://github.com/bmad-code-org/BMAD-METHOD/blob/v6.12.4/docs/plan/design-ux-and-architecture.md), [requisitos/specs](https://github.com/bmad-code-org/BMAD-METHOD/blob/v6.12.4/docs/plan/define-requirements-and-a-specification.md), [readiness](https://github.com/bmad-code-org/BMAD-METHOD/blob/v6.12.4/docs/plan/break-work-into-stories-and-track-it.md), [template](https://github.com/cirinho/template-laravel-bmad), [Correios rastreamento](https://github.com/opastorello/correios-rastreamento).

---

## Síntese de contratos transversais e critérios de verificação (sem catálogo paralelo)

### 1. Modelo de identidade e controle de acesso

```mermaid
erDiagram
  pessoas ||--o| usuarios : "pode_ter"
  pessoas ||--o{ ingressos : "referenciada"
  pessoas ||--o{ conquistas : "referenciada"
  pessoas ||--o{ designacoes_servico : "serve"
  usuarios ||--o{ sessoes_elevadas : "eleva"
  usuarios ||--o{ auditorias : "autor_humano"
  designacoes_servico }o--|| encargos : "encargo"
```

- Todo `usuario` humano possui `pessoa_id FK NOT NULL UNIQUE`; a pessoa pode existir sem login. Contas técnicas do template **não entram em `usuarios`**: provedores/jobs são atores de integração identificados nos eventos e na auditoria. `root` não é uma segunda identidade de login, é **estado efêmero de elevação da sessão pessoal**.
- Buscar por normalização de nomes e atributos auxiliares, jamais usar nome/telefone como PK ou fundir automaticamente homônimos.
- Criar FK opcional `pessoa_id` em ingresso, conquista, temática e participantes operacionais conforme confirmação humana; preservar `nome_historico`/`nome_exibicao_original` da ata para evitar alteração retroativa da evidência.
- O usuário pode manter login após encerramento, mas Policies recusam capacidades de mandato expirado independentemente de cron. O scheduler atualiza vagas e notifica; não é fonte do controle de acesso.
- Elevação root: usuário autenticado + segredo institucional protegido + MFA + justificativa + TTL curto configurável + identificação vinculada à sessão; acesso integral excepcional incluindo Tesouraria e Kit; grava auditoria de leitura de dados sensíveis, mudanças e entrada/saída da elevação. Impedir sessão root anônima ou login direto. Não usar concessão persistente de papel root ao usuário.

### 2. Auditoria forte e proteção de dados

**Alcance aprovado na #35 (RF-AUDITORIA-001/007/008):** auditar todas as mutações persistentes de negócio, identidade, mandatos, permissões, configurações/credenciais/integrações, em HTTP, CLI, jobs ou provedores. O contexto diferencia ator pessoal autenticado e ator técnico identificado; não fabricar usuário humano para automação. Registrar eventos próprios de autenticação/login, negações relevantes, autorização, leitura sensível e elevação mesmo sem mutação de negócio, sem registrar os valores secretos.

Não auditar por linha as atualizações mecânicas de sessões/cache/filas, nem fazer a inserção de `auditorias` disparar auditoria recursiva. Isso não dispensa eventos de segurança nesses componentes nem a auditoria do efeito de negócio produzido por um job. Aplicar o escopo explicitamente aos serviços/entradas e às gravações relevantes fora do ORM, não um observador recursivo de qualquer escrita SQL. As mutações de negócio e suas trilhas falham juntas: rollback integral se a auditoria não persistir, inclusive no bootstrap com sua marca permanente. Eventos de tentativa negada devem refletir rejeição, não uma alteração confirmada que sofreu rollback. O detalhe de autenticação/autorização negada e sua finalidade/retenção pertence ao contexto de segurança, sem carregar PII ou senha para mensagens/logs.

```mermaid
sequenceDiagram
  participant O as Operador ou job
  participant L as Laravel Service
  participant D as PostgreSQL
  O->>L: Comando ou webhook
  L->>D: BEGIN
  L->>D: Salva mutação de negócio
  L->>D: Insere auditoria com ator e snapshot redigido
  L->>D: Insere evento_outbox pendente na mesma transação
  D-->>L: COMMIT atômico de negócio, auditoria e outbox
  L-->>O: Confirma alteração
  Note over L,D: Worker publica depois do commit; retries usam chave idempotente
```

Recomenda-se tabela append-only `auditorias` com `ator_tipo` (`usuario`, `integracao`, `job`, `sistema`), `ator_usuario_id?`, `origem`, `correlacao_id`, `ip_origem?`, `user_agent?`, `entidade`, `entidade_id`, `acao`, `antes_redigido jsonb?`, `depois_redigido jsonb?`, `justificativa?`, `ocorrido_em timestamptz`, `sessao_elevada_id?`, `versao`. Registros de leitura/revelação de dados restritos entram em eventos específicos, sem capturar os valores secretos. Usar triggers de banco quando cobertura de mutações fora do ORM for necessária; os Services Laravel registram contexto humano/jog e resultado sem expor dados. Mesmo administrador root não deve ter mecanismo de edição normal desses logs. **Timestamps com UTC em `timestamptz`; exibição em `America/Bahia`**.

Soft delete em registros institucionais, controle de versões e auditoria com antes/depois; retenção de informações pessoais limitada à finalidade e eventual eliminação/anonimização efetiva quando exigível, inclusive backups e logs conforme política. Os segredos nunca entram em `jsonb` de auditoria.

### 3. Checklist de esquemas e invariantes já integrados à seção 6

| Modelo | Campos/invariantes incorporados no dicionário principal | Origem da regra |
|---|---|---|
| `atas` | `enviada_por_usuario_id`, `enviada_em`, `visitas_total`, `ingressos_total`, `conquistas_total`, `setima_tradicao_valor`, `setima_tradicao_atualizada_em`, `despesas_valor`, `despesas_descricao`, `eleicoes`, `informacoes_adicionais`, `observacoes`; totais nulos no rascunho, inteiros >=0 no envio | RF-ATA-028, migração histórica e assistente |
| `designacoes_servico` | `horario_reuniao_id?`, `pessoa_id NOT NULL`, data inicial/final, estados **somente** `ativo`, `concluido`, `entregue`, `destituido`; `VAGO` é ausência de designação ativa e vigente, sem registro fantasma | Escala por dia/horário e mandatos do grupo |
| `pessoas` | Nome/apelido, chaves de busca, contato opcional criptografado e local geral; nunca chave natural de identidade | Cadastro contínuo e reconciliação |
| `ingressos`, `conquistas` | `pessoa_id?`, texto original, nome público mínimo, `publicacao_status` em_triagem/liberado na migração e flag de ocultação opt-out | Histórico saneado antes de liberação; novos oficiais publicáveis por padrão |
| `lancamentos_financeiros` | `descricao text NOT NULL`: PIX entrada = “7ª Tradição - PIX recebido”; despesa = mensagem sanitizada quando disponível; retificação só Tesoureiro ou root elevado | Contabilização imediata e auditoria posterior |
| `reunioes_servico`, `atas_servico`, `revisoes_ata_servico`, `arquivos` | `presentes_total`, `faltas_total`, `justificadas_total`, `interessados_total`; rascunho/finalização/correção/anulação; snapshots e PDFs versionados; arquivo anterior preservado | Geração RSG, relatórios por totais e atas PDF no Drive |
| `auditorias` | Ator humano/automação, dados antes/depois redigidos, correlação, origem, elevação | Responsabilização e rastreabilidade |
| `eventos_zoom` | `nome_exibicao_cipher?`, identidade efêmera, expurgo por TTL configurável (default 365 dias) | Conferência nominal restrita e possíveis duplicações |
| `contatos_kit`, `grupos_mensagem` | Blind index HMAC com chave derivada distinta da APP_KEY para buscas normalizadas, valor cifrado na aplicação | Busca autorizada sem descriptografar a tabela inteira |
| `eventos_asaas` | Chave de evento externa única e vínculo único ao lançamento quando elegível; não reprocessar efeitos | Webhook at-least-once com lançamento uma vez |
| `revisoes_ata`, `escalas_reuniao` | `UNIQUE(ata_id, revisao)` e unicidade de escalas ativas por ocorrência/função | Concorrência e coerência |

### 4. Automações: engine declarativa básica

```mermaid
flowchart LR
  T[Gatilho: evento/webhook/agenda] --> C{Condições declarativas}
  C -->|Sim| A[Ações permitidas no catálogo]
  C -->|Não| X[Ignorar/registrar]
  A --> O[Outbox e auditoria]
  O --> E[Provedor autorizado]
```

As entidades `rotinas_programadas`, `gatilhos_automacao`, `condicoes_automacao`, `acoes_automacao`, `destinos_mensagem`, `credenciais_integracao` e `eventos_outbox` estão integradas no dicionário da §6.7; `solicitacoes_atendimento` está na §6.8 e `templates_ata_servico` na §6.4. `eventos_webhook` é o envelope comum; `eventos_asaas`/`eventos_zoom` são detalhes de domínio com idempotência própria e não uma segunda entrada pública independente. **Não permitir PHP, SQL ou shell editável pelo painel.** As ações são handlers Laravel registrados e versionados, com validação de permissões antes do disparo e confirmação/controle de destino. Restrição padrão: Coordenador Geral/Secretário Geral no geral; Tesoureiro em rotinas financeiras e Coordenador Kit em rotinas de remessas; root elevado excepcional pode tudo.

- ASAAS: verificar assinatura/segredo aplicável, guardar identificador externo único, classificar o evento, inserir lançamento **efetivo imediatamente** para evento elegível; correção manual posterior pelo Tesoureiro é versionada. Exceção root elevada audita a intervenção. Remover a seção antiga duplicada de conciliação prévia e não construir workflow de aprovação.
- Evolution: autenticar webhook, deduplicar por mensagem externa, interpretar comando por grupo com rate limit. `!saldo` usa vista pública financeira; `!senha` restringe-se a grupo de servidores e permissão efetiva, com registro de revelação; `!kit` não migra. Texto da divulgação vem da agenda e dispara a 15 minutos de cada ocorrência, incluindo turnos múltiplos.
- Ata: salvamento progressivo por versão, registro de evento/outbox **na mesma transação do envio oficial** e publicação posterior; retificação **somente pelo usuário que enviou**, **Coordenador Geral** ou **Secretário Geral** (ou root elevado), com auditoria e correlação às mensagens. O reenvio deve ser ação explícita/política configurada, nunca reexecução acidental.
- Mandatos: valores padrão de seis meses para moderadores e um ano para Mesa, alertas configuráveis 90/60/30/15/7/3/2/1 dias e conclusão; status e vaga derivados do domínio de designações; scheduler reconcilia projeções mas não autoriza acesso.
- Fale Conosco: protocolo, contato opcional protegido, notificação de triagem restrita sem PII e decisão exclusivamente humana.

### 5. IA, NotebookLM e implantação

| Componente | Contrato | Operação segura |
|---|---|---|
| IA compatível OpenAI | Base URL, modelo, token, timeout, testes e parâmetros por tarefa | Chaves cifradas/secrets, sem conteúdo sensível em logs |
| NotebookLM-py REST | Client PHP HTTP para proxy privado de notebooks | Tokens, API interna e monitoramento; integração substituível |
| NotebookLM MCP/CLI | Administração/integradores, quando necessários | Não expor diretamente ao visitante, volume de perfil protegido |
| Notebook treinamento | Fontes criadas/atualizadas manualmente pelos responsáveis | Conteúdo distribuído inicialmente em grupos autorizados |
| Notebook meditação | Prompt e rotina de mensagem ao grupo | Desativado por padrão; envio e horário configuráveis |
| Notebook literatura | Respostas públicas baseadas em fontes autorizadas, com citação | Rate limit, segregação de notebooks e política de licenças |

Produção em **GrupoQuarenteNA.org** via **Cloudflare Tunnel em Docker Swarm**, com Laravel, PostgreSQL, worker, scheduler, NotebookLM-py REST/MCP/CLI em serviços privados segregados, rastreamento Correios e Redis somente se necessário. Cuidado com concorrência sobre `master_token.json` e perfil Google mutável; proteger em volume privado e implementar reautenticação/monitoramento. A execução do NotebookLM-py usa serviço remoto, não é modelo local. Fixar versão homologada, testar rotas reais e ter fallback quando Google/NotebookLM estiver indisponível. Fuso institucional `America/Bahia`, armazenamento de instantes UTC.
