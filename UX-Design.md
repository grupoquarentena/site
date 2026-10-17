---
title: "UX Design — Grupo QuarenteNA"
project: Grupo QuarenteNA — Plataforma Web
artifact: ux-design
method: "BMAD 6.12.4 — DESIGN + EXPERIENCE em especificação única"
status: "PROJETO DE UX DERIVADO DO BRIEF E PRD APROVADOS; testes com usuários a executar"
date: "2026-10-17"
---

# UX Design — Site e Administração do Grupo QuarenteNA

> **Origem normativa:** [`ProductBrief.md`](ProductBrief.md) e [`PRD.md`](PRD.md), ambos **APROVADOS**. Inventário operacional: [`QuarenteNA.md`](QuarenteNA.md). **Este documento é uma proposta de UX**, não um protótipo validado. Mantém em um único Markdown a visão **DESIGN** (visual) e **EXPERIENCE** (comportamento) que o workflow oficial `bmad-ux` 6.12.4 normalmente produz em dois arquivos distintos. O template Laravel não define a aparência final.

## 1. Experiência desejada

A plataforma terá duas experiências distintas: **entrada pública simples e acolhedora**, sem login; e **administração orientada a tarefas**, pensada principalmente para servidores voluntários que usam celular. A interface acompanha o serviço, sem produzir vigilância de membros ou privilegiar conhecimento técnico.

| Público | Necessidade prioritária | Resultado observável | RF principais |
|---|---|---|---|
| Recém-chegado | Encontrar reunião e entender se pode participar | Localiza link, horário, tipo e orientações sem cadastro | `RF-PUBLICO-001…005` |
| Participante recorrente | Entrar imediatamente | Consegue acessar Zoom sem percorrer outras páginas | `RF-PUBLICO-002` |
| Secretário do dia | Preencher e corrigir a ata | Usa fluxo guiado, rascunho, conferência e envio no celular | `RF-ATA-001…025` |
| Mesa/servidores | Consultar serviço e decisões | Encontra escalas, vagas, decisões e histórico | `RF-SERVIDORES-*`, `RF-ATASERVICO-*` |
| Tesouraria | Conferir lançamentos e saldo | Concilia ASAAS e transações manuais com trilha | `RF-TESOURARIA-*` |
| Kit ingresso | Atender remessas com sigilo | Resolve endereço, remessa e rastreio com acesso restrito | `RF-KIT-*` |
| Administrador técnico | Manter sistema saudável | Enxerga jobs, integrações, incidentes, backup e auditoria | `RF-ADMINISTRACAO-*`, `RF-BACKUP-*` |

### Princípios de interface

| Prioridade | Princípio | Consequência no design |
|---|---|---|
| 1 | Anonimato e discrição | Nenhum nome/endereço de ingressante em visão pública ou em cartões globais |
| 2 | Reunião sempre encontrável | CTA principal único; não depender de script de terceiros para aparecer |
| 3 | Economia de atenção | Menus por tarefa e funções realmente autorizadas; sem painel lotado |
| 4 | Retomada segura | Rascunhos e estados persistentes; nunca confirmar envio que falhou |
| 5 | Visibilidade de responsabilidade | Mostrar autor, última alteração e estado em operações restritas |
| 6 | Correção sem apagamento | Revisar/anular sem destruir histórico auditável |
| 7 | Acessibilidade | Teclado, leitores de tela, foco e responsividade desde o primeiro componente |
| 8 | Linguagem do grupo | “Ata”, “Encargo”, “Vago”, “Kit ingresso”, “7ª Tradição”; explicar jargões técnicos |

## 2. Estrutura da informação

```mermaid
flowchart TD
  HOME[Portal público /] --> REUN[Agenda e Zoom]
  HOME --> SOBRE[Sobre NA / Grupo]
  HOME --> RELPUB[Relatórios públicos]
  HOME --> ENTRAR[Entrar no painel]
  ENTRAR --> LOGIN[Autenticação]
  LOGIN --> PAINEL[Painel pessoal /admin]
  PAINEL --> ATAS[Atas regulares]
  PAINEL --> SER[Servidores e encargos]
  PAINEL --> KIT[Kit ingresso]
  PAINEL --> TES[Tesouraria]
  PAINEL --> AS[Atas de serviço / decisões]
  PAINEL --> REL[Relatórios autorizados]
  PAINEL --> CONFIG[Administração técnica]
  CONFIG --> JOBS[Jobs / mensagens / integrações]
  CONFIG --> HIST[Auditoria / migração / backups]
```

### Navegação pública

| Ordem | Bloco | Objetivo | Conteúdo mínimo |
|---:|---|---|---|
| 1 | Cabeçalho discreto | Identidade oficial | Marca do grupo, navegação curta, acesso administrativo secundário |
| 2 | Hero da reunião | Ação prioritária | Botão “Entrar na reunião”, horário, fuso, tipo, eventual próxima ocorrência |
| 3 | Informação de elegibilidade | Reduzir dúvidas | Reunião aberta ou fechada, instruções claras e sem julgamento |
| 4 | O que é NA | Acolher | Texto breve, institucional e revisado |
| 5 | Sobre QuarenteNA | Reforçar legitimidade | História resumida e funcionamento |
| 6 | Agenda completa | Outras opções da semana | Dia, horário e formato, sempre derivados de dados oficiais |
| 7 | Apoio e transparência | Referências complementares | Diretório, relatórios agregados e contatos autorizados |
| 8 | Rodapé | Acesso institucional | Privacidade, referências e link de administração discreto |

### Navegação `/admin`

| Entrada de menu | Exibir para | Prioridade móvel | Observação |
|---|---|---|---|
| Início | Todos com login | Alta | Próxima tarefa autorizada e avisos operacionais |
| Preencher ata | Secretário/autorizados | Máxima | Atalho persistente e foco na ocorrência do dia |
| Atas | Autorizados | Alta | Consulta, rascunhos, revisão e correções |
| Encargos e vagas | Mesa/serviço | Alta | Escala, histórico e término |
| Kit ingresso | Responsável kit | Alta | Pendências prioritárias, remessas, rastreio |
| Tesouraria | Tesouraria/mesa autorizada | Alta | Conciliação, lançamentos e relatórios |
| Reuniões de serviço | Serviço autorizado | Média | Pauta, ata, decisões, PDF |
| Indicadores | Públicos ou restritos conforme política | Média | Agregações sem PII |
| Configurações | Administradores autorizados | Baixa | Credenciais técnicas não devem aparecer para servidores comuns |
| Auditoria / integrações | Administração técnica | Baixa | Páginas protegidas sem PII em logs |

> O menu é **filtrado pela permissão**, mas o backend faz a verificação real em cada ação. Esconder item de menu não autoriza nem protege endpoints.

## 3. DESIGN — direção visual

### Identidade e diretrizes

O site provisório e o planejamento antigo oferecem uma direção útil (azul profundo, branco e amarelo), mas **o site BMAD antigo nunca foi publicado**. A direção abaixo é **proposta de design**, não uma identidade visual homologada. A marca e o conteúdo oficial de NA precisam de conferência editorial do grupo.

| Token proposto | Valor ilustrativo | Finalidade | Observação |
|---|---|---|---|
| `--cor-fundo` | `#0B1120` | Fundo público profundo | Derivado da referência visual anterior; validar contraste |
| `--cor-superficie` | `#17243B` | Cards e navegação | Superfície elevada |
| `--cor-primaria` | `#38BDF8` | Links e elementos secundários | Não disputar atenção com CTA |
| `--cor-acao` | `#FACC15` | CTA principal público | Contraste forte com texto escuro |
| `--cor-texto` | `#F8FAFC` | Texto principal em fundo escuro | Priorizar legibilidade |
| `--cor-secundaria` | `#CBD5E1` | Texto auxiliar | Contraste a medir |
| `--cor-sucesso` | `#16764C` | Status de sucesso | Nunca usar cor como único sinal |
| `--cor-aviso` | `#9A5A06` | Avisos operacionais | Acompanhar ícone e texto |
| `--cor-erro` | `#B42318` | Erros relevantes | Explicitar consequência e resolução |
| `--cor-painel` | `#F7F9FC` | Superfícies administrativas claras | Proposta: admin claro para reduzir densidade |

**Tipografia:** sistema nativo acessível com fonte sans-serif de boa legibilidade; testar Inter disponível na referência HTML. O uso de fontes externas não pode impedir leitura quando CDN falhar. Base 16px; controles com alvo de toque confortável; hierarquia por tamanho, peso e espaçamento, não só por cor.

**Grid e espaçamento:** sistema de 4/8/12/16/24/32/48px; coluna única para formulários móveis; cards de largura limitada no desktop; tabelas complexas com versão resumida em cards e opção de detalhar/baixar sem rolagem horizontal obrigatória para tarefas essenciais.

### Comparação entre as duas superfícies

| Característica | Página pública | Administração |
|---|---|---|
| Ambiente | Sóbrio, acolhedor, marca presente | Objetivo, operacional e claro |
| Navegação | Poucas âncoras; CTA único | Navegação por módulos e tarefas permitidas |
| Cards | Agenda e informações essenciais | Pendências, rascunhos, alertas e filas |
| Formulários | Mínimos ou ausentes | Assistidos, com validação e retomada |
| Dados pessoais | Nunca expostos | Exibição sob finalidade/permissão |
| Gráficos | Apenas agregados aprovados | Agregados + operações dentro da autorização |

## 4. Padrões de componentes

| Componente | Estados obrigatórios | Regras |
|---|---|---|
| Botão primário | Normal, foco, carregando, desabilitado, sucesso/erro | Não mostrar sucesso antes da gravação |
| Campo | Vazio, preenchido, inválido, somente leitura, indisponível | Label externa persistente; erro associado ao campo |
| Autocomplete de pessoa | Buscando, nenhuma correspondência, múltiplas, seleção | Não fazer fusão automática de homônimos |
| Seletor de reunião | Agenda, horário, data, estado | Fuso `America/Bahia`; explicitar ocorrência |
| Tabela administrativa | Carregamento, vazia, paginada, filtro, erro | Exportação só com permissão |
| Modal de confirmação | Cancelar, confirmar, processando, concluído | Usar apenas em ações irreversíveis ou sensíveis |
| Banner de integração | Normal, degradado, indisponível, reprocessando | Distinguir falha do serviço externo de falha do banco |
| Status de ata | Rascunho, enviada, revisada, corrigida, anulada | Texto e explicação; não apenas cor |
| Status de kit | Verificar, falta endereço, falta enviar, enviado, recebido, devolvido, cancelado | Mudanças registram origem e autor |
| Aviso financeiro | Pendente, conciliado, divergente, estornado | Dados de contribuinte não aparecem publicamente |
| Gráfico | Sem dados, dados parciais, carregando, disponível | Eixos, unidades, período, fonte e tabela alternativa |

## 5. EXPERIENCE — fluxo principal da página pública

```mermaid
flowchart TD
  A[Acessar o site] --> B[Ver reunião e horário]
  B --> C{Quer entrar agora?}
  C -->|Sim| D[CTA direto para Zoom]
  C -->|Não| E[Ver tipo / próximas reuniões]
  E --> F[Informações NA e outras referências]
  F --> D
  D --> G[Serviço externo Zoom]
  G -->|Falha| H[Reexibir ID, senha e orientação de entrada]
```

### Wireframe informativo (proposta, desktop)

```text
┌─────────────────────────────────────────────────────────────────────────┐
│ QuarenteNA                                      Agenda  Sobre   Entrar │
├─────────────────────────────────────────────────────────────────────────┤
│ GRUPO QUARENTENA — NA ONLINE                                            │
│ Reuniões e acolhimento de pessoas que desejam parar de usar drogas     │
│                                                                         │
│ [ ENTRAR NA REUNIÃO ]    Próxima: dia • HH:MM • aberta/fechada          │
│                                                                         │
│ ID: ...        Senha: ...        Fuso: Bahia                             │
├─────────────────────────────────────────────────────────────────────────┤
│ Reuniões da semana         │ O que é NA?                                │
│ Dia / horário / tipo       │ Texto institucional curto                  │
├─────────────────────────────────────────────────────────────────────────┤
│ História e apoio                      Relatórios públicos aprovados    │
└─────────────────────────────────────────────────────────────────────────┘
```

### Wireframe informativo (proposta, smartphone)

```text
┌────────────────────────────┐
│ QuarenteNA         Menu    │
│ Próxima reunião: HH:MM     │
│                            │
│ ┌────────────────────────┐ │
│ │  ENTRAR NA REUNIÃO     │ │
│ └────────────────────────┘ │
│ Tipo  •  ID  •  Senha      │
│                            │
│ O que esperar?             │
│ Reuniões da semana         │
│ Sobre NA e o grupo         │
│ Relatórios / apoio         │
└────────────────────────────┘
```

**Validação a realizar:** teste real em 360px, 375px, 768px, 1024px e 1440px; zoom de 200%; navegação apenas por teclado, sem JS; leitura por tecnologia assistiva.

## 6. EXPERIÊNCIA — ata regular pelo celular

```mermaid
flowchart TD
  L[Login] --> O[Selecionar ocorrência]
  O --> R[Rascunho pré-preenchido]
  R --> C[Registrar contagens, visitas, ingressos, conquistas, temáticas]
  C --> S[Salvar rascunho]
  S --> V[Conferência final]
  V --> E{Consistente?}
  E -->|Não| C
  E -->|Sim| T[Enviar ata]
  T --> N[Recibo de envio e histórico]
  N --> J[Jobs de resumo, kit e indicadores]
```

### Etapas propostas para o assistente de ata

| Etapa | Campos/elementos | Validação em interface | Persistência |
|---:|---|---|---|
| 1. Reunião | Data, horário, tipo, secretário, coordenador | Mostrar ocorrência; conferir permissão e fuso | Rascunho inicial |
| 2. Indicadores | Membros, partilhas, visitantes, ingressos e conquistas | Números inteiros não negativos; explicar totais | Salvamento incremental |
| 3. Detalhes | Origem de visitas, nomes mínimos de ingressos, temática e partilhador autorizado | Se necessário, dados pessoais em seção protegida | Sub-registros vinculados |
| 4. Resumo | Texto gerado/editável, observações | Visualização prévia e alerta sobre anonimato | Rascunho do resumo |
| 5. Conferência | Dados completos, contagem Zoom como sugestão | Comparar valores; mostrar divergências sem impedir correção humana | Versão pronta para envio |
| 6. Confirmação | Recibo, status, hora e identificador da ata | Feedback após commit bem-sucedido | Ata enviada + trilha |

**Autossalvamento:** salvar localmente apenas informação não sensível temporária quando necessário e de modo consciente; a persistência definitiva deve ser do servidor. Não armazenar telefone/endereço sem necessidade em localStorage. Exibir status “Salvando”, “Salvo” e “Falha ao salvar”; prevenir envio duplo.

### Estados e comportamentos da ata

```mermaid
stateDiagram-v2
  [*] --> Rascunho
  Rascunho --> Enviada: envia e confirma
  Enviada --> Revisada: conferência autorizada
  Enviada --> Corrigida: retificação justificada
  Revisada --> Corrigida: retificação justificada
  Corrigida --> Revisada: conferência
  Enviada --> Anulada: anulação autorizada
  Revisada --> Anulada: anulação autorizada
  Corrigida --> Anulada: anulação autorizada
```

## 7. EXPERIÊNCIA — kit ingresso e rastreamento

```mermaid
flowchart TB
  IN[Ingresso confirmado] --> KIT[Kit pendente de verificação]
  KIT --> END{Endereço autorizado disponível?}
  END -->|Não| SOL[Solicitar dados em canal autorizado]
  SOL --> END
  END -->|Sim| PREP[Preparar embalagem]
  PREP --> POST[Registrar código de remessa]
  POST --> JOB[Consulta Correios por REST]
  JOB -->|Eventos válidos| HIST[Histórico de rastreamento]
  JOB -->|Falha| RET[Retry / consulta manual]
  RET --> JOB
  HIST --> FIM{Situação final?}
  FIM -->|Recebido| OK[Recebido]
  FIM -->|Devolvido| DEV[Devolvido]
  FIM -->|Em trânsito| JOB
```

| Tela / ação | Público autorizado | Dado exibido | Proteção |
|---|---|---|---|
| Fila de kits | Responsáveis e mesa conforme permissão | Status, pendência, dias de espera | Identificadores mínimos, sem endereço na listagem |
| Detalhe do kit | Responsável kit | Contato/endereço, consentimento, remessa, observações | Autorização server-side, auditoria, campos sensíveis |
| Registro de expedição | Responsável kit | Código de rastreio, data, transportadora, comprovante | Validar código e evitar repetição |
| Eventos de rastreio | Responsável kit | Data, evento, cidade/UF e estado | Sem PII pública; histórico institucional |
| Integração indisponível | Responsável kit | Última consulta e próximo retry | Atualização manual permitida e rastreável |

O rastreio é processado pelo projeto [correios-rastreamento](https://github.com/opastorello/correios-rastreamento). **A interface não chama a API externa diretamente do navegador**: Laravel consulta o serviço por rede privada e aplica suas autorizações.

## 8. EXPERIÊNCIA — tesouraria e relatórios

```mermaid
flowchart LR
  A[ASAAS / registro manual] --> B[Pendente de conferência]
  B --> C[Tesoureiro revisa categoria e valor]
  C --> D{Conciliado?}
  D -->|Não| E[Divergência / justificativa]
  E --> C
  D -->|Sim| F[Lançamento confirmado]
  F --> G[Relatório restrito]
  F --> H[Agregados públicos aprovados]
```

| Visualização | Público | Filtros | Restrições |
|---|---|---|---|
| Resumo financeiro público | Qualquer visitante | Período, categoria pública | Sem contribuinte, instrumento financeiro individual ou referência privada |
| Conciliação | Tesouraria | Datas, origem ASAAS/manual, status, categoria | Mostra divergências e comparação de valores |
| Dashboard de ata | Mesa/serviço autorizado | Reunião, dia, horário, período | Contagens agregadas, sem rastrear indivíduos Zoom |
| Quadro de encargos | Públicos/restritos conforme regra editorial | Data, encargo, reunião | Publicar apenas primeiro nome/apelido se autorizado |
| Dados de visitantes/ingresso | Responsável de registro | Período, origem e total | Contatos limitados ao kit e prazo justificado |

### Especificações de gráficos

| Gráfico | Eixos/medidas | Fonte | Alternativa acessível |
|---|---|---|---|
| Presença por data | X=ocorrência; Y=total de participantes | Atas confirmadas | Tabela data/horário/total |
| Visitas/ingressos/conquistas | X=período; Y=contagens | Sub-registros de ata | Tabela de indicadores |
| Distribuição semanal | X=dia/horário; Y=total/média | Ocorrências e atas | Tabela com total e média |
| Saldo financeiro | X=período; Y=valor e saldo | Lançamentos conciliados | Tabela de totais |
| Kits por estado | Categorias de status e quantidade | Kits/remessas | Tabela status/quantidade |
| Encargos | Ativos, vagos e próximos ao fim | Designações | Tabela de cargos e datas |

## 9. Erros, privacidade e acessibilidade

| Situação | Mensagem ao usuário | Comportamento requerido |
|---|---|---|
| Sessão expirada | “Sua sessão expirou. Entre novamente.” | Não perder rascunho confirmado no servidor; não vazar a tentativa |
| Sem permissão | “Você não tem acesso a esta função.” | Código 403; nunca mostrar dados em HTML/JSON |
| Falha ao salvar ata | “A ata não foi salva. Revise a conexão e tente novamente.” | Preservar dados em tela sem informar sucesso falso |
| Webhook atrasado | “Dados do Zoom ainda não disponíveis.” | Entrada manual e conferência continuam possíveis |
| Serviço de rastreamento fora | “Consulta indisponível no momento.” | Último evento permanece e job terá retry controlado |
| Duplicidade potencial | “Encontramos possíveis registros semelhantes.” | Não fundir nem apagar automaticamente |
| Importação parcial | “Existem registros pendentes de conferência.” | Lista de exceções e relatório de paridade |
| Desativação de usuário | “Acesso desativado.” | Revogar tokens/sessões conforme política |
| Ausência de dados | “Nenhum registro no período selecionado.” | Não exibir zeros como se fossem dados validados |

**Acessibilidade exigida pelo projeto:** estrutura semântica, contraste conforme WCAG 2.1 AA como alvo, labels associadas, erros anunciados, foco visível, navegação teclado, conteúdo sem cor como único significado, botões/tabelas operáveis em celular, respeito a preferência por movimento reduzido e gráficos com tabela acessível. As metas numéricas finais serão ensaiadas no gate de UX.

**Privacidade:** não registrar em analytics público nome de ingressante, telefone, endereço, identificação Zoom, dados de pagador ou conteúdo integral de mensagens. Nunca mostrar segredos em logs, toast, relatórios ou exportações comuns.

## 10. Critérios de aceite de UX

| Teste | Roteiro objetivo | Aceite |
|---|---|---|
| Público sem login | Abrir `/` com JavaScript desativado | CTA, data/horário, ID e orientação disponíveis |
| Primeiro acesso | Pessoa que não conhece o grupo localiza reunião | Sem leitura longa nem conta; avaliar tempo em teste observado |
| Ata móvel | Secretário preenche, corrige, interrompe e retoma | Dados recuperáveis, revisão visível, envio único |
| Kit restrito | Usuário sem permissão tenta URL/API do detalhe | Nenhum dado de contato exibido |
| Tesouraria | Tesoureiro identifica lançamento duplicado ASAAS | Sem saldo duplicado e com feedback compreensível |
| Dashboard público | Consultar e exportar agregados | Sem PII nem filtros que revelem indivíduos |
| Falha de integração | Desligar Correios/Zoom/Evolution simuladamente | Fluxo interno preservado e aviso adequado |
| Teclado e leitores de tela | Executar rotas e formulários críticos | Ordem de foco/labels/erros coerentes |
| Navegação responsiva | 360px e desktop | Tarefas concluíveis sem zoom ou rolagem horizontal essencial |

## 11. Decisões de UX a homologar com usuários

O Brief e PRD estão **aprovados**. Os seguintes pontos são decisões de apresentação que exigem teste, não reabertura do escopo: identidade visual final/logo; contraste da paleta; disposição do menu; ordem exata das telas; quantidade de passos da ata; textos de validação; acessibilidade real; terminologia e permissões de visualização por encargo. Prototipar as telas críticas e confrontar com servidores em contexto real.

## 12. Vinculação ao método e às fontes

- BMAD 6.12.4: [Design UX and Architecture](https://github.com/bmad-code-org/BMAD-METHOD/blob/v6.12.4/docs/plan/design-ux-and-architecture.md), com separação conceitual entre visual (`DESIGN.md`) e funcionamento (`EXPERIENCE.md`).
- Referências internas canônicas: [`ProductBrief.md`](ProductBrief.md), [`PRD.md`](PRD.md), [`QuarenteNA.md`](QuarenteNA.md), [`Arquitetura.md`](Arquitetura.md).
- Framework-base: [template-laravel-bmad](https://github.com/cirinho/template-laravel-bmad); **a UX não precisa herdar interface comercial de clientes** do template.
