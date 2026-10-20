---
title: "PRD.md — Requisitos da Plataforma QuarenteNA"
project: Grupo QuarenteNA — Plataforma Web
artifact: prd
method: "BMAD 6.12.4 — artefato canônico de planejamento"
status: "APROVADO"
date: "2026-10-18"
updated: "2026-10-19"
revision: "Integração de contratos e auditoria cruzada BMAD 6.12.4"
source_window: "2026-03 a 2026-05"
---


# PRD.md — Requisitos da Plataforma QuarenteNA


> **Situação: APROVADO.** Documento único e canônico de requisitos. Aprovação do escopo não substitui comprovação de execução, homologação das integrações, testes de segurança nem validação operacional da migração.


## 1. Declaração do produto, escopo e status


A plataforma terá **home pública sem autenticação** e **painel `/admin` protegido**, consolidando os processos de ata, serviço, kit ingresso, tesouraria, relatórios, permissões e integrações atualmente distribuídos. O objetivo é um produto operacional completo, a ser construído por incrementos verificáveis e ativado somente após migração/reconciliação; **todo fluxo de planilha, script ou automação legada com função ativa deve ter equivalente ou descontinuação formal aprovada**.


**Status de aprovação: APROVADO.** Este documento define *o quê, para quem e com qual condição de aceite*. Laravel (adaptado do template oficial indicado pelo projeto), PostgreSQL e Docker são decisões de tecnologia aprovadas; schema físico, limites de domínio, filas e infraestrutura são detalhados em `Arquitetura.md`. Não executar migração nem expor dados particulares por inferência documental.


### Princípios e objetivos mandatórios


| Princípio | Interpretação de requisito |
|---|---|
| Serviço e consciência coletiva | Software não toma decisões sociais/espirituais em lugar do grupo |
| Site acessível | Zoom e agenda encontram-se sem login e sem depender de API externa na renderização crítica |
| Fonte autoritativa | Alteração operacional refletida nos módulos interessados sem cópias manuais conflitantes |
| Cadastro contextual | Pessoa operacional não precisa ser membro Zoom nem ter credenciais |
| Dados mínimos | Exposição pública limitada a agregados e primeiro nome/apelido de ingressante editorialmente permitido; nunca contato/endereço |
| Auditabilidade | Operações de escrita produzem histórico autorizável/consultável |
| Reversibilidade | Exportação e restore reais, não apenas documentação de backup |
| Automação humana | Sugestão e automação nunca impossibilitam revisão humana |
| Uso móvel | Secretário conclui ata pelo celular, sem manipular planilhas |
| Single-group | Reuso por fork, não multi-tenancy/SaaS nesta entrega |


### Alcance por papel


| Papel / entidade | Acesso geral esperado | Restrições |
|---|---|---|
| Visitante ou membro público | Home, informações, links e relatórios autorizados | Sem cadastro; somente apelidos/primeiros nomes de ingressantes autorizados, sem contato/endereço |
| Pessoa no cadastro operacional | Registro de contexto | Pode existir sem login; se passar a operar o painel como servidor, recebe conta pessoal `usuarios` vinculada à pessoa |
| Servidor autenticado | Ata/consulta e módulos conforme encargo | Sem acesso automático a kit ou finanças |
| Mesa | Gestão e supervisão do grupo | Auditoria e controles proporcionais; não elimina separação de deveres |
| Tesoureiro / Coordenador Kit / RSG / RSG Suplente | Capacidades estritamente vinculadas à designação vigente | Tesoureiro exclusivo em finanças; Coordenador Kit exclusivo no kit |
| Admin técnico (Coordenador Geral/Secretário Geral) | Usuários, integrações, configurações e auditoria | Não tem edição financeira nem acesso operacional ao kit |
| Conta institucional de contingência | Recuperação controlada | Procedimento break-glass, controles fortes e trilha específica |


### Diagrama de contexto funcional


```mermaid
flowchart TB
  PUBLIC[Visitante / Home pública] --> CORE[Plataforma PHP - v2]
  STAFF[Servidores / Mesa] --> AUTH[Autenticação e autorizações]
  AUTH --> CORE
  CORE --> DB[(PostgreSQL - operações)]
  CORE --> DASH[Relatórios e painéis públicos / internos]
  CORE --> Q[Jobs / fila / integrações]
  Q <--> Z[Zoom]
  Q <--> E[Evolution API]
  Q <--> A[ASAAS]
  Q <--> K[Correios / rastreio / CEP]
  DB --> EXPORT[Backups / exportação / contingência]
  DB --> AUD[Auditoria restrita]
```


### Fluxo de dados e limites de visibilidade


```mermaid
flowchart LR
  Z[Eventos Zoom com identificadores eventuais] --> P[Área restrita de conferência]
  P -->|contagem sugerida| A[Ata editável pelo servidor]
  A -->|aprovada| D[(Registros operacionais)]
  D --> R[Indicadores agregados]
  R --> PUB[Relatórios públicos sem PII]
  D --> ADM[Painéis restritos autorizados]
  AUD[Auditoria de alterações] --> ADM
  P -. proibido .-> PUB
```


## 2. Jornadas essenciais e casos de uso


| Jornada | Gatilho | Resultado e controles |
|---|---|---|
| Recém-chegado | Acessa domínio público | Localiza próximo horário, tipo, Zoom e orientações sem login |
| Secretário | Reunião terminou ou está ocorrendo | Ata abre com sugestões; altera, guarda rascunho, revisa e envia |
| Autor que enviou a ata, Coordenador Geral ou Secretário Geral | Precisa retificar ata enviada | Justificativa, versão e recálculo de indicadores com autoria rastreável; demais encargos da Mesa não herdam esse direito |
| Tesoureiro | Chega evento ASAAS | Evento validado/deduplicado e **lançamento efetivo imediato** quando elegível; divergência posterior é retificada/estornada pelo Tesoureiro com auditoria |
| Kit ingresso | Ata registra ingresso | Pendência criada; dados privados consultados só por autorizado; expedição acompanhada |
| Membro WhatsApp | Envia comando permitido | Resposta coerente com dados atuais, com controle de grupo, permissão e abuso |
| Responsável técnico | Falha operação ou serviço externo | Recupera estado, analisa logs, exporta, reconcilia ou aciona contingência |


### Máquina de estados de uma ata (modelo funcional proposto)


```mermaid
stateDiagram-v2
  [*] --> Rascunho
  Rascunho --> Enviada: revisão e envio
  Enviada --> Revisada: conferência
  Enviada --> Corrigida: alteração justificada
  Revisada --> Corrigida: alteração justificada
  Enviada --> Anulada: cancelamento auditado
  Revisada --> Anulada: cancelamento auditado
  Corrigida --> Anulada: cancelamento auditado
  Corrigida --> Revisada: nova conferência
  Anulada --> [*]
```


> A máquina de estados é o contrato funcional adotado para desenho e testes. A matriz de permissões operacional e condições específicas de alteração serão configuradas conforme as regras vigentes do grupo, sem inventar poderes deliberativos.


## 3. Requisitos funcionais — catálogo canônico


**Convenção utilizada:** `RF` significa **Requisito Funcional**; `RNF` significa **Requisito Não Funcional**; `RB` significa **Regra de Negócio**; `CA` significa **Caso de Aceite**. Esses são termos comuns de engenharia de requisitos, **não identificadores obrigatórios nem exclusivos do BMAD**. A segunda parte do identificador é sempre um nome português legível do domínio (`RF-ATASERVICO-001`, por exemplo). O número é sequencial dentro do domínio e não deve ser reaproveitado com outro significado. Esses IDs permitem ligar requisitos a UX, arquitetura, testes e Stories.


Este é o **único PRD canônico**. Os 236 requisitos aprovados estão integrados neste catálogo, com IDs permanentes. A tabela seguinte é a distribuição **vigente**; o total histórico anterior era 206. A execução deve rastrear todas as 22 famílias, inclusive as incorporadas na conciliação final.


| Família | Área | Nº de requisitos |
|---|---|---:|
| `RF-PUBLICO` | Portal e conteúdo público | 5 |
| `RF-ACESSO` | Identidade, autenticação e autorizações | 13 |
| `RF-AUDITORIA` | Auditoria | 9 |
| `RF-ATA` | Ata das reuniões regulares | 28 |
| `RF-ATASERVICO` | Ata e reuniões de serviço | 10 |
| `RF-RELATORIOS` | Dashboards e relatórios | 20 |
| `RF-MIGRACAO` | Migração e reconciliação | 16 |
| `RF-SERVIDORES` | Servidores, mandatos e escalas | 17 |
| `RF-KIT` | Kit ingresso | 18 |
| `RF-TESOURARIA` | Tesouraria e ASAAS | 17 |
| `RF-WHATSAPP` | WhatsApp / Evolution API | 10 |
| `RF-ZOOM` | Zoom e eventos | 11 |
| `RF-ADMINISTRACAO` | Administração e configurações | 10 |
| `RF-BACKUP` | Backup, recuperação e operações | 6 |
| `RF-DOCUMENTACAO` | Documentação e replicabilidade | 5 |
| `RF-AUTOMACOES` | Rotinas operacionais migradas | 25 |
| `RF-IDENTIDADE` | Identidade operacional e reconciliação | 4 |
| `RF-ROOT` | Elevação excepcional de sessão | 3 |
| `RF-IA` | Assistência e NotebookLM | 4 |
| `RF-ATENDIMENTO` | Fale Conosco e triagem humana | 2 |
| `RF-RSG` | Representação e relatório bimestral | 1 |
| `RF-COMUNICACAO` | Publicação e comunicação | 2 |
| **Total vigente — 22 famílias** | | **236** |


**Catálogo vigente:** 236 RF em 22 famílias, incluindo requisitos antes mantidos em anexos de complementação. Os **206 RF originais** constituem apenas a linha de base histórica, **não uma especificação concorrente**. Os casos de aceite totalizam **38** (`CA-01` a `CA-38`); nenhuma Story deve ignorar as famílias adicionais.

### Distribuição dos requisitos vigentes por domínio (236 RF; não representa progresso)


```mermaid
pie showData
  title Catálogo vigente - 236 requisitos funcionais
  "PUBLICO" : 5
  "ACESSO" : 13
  "AUDITORIA" : 9
  "ATA" : 28
  "ATASERVICO" : 10
  "RELATORIOS" : 20
  "MIGRACAO" : 16
  "SERVIDORES" : 17
  "KIT" : 18
  "TESOURARIA" : 17
  "WHATSAPP" : 10
  "ZOOM" : 11
  "ADMINISTRACAO" : 10
  "BACKUP" : 6
  "DOCUMENTACAO" : 5
  "AUTOMACOES" : 25
  "IDENTIDADE" : 4
  "ROOT" : 3
  "IA" : 4
  "ATENDIMENTO" : 2
  "RSG" : 1
  "COMUNICACAO" : 2
```


### Mapa visual de domínios, dados e automações


```mermaid
flowchart TB
  SITE[Portal público] --> AG[Agenda e reuniões]
  PAINEL[Painel administrativo] --> ATA[Atas / serviço]
  PAINEL --> KIT[Kit ingresso]
  PAINEL --> TES[Tesouraria]
  PAINEL --> ENC[Servidores]
  ATA --> DB[(PostgreSQL)]
  KIT --> DB
  TES --> DB
  ENC --> DB
  DB --> REL[Relatórios segregados]
  AUTO[Jobs e eventos Laravel] --> KIT
  AUTO --> TES
  AUTO --> ATA
  AUTO --> ENC
  AUTO <--> EXT[Zoom / ASAAS / Evolution / Correios]
  DB --> MIG[Migração e reconciliação]
```


### RF-PUBLICO — Portal e conteúdo público (5)


| ID | Capacidade | Condição/requisito da fonte consolidada |
|---|---|---|
| `RF-PUBLICO-001` | Landing page pública | O sistema deve possuir página pública no domínio institucional **GrupoQuarenteNA.org**, acessível em HTTPS por Cloudflare Tunnel em produção, sem login para conteúdo público. |
| `RF-PUBLICO-002` | Informações de reunião | A landing page deve exibir dias, horários e link do Zoom. |
| `RF-PUBLICO-003` | Links públicos | A página pública deve conter links para relatórios/dashboard públicos permitidos. |
| `RF-PUBLICO-004` | Acesso ao painel | A página pública deve permitir acesso ao painel administrativo em /admin. |
| `RF-PUBLICO-005` | Mobile-first | A página pública e o painel devem ser responsivos e priorizar boa experiência em celular. |


### RF-ACESSO — Identidade, autenticação e autorizações (13)


| ID | Capacidade | Condição/requisito da fonte consolidada |
|---|---|---|
| `RF-ACESSO-001` | Login | O sistema deve permitir login para usuários com senha cadastrada e status ativo. |
| `RF-ACESSO-002` | Logout | O sistema deve permitir logout seguro. |
| `RF-ACESSO-003` | Redefinição de senha | O sistema deve permitir redefinir senha por fluxo administrativo, e futuramente por e-mail se configurado. |
| `RF-ACESSO-004` | Usuário sem senha | Pessoas operacionais sem login ficam em `pessoas`, separadas de `usuarios` (login exige credencial); não gerar contas de login por ingresso ou Zoom. |
| `RF-ACESSO-005` | Gestão de usuários | Coordenador Geral e Secretário Geral administram usuários e mandatos; elevação root requer login pessoal prévio, reautenticação forte, justificativa e auditoria; não há login direto root. |
| `RF-ACESSO-006` | Proteção do root | A credencial institucional root de contingência é protegida e usada apenas para elevar sessão pessoal já autenticada, sem conta compartilhada de login direto; exigir MFA, TTL, auditoria e recuperação segura. |
| `RF-ACESSO-007` | Controle de identidade e privilégios | O projeto **adapta** o template Laravel: `cliente`, `admin` e `super_admin` legados não são perfis normativos. Usuários humanos possuem conta pessoal vinculada obrigatoriamente a `pessoas`; capacidades provêm de encargo vigente. Elevação root temporária requer segunda autenticação, é irrestrita e auditada; desativação de contas, recuperação e rotação institucional têm autor identificado. |
| `RF-ACESSO-008` | Permissões extras | O sistema atribui capacidades automaticamente por encargo ativo; delegações excepcionais exigem prazo, justificativa e auditoria, sem contornar exclusividade de Tesouraria e Kit no acesso ordinário; elevação root excepcional e explicitamente auditada tem acesso integral. |
| `RF-ACESSO-009` | Controle por módulo | Toda operação deve usar Policy/Gate e encargo **vigente**; tesouraria com edição ordinária exclusiva do Tesoureiro e kit com acesso ordinário exclusivo do Coordenador Kit; elevação root pessoal excepcional pode acessar ambos mediante auditoria reforçada. |
| `RF-ACESSO-010` | Relatórios públicos | O sistema deve permitir relatórios públicos sem login, desde que definidos como públicos e sem dados sensíveis. |
| `RF-ACESSO-011` | Privilégios por mandato vigente | Ao designar servidor, conceder capacidades somente enquanto o mandato estiver vigente; Policies verificam data atual mesmo se scheduler falhar, além de revogar em encerramento/entrega/destituição e invalidar sessões/cache privilegiado. |
| `RF-ACESSO-012` | Root institucional excepcional | Permitir ao usuário pessoal já autenticado ativar elevação `root` temporária por segunda autenticação forte, com MFA, justificativa, aviso e auditoria; não permitir login direto como root; com elevação ativa, acesso integral inclusive a Kit e Tesouraria. |
| `RF-ACESSO-013` | Segregação de encargos | Coordenador Geral e Secretário Geral administram acessos e integrações, mas não herdam edição financeira nem acesso operacional ao kit; tesoureiro edita finanças; Coordenador Kit é o único que vê e gere dados privados do kit. |


### RF-AUDITORIA — Auditoria (9)


| ID | Capacidade | Condição/requisito da fonte consolidada |
|---|---|---|
| `RF-AUDITORIA-001` | Auditar toda modificação | Toda operação que altere o banco de dados deve gerar registro de auditoria. |
| `RF-AUDITORIA-002` | Dados mínimos do log | Cada log deve registrar: ; usuário responsável; data e hora; IP ou origem da requisição quando disponível; user-agent quando disponível; módulo; entidade; id do registro; ação realizada; valores anteriores; valores novos; justificativa quando aplicável. |
| `RF-AUDITORIA-003` | Justificativa em edição posterior | Edições em registros oficiais já enviados, como atas enviadas, devem exigir justificativa. |
| `RF-AUDITORIA-004` | Consulta de auditoria | Coordenador Geral e Secretário Geral consultam auditoria institucional; root elevado pessoalmente consulta tudo durante a elevação; Tesoureiro consulta registros financeiros pertinentes ao serviço. Logs do Kit permanecem sem PII para terceiros. |
| `RF-AUDITORIA-005` | Auditoria de automações | Ações feitas por jobs, webhooks ou integrações devem ser registradas como ações de sistema, identificando origem como ASAAS, Zoom, Evolution API, job de rastreamento, job de serviços etc. |
| `RF-AUDITORIA-006` | Auditoria não editável | Logs de auditoria não devem ser editáveis pela interface comum. |
| `RF-AUDITORIA-007` | Inserção, atualização, correção, exclusão lógica, alteração de permissões e configuração são auditadas independentemente de serem humanas ou automatizadas. | Ator do tipo usuário/sistema, timestamp UTC, correlação, origem, entidade, antes/depois redigidos e operação em uma transação. |
| `RF-AUDITORIA-008` | Auditoria de leitura de segredo Zoom, dados pessoais Kit e acesso de elevação root. | Registro suficiente para reconstruir quem consultou, sem copiar os próprios segredos para logs. |
| `RF-AUDITORIA-009` | Não eliminar destrutivamente registros institucionais no fluxo comum; usar versionamento/exclusão lógica. | Retificações e reversões preservam razão, autoria e valores; retenção pessoal sujeita à eventual necessidade de anonimização/expurgo legal. |


### RF-ATA — Ata das reuniões regulares (28)


| ID | Capacidade | Condição/requisito da fonte consolidada |
|---|---|---|
| `RF-ATA-001` | Criar ata | A dupla moderadora escalada para a ocorrência, ou administrador em contingência justificada, pode criar a ata; autenticação pessoal e mandato/escala vigente são obrigatórios. |
| `RF-ATA-002` | Ata pré-preenchida | Pré-preencher quando disponível: data/ocorrência, horário, tipo/formato, dupla escalada, presença sugerida pelo Zoom e campos institucionais padrão; lista nominal Zoom é **temporária e exclusivamente restrita** para conferência; incluir referência da 7ª Tradição quando aplicável. Todos os valores são conferíveis/editáveis, sem presumir que dados Zoom identifiquem membros cadastrados. |
| `RF-ATA-003` | Editar dados pré-preenchidos | Todo dado pré-preenchido deve ser editável antes do envio. |
| `RF-ATA-004` | Secretário sugerido | Sugerir a função de chat/secretaria da ocorrência conforme escala, com possibilidade de troca entre os dois moderadores. |
| `RF-ATA-005` | Coordenador sugerido | Escalar sempre dupla de moderadores quando houver cobertura; designar por ocorrência as funções de fala (coordenação) e chat/ata (secretaria), intercambiáveis em caso de substituição. |
| `RF-ATA-006` | Presença sugerida pelo Zoom | O sistema pode sugerir total de presenças com base nos participantes capturados pelo Zoom, mas o servidor pode modificar se julgar pertinente. |
| `RF-ATA-007` | Conferência antes de envio | Antes do envio oficial, o sistema deve exibir uma tela de revisão/rascunho para conferência. |
| `RF-ATA-008` | Rascunho automático | Ao iniciar o preenchimento, o sistema deve salvar automaticamente como rascunho e atualizar o rascunho durante o preenchimento. |
| `RF-ATA-009` | Envio oficial | Após conferência, o usuário pode enviar oficialmente a ata. |
| `RF-ATA-010` | Editar ata enviada | **Somente** o usuário pessoal que fez o envio oficial da ata, o **Coordenador Geral** ou o **Secretário Geral** podem retificá-la após o envio, além da sessão root pessoalmente elevada (exceção irrestrita). Registrar `enviada_por_usuario_id`, justificativa, antes/depois, versão e autoria da retificação. Outro membro da Mesa não adquire essa capacidade por ser da Mesa ou por ter participado da escala. |
| `RF-ATA-011` | Status da ata | Estados: `rascunho`, `enviada`, `revisada`, `corrigida`, `anulada`. **Revisada** = conferência formal sem alterar o conteúdo oficial; **corrigida** = retificação de conteúdo com motivo, inclusive pelo próprio autor; **anulada** = retirada dos relatórios oficiais, preservando histórico. Rascunhos não integram indicadores. A identidade do editor determina permissão e autoria, **não** a classificação da transição. |
| `RF-ATA-012` | Campos numéricos | O sistema deve validar campos numéricos, como presenças, partilhas, visitas, ingressos, conquistas e valores financeiros, impedindo valores inválidos. |
| `RF-ATA-013` | Campos estruturados | Campos objetivos devem usar seletores, cadastros ou opções controladas. |
| `RF-ATA-014` | Campos subjetivos | Campos como observações e informações adicionais podem ser texto livre. |
| `RF-ATA-015` | Opção “outro” | Quando necessário, seletores devem permitir opção “outro” com campo adicional de texto. |
| `RF-ATA-016` | Relação visita/ingresso | Sempre que houver ingresso, objetivamente houve visita. Pode haver visita sem ingresso. O sistema pode sugerir coerência, mas não deve impedir visita sem ingresso. |
| `RF-ATA-017` | Ingressos | Quando houver ingresso, registrar apelido/primeiro nome em `ingressos` sem criar login automaticamente; vínculo com pessoa já conhecida exige confirmação humana contextual. |
| `RF-ATA-018` | Kit ingresso automático | O envio oficial de ata com ingresso gera pendência de Kit quando aplicável. **Retificação/anulação posterior** gera alerta privado ao Coordenador Kit e tarefa de revisão humana, sem cancelar automaticamente remessa expedida, sem apagar histórico e sem divulgar contatos em grupos. |
| `RF-ATA-019` | Conquistas | Registrar quantidade e tempo limpo quando houver; permitir vínculo **opcional a `pessoas`**, somente mediante confirmação humana, preservando nome/apelido originalmente anotado na ata, inclusive sem cadastro de login. |
| `RF-ATA-020` | Visitas | Quando houver visitas, o sistema deve registrar quantidade, origem da visita e informações textuais quando necessário. |
| `RF-ATA-021` | Temáticas | Registrar temática e nome/apelido histórico do partilhador; admitir vínculo **opcional a `pessoas`** após conferência humana, sem exigir `usuarios` e sem alterar o texto da ata por mudança cadastral. |
| `RF-ATA-022` | Resumo automático | Após envio oficial, gerar resumo publicável com modelo sanitizado; **não copiar o script legado literalmente nem divulgar coluna Z/contatos**, endereço, segredo ou dados individuais do Kit. |
| `RF-ATA-023` | Envio de resumo | O sistema deve poder enviar o resumo da ata via Evolution API e/ou Telegram, se configurado. |
| `RF-ATA-024` | Histórico | O sistema deve permitir consultar histórico de atas por período, tipo, dia, horário, servidor, visitas, ingressos, conquistas e outros filtros. |
| `RF-ATA-025` | Exportação | O sistema deve permitir exportar atas em CSV e gerar relatório imprimível. |
| `RF-ATA-026` | Dupla de moderação e substituição | A reunião regular diária 20h–22h possui dois moderadores treinados; um responde por fala/coordenação e outro por chat/ata, funções intercambiáveis sem criação de dois encargos fixos. |
| `RF-ATA-027` | Identidade e nomes normalizados | Selecionar servidor por cadastro/escala, não por texto livre; sugerir reconciliação de nomes com caixa/espaços/acentos divergentes sem fundir homônimos automaticamente. |
| `RF-ATA-028` | Paridade com formulário antigo | Preservar data, horário, tipo, presenças, partilhas, visitas/origens, ingressos/nomes, conquistas/tempo, temática/partilhador, 7ª Tradição/referência, despesas, adicionais, eleições e observações; PII de contatos permanece só no Kit restrito. |


### RF-ATASERVICO — Ata e reuniões de serviço (10)


| ID | Capacidade | Condição/requisito da fonte consolidada |
|---|---|---|
| `RF-ATASERVICO-001` | Criar ata de serviço | O sistema deve permitir criar ata de reunião de serviço. |
| `RF-ATASERVICO-002` | Template padrão | A ata de serviço deve possuir template padrão configurável. |
| `RF-ATASERVICO-003` | Pré-preenchimento | Algumas informações podem vir pré-preenchidas, como data, tipo de reunião, servidores esperados, pauta padrão e dados do calendário. |
| `RF-ATASERVICO-004` | Rascunho e conclusão | O Secretário Geral abre uma ata a partir da versão vigente do template, salva o rascunho sucessivamente no servidor, confere e **finaliza** a ata; sua finalização é o ato oficial, sem exigir segunda aprovação não prevista. Rascunhos não alimentam indicadores oficiais nem geram PDF definitivo. |
| `RF-ATASERVICO-005` | Geração de PDF | Ao **finalizar** a ata de serviço, gerar PDF oficial, arquivar em pasta privada no Google Drive institucional e registrar versão/ID do arquivo no PostgreSQL; correções posteriores produzem nova versão sem apagar a original. |
| `RF-ATASERVICO-006` | Dados da reunião de serviço | Registrar presenças, ausências, justificativas e decisões individualizadas quando disponíveis e **agregados oficiais** `presentes_total`, `faltas_total`, `justificadas_total`, mesmo para acervos que contenham apenas totais; valores desconhecidos permanecem nulos, nunca presumidos como zero. Incluir interessados, contexto e links/documentos relacionados. |
| `RF-ATASERVICO-007` | Histórico, correções e relatórios | Consultar reuniões de serviço por data, tipo, presenças/faltas/justificativas e decisões, usando **agregados próprios** quando as listas individuais históricas não existirem. Ata finalizada pode ser conferida sem alteração, corrigida com justificativa ou anulada, com revisões imutáveis, efeitos em relatórios e PDFs versionados. |
| `RF-ATASERVICO-008` | Decisões e consciências  | O sistema deve permitir registrar decisões ou consciências coletivas, com data, contexto e link de ata. |
| `RF-ATASERVICO-009` | Consulta pública ou restrita  | Decisões autorizadas podem ser públicas, mas dados internos de faltas e justificativas são restritos; PDF e versão oficial devem permanecer vinculados ao Google Drive institucional. |
| `RF-ATASERVICO-010` | Template editável da Secretaria Geral | Secretário Geral mantém modelo padronizado de ata de serviço com histórico de versões, prévia, geração de PDF e arquivamento privado no Google Drive institucional. |


### RF-RELATORIOS — Dashboards e relatórios (20)


| ID | Capacidade | Condição/requisito da fonte consolidada |
|---|---|---|
| `RF-RELATORIOS-001` | Substituir Looker Studio | O sistema deve substituir o dashboard atual do Looker Studio por páginas PHP. |
| `RF-RELATORIOS-002` | Relatório clássico | O sistema deve oferecer visão equivalente ao Looker atual para validação dos números históricos. |
| `RF-RELATORIOS-003` | Dashboard melhorado | O sistema deve oferecer dashboard melhorado com filtros, cards, gráficos e tabelas mais confiáveis. |
| `RF-RELATORIOS-004` | Filtros | O dashboard deve permitir filtros por período, dia da semana, horário, tipo/formato de reunião, visitas, ingressos, conquistas, temática, servidor e status. |
| `RF-RELATORIOS-005` | Página Resumo | Deve existir página de resumo com indicadores principais de reuniões, presenças, partilhas, visitas, ingressos e conquistas. |
| `RF-RELATORIOS-006` | Página Dados da Reunião | Deve existir listagem detalhada de atas e reuniões. |
| `RF-RELATORIOS-007` | Página Conquistas e Ingressos | Deve existir relatório específico para conquistas e ingressos. |
| `RF-RELATORIOS-008` | Página Temáticas | Deve existir relatório de reuniões temáticas, títulos, partilhadores e presenças. |
| `RF-RELATORIOS-009` | Página Visitas | Deve existir relatório de visitas, origem e relação com ingressos. |
| `RF-RELATORIOS-010` | Página Quadro de Serviços | Deve existir relatório de servidores, encargos, vagas, histórico, conclusão, entrega e destituição. |
| `RF-RELATORIOS-011` | Página Reuniões de Serviço | Deve existir relatório de reuniões de serviço, atas, PDFs, participantes, faltas, justificativas e decisões. |
| `RF-RELATORIOS-012` | Página Tesouraria | Deve existir dashboard financeiro com entradas, saídas, categorias, saldos, reserva prudente, média de contribuição e projeções de autossustentabilidade. |
| `RF-RELATORIOS-013` | Página Kit Ingresso | Deve existir relatório de kit ingresso, status de envio, pendências, rastreamento e responsáveis. |
| `RF-RELATORIOS-014` | Página Participantes Zoom | A página de participantes Zoom, quando existir, deve ser restrita à mesa ou permissões específicas. Não deve ser relatório público ou acessível a servidores comuns. |
| `RF-RELATORIOS-015` | Relatórios públicos | Alguns relatórios agregados devem poder ser disponibilizados por link público sem login. |
| `RF-RELATORIOS-016` | Proteção de dados sensíveis | Relatórios públicos não devem expor telefone, endereço, lista nominal de participantes do Zoom, logs, dados internos do kit ou informações restritas. |
| `RF-RELATORIOS-017` | Relatório financeiro público | O relatório financeiro pode ser público como prestação de contas, exibindo data, valor, categorias e saldo, mas sem expor nomes de contribuintes. |
| `RF-RELATORIOS-018` | Exportação | Relatórios devem poder ser exportados em CSV quando autorizado. |
| `RF-RELATORIOS-019` | Impressão/PDF via navegador | Relatórios devem ter visualização amigável para impressão ou geração de PDF pelo navegador. |
| `RF-RELATORIOS-020` | Nomes permitidos no relatório de ingresso | Publicar somente primeiro nome/apelido mínimo no registro **novo** por prática `opt-out`, sem PII do Kit. Solicitações de ocultação/correção em Fale Conosco geram protocolo, notificação privada a Coordenador Geral/Secretário Geral e análise humana; históricos migrados dependem de revisão de privacidade antes da publicação. |


### RF-MIGRACAO — Migração e reconciliação (16)


| ID | Capacidade | Condição/requisito da fonte consolidada |
|---|---|---|
| `RF-MIGRACAO-001` | Importar planilha de atas | O sistema deve importar histórico da planilha “Reuniões Grupo QuarenteNA Bahia (Responses)”. |
| `RF-MIGRACAO-002` | Importar kit ingresso | O sistema deve importar histórico da planilha Kit Ingresso. |
| `RF-MIGRACAO-003` | Importar tesouraria | O sistema deve importar histórico da planilha de tesouraria e reserva prudente, quando disponível. |
| `RF-MIGRACAO-004` | Importar quadro de serviços | O sistema deve importar quadro de servidores, cargos, início, término e histórico. |
| `RF-MIGRACAO-005` | Importar atas de serviço | O sistema deve importar ou referenciar atas de serviço e links PDF. |
| `RF-MIGRACAO-006` | Mapeamento de colunas | A importação deve permitir mapear colunas das planilhas para campos do sistema. |
| `RF-MIGRACAO-007` | Varredura prévia | Antes da migração, o sistema deve permitir varrer as planilhas atuais e identificar problemas, inconsistências, campos quebrados, duplicidades e conflitos. |
| `RF-MIGRACAO-008` | Normalização e mesclagem sugerida | A importação deve identificar possíveis duplicidades por apelido, nome, telefone, variações de acento, caixa e espaços, sugerindo mesclar/unir registros com revisão humana. |
| `RF-MIGRACAO-009` | Revisão manual | O sistema deve permitir revisão manual de conflitos e duplicidades antes de consolidar. |
| `RF-MIGRACAO-010` | Relatório de importação | Cada importação deve gerar relatório com total de linhas, linhas importadas, erros, avisos, duplicidades e decisões manuais. |
| `RF-MIGRACAO-011` | Validação contra relatórios atuais | Os totais importados devem ser comparáveis com planilhas e Looker atual antes da virada. |


| ID | Capacidade | Condição/requisito da fonte consolidada |
|---|---|---|
| `RF-MIGRACAO-012` | Migração integral das planilhas | Inventariar e migrar todas as planilhas, abas, colunas, fórmulas com efeito operacional e relacionamentos relevantes, identificando o destino de cada regra/campo. |
| `RF-MIGRACAO-013` | Migração de scripts Google Apps Script | Reproduzir em Laravel funções de `ata-resumo`, `ata-kitingresso`, `kit-ingresso` e `servico-terminio`, com testes de comportamento e tratamento de exceções. |
| `RF-MIGRACAO-014` | Migração de workflows n8n | Exportar, catalogar e substituir cada workflow ativo por endpoints, eventos, jobs, comandos ou telas próprias; documentar fluxos descontinuados e motivo. |
| `RF-MIGRACAO-015` | Substituição de visões Google Docs/Looker | Reconstituir `VAGO`, `Relação de Servidores`, documentos operacionais e relatórios/dashboards a partir dos dados normalizados, sem cópias manuais. |
| `RF-MIGRACAO-016` | Reconciliar históricos de ingressos, fichas, servidores e pessoas por sugestões cruzadas, revisadas por equipe de servidores antigos. | Lote tem fila de ambiguidades, justificativa e confirmação humana antes de vincular identidades. |


### RF-SERVIDORES — Servidores, mandatos e escalas (17)


| ID | Capacidade | Condição/requisito da fonte consolidada |
|---|---|---|
| `RF-SERVIDORES-001` | Cadastro de encargos | O sistema deve permitir cadastrar encargos e funções de serviço. |
| `RF-SERVIDORES-002` | Cadastro de reuniões/horários | O sistema deve permitir cadastrar dias, horários e tipos de reunião vinculados a encargos. Hoje há reunião principal às 20h, mas o sistema deve suportar múltiplos horários no mesmo dia. |
| `RF-SERVIDORES-003` | Encargos configuráveis | O sistema permite cadastrar e gerir encargos preservando seis cargos atuais da mesa: Coordenador Geral, Secretário Geral, Tesoureiro, RSG, RSG Suplente e Coordenador Kit Ingresso; mudanças futuras exigem decisão registrada. |
| `RF-SERVIDORES-004` | Associação de servidor | Designar uma **pessoa operacional** a encargo/dia/horário/reunião. **Todo servidor atual que opere a plataforma tem `usuario` pessoal** vinculado à pessoa e credencial própria; o histórico de mandatos pode referenciar pessoas antigas sem login. Permissões decorrem da designação/escala **ativa e vigente**, não apenas da existência de conta. |
| `RF-SERVIDORES-005` | Datas de serviço | A designação registra início e fim; mandato ordinário dos moderadores é de seis meses e da Mesa de um ano, parametrizáveis por encargo. |
| `RF-SERVIDORES-006` | Status do serviço | Estados **exclusivos** de designação: `ativo`, `concluido`, `entregue`, `destituido`. O encargo está **VAGO** quando não houver designação `ativo` com titular e período vigente. Uma substituição temporária é **nova escala/designação com vigência**, não status adicional; suspensão não pertence ao vocabulário canônico. Destituição por deliberação ou **automaticamente quando configurado e após confirmação autorizada** de duas faltas injustificadas ou recaída; a automação aplica a consequência institucional sem inferir condições pessoais, com auditoria mínima e privacidade. |
| `RF-SERVIDORES-007` | Histórico | O sistema deve manter histórico de servidores por encargo. |
| `RF-SERVIDORES-008` | Vagas | O sistema deve gerar automaticamente lista de vagas. |
| `RF-SERVIDORES-009` | Relação de servidores | O sistema deve gerar automaticamente relação de servidores por dia, horário e encargo. |
| `RF-SERVIDORES-010` | Avisos de término | O sistema deve enviar avisos automáticos de término de serviço em marcos configuráveis, como 90, 60, 30, 15, 7, 3, 2 e 1 dia, além do evento de conclusão. |
| `RF-SERVIDORES-011` | Notificação privada e grupo | Enviar lembretes de mandato ao servidor usando telefone cadastrado sob permissão e, se autorizado, aviso ao grupo de serviço **sem expor contato pessoal**; desativação e expiração impedem alertas indevidos. |
| `RF-SERVIDORES-012` | Encargo concluído, entregue ou destituído | Quando o encargo termina, é entregue ou há destituição, a mesa deve poder registrar o motivo, manter histórico e atualizar a vaga. |
| `RF-SERVIDORES-013` | Comando !vago | O comando !vago deve retornar dados gerados do banco, não de Google Docs manual. |
| `RF-SERVIDORES-014` | Comando !servidores | O comando !servidores deve retornar relação atual de servidores gerada do banco. |
| `RF-SERVIDORES-015` | Encargos da mesa | Cadastrar Coordenador Geral, Secretário Geral, Tesoureiro, RSG, RSG Suplente e Coordenador Kit Ingresso como encargos distintos com responsabilidades/mandatos definidos. |
| `RF-SERVIDORES-016` | Moderação diária em dupla | Modelar moderador de reunião como função de serviço com duas pessoas por ocorrência, alternando papéis de fala e chat; não confundir cargos gerais da mesa com papéis do dia. |
| `RF-SERVIDORES-017` | Término e destituição com revogação | Conceder permissões pelo encargo quando ativado; expiração automática ou entrega/destituição manual revoga direito na mesma operação, guardando autor, motivo, data e histórico. |


### RF-KIT — Kit ingresso (18)


| ID | Capacidade | Condição/requisito da fonte consolidada |
|---|---|---|
| `RF-KIT-001` | Criar pendência de kit | Somente **ao enviar oficialmente** a ata com ingresso, criar pendência idempotente; rascunho não dispara contato, encomenda ou comunicação. Retificação/anulação posterior abre revisão humana. |
| `RF-KIT-002` | Relacionar ingressante | Cada pendência deve estar vinculada ao registro de ingresso; o ingressante não precisa ter conta de login em `usuarios`. |
| `RF-KIT-003` | Dados do ingressante | O módulo deve permitir registrar telefone, CEP, endereço, status, observações, responsáveis e código de rastreio. |
| `RF-KIT-004` | Busca de CEP | O sistema deve permitir buscar/preencher endereço com base no CEP, quando integração estiver disponível. |
| `RF-KIT-005` | Status do kit | O status deve suportar verificar, falta endereço, falta enviar, enviado, recebido, devolvido e cancelado. |
| `RF-KIT-006` | Mensagem solicitando endereço | O sistema deve poder enviar mensagem ao ingressante solicitando endereço. |
| `RF-KIT-007` | Mensagem de falta enviar | Notificar **privadamente** Coordenador Kit e ingressante, conforme canal permitido. Eventual aviso a grupo pode conter **somente totais/estado agregado**, sem nome, telefone, endereço, código de rastreio pessoal ou referência identificável ao ingressante. |
| `RF-KIT-008` | Mensagem de enviado | Código de rastreio enviado apenas ao destinatário/Coordenador Kit por canal privado autorizado; jamais incluir código ou destinatário em grupo geral ou relatório público. |
| `RF-KIT-009` | Rastreamento | O sistema deve consultar rastreamento de objetos enviados por job configurado. |
| `RF-KIT-010` | Eventos de rastreio | Eventos de rastreio devem ser armazenados no banco. |
| `RF-KIT-011` | Atualização automática de status | Normalizar entrega/devolução, atualizar estado com auditoria e notificar Coordenador Kit/destinatário **por canal privado**; grupo externo recebe, quando cabível, apenas agregado não identificável. |
| `RF-KIT-012` | Permissão de edição | **Somente o Coordenador Kit Ingresso com mandato ativo** pode acessar e modificar dados operacionais do kit. Administradores gerais não herdam esse acesso; root excepcionalmente elevado por usuário pessoal pode acessar/alterar com auditoria reforçada. |
| `RF-KIT-013` | Proteção de telefone e endereço | No fluxo ordinário, telefone/endereço do ingresso são visíveis somente ao Coordenador Kit em mandato vigente e toda leitura é auditada; exceção root requer **elevação pessoal excepcional**, MFA, justificativa e auditoria reforçada, conforme `RF-ROOT-002`. |
| `RF-KIT-014` | Auditoria | Toda alteração em kit ingresso deve gerar auditoria. |


| ID | Capacidade | Condição/requisito da fonte consolidada |
|---|---|---|
| `RF-KIT-015` | Serviço dedicado de rastreamento | Integrar o serviço [opastorello/correios-rastreamento](https://github.com/opastorello/correios-rastreamento) por API REST autenticada em rede privada; as informações oficiais de remessa permanecem no PostgreSQL do QuarenteNA. |
| `RF-KIT-016` | Atualização agendada e reprocessamento | Consultar periodicamente remessas não finalizadas com idempotência, limites, tratamento de falhas e mecanismo de reconsulta manual. |
| `RF-KIT-017` | Proteção e consistência do rastreio | Normalizar eventos, rejeitar duplicatas, registrar data/hora e não divulgar destinatário, endereço ou telefone em interfaces e logs públicos. |
| `RF-KIT-018` | Exclusividade do coordenador do kit | Somente Coordenador Kit Ingresso com mandato vigente pode acessar filas, contatos, endereços e remessas; mesa e root não possuem acesso de negócio ordinário; root elevado excepcionalmente pode intervir com registro pessoal de auditoria. |


### RF-TESOURARIA — Tesouraria e ASAAS (17)


| ID | Capacidade | Condição/requisito da fonte consolidada |
|---|---|---|
| `RF-TESOURARIA-001` | Receber webhook ASAAS | O sistema deve receber eventos financeiros do ASAAS via webhook. |
| `RF-TESOURARIA-002` | Registrar eventos automáticos | Eventos ASAAS devem gerar lançamentos financeiros automáticos. |
| `RF-TESOURARIA-003` | Evitar duplicidade | Registrar ID único do evento recebido **e chave única do efeito financeiro por transação/tipo de movimento**; eventos distintos referentes ao mesmo pagamento não podem duplicar saldo, inclusive em concorrência e replays. |
| `RF-TESOURARIA-004` | Tipos de lançamento | O sistema deve suportar entradas e saídas. |
| `RF-TESOURARIA-005` | Descrição e categorização automática | Todo lançamento possui `descricao`. **Entrada PIX recebida via ASAAS:** descrição pública/contábil padronizada `7ª Tradição - PIX recebido` e categoria de contribuição, sem identificar pagador no relatório. **Despesa/saída:** usar mensagem/descrição do evento, sanitizada, quando existir; na falta dela, descrição-padrão explícita para revisão. O lançamento é **imediato**, sem aprovação prévia, e o Tesoureiro pode retificar posteriormente com auditoria. |
| `RF-TESOURARIA-006` | Categorias | Lançamentos devem ter categorias, como 7ª Tradição, literatura, reserva prudente, despesas, Zoom, doação, transferência e outras definidas pela mesa/tesoureiro. |
| `RF-TESOURARIA-007` | Revisão pelo tesoureiro | Somente o Tesoureiro com designação ativa pode corrigir ou categorizar lançamentos já efetivados, com auditoria. |
| `RF-TESOURARIA-008` | Edição com auditoria | Qualquer edição em lançamento financeiro deve gerar auditoria com valor anterior, valor novo e justificativa quando aplicável. |
| `RF-TESOURARIA-009` | Lançamento manual | Somente o Tesoureiro pode criar lançamento manual com efeito imediato após salvar, sem rascunho ou conferência obrigatória. |
| `RF-TESOURARIA-010` | Reserva prudente | O sistema deve controlar reserva prudente, inclusive lançamentos manuais, pois essa parte pode não ter webhook. |
| `RF-TESOURARIA-011` | Saldo | O sistema deve calcular saldo por período, conta, categoria e visão geral. |
| `RF-TESOURARIA-012` | Dashboard financeiro | O sistema deve exibir dashboard financeiro com entradas, saídas, saldo, categorias, evolução, média de contribuição e projeções de autossustentabilidade. |
| `RF-TESOURARIA-013` | Visualização restrita | Servidores podem consultar apenas projeções autorizadas. A edição financeira ordinária é **exclusiva do Tesoureiro com encargo vigente**, sem herança pela Mesa; sessão pessoal excepcionalmente elevada a root pode intervir, com justificativa e auditoria reforçada. |
| `RF-TESOURARIA-014` | Relatório financeiro público | O relatório financeiro público deve omitir nomes de contribuintes, exibindo apenas informações agregadas ou transacionais permitidas, como data, valor, categoria e saldo. |
| `RF-TESOURARIA-015` | Comando !saldo | O comando !saldo deve responder com saldo ou relatório resumido autorizado, sem expor dados restritos indevidos. |
| `RF-TESOURARIA-016` | Lançamento financeiro imediato | Evento ASAAS autenticado e financeiramente elegível gera lançamento confirmado idempotente; formulário manual salvo pelo tesoureiro gera lançamento direto, sem rascunho ou etapa de aprovação. |
| `RF-TESOURARIA-017` | Correção, estorno e exclusão auditáveis | Somente tesoureiro edita, estorna ou exclui logicamente lançamento; preservar origem, evento, valores antes/depois, responsável, motivo e impacto no saldo. |


### RF-WHATSAPP — WhatsApp / Evolution API (10)


| ID | Capacidade | Condição/requisito da fonte consolidada |
|---|---|---|
| `RF-WHATSAPP-001` | Receber webhook Evolution API | O sistema deve receber mensagens de WhatsApp via webhook da Evolution API. |
| `RF-WHATSAPP-002` | Identificar grupo de origem | O sistema deve identificar se a mensagem veio do grupo de serviço, grupo de membros ou outro grupo configurado. |
| `RF-WHATSAPP-003` | Processar comandos | Mensagens iniciadas com ! devem ser processadas como comandos. |
| `RF-WHATSAPP-004` | Comandos do grupo de serviço | Manter ou reformular, por permissão e finalidade, `!ajuda`, `!ata`, `!coletiva`, `!diretriz`, `!link`, `!gbatepapo`, `!gingresso`, `!participantes`, `!relatorio`, `!resumo`, `!saldo`, `!script`, `!senha`, `!servidores`, `!setima`, `!telas`, `!tematica`, `!textos` e `!vago`. **`!kit` foi descontinuado e não recebe handler.** `!participantes` é agregado e não lista pessoas Zoom; `!senha` somente em grupo e papel expressamente autorizados com auditoria. |
| `RF-WHATSAPP-005` | Comandos do grupo de membros | O sistema deve suportar ou reformular comandos do grupo de membros, incluindo !ajuda, !coletiva, !diretriz, !link_cpsanto, !link_quarentena, !link_novaera, !relatorio, !saldo, !servidores, !setima, !zap_cpsanto, !zap_novaera e !zap_quarentena. |
| `RF-WHATSAPP-006` | Dados vindos do banco | Comandos como !vago, !servidores, !saldo, !relatorio, !ata e !resumo devem preferencialmente responder com dados estruturados do banco. |
| `RF-WHATSAPP-007` | Logs | Mensagens recebidas, comandos processados e respostas enviadas devem gerar logs técnicos conforme política de retenção. |
| `RF-WHATSAPP-008` | Proteção contra abuso | O sistema deve ter algum mecanismo de proteção contra uso excessivo ou chamadas repetitivas. |
| `RF-WHATSAPP-009` | Logs de mensagens  | O sistema deve registrar logs de mensagens enviadas por automação. |
| `RF-WHATSAPP-010` | Rate limit e falhas  | Falhas de envio devem ser registradas e, quando possível, reenviadas ou marcadas como pendentes. |


### RF-ZOOM — Zoom e eventos (11)


| ID | Capacidade | Condição/requisito da fonte consolidada |
|---|---|---|
| `RF-ZOOM-001` | Receber webhook Zoom | O sistema deve receber eventos do Zoom via webhook. |
| `RF-ZOOM-002` | Validar webhook | O sistema deve validar o endpoint e a origem conforme exigências técnicas do Zoom. |
| `RF-ZOOM-003` | Evento participant_joined | O sistema deve suportar o evento meeting.participant_joined, capturando campos úteis como evento, timestamp, reunião, tópico, duração, timezone, usuário, email, ids e horário de entrada. |
| `RF-ZOOM-004` | Campos úteis | A arquitetura deve avaliar os campos efetivamente permitidos no payload Zoom: `event`, `event_ts`, `payload.account_id`, identificador e UUID da reunião, `host_id`, `topic`, `type`, `start_time`, `duration`, `timezone`, além dos campos de participante `user_name`, `id`, `user_id`, `customer_key`, `join_time`, `email` e `registrant_id`. **Disponibilidade e necessidade de cada campo devem ser verificadas**; dados identificáveis exigem retenção mínima, propósito legítimo e acesso restrito. |
| `RF-ZOOM-005` | Salvar eventos | Eventos de participantes devem ser salvos em tabela própria, separada de usuarios. |
| `RF-ZOOM-006` | Sugerir presença total | O sistema deve usar eventos do Zoom para sugerir presença total na ata. |
| `RF-ZOOM-007` | Permitir correção manual | A presença sugerida pelo Zoom deve poder ser alterada pelo servidor responsável pela ata. |
| `RF-ZOOM-008` | Restringir lista nominal | Lista nominal de participantes Zoom deve ser restrita à mesa ou usuários com permissão específica. |
| `RF-ZOOM-009` | Não vigilância individual | O sistema não deve usar dados do Zoom para criar controle público ou operacional comum de frequência individual de membros. |
| `RF-ZOOM-010` | Pesquisa técnica futura | Na fase de arquitetura, deve-se consultar a documentação atual do Zoom para verificar outros eventos úteis além de participant_joined. |
| `RF-ZOOM-011` | Eventos Zoom temporários guardam nome exibido de forma criptografada e evidências mínimas para sugerir duplicações. | Servidores autorizados conferem lista e ajustam total manualmente; prazo default 365 dias, configurável por Coordenador Geral/Secretário Geral. |


### RF-ADMINISTRACAO — Administração e configurações (10)


| ID | Capacidade | Condição/requisito da fonte consolidada |
|---|---|---|
| `RF-ADMINISTRACAO-001` | Configurações do grupo  | O sistema deve permitir configurar dados do grupo, como nome, links, horários, reuniões, textos padrão e contatos. |
| `RF-ADMINISTRACAO-002` | Tipos de reunião  | O sistema deve permitir configurar tipos/formato de reunião. |
| `RF-ADMINISTRACAO-003` | Horários  | O sistema deve permitir configurar horários padrão. |
| `RF-ADMINISTRACAO-004` | Textos de comandos  | O sistema deve permitir configurar textos retornados por comandos WhatsApp quando aplicável. |
| `RF-ADMINISTRACAO-005` | Integrações  | O sistema deve permitir configurar credenciais e status de integrações em ambiente seguro, sem expor segredos publicamente. |
| `RF-ADMINISTRACAO-006` | Jobs  | O sistema deve permitir ver status de jobs automáticos, como rastreamento, avisos de serviço e backups. |
| `RF-ADMINISTRACAO-007` | Logs técnicos  | Coordenador Geral e Secretário Geral podem consultar logs técnicos; root somente em sessão excepcional registrada. Nenhuma dessas permissões expõe dados privados de kit ou tesouraria. |
| `RF-ADMINISTRACAO-008` | Painel de integrações | Coordenador Geral, Secretário Geral e root excepcional podem configurar URL, API, webhook, parâmetros e testes; segredos mascarados/criptografados e alteração auditada. |
| `RF-ADMINISTRACAO-009` | Armazenamento Google Drive | Documentos institucionais e PDFs devem ser armazenados no Google Drive com controles de acesso e metadados no PostgreSQL; Drive não substitui o banco. |
| `RF-ADMINISTRACAO-010` | Serviços opcionais | Usar MinIO/S3 e Redis apenas se houver necessidade medida; configuração e falhas aparecem na administração quando habilitados. |


### RF-BACKUP — Backup, recuperação e operações (6)


| ID | Capacidade | Condição/requisito da fonte consolidada |
|---|---|---|
| `RF-BACKUP-001` | Backup do banco  | O sistema deve ter procedimento documentado para backup do PostgreSQL. |
| `RF-BACKUP-002` | Restauração  | O sistema deve ter procedimento documentado para restaurar backup. |
| `RF-BACKUP-003` | Backup antes da migração  | Antes da virada oficial, deve existir backup dos dados importados e dos arquivos históricos. |
| `RF-BACKUP-004` | Documentação de instalação  | O sistema deve ter documentação para instalação via Docker. |
| `RF-BACKUP-005` | Documentação operacional  | O sistema deve ter documentação para servidores usarem painel, ata, relatórios, kit ingresso, tesouraria e comandos. |
| `RF-BACKUP-006` | Documentação técnica  | O sistema deve ter documentação de arquitetura, variáveis de ambiente, jobs, webhooks, integrações, backup e deploy. |


### RF-DOCUMENTACAO — Documentação e replicabilidade (5)


| ID | Capacidade | Condição/requisito da fonte consolidada |
|---|---|---|
| `RF-DOCUMENTACAO-001` | Documentação para iniciantes | O sistema deve incluir documentação simples para novos servidores. |
| `RF-DOCUMENTACAO-002` | Documentação por módulo | Deve haver orientações para ata, tesouraria, kit ingresso, serviços, relatórios, comandos e painel. |
| `RF-DOCUMENTACAO-003` | Documentação administrativa | Mesa/admin deve ter documentação de usuários, permissões, auditoria, migração e correções. |
| `RF-DOCUMENTACAO-004` | Documentação técnica | Responsáveis técnicos devem ter documentação de instalação, deploy, backup, restore, jobs, webhooks e variáveis de ambiente. |
| `RF-DOCUMENTACAO-005` | Adaptação para outro grupo | O projeto deve incluir instruções versionadas para adaptação por fork, explicando como adaptar um fork para outro grupo, alterando nome, domínio, e-mail, Zoom, Evolution API, ASAAS, textos, links, cores, logo, .env, usuário root e configurações iniciais. |


### RF-AUTOMACOES — Migração de rotinas e integrações operacionais (25)


Os itens abaixo formalizam a decisão aprovada de migrar **todas as funções ativas** hoje dependentes de n8n, Sheets e Apps Script para o sistema, sem copiar literalmente scripts inseguros. Cada rotina deve ter origem, gatilho, destino, entradas, saídas, tratamento de erro, testes e responsável.


| ID | Capacidade | Critério funcional |
|---|---|---|
| `RF-AUTOMACOES-001` | Inventário verificável | Catalogar fluxos e funções legados ativos com trigger, credenciais referenciadas, payload, dependências e critério de migração. |
| `RF-AUTOMACOES-002` | Resumo da ata | Gerar resumo aprovado com os dados publicáveis da ata, **excluindo sempre a coluna Z legada** e qualquer telefone, endereço, segredo Zoom ou informação individual do Kit; enviar só após envio oficial com outbox/idempotência. |
| `RF-AUTOMACOES-003` | Ingresso para kit | Após **envio oficial da ata** (não no autossalvamento), criar pendência de Kit via outbox idempotente, substituindo `ata-kitingresso`; corrigir/anular gera solicitação de revisão humana. |
| `RF-AUTOMACOES-004` | Solicitação de endereço | Substituir automações de `kit-ingresso` para solicitar endereço ao destinatário somente pelo canal autorizado. |
| `RF-AUTOMACOES-005` | CEP e expedição | Substituir formatação de CEP, consulta de endereço, status de envio e mensagens previstas nos scripts. |
| `RF-AUTOMACOES-006` | Rastreio postal | Usar `correios-rastreamento` no lugar das integrações de rastreio dispersas e registrar eventos únicos. |
| `RF-AUTOMACOES-007` | Término de serviço | Reproduzir avisos previstos em `servico-terminio` (90, 60, 30, 15, 7, 3, 2, 1 dia e na conclusão) com regras configuráveis. |
| `RF-AUTOMACOES-008` | Encargos concluídos | Encerrar encargos por regra aprovada, preservar histórico e refletir `VAGO` sem duplicar linhas. |
| `RF-AUTOMACOES-009` | Robô WhatsApp | Migrar o workflow n8n `Robô` para processamento de comandos autenticado/autorizado. |
| `RF-AUTOMACOES-010` | Divulgação de reuniões | Migrar avisos automáticos de agenda do n8n para jobs/templates parametrizados. |
| `RF-AUTOMACOES-011` | Mensagens de servidores | Migrar mensagens em grupo/individuais para notificações direcionadas com consentimento, limites e histórico mínimo. |
| `RF-AUTOMACOES-012` | Tesouraria/ASAAS | Substituir o fluxo n8n de entrada ASAAS para lançamentos/conciliação auditáveis. |
| `RF-AUTOMACOES-013` | Dashboards e relatórios | Reproduzir agregações, filtros e cálculos atualmente usados no Looker e Sheets com rastreabilidade de fórmula. |
| `RF-AUTOMACOES-014` | Mensageria Telegram | Manter equivalência do resumo para Telegram quando a operação confirmar esse canal como ativo. |
| `RF-AUTOMACOES-015` | Desativação segura | Desligar cada workflow/script legado somente após comparação funcional, backup, reprocessamento e aceite específico. |
| `RF-AUTOMACOES-016` | Rotinas e modelos configuráveis | Cada cron/job migrado deve permitir ativar/desativar, editar modelo de mensagem, frequência e destinos permitidos, inspecionar execução e reprocessar falhas sem duplicar envios; comandos Evolution tratados por handlers Laravel. |


```mermaid
flowchart LR
  G[Forms / Sheets / Docs] --> M[Importação e saneamento]
  N[n8n / Apps Script] --> F[Funções equivalentes Laravel]
  L[Looker Studio] --> R[Relatórios do PostgreSQL]
  M --> DB[(PostgreSQL)]
  F --> DB
  DB --> R
  F <--> I[Zoom / ASAAS / Evolution / Correios]
  R --> V[Verificação de paridade]
  F --> V
  V --> C{Aceite de cada rotina}
  C -->|sim| D[Desligamento legado assistido]
  C -->|não| N
```
| ID | Capacidade | Critério funcional |
|---|---|---|
| `RF-AUTOMACOES-017` | Construtor visual básico com gatilhos, condições e ações previamente permitidos. | Operador leigo configura via campos/menus, pré-visualiza e testa sem PHP/SQL ou código arbitrário. |
| `RF-AUTOMACOES-018` | Permitir ativação, desativação, agendamento, intervalos, destinos e modelos por rotina e módulo. | Coordenador Geral/Secretário Geral gerem rotinas comuns, Tesoureiro as rotinas específicas da tesouraria e Coordenador Kit as de Kit; root elevado é exceção auditada. |
| `RF-AUTOMACOES-019` | Divulgação por agendamento derivado de data/hora de cada ocorrência, 15 min antes por padrão. | Duas reuniões no mesmo dia geram dois lembretes independentes se habilitados. |
| `RF-AUTOMACOES-020` | Mensagens de ata somente ao enviar oficialmente; correção salva revisão e permite reenvio explícito conforme regras da rotina. | Sem duplicação em replay e sem contatos pessoais na versão para grupos. |
| `RF-AUTOMACOES-021` | IA: endpoint base compatível com OpenAI, modelo e chave protegida, teste de conectividade e limites. | Recursos de IA desligados até serem explicitamente habilitados. |
| `RF-AUTOMACOES-022` | Configuração geral de APIs e automações específicas delegadas por encargo. | Alteração de URL/segredo/template e envio são ações auditadas com permissões separadas. |
| `RF-AUTOMACOES-023` | Rotina de resumo de conversas do WhatsApp distinta do resumo da ata, desabilitada inicialmente. | Nenhuma coleta ou envio sem habilitação específica; políticas de privacidade e minimização definidas. |
| `RF-AUTOMACOES-024` | Comandos WhatsApp por grupo com parser, rate limit, destinos e respostas parametrizadas. | `!kit` não existe; `!senha` só no grupo de servidores com consulta autorizada; `!saldo` consulta livro financeiro do PostgreSQL. |
| `RF-AUTOMACOES-025` | Evento ASAAS válido e financeiramente elegível gera lançamento efetivo assim que processado; idem no manual ao salvar. | Deduplicar por ID do evento **e identidade do efeito de negócio**, ainda que eventos ASAAS diferentes refiram a mesma transação; Tesoureiro retifica/estorna depois, com auditoria. |


### RF-IDENTIDADE — Identidade operacional e reconciliação (4)

| ID | Comportamento obrigatório | Critério de aceite |
|---|---|---|
| `RF-IDENTIDADE-001` | Criar pessoa sem obrigar conta de usuário; permitir nome/apelido e opcionalmente WhatsApp, e-mail, estado e país. | Pessoa identificada por PK interna estável, não por apelido, telefone ou e-mail. |
| `RF-IDENTIDADE-002` | Busca obrigatória antes de novo cadastro de pessoa ou servidor; sugerir resultados semelhantes e só apresentar cadastro quando a pessoa não for encontrada. | Homônimos não são associados automaticamente; confirmação humana. |
| `RF-IDENTIDADE-003` | Vincular ingresso, conquista, temática e histórico de serviço a pessoa quando confirmada, mantendo o texto nominal original da ocorrência. | Mudança do apelido no cadastro não altera a ata histórica. |
| `RF-IDENTIDADE-004` | Servidores que utilizam o sistema recebem uma conta pessoal de login/senha vinculada obrigatoriamente a uma única `pessoa` (`usuarios.pessoa_id NOT NULL UNIQUE`), reaproveitando o cadastro prévio sem trocar o identificador. Jobs, provedores e integrações são **atores técnicos de auditoria**, não logins pessoais. | Conta humana só é criada após pessoa válida; pode continuar autenticável depois do encargo, mas sem capacidades do mandato encerrado. |

### RF-ROOT — Elevação excepcional de sessão (3)

| ID | Comportamento obrigatório | Critério de aceite |
|---|---|---|
| `RF-ROOT-001` | Sessão root é elevação secundária de usuário previamente autenticado, nunca login direto. | Reautenticar com segredo root, MFA, justificativa, TTL curto e confirmação individual de identidade. |
| `RF-ROOT-002` | Sessão root elevada possui acesso integral excepcional, inclusive Tesouraria e Kit. | Qualquer leitura sensível ou alteração registra identidade pessoal, modo elevado e motivo; sem elevação aplicam-se exclusividades ordinárias. |
| `RF-ROOT-003` | Revogar elevação por expiração, logout, inatividade ou ação administrativa, sem degradar proteção de autenticação pessoal. | Replay, sessão compartilhada e tentativa não autenticada falham. |

### RF-IA — Assistência e NotebookLM (4)

| ID | Comportamento obrigatório | Critério de aceite |
|---|---|---|
| `RF-IA-001` | Integração NotebookLM-py por REST/CLI/MCP apropriado, instância Docker/Swarm privada e habilitável. | Sem dependência do NotebookLM para abrir site ou registrar atas; diagnóstico de indisponibilidade. |
| `RF-IA-002` | Cadastro de notebooks pelo ID e finalidade, fontes geridas manualmente por servidores, prompt/forma de resposta configurável e versionado. | Selecionar notebook autorizado de treinamento, meditação ou literatura. |
| `RF-IA-003` | Consulta pública à literatura autorizada com respostas referenciadas e indicação de ausência de fonte. | Não consultar notebook interno por requisição pública; limitar abuso e conteúdo sem fonte. |
| `RF-IA-004` | Automação de meditação e treinamento com envio predominantemente a grupos WhatsApp autorizados. | IA desabilitada por padrão; modelo, destinatários, horário, prompt e execução configuráveis. |

### RF-ATENDIMENTO — Fale Conosco e triagem humana (2)

| ID | Comportamento obrigatório | Critério de aceite |
|---|---|---|
| `RF-ATENDIMENTO-001` | **Fale Conosco geral** recebe dúvidas, sugestões, reclamações, problemas e pedidos de ocultação/retificação, com relato em texto, contato de retorno opcional e protocolo. | Confirmar recebimento e **sempre avisar Coordenador Geral e Secretário Geral** por canal privado sanitizado; permitir pedido sem vínculo a entidade; iniciar análise humana sem alteração automática. |
| `RF-ATENDIMENTO-002` | Coordenador Geral e Secretário Geral triagem **todos os pedidos**; quando a solicitação identificar um registro, vinculá-lo ao chamado por tipo/ID permitidos ou posteriormente na análise, sem exigir esse dado para um contato genérico. Dados operacionais do Kit são tratados somente pelo Coordenador Kit após encaminhamento seguro. | Registrar responsável, decisão humana e motivo; se procedente, retificar/ocultar dado na fonte e projeções públicas com trilha e correlação ao protocolo, respondendo quando houver contato. Não expor PII do Kit à administração geral. |

### RF-RSG — Representação e relatório bimestral (1)

| ID | Comportamento obrigatório | Critério de aceite |
|---|---|---|
| `RF-RSG-001` | Geração de relatório bimestral do RSG com período inicial/final configuráveis e modelo do grupo. | Dados vêm do banco; RSG confere/complementa, exporta PDF e arquiva documento com referência. |

### RF-COMUNICACAO — Publicação e comunicação (2)

| ID | Comportamento obrigatório | Critério de aceite |
|---|---|---|
| `RF-COMUNICACAO-001` | Publicação de primeiro nome/apelido de ingressantes/conquistas segue prática opt-out com pedido humano de ocultação; sem contato/endereço públicos. | Pedidos não apagam automaticamente registros; nomes históricos sujeitos à revisão no lote de migração. |
| `RF-COMUNICACAO-002` | Informações da tesouraria para consulta pública por data, categoria, valor e saldo sem identificação de contribuinte. | Sem identificação pessoal, endereço, telefone, ID de pagador ou dado bancário individual. |

### Mapa dos ciclos de serviço e publicação

```mermaid
flowchart LR
  S[Rascunho de ata de serviço] --> F[Finalização oficial]
  F --> P[PDF versionado no Drive privado]
  F --> H[Agregados de serviço e histórico]
  F --> R{Correção ou anulação posterior?}
  R -->|Sim| V[Revisão imutável, motivo e auditoria]
  V --> N[Nova versão PDF, original preservado]
  L[Planilhas históricas] --> T[Importação fora do painel e triagem humana]
  T --> A[Publicação padrão, opt-out posterior]
```

## 4. Requisitos não funcionais e limites de proteção


| ID | Resultado esperado | Evidência de aceite / limiar |
|---|---|---|
| RNF-01 | HTTPS e sessões protegidas; não vazar segredos em repositório/HTML | Análise de configurações e testes de autenticação; parâmetros em arquitetura |
| RNF-02 | Senhas armazenadas como hash apropriado, política de recuperação | Revisão de segurança e testes positivos/negativos |
| RNF-03 | Autorização no servidor em toda rota/dado sensível | Matriz RBAC/ABAC validada por mesa; testes de acesso negado |
| RNF-04 | Auditoria de escrita sem expor segredos | Testes insert/update/delete, comparação de valores e retenção aprovada |
| RNF-05 | Minimização LGPD/anonimato, segregação Zoom, kit e finanças | Inventário de dados, regras de finalidade e testes de acesso/exportação |
| RNF-06 | Interface mobile-first acessível por teclado e leitor de tela | Testes em viewport pequeno e auditoria WCAG 2.1 AA como alvo proposto |
| RNF-07 | Home pública utilizável sem dependência de APIs/JS para dado crítico | Testes de bloqueio de terceiros e JS desligado |
| RNF-08 | Falhas e duplicações de webhook não geram lançamentos duplicados | Replay controlado, idempotência e testes de evento fora de ordem |
| RNF-09 | Tarefas/jobs podem falhar e retomar sem perda de integridade | Retries idempotentes, logs e tratamento de falha |
| RNF-10 | Tráfego de WhatsApp é limitado e estritamente autorizado | Testes de origem, frequência e comandos por grupo |
| RNF-11 | Backup/restauração utilizáveis | Restore de amostra e teste de recuperação em ambiente isolado |
| RNF-12 | Migração verificável e reversível | Reconciliação por total/entidade e relatório de divergências |
| RNF-13 | Documentação para servidores não técnicos | Teste de tarefa assistida apenas pelo material publicado |
| RNF-14 | Desempenho aceitável em conexão móvel e cargas típicas | Metas de tempo e tamanho **a estabelecer após baseline** |
| RNF-15 | Observabilidade sem vigilância individual | Logs e dashboards sem ranking nominal de presença |
| RNF-16 | Exportação em formatos abertos e controle de contingência | Amostra importável de export e procedimento operacional revisado |
| RNF-17 | Painel público não expõe registros privados via cache/consulta | Testes de consulta, exportação, índices de busca e cache |
| RNF-18 | Sistema single-group configurável e documentado para forks | Instalação limpa e parametrização sem códigos/credenciais do QuarenteNA |


**Importante:** os IDs `RNF-01` a `RNF-18` identificam requisitos não funcionais do único PRD, para rastreabilidade e testes. Acessibilidade equivalente a WCAG 2.1 AA constitui objetivo verificável de qualidade, sujeito a auditoria. SLA, RTO, RPO, retenção, volumes e p95 não foram definidos nos arquivos; não foram inventados.


## 5. Regras de negócio transversais


| Código | Regra e implicação |
|---|---|
| RB-01 | Ata oficial depende de autenticação e permissão; rascunhos não entram em indicadores oficiais |
| RB-02 | Dados sugeridos pelo Zoom nunca se tornam prova de presença pessoal; usuário corrige a contagem |
| RB-03 | Entrada/visita são conceitos distintos, com vínculo/contagem esclarecidos antes do relatório |
| RB-04 | **Atas regulares e de serviço**: corrigir conteúdo, revisar sem modificá-lo ou anular mantém autor, versão anterior, motivo, data, efeitos de relatório e documento oficial anterior imutável; a ata regular enviada só é retificada pelo remetente, Coordenador Geral, Secretário Geral ou root excepcional pessoalmente elevado |
| RB-05 | **Pessoa operacional** pode não ter login; **usuário humano** do sistema tem necessariamente `pessoa_id` e credencial pessoal; todos os servidores operadores usam contas individuais; não autogerar login por ingresso/Zoom nem representar jobs como usuários |
| RB-06 | Root institucional é contingência protegida; dados nominais/IDs fixos devem ser revisados fora de material público |
| RB-07 | O Kit usa contato/endereço exclusivamente para entrega e só seu Coordenador ativo acessa (exceto root pessoalmente elevado e auditado); mensagens privadas podem enviar o código ao destinatário, mas **nenhum grupo recebe contato/endereço/código individual**. Relatório público exibe apenas nome/apelido mínimo sujeito a opt-out, nunca PII do Kit. |
| RB-08 | ASAAS gera lançamento financeiro efetivo por evento validado; divergências/correções posteriores competem somente ao tesoureiro com trilha |
| RB-09 | Comandos respondem dentro de grupo e permissões configuradas; `!saldo` não pode vazar detalhes proibidos |
| RB-10 | Dados agregados públicos seguem política editorial do grupo; nomes/apelidos **mínimos** de ingresso/conquista são exceções específicas por opt-out, com Fale Conosco e revisão humana. Não publicar identidade Zoom nem dados de kit/contribuinte. |
| RB-11 | Designação tem titular obrigatório e status `ativo`/`concluido`/`entregue`/`destituido`; `VAGO` decorre de nenhuma designação ativa e vigente. Destituição segue deliberação do grupo ou hipótese institucional confirmada de **duas faltas injustificadas** ou **recaída**, com registro mínimo e sigiloso do fundamento, sem inferência automática por Zoom. Histórico não é destruído |
| RB-12 | As funções ativas e aprovadas das planilhas, scripts, workflows e relatórios são transferidas para operações nativas e auditáveis; `n8n`, Sheets, Apps Script e Looker perdem função operacional **após** cutover aceito, não antes |
| RB-13 | Nenhum fluxo automático substitui decisão de consciência coletiva ou validação humana sensível |
| RB-14 | Cadastro/fusão de pessoas demanda prevenção de colisão e validação, especialmente homônimos |


| Código | Regra e implicação |
|---|---|
| RB-15 | Mesa = Coordenador Geral, Secretário Geral, Tesoureiro, RSG, RSG Suplente e Coordenador Kit. Moderadores do dia compõem dupla escalada e podem alternar fala/chat, sem cargos fixos independentes |
| RB-16 | Permissão é derivada de encargo vigente: concessão na designação, revogação automática no término e imediata na entrega/destituição; usuário usa conta pessoal |
| RB-17 | Apenas tesoureiro altera finanças; apenas Coordenador Kit lê/gerencia contatos, endereços e remessas; admins da mesa cuidam de acessos; root elevado tem poderes integrais apenas de forma excepcional, pessoal e auditada |
| RB-18 | Root genérico institucional é excepcional para recuperação/configuração, com MFA, sessão justificada e operador identificado; jamais substituir login nominal habitual |
| RB-19 | Financeiro: salvar lança; ASAAS validado lança; alteração/exclusão lógica posterior gera auditoria completa, sem rascunho ou pendência de aprovação |
| RB-20 | Publicação **padrão**, por opt-out, somente do primeiro nome/apelido mínimo em registros de ingresso, conquista, eleição e demais registros editoriais autorizados; a pessoa pode pedir ocultação ou correção pelo Fale Conosco geral, com revisão humana pela Coordenação/Secretaria Geral e trilha. O legado é importado com `publicacao_status=em_triagem`, **não é publicado durante a importação**: reconciliar nomes, homônimos e inconsistências em lote; após liberação humana (`publicacao_status=liberado`), aplica-se a mesma publicação padrão, sem autorização individual prévia para cada registro. Sem contato/endereço, sobrenomes completos por padrão, Kit, identidade Zoom ou dados sensíveis. |
| RB-21 | Grafias fictícias como `Lia Exemplo`, `lia  exemplo` e `LIA EXEMPLO` são equivalências de busca, **não identidade única**; homônimos não são mesclados automaticamente |
| RB-22 | Modelo de ata de serviço editável pelo Secretário Geral; documentos no Google Drive institucional, com PostgreSQL autoritativo para metadados e regras |
| RB-23 | Rotinas de Apps Script/n8n migram para jobs/handlers Laravel com flags, agendamento e template editável; Redis/MinIO não são serviços obrigatórios |


## 6. Casos de aceitação ponta a ponta


| CA | Dado / cenário | Aceitação demonstrável |
|---|---|---|
| CA-01 | Visitante sem login em smartphone | Vê agenda, tipo, horário e CTA de Zoom; terceiros indisponíveis não bloqueiam |
| CA-02 | Secretário inicia ata, perde conexão e retorna | Rascunho preservado ou falha explicitada sem perda silenciosa; revisão e envio registrados |
| CA-03 | Zoom indisponível ou sugere contagem incorreta | Secretário pode inserir/corrigir presença; público não recebe lista nominal |
| CA-04 | Mesmo pagamento ASAAS em eventos diferentes ou repetidos | Um único efeito contábil por movimento, mesmo com IDs de eventos distintos; reversão/estorno legítimos têm efeito próprio e auditável |
| CA-05 | Usuário sem encargo vigente acessa kit/tesouraria/Zoom | Policies recusam leitura de kit e escrita financeira em interface e endpoint, inclusive para administração geral; sem PII nos logs |
| CA-06 | Ata enviada corrigida com justificativa | Alteração anterior/nova registrada; indicadores recalculados com status correto |
| CA-07 | Ingresso gera pendência de kit | Responsável autorizado acompanha pendência e expedição; dados pessoais invisíveis aos demais |
| CA-08 | Encargo se encerra ou está vago | Escala e comandos `!vago`/`!servidores` refletem estado atual |
| CA-09 | Importação com homônimos/datas faltantes | Sistema destaca conflitos e exige decisão humana; relatório mostra divergências |
| CA-10 | Relatório público financeiro | Agregados concisos visíveis sem identidade nem referência indevida a contribuintes |
| CA-11 | Webhook Evolution falso/duplicado | Mensagem rejeitada ou deduplicada; nenhum comando indevido executado |
| CA-12 | Incidente exige restauração | Backup restaurado em ambiente isolado, dados comparados e modo de contingência documentado |
| CA-13 | Novo servidor com celular | Conclui ata e consulta documentação sem instrução de planilhas, com observação de UX |
| CA-14 | Registros migrados de Sheets/Looker | Totais por período reconciliados; exceções assinadas pelo responsável |
| CA-15 | Instalação por fork para outro grupo | Configura nome, domínio, integrações e identidade sem herdar credenciais ou dados reais |


| CA | Dado / cenário | Aceitação demonstrável |
|---|---|---|
| CA-16 | Rastreamento de remessa com serviço indisponível | A consulta entra em retry controlado; kit continua acessível e atualizável; não há duplicidade de eventos nem vazamento de endereço. |
| CA-17 | Workflow migrado de n8n e Apps Script | A mesma entrada produz resultado equivalente e rastreável; erros geram incidentes reprocessáveis; fluxo anterior só é desligado após aceite. |
| CA-18 | Planilha com fórmulas e abas adicionais | Cada aba/campo/regra ativa é mapeada para destino ou descarte justificado; totais e cálculo do dashboard são comparados com a fonte. |


| CA | Dado / cenário | Aceitação demonstrável |
|---|---|---|
| CA-19 | Designação, fim e destituição de encargo | Atribuição habilita funções certas; prazo encerra direito mesmo sem execução de cron; entrega/destituição revoga imediatamente; histórico íntegro. |
| CA-20 | Dupla de moderadores da reunião diária | Dois escalados aptos a fala/chat alternam funções na ocorrência; ata usa usuários estáveis e nomes não duplicam cadastro. |
| CA-21 | Financeiro ASAAS e manual | Webhook válido contabiliza uma única vez; tesoureiro salva lançamento manual com efeito imediato; correção/exclusão lógica preserva antes/depois sem outro usuário conseguir editar. |
| CA-22 | Kit e relatório público | Apenas Coordenador Kit ativo (ou root pessoalmente elevado e auditado) consulta contato/endereço; relatório público usa apelido/primeiro nome mínimo sujeito a opt-out; nenhuma mensagem de grupo traz código de rastreio individual. |
| CA-23 | Administração técnica e root | Admins da mesa configuram APIs/webhooks com segredos mascarados; root como elevação de sessão pessoal, com MFA, justificativa, TTL e operador obrigatoriamente identificado. |
| CA-24 | Template de serviço e Drive | Secretário Geral versiona template, preenche rascunho persistente e finaliza ata; PDF privado nasce apenas na finalização, sem substituir versões anteriores; PostgreSQL guarda metadados. |
| CA-25 | Rotinas Laravel substituem cron e n8n | Desabilitar rotina impede envio; editar template altera próxima mensagem; `!ajuda`/`!vago` são roteados pelo webhook Evolution; replays não duplicam ações. |


### Casos de aceite 26–38 (integrantes do mesmo catálogo canônico)

| CA | Ensaio | Critério |
|---|---|---|
| `CA-26` | Mandato expira com scheduler desligado | Policy nega acesso específico; usuário conserva login pessoal. |
| `CA-27` | Usuário sem encargo eleva sessão root | Autenticação dupla, MFA, duração finita e operações com autoria individual, inclusive Kit/Tesouraria. |
| `CA-28` | Automação ASAAS e ajuste do tesoureiro | Um lançamento efetivo por evento, correção auditada sem alterar evento bruto. |
| `CA-29` | Usuário reusa apelido de homônimo | Busca apresenta alternativas; vínculo só mediante confirmação. |
| `CA-30` | Formulário recebe pedido de anonimato | Protocolo, confirmação, aviso restrito e análise humana; sem exclusão automática. |
| `CA-31` | Duas reuniões no mesmo dia | Disparos independentes 15 min antes, salvo configuração diferente. |
| `CA-32` | NotebookLM e OpenAI indisponíveis | Ata/serviço/financeiro permanecem operacionais; IA registra falha sem bloquear site. |
| `CA-33` | Pessoa deixa mandato | Histórico mantido, conta ativa sem capacidades do antigo encargo. |
| `CA-34` | Nome Zoom corrigido/duplicado | Lista restrita, sugestão deduplicada, total editável, retenção configurável. |

| CA | Ensaio | Critério |
|---|---|---|
| `CA-35` | Produção por domínio e Tunnel | Em `https://grupoquarentena.org/`, portal/Zoom/Fale Conosco funcionam, `/admin` exige login e nenhuma porta de app, PostgreSQL ou serviços privados fica acessível diretamente da Internet; rotas ASAAS/Zoom/Evolution passam pelas verificações apropriadas. |
| `CA-36` | Retificar/anular ata que já originou Kit | Corrige indicadores e histórico, notifica apenas o Coordenador Kit, abre revisão e preserva remessa já expedida sem cancelamento automático nem divulgação de endereço/telefone. |
| `CA-37` | Fale Conosco geral e eventual ocultação | Solicitação livre sem registro vinculado gera protocolo e aviso privado **aos dois** administradores; pedido sobre registro permite vínculo tipo/ID opcional, revisão humana e, se procedente, retificação auditada da projeção pública, seguida de resposta quando viável. |
| `CA-38` | Ciclo completo de ata de serviço e histórico legado | Secretário Geral abre template versionado, preenche e retoma rascunho, finaliza, gera PDF no Drive, registra totais de presentes/faltas/justificadas; corrige/anula com motivo, nova versão e PDF anterior preservado. Fonte antiga só com agregados alimenta relatórios sem inventar presenças nominativas. |


## 7. Entidades conceituais (NÃO é o schema físico)


| Domínio | Entidades candidatas / relacionamentos | Proteção |
|---|---|---|
| Acesso | Pessoa operacional, credencial opcional, papel, autorização extra, sessão | Cadastro não equivale a participante Zoom |
| Agenda | Reunião/tipo/horário, ocorrência, escala e serviço | Metadados públicos mínimos |
| Ata | Ata, status, revisões, ingressos, conquistas, visitas, temáticas | Relatórios agregados |
| Serviço | Encargo, mandato, reunião de serviço, ata, consciência coletiva | Restrições pela mesa |
| Kit | Pendência, destino de expedição, eventos de rastreio | Endereço/telefone restritos |
| Tesouraria | Lançamento, categoria, evento ASAAS, reconciliação | Dados do pagador restritos |
| Comunicação | Comando, grupos, mensagens, eventos e falhas | Conteúdo e retenção minimizados |
| Zoom | Evento de participante, referência de reunião, contador derivado | Dados brutos restritos/temporários |
| Governança | Auditoria, importação, erros, arquivos, backup, configuração | Segregação e trilha |


```mermaid
erDiagram
  PESSOA_OPERACIONAL ||--o{ CREDENCIAL : "pode ter"
  PESSOA_OPERACIONAL ||--o{ SERVICO : "pode cumprir"
  REUNIAO ||--o{ ATA : "gera"
  REUNIAO ||--o{ SERVICO : "tem escala"
  ATA ||--o{ ATA_REVISAO : "tem"
  ATA ||--o{ INGRESSO : "registra"
  INGRESSO ||--o| KIT : "pode originar"
  REUNIAO ||--o{ ZOOM_EVENTO : "correlaciona"
  ASAAS_EVENTO ||--o| LANCAMENTO : "pode gerar"
  PESSOA_OPERACIONAL ||--o{ AUDITORIA : "pratica alteracao"
```


### Contratos ponta a ponta para retificação e publicação

```mermaid
flowchart LR
  A[Nome mínimo no registro novo] --> P[Relatório público: opt-out]
  P --> F[Fale Conosco: pedido de ocultação/correção]
  F --> C[Protocolo e notificação CG + SG]
  C --> H{Revisão humana}
  H -->|Procede| R[Retifica registro e projeções com auditoria]
  H -->|Não procede| N[Resposta fundamentada]
  A --> K[Ingresso gera Kit restrito]
  K --> X{Ata retificada ou anulada?}
  X -->|Sim| V[Alertar Coordenador Kit e abrir revisão]
  V --> Q[Decisão humana, sem apagar remessas]
```

Para dados novos, a divulgação do **primeiro nome/apelido mínimo** segue a prática `opt-out`; ocultação pode ser solicitada pelo titular e não depende de integração Zoom. Dados históricos de planilhas passam antes por análise editorial e de privacidade. O alerta do Fale Conosco comunica **protocolo e tipo**, sem transmitir corpo contendo dados pessoais em grupos ou logs externos. Nomes/contatos do Kit não se tornam públicos devido à existência da ata.


## 8. Integrações, falhas e fronteiras


| Integração | Entrada ou saída | Dado mínimo necessário | Regras de falha |
|---|---|---|---|
| Zoom | Eventos de reunião/participante | Identificador evento/reunião, horário e contagem derivada | Validar origem; deduplicar; não criar usuário nem perfil de presença |
| ASAAS | Eventos financeiros | Identificador transação/evento, valor, status e categoria preliminar | Assinatura/autenticidade, idempotência e revisão de divergência |
| Evolution API | Mensagens e comandos por grupo | Grupo autorizado, comando, resultado e status | Autenticidade, controle de acesso, rate limit, retry e logs minimizados |
| Serviço `correios-rastreamento` + consulta CEP | Eventos de expedição e complementação de endereço | CEP e código quando necessários | Consentimento/finalidade, indisponibilidade e reconsulta manual |
| Google legado | Importação somente até o cutover | Export de cada planilha/documento com versão | Read-only, snapshot, reconciliação e conservação de evidências |
| Dashboard público | Saída agregada do sistema | Indicadores aprovados | Nenhum acesso direto a PII ou export restrito |


### Canal público oficial e implantação em produção

| Componente | Regra de entrega | Teste de aceite |
|---|---|---|
| Domínio | `GrupoQuarenteNA.org` será o endereço institucional do portal, com redirecionamento canônico de `www` **somente se configurado** | Agenda, Zoom e Fale Conosco acessíveis publicamente via HTTPS |
| Orquestração | Produção em Docker Swarm: Laravel, PostgreSQL, worker, scheduler e adaptadores privados necessários | `docker stack` saudável, volumes e persistência verificáveis |
| Entrada web | Cloudflare Tunnel (`cloudflared`) gerenciado e roteado ao serviço Laravel/reverse proxy na rede privada do Swarm | Sem publicação direta de portas do app/origem; acesso público preservado |
| Proteção | Cloudflare não substitui login/Policies; `/admin` protegido pela aplicação, rotas de webhook validadas por assinatura/segredo e políticas de borda compatíveis | Rotas públicas, restritas e webhooks funcionam; acessos indevidos negados |
| Contingência | Manter DNS, token do túnel, recuperação da configuração, restore do banco e monitoração de conectividade | Simular indisponibilidade do túnel/origem e verificar recuperação sem vazamento |

> `cloudflared` apenas cria conexões de saída; PostgreSQL, filas, NotebookLM e ferramentas administrativas **não terão hostname público nem portas de entrada abertas**. DNS/SSL reais, segredos e aceites operacionais são verificados na implantação, não presumidos prontos pelo documento.


## 9. Migração e virada operacional


```mermaid
flowchart LR
  A[Inventário das fontes] --> B[Snapshot e backup dos legados]
  B --> C[Mapeamento + validação de campos]
  C --> D[Varredura de problemas e sugestões de merge]
  D --> E[Revisão humana]
  E --> F[Importação controlada]
  F --> G[Reconciliar Looker/planilhas]
  G --> H{Paridade e testes aceitos?}
  H -->|Não| D
  H -->|Sim| I[Virada assistida]
  I --> J[Monitoramento + contingência]
```


| Gate | Critério mínimo de saída |
|---|---|
| M-1 Inventário | Fontes, responsáveis, fluxos e campos críticos conhecidos |
| M-2 Normalização | Inconsistências classificadas; merges apenas com confirmação humana |
| M-3 Migração | Logs e relatório por entidade, erro e import batch; backups íntegros |
| M-4 Paridade | Totais amostrais por reunião/data/valor conferidos com fontes antigas |
| M-5 Segurança | Permissões, webhooks, exposição pública e exportação testados |
| M-6 Operação | Servidores executam ata, kit, tesouraria e consulta sem planilhas |
| M-7 Reversibilidade | Restore exercitado; procedimento para retorno manual validado |
| M-8 Aprovação | Mesa registra aceite e responsáveis pela virada |


**Nota:** substituir o ecossistema principal é objetivo final. Os documentos não definem data de cutover, taxa máxima de erro aceitável nem duração de operação assistida; esses parâmetros não devem ser inventados.


## 10. Parâmetros operacionais para implementação


**O PRD está aprovado.** As decisões a seguir são parâmetros de execução, configuração, segurança ou migração que exigem dados reais e aprovação operacional no momento correto; não significam reabertura do escopo aprovado. A numeração é apenas ordinal (01–13), sem siglas inventadas.


| Nº | Parâmetro que demanda definição operacional ou verificação técnica | Responsabilidade |
|---|---|---|
| 01 | Agenda exata, tipos e definição de ocorrência para múltiplos horários | Conferência operacional |
| 02 | Matriz de acesso de mesa, moderadores, Tesoureiro exclusivo, Coordenador Kit exclusivo e mandato temporário **aprovada**; parametrizar prazos exatos | Parametrização da implementação |
| 03 | Política de retenção e finalidade de Zoom, mensagens e PII de ingressantes | Conferência operacional |
| 04 | Conta root excepcional aprovada; definir cofre de credenciais, MFA e custódia sem ID fixo | Arquitetura e segurança |
| 05 | Conciliação de visitas × ingresso × conquista e regra para homônimos | Conferência operacional |
| 06 | Validar a aplicação dos fluxos **já definidos** para retificação de ata regular (remetente/CG/SG/root) e serviço (Secretaria Geral/root), sem criar aprovações extras | Conferência operacional |
| 07 | Financeiro com lançamento imediato aprovado; mapear categorias e limites de divulgação agregada | Tesouraria e mesa |
| 08 | Inventário integral de comandos WhatsApp e resposta por grupo | Conferência operacional |
| 09 | Formatos, acesso e aceite da migração das fontes históricas | Conferência operacional |
| 10 | DNS, ownership técnico e conteúdo do domínio **já escolhido** `GrupoQuarenteNA.org` | Conferência operacional |
| 11 | Métricas e metas quantitativas (UX, p95, backup, disponibilidade) | Definição após medição |
| 12 | Limites legítimos de listas nominais e informes Zoom | Privacidade e mesa |
| 13 | Operação das integrações como atores técnicos identificados em auditoria, **sem contas humanas fictícias**, e teste de elevação root irrestrita | Mesa e técnico |


## 11. Governança do catálogo e rastreabilidade


Este é o **único documento `PRD.md`**. Cada ID é único e permanente dentro do projeto. A alteração de conteúdo deverá ser justificada em commit/PR e refletida, quando aplicável, nos testes, no UX e na arquitetura. Não existem requisitos concorrentes em arquivos alternativos.


| Relação | Regra |
|---|---|
| PRD → UX | Cada jornada principal identifica módulos e requisitos envolvidos |
| PRD → arquitetura | Cada regra transversal que afeta componentes, banco ou segurança tem uma decisão técnica correspondente |
| PRD → Story | Cada Story indica os RF, RNF, RB e CA satisfeitos |
| Story → teste | Aceite deve poder ser comprovado por teste automatizado ou ensaio documentado |
| Fonte antiga → dado novo | Migração registra origem, transformação, destino, erro e reconciliação |


**Nomenclatura:** `RF` = Requisito Funcional; `RNF` = Requisito Não Funcional; `RB` = Regra de Negócio; `CA` = Caso de Aceite. Nomes de domínio são escritos por extenso em português. `DESIGN.md`/`EXPERIENCE.md` são entregas separadas previstas pelo workflow UX oficial BMAD, mas este projeto fornece inicialmente sua especificação conjunta em `UX-Design.md`.


## 12. Critérios de qualidade BMAD e próximos entregáveis


| Etapa | Evidência necessária | Estado nesta entrega |
|---|---|---|
| Analysis / contexto | Inventário, fronteiras, histórico e conflitos marcados | Consolidado documentalmente |
| Product Brief | Problema, público, valor, metas e não objetivos | **APROVADO** |
| PRD | Funcionalidades com IDs, regras, RNFs e casos de aceite | **APROVADO** |
| Validação técnica do PRD | Testabilidade, segurança, rastreabilidade e contratos | A executar no gate BMAD, sem reabrir aprovação do escopo |
| UX Design | Fluxos/telas e acessibilidade de áreas público e admin | **APROVADO** em `UX-Design.md`; testes práticos integram homologação |
| Arquitetura | Decisões de runtime, banco, APIs, permissão, deploy, webhooks e recuperação | **APROVADA** em `Arquitetura.md`; migrations/infra ainda não executadas |
| Epics/stories | Slicing de ponta a ponta a partir de PRD **aprovado** | Não gerados nesta entrega |
| Implementation | Construção, testes, migração e cutover | Não iniciada por estes artefatos |


**Método e fontes:** [Pacote 6.12.4](https://www.npmjs.com/package/bmad-method/v/6.12.4), [documentação oficial](https://docs.bmad-method.org/) e [repositório](https://github.com/bmad-code-org/BMAD-METHOD). Usaram-se princípios de separação Analysis → Requirements → UX/Architecture → Stories e revisão explícita de divergências; o pacote não foi instalado nem executado.
