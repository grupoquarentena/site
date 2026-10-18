# site - Epic Breakdown

> **Documento histórico.** Planejamento por épicos e stories do MVP PHP (março de
> 2026), **nunca publicado em produção**. Consolidado em 18/10/2026 no
> arquivamento da branch `legado/site-2026` a partir de `epics.md` e do
> `implementation-readiness-report-2026-03-24.md` (resumo abaixo). Nenhuma story
> nova foi criada e nenhum estado foi atualizado neste arquivamento.

## Overview

This document provides the complete epic and story breakdown for site, decomposing the requirements from the PRD, UX Design and Architecture into implementable stories.

## Prontidão de implementação avaliada em 24/03/2026 (registro histórico)

Em 24/03/2026, o conjunto PRD + UX + Arquitetura + Épicos foi submetido ao fluxo
BMAD de prontidão de implementação (avaliação conduzida por Codex; o relatório
original `implementation-readiness-report-2026-03-24.md` foi consolidado neste
resumo). Resultados e ressalvas registrados na época:

- **Resultado:** `NEEDS WORK` no escopo completo do projeto, com **MVP público
  considerado READY**. O status geral refletia apenas os Épicos 7–9 (admin
  futuro), que exigiriam novo ciclo de descoberta antes de qualquer build.
- **Cobertura de requisitos:** 33/33 FRs mapeados para épicos/stories (100%);
  nenhuma lacuna de requisito no MVP.
- **Qualidade dos épicos:** Épicos 1–5 entregam valor de usuário, funcionam de
  forma independente, sem dependências adiantadas e com stories em tamanho de MVP.
- **Critérios de qualidade avaliados:** valor de usuário por épico, independência,
  dimensionamento das stories, ausência de dependências futuras, critérios de
  aceite Given/When/Then, rastreabilidade a FRs e momento de criação de
  banco/entidades (nenhum no MVP).
- **Alinhamento UX:** sem desalinhamento crítico no MVP; a trilha de `/admin`
  exigiria novo ciclo de UX se entrasse em execução imediata.
- **Limitações registradas na época:** a copy pública ainda dependia de aprovação
  humana do grupo; o checklist operacional precisaria ser adotado pelo time de
  conteúdo; os NFRs práticos (resiliência, acessibilidade, analytics) dependeriam
  de desdobramento em critérios de teste.

**Ressalva de arquivamento:** este é um registro *documental* de março de 2026.
O MVP **nunca entrou em produção** e não deve ser publicado; o resumo não é uma
certificação atual de prontidão nem autorização de lançamento.

## Requirements Inventory

### Functional Requirements

FR1: Visitantes podem acessar o link principal da reunião a partir do site público.  
FR2: Visitantes podem visualizar horário, ID, senha e tipo da reunião do dia antes de tentar entrar.  
FR3: Visitantes podem entender se a reunião atual é aberta ou fechada antes de participar.  
FR4: Participantes recorrentes podem confirmar se a reunião está acontecendo agora, mais tarde no dia ou no próximo horário previsto.  
FR5: Participantes recorrentes podem retornar rapidamente ao caminho principal de entrada da reunião a partir da home.  
FR6: Visitantes podem acessar as informações essenciais da reunião mesmo quando recursos complementares externos estiverem indisponíveis.  
FR7: Visitantes podem entender o que é Narcóticos Anônimos a partir do conteúdo público do site.  
FR8: Visitantes podem entender o que é o Grupo QuarenteNA e como ele se relaciona com NA.  
FR9: Visitantes podem avaliar se o grupo é relevante para sua situação antes de entrar na reunião.  
FR10: Público geral pode compreender a diferença entre reunião aberta e reunião fechada.  
FR11: Visitantes podem receber orientação acolhedora e não julgadora antes de decidir participar.  
FR12: Público geral pode reconhecer o site como ponto oficial de referência do grupo.  
FR13: Público geral pode acessar uma história resumida do Grupo QuarenteNA.  
FR14: Público geral pode acessar relatórios públicos disponibilizados pelo grupo.  
FR15: Público geral pode acessar informações sobre a frequência regular das reuniões e o contexto público de funcionamento do grupo.  
FR16: Público geral pode acessar referências para outras reuniões e para o diretório oficial de reuniões virtuais como caminhos de apoio.  
FR17: Público geral pode acessar relatórios públicos sem exposição de informações pessoais identificáveis de membros.  
FR18: Participantes podem acessar o link do grupo de WhatsApp de membros a partir do site quando isso for apropriado.  
FR19: Visitantes podem acessar caminhos alternativos de apoio quando o Grupo QuarenteNA não for a única referência desejada.  
FR20: Público geral pode navegar para recursos externos aprovados sem perder a clareza sobre o caminho principal de entrada na reunião.  
FR21: Servidores voluntários podem manter as informações públicas da reunião a partir de uma fonte única e autoritativa.  
FR22: Servidores voluntários podem atualizar os dados públicos da reunião sem editar múltiplas cópias independentes da mesma informação.  
FR23: Servidores voluntários podem publicar conteúdo institucional preservando as regras de anonimato do grupo.  
FR24: Servidores voluntários podem referenciar responsáveis publicamente usando apenas primeiro nome ou apelido quando isso for necessário.  
FR25: Servidores voluntários podem revisar fotos, depoimentos e outros materiais públicos antes da publicação para evitar identificação indevida de membros.  
FR26: Público geral pode descobrir o site por mecanismos de busca através de páginas públicas indexáveis.  
FR27: Grupo QuarenteNA pode medir acessos e eventos principais de forma agregada e sem identificar visitantes individualmente.  
FR28: Grupo QuarenteNA pode acompanhar, em nível agregado, interações com o link principal da reunião, o link do WhatsApp e os links de relatórios ou apoio.  
FR29: Servidores voluntários podem autenticar-se em uma área administrativa futura.  
FR30: Servidores voluntários podem gerenciar dados da reunião por meio da área administrativa futura.  
FR31: Servidores voluntários podem gerenciar atas, relatórios e dashboards por meio da área administrativa futura.  
FR32: Servidores voluntários podem migrar dados operacionais de planilhas e ferramentas externas para registros internos gerenciados.  
FR33: Servidores voluntários podem acessar capacidades administrativas de acordo com seu papel na estrutura de serviço do grupo.

### NonFunctional Requirements

NFR1: O conteúdo crítico da home deve estar disponível para uso em até 3 segundos em conexão móvel típica.  
NFR2: O acesso ao link principal da reunião não deve depender do carregamento bem-sucedido de recursos de terceiros.  
NFR3: A página inicial deve priorizar carregamento progressivo do conteúdo principal antes de elementos complementares.  
NFR4: A landing page pública deve perseguir disponibilidade mensal mínima de 99,5% no MVP.  
NFR5: O site deve continuar entregando link, horário, ID, senha, tipo da reunião e contexto básico do grupo mesmo quando recursos externos estiverem indisponíveis.  
NFR6: Falhas em relatórios públicos, diretórios externos ou links de apoio não podem impedir o acesso ao caminho principal da reunião.  
NFR7: O MVP não deve exigir criação de conta, login público ou coleta de dados pessoais para acesso ao conteúdo aberto.  
NFR8: O site deve operar com transporte seguro via HTTPS em todas as páginas públicas sob controle do projeto.  
NFR9: Medição de uso deve ser agregada e não identificável, sem perfil individual de visitante no MVP.  
NFR10: O conteúdo público não deve expor nomes completos, fotos identificáveis ou depoimentos identificáveis de membros sem validação explícita.  
NFR11: Os fluxos públicos principais devem atender, no mínimo, a um nível prático equivalente a WCAG 2.1 AA.  
NFR12: O site deve oferecer skip link para o conteúdo principal e permitir acesso ao CTA da reunião apenas com teclado.  
NFR13: O conteúdo textual crítico deve permanecer legível em telas móveis comuns sem exigir zoom ou rolagem horizontal.  
NFR14: O MVP deve funcionar adequadamente nas versões recentes de Chrome, Edge, Firefox e Safari, em desktop e mobile.  
NFR15: O layout deve ser utilizável a partir de larguras móveis pequenas e escalar de forma consistente para telas maiores.  
NFR16: As páginas públicas devem ser rastreáveis por mecanismos de busca sem dependência de execução obrigatória de JavaScript para o conteúdo principal.  
NFR17: Cada página pública relevante deve possuir título, descrição e hierarquia semântica coerentes com o objetivo de descoberta orgânica.  
NFR18: Integrações ou links para WhatsApp, Looker Studio e diretórios externos devem falhar de forma graciosa, sem quebrar a home.  
NFR19: O caminho principal da reunião deve permanecer claro e acessível mesmo quando o usuário navegar para recursos externos de apoio.  
NFR20: As informações da reunião no MVP devem ser atualizáveis a partir de uma única fonte de verdade.  
NFR21: Uma alteração de link, horário, ID, senha ou tipo da reunião não deve exigir manutenção em múltiplos pontos independentes da interface.

### Additional Requirements

- AR1: O MVP usa PHP server-rendered, sem SPA e sem banco de dados.
- AR2: A superfície pública do MVP é mínima e centrada em uma home oficial.
- AR3: A fonte única de verdade vive em um arquivo versionado `app/data/meeting.php`.
- AR4: O status da reunião suporta `agora`, `mais tarde hoje` e `próxima reunião`, com `status_override` opcional.
- AR5: Recursos externos são complementares e devem falhar sem quebrar o fluxo principal.
- AR6: Analytics do MVP é agregado, anônimo e pode usar logs/redirecionamentos internos.
- AR7: Todo conteúdo público deve seguir checklist de anonimato antes de publicar.
- AR8: O fuso padrão do grupo é `America/Sao_Paulo`.
- AR9: `/admin` fica explicitamente fora do MVP e isolado como evolução futura.
- AR10: O deploy do MVP deve funcionar em hospedagem PHP simples com HTTPS.

### UX Design Requirements

UX-DR1: A home deve exibir o CTA principal da reunião acima da dobra em viewport de 360px sem depender de JavaScript.  
UX-DR2: O card principal da reunião deve exibir `horário`, `ID`, `senha`, `tipo` e `status` no mesmo bloco visual.  
UX-DR3: O status da reunião deve suportar exatamente `agora`, `mais tarde hoje` e `próxima reunião`.  
UX-DR4: A diferença entre reunião `aberta` e `fechada` deve aparecer perto do contexto da reunião.  
UX-DR5: A mensagem de acolhimento deve ser curta e não competir com o CTA.  
UX-DR6: A seção `O que é NA` deve responder à dúvida em linguagem curta.  
UX-DR7: A seção `Sobre o Grupo QuarenteNA` deve reforçar legitimidade e frequência regular.  
UX-DR8: A história do grupo deve aparecer em versão resumida.  
UX-DR9: Links complementares devem ser visualmente secundários ao CTA principal.  
UX-DR10: O link de WhatsApp deve parecer complementar e não principal.  
UX-DR11: A home deve oferecer skip link, foco visível, navegação por teclado e landmarks semânticos.  
UX-DR12: O conteúdo crítico deve permanecer legível em mobile sem zoom nem rolagem horizontal.  
UX-DR13: Os estados de foco, hover e ativo do CTA principal devem ter contraste forte.  
UX-DR14: Recursos externos indisponíveis não podem obscurecer o card principal da reunião.  
UX-DR15: A hierarquia visual de desktop deve preservar a prioridade definida para mobile.  
UX-DR16: Links externos devem ser identificados como recursos complementares.  
UX-DR17: A paleta do MVP usa base azul, texto claro e acento amarelo para CTA e foco.  
UX-DR18: A interface pública deve evitar fotos identificáveis, nomes completos e depoimentos identificáveis.

### FR Coverage Map

FR1: Epic 1 / Stories 1.1 e 1.2 - home oficial e CTA principal  
FR2: Epic 1 / Story 1.2 - card da reunião com dados completos  
FR3: Epic 1 / Story 1.3 - tipo aberta/fechada no bloco principal  
FR4: Epic 1 / Story 1.4 - status agora/mais tarde/próxima reunião  
FR5: Epic 1 / Story 1.1 - retorno rápido ao caminho principal  
FR6: Epic 1 / Story 1.5 - fluxo principal resiliente a falhas externas  
FR7: Epic 2 / Story 2.2 - explicação curta sobre NA  
FR8: Epic 2 / Story 2.3 - apresentação do Grupo QuarenteNA  
FR9: Epic 2 / Stories 2.1, 2.2 e 2.4 - acolhimento, contexto e elegibilidade  
FR10: Epic 2 / Story 2.4 - explicação aberta/fechada  
FR11: Epic 2 / Story 2.1 - microcopy acolhedora  
FR12: Epic 1 / Stories 1.1 e 1.2; Epic 2 / Story 2.3 - legitimidade visual e informacional  
FR13: Epic 2 / Story 2.5 - história resumida  
FR14: Epic 3 / Story 3.1 - acesso ao relatório público  
FR15: Epic 2 / Story 2.3 - frequência e contexto do grupo  
FR16: Epic 3 / Stories 3.2 e 3.4 - diretório e caminhos de apoio  
FR17: Epic 3 / Story 3.1 e Epic 4 / Story 4.3 - publicação segura de relatórios  
FR18: Epic 3 / Story 3.3 - link de WhatsApp  
FR19: Epic 3 / Stories 3.2 e 3.4 - caminhos alternativos de apoio  
FR20: Epic 3 / Story 3.4 - navegação externa sem perder clareza  
FR21: Epic 4 / Story 4.1 - fonte única de verdade  
FR22: Epic 4 / Story 4.2 - atualização sem múltiplas cópias  
FR23: Epic 4 / Stories 4.3 e 4.4 - anonimato e revisão editorial  
FR24: Epic 4 / Story 4.4 - uso de primeiro nome/apelido quando necessário  
FR25: Epic 4 / Story 4.3 - revisão de imagens, depoimentos e materiais públicos  
FR26: Epic 5 / Story 5.1 - SEO e indexabilidade  
FR27: Epic 5 / Story 5.3 - analytics agregado e anônimo  
FR28: Epic 5 / Story 5.3 - tracking mínimo de eventos principais  
FR29: Epic 7 / Story 7.2 - autenticação em área administrativa futura  
FR30: Epic 8 / Story 8.1 - gestão de dados da reunião no admin  
FR31: Epic 9 / Story 9.1 - atas, relatórios e dashboards internos  
FR32: Epic 9 / Story 9.2 - migração de dados operacionais  
FR33: Epic 7 / Story 7.3 - acesso por papel/role

## Epic List

### Epic 1: Acesso Oficial à Reunião
Visitantes e participantes recorrentes conseguem localizar e usar o caminho principal da reunião em segundos, com informações logísticas visíveis e resilientes.
**FRs covered:** FR1, FR2, FR3, FR4, FR5, FR6, FR12

### Epic 2: Acolhimento e Contexto Público Essencial
Visitantes entendem o que é NA, o que é o Grupo QuarenteNA e se a reunião pode fazer sentido para sua situação antes de entrar.
**FRs covered:** FR7, FR8, FR9, FR10, FR11, FR13, FR15

### Epic 3: Recursos Complementares e Continuidade
O público acessa relatórios e caminhos de apoio sem perder a clareza sobre o fluxo principal da reunião.
**FRs covered:** FR14, FR16, FR17, FR18, FR19, FR20

### Epic 4: Governança Editorial e Operação Manual Segura
Servidores voluntários mantêm o conteúdo público correto, centralizado e aderente às regras de anonimato.
**FRs covered:** FR21, FR22, FR23, FR24, FR25

### Epic 5: Descoberta, Acessibilidade e Medição do MVP
O MVP se torna encontrável, acessível, observável e pronto para lançamento público sem inflar o escopo.
**FRs covered:** FR26, FR27, FR28

### Epic 6: Expansão Pública Pós-MVP
O site amplia sua camada pública com melhor organização de conteúdo, relatórios e governança editorial sem sacrificar a simplicidade original.
**FRs covered:** Growth scope only

### Epic 7: Fundação do Admin Interno
Servidores voluntários passam a acessar uma área interna segura, com autenticação e permissões alinhadas à estrutura de serviço do grupo.
**FRs covered:** FR29, FR33

### Epic 8: Operação Interna de Dados da Reunião
Servidores voluntários gerenciam dados operacionais da reunião e conteúdo essencial por meio da área administrativa.
**FRs covered:** FR30

### Epic 9: Registros, Migração e Dashboards Internos
O grupo migra registros operacionais para dentro do produto e passa a gerir atas, relatórios e dashboards internos.
**FRs covered:** FR31, FR32

## Epic 1: Acesso Oficial à Reunião

Visitantes e participantes recorrentes conseguem localizar e usar o caminho principal da reunião em segundos, com informações logísticas visíveis e resilientes.

### Story 1.1: Home Oficial com CTA Primário

As a visitante ou participante recorrente,  
I want abrir uma home oficial que destaque imediatamente a entrada da reunião,  
So that eu saiba em segundos onde clicar.

**Acceptance Criteria:**

**Given** que o usuário acessa a home em mobile ou desktop  
**When** a página termina o carregamento inicial  
**Then** o CTA principal da reunião aparece acima da dobra  
**And** ele é o único elemento visual tratado como ação primária  
**And** a home pode ser consumida sem JavaScript obrigatório.

### Story 1.2: Card da Reunião com Dados Completos

As a visitante,  
I want ver horário, ID, senha e tipo da reunião no mesmo bloco visual,  
So that eu possa decidir entrar com segurança.

**Acceptance Criteria:**

**Given** que existe uma reunião configurada na fonte única de verdade  
**When** a home é renderizada  
**Then** o card principal exibe `horário`, `ID`, `senha` e `tipo` no mesmo contexto visual  
**And** nenhum desses dados é duplicado manualmente em outro ponto crítico da interface.

### Story 1.3: Tipo de Reunião e Elegibilidade no Bloco Principal

As a recém-chegado,  
I want entender se a reunião é aberta ou fechada perto do CTA principal,  
So that eu saiba se posso participar sem procurar essa informação em outra seção.

**Acceptance Criteria:**

**Given** que a reunião possui um tipo definido  
**When** o card principal é exibido  
**Then** o tipo `aberta` ou `fechada` aparece com copy explicativa curta  
**And** a explicação não exige navegação para outra página  
**And** o entendimento do tipo não depende apenas de cor.

### Story 1.4: Status Agora, Mais Tarde Hoje ou Próxima Reunião

As a participante recorrente,  
I want ver se a reunião está acontecendo agora, mais tarde hoje ou no próximo horário,  
So that eu entenda rapidamente se devo entrar ou aguardar.

**Acceptance Criteria:**

**Given** que a fonte única de verdade informa timezone e janela de reunião ou `status_override`  
**When** a home é renderizada  
**Then** o sistema mostra exatamente um entre os estados `agora`, `mais tarde hoje` ou `próxima reunião`  
**And** o cálculo usa o timezone configurado do grupo  
**And** existe fallback manual quando a agenda não permitir cálculo automático seguro.

### Story 1.5: Fluxo Principal Resiliente a Falhas Externas

As a visitante,  
I want continuar encontrando a entrada da reunião mesmo quando recursos externos falham,  
So that o site mantenha sua função principal.

**Acceptance Criteria:**

**Given** que WhatsApp, relatório ou diretório estejam indisponíveis  
**When** a home é acessada  
**Then** o CTA principal e o card da reunião continuam visíveis e utilizáveis  
**And** blocos complementares podem degradar ou ocultar sem quebrar o layout principal  
**And** a página continua cumprindo `FR1` a `FR6`.

## Epic 2: Acolhimento e Contexto Público Essencial

Visitantes entendem o que é NA, o que é o Grupo QuarenteNA e se a reunião pode fazer sentido para sua situação antes de entrar.

### Story 2.1: Mensagem Curta de Acolhimento

As a recém-chegado inseguro,  
I want receber uma mensagem curta e acolhedora perto do CTA principal,  
So that eu sinta menos atrito antes de entrar.

**Acceptance Criteria:**

**Given** que o usuário chega pela primeira vez à home  
**When** ele vê a área principal da página  
**Then** existe uma mensagem de acolhimento curta e não julgadora  
**And** ela aparece sem competir visualmente com o CTA  
**And** o texto evita jargão institucional pesado.

### Story 2.2: Bloco O que é NA

As a visitante,  
I want entender rapidamente o que é Narcóticos Anônimos,  
So that eu avalie se este espaço é relevante para minha situação.

**Acceptance Criteria:**

**Given** que o usuário precisa de contexto antes de entrar  
**When** ele avança abaixo da dobra  
**Then** encontra um bloco curto `O que é NA` em linguagem simples  
**And** o bloco responde à dúvida sem exigir leitura longa  
**And** o texto não expõe membros nem promete resultados indevidos.

### Story 2.3: Bloco Sobre o Grupo QuarenteNA

As a visitante,  
I want entender o que é o Grupo QuarenteNA e sua frequência regular,  
So that eu reconheça o site como referência oficial do grupo.

**Acceptance Criteria:**

**Given** que o conteúdo institucional foi aprovado para publicação  
**When** a home apresenta o bloco do grupo  
**Then** o texto explica a relação do grupo com NA e o contexto público de funcionamento  
**And** comunica legitimidade e regularidade sem expor identidades pessoais.

### Story 2.4: Explicação Aberta versus Fechada

As a visitante,  
I want entender a diferença entre reunião aberta e fechada,  
So that eu saiba se posso participar antes de clicar no link principal.

**Acceptance Criteria:**

**Given** que o site apresenta uma reunião com tipo definido  
**When** o usuário lê a explicação de elegibilidade  
**Then** ele encontra a diferença entre `aberta` e `fechada` em copy clara  
**And** a explicação é compatível com as regras de anonimato e comunicação pública do grupo.

### Story 2.5: História Resumida do Grupo

As a público geral,  
I want acessar uma história resumida do grupo,  
So that eu perceba continuidade e existência real do QuarenteNA.

**Acceptance Criteria:**

**Given** que o usuário procura sinais de legitimidade  
**When** ele chega à seção histórica  
**Then** encontra uma versão resumida e pública da história do grupo  
**And** o bloco permanece subordinado ao objetivo principal da página  
**And** não traz fotos ou relatos que violem anonimato.

## Epic 3: Recursos Complementares e Continuidade

O público acessa relatórios e caminhos de apoio sem perder a clareza sobre o fluxo principal da reunião.

### Story 3.1: Cartão de Relatório Público

As a público geral,  
I want acessar o relatório público existente a partir do site,  
So that eu encontre transparência sem desviar do objetivo principal da reunião.

**Acceptance Criteria:**

**Given** que existe um relatório público aprovado  
**When** o usuário acessa a seção de recursos complementares  
**Then** encontra um link claro para o relatório  
**And** o link é visualmente secundário ao CTA principal  
**And** o conteúdo vinculado não expõe dados pessoais identificáveis.

### Story 3.2: Diretório e Outras Reuniões de Apoio

As a visitante,  
I want acessar outras referências de apoio quando necessário,  
So that eu não dependa exclusivamente de um único caminho de ajuda.

**Acceptance Criteria:**

**Given** que o usuário busca caminhos alternativos  
**When** ele navega pela seção complementar  
**Then** encontra links aprovados para diretório e outras reuniões  
**And** esses links aparecem como apoio, não como substitutos do CTA principal.

### Story 3.3: Link de WhatsApp para Membros

As a participante,  
I want acessar o grupo de WhatsApp de membros a partir do site quando apropriado,  
So that eu encontre continuidade de comunicação sem confundir esse recurso com a entrada da reunião.

**Acceptance Criteria:**

**Given** que o link de WhatsApp foi aprovado para publicação  
**When** ele é exibido na home  
**Then** aparece com rotulagem explícita para membros  
**And** sua apresentação não compete com o CTA principal da reunião  
**And** o clique pode ser medido de forma agregada e anônima.

### Story 3.4: Navegação Externa com Fallback e Clareza

As a público geral,  
I want saber quando estou saindo do site para um recurso externo,  
So that eu não perca a clareza sobre qual é o caminho principal.

**Acceptance Criteria:**

**Given** que o usuário clica em um link externo complementar  
**When** o site apresenta esse recurso  
**Then** a interface deixa claro que se trata de um link externo complementar  
**And** se o recurso não estiver disponível o site mantém mensagem de fallback sem quebrar a home.

## Epic 4: Governança Editorial e Operação Manual Segura

Servidores voluntários mantêm o conteúdo público correto, centralizado e aderente às regras de anonimato.

### Story 4.1: Contrato da Fonte Única de Verdade

As a servidor voluntário,  
I want manter os dados da reunião em um único contrato autorizado,  
So that eu atualize o site sem espalhar informação por múltiplos arquivos.

**Acceptance Criteria:**

**Given** que o MVP não possui admin nem banco  
**When** o projeto define a estrutura de dados da reunião  
**Then** existe um único arquivo versionado com `link`, `horário`, `ID`, `senha`, `tipo`, `status`, `timezone` e `updated_at`  
**And** o restante da interface consome esses campos sem cópias manuais.

### Story 4.2: Fluxo Manual de Atualização Operacional

As a servidor voluntário,  
I want um procedimento curto para atualizar a reunião do dia,  
So that eu consiga restabelecer rapidamente a confiabilidade do site quando algo mudar.

**Acceptance Criteria:**

**Given** que uma informação da reunião mudou  
**When** o responsável executa o fluxo manual  
**Then** ele altera apenas a fonte única de verdade  
**And** confirma o resultado na home  
**And** o processo não exige editar múltiplos pontos independentes da interface.

### Story 4.3: Checklist de Anonimato para Conteúdo Público

As a servidor voluntário,  
I want revisar conteúdos públicos com um checklist de anonimato,  
So that o site não exponha indevidamente membros do grupo.

**Acceptance Criteria:**

**Given** que uma imagem, depoimento, nome ou relatório será publicado  
**When** o conteúdo passa por revisão  
**Then** o checklist verifica nomes completos, fotos identificáveis e depoimentos identificáveis  
**And** o material só é liberado se estiver compatível com as regras de anonimato.

### Story 4.4: Regras Editoriais para Créditos e Responsáveis

As a servidor voluntário,  
I want regras editoriais claras para citar responsáveis quando necessário,  
So that o conteúdo público preserve discrição sem perder clareza operacional.

**Acceptance Criteria:**

**Given** que o site precisa citar um responsável ou referência  
**When** o conteúdo é aprovado  
**Then** a publicação usa apenas primeiro nome ou apelido quando aplicável  
**And** a regra editorial fica registrada como padrão para o MVP.

## Epic 5: Descoberta, Acessibilidade e Medição do MVP

O MVP se torna encontrável, acessível, observável e pronto para lançamento público sem inflar o escopo.

### Story 5.1: SEO e Semântica da Home

As a visitante vindo de busca orgânica,  
I want encontrar uma página rastreável e semanticamente clara,  
So that eu descubra o grupo e entenda o propósito da página nos resultados de busca.

**Acceptance Criteria:**

**Given** que a home será pública e indexável  
**When** o HTML é renderizado  
**Then** ele inclui `title`, `meta description`, headings coerentes e conteúdo textual suficiente  
**And** o conteúdo principal não depende de JavaScript para ser lido por mecanismos de busca.

### Story 5.2: Acessibilidade do Fluxo Principal

As a usuário de teclado ou leitor de tela,  
I want alcançar o CTA da reunião e ler a informação crítica com estrutura acessível,  
So that eu use o site sem barreiras desnecessárias.

**Acceptance Criteria:**

**Given** que o usuário navega apenas com teclado  
**When** acessa a home  
**Then** consegue usar skip link, chegar ao CTA principal, perceber foco visível e ler a hierarquia semântica básica  
**And** o conteúdo crítico permanece legível em telas pequenas sem zoom horizontal.

### Story 5.3: Analytics Agregado e Anônimo

As a grupo QuarenteNA,  
I want medir eventos principais de forma agregada e anônima,  
So that eu acompanhe se o site está cumprindo sua função principal sem identificar visitantes.

**Acceptance Criteria:**

**Given** que o MVP precisa de medição mínima  
**When** ocorrem cliques no CTA principal ou em links complementares  
**Then** o sistema registra apenas eventos agregados de `meeting_cta_click`, `whatsapp_click`, `report_click` e `support_directory_click`  
**And** não cria perfil individual nem exige conta ou login.

### Story 5.4: Checklist de Lançamento Técnico do MVP

As a equipe do projeto,  
I want validar performance, compatibilidade e resiliência antes do lançamento,  
So that o site entre no ar com o risco operacional controlado.

**Acceptance Criteria:**

**Given** que o MVP está pronto para release  
**When** o checklist de lançamento é executado  
**Then** existem evidências para performance móvel, compatibilidade em navegadores alvo, HTTPS e graceful degradation de recursos externos  
**And** o caminho principal da reunião continua funcional nos cenários de falha previstos.

## Epic 6: Expansão Pública Pós-MVP

O site amplia sua camada pública com melhor organização de conteúdo, relatórios e governança editorial sem sacrificar a simplicidade original.

### Story 6.1: Estrutura Pública para Relatórios e Apoios

As a público geral,  
I want encontrar relatórios e recursos de apoio em uma organização pública melhor,  
So that o site cresça sem virar uma lista confusa de links.

**Acceptance Criteria:**

**Given** que o MVP já está estável  
**When** a camada pública evolui  
**Then** relatórios e apoios ganham estrutura mais organizada  
**And** a reunião continua sendo a prioridade máxima da experiência.

### Story 6.2: Expansão do Conteúdo Institucional

As a visitante,  
I want acessar conteúdo institucional mais completo sobre NA e o grupo,  
So that eu aprofunde contexto sem depender de fontes dispersas.

**Acceptance Criteria:**

**Given** que o produto entra em fase pós-MVP  
**When** novas páginas ou seções públicas são planejadas  
**Then** elas ampliam o conteúdo institucional com governança editorial melhor  
**And** mantêm anonimato e clareza de linguagem.

### Story 6.3: Governança Editorial Pública Refinada

As a servidor voluntário,  
I want operar uma governança pública mais estruturada,  
So that conteúdo e relatórios cresçam com consistência.

**Acceptance Criteria:**

**Given** que a camada pública já contém múltiplos ativos  
**When** a governança editorial evolui  
**Then** existem padrões de revisão, organização e atualização mais robustos  
**And** eles não reintroduzem complexidade desnecessária no fluxo principal.

### Story 6.4: Instrumentação Analítica Refinada

As a grupo QuarenteNA,  
I want ampliar a visão analítica mantendo o anonimato,  
So that eu aprenda com o uso real sem mudar o compromisso de privacidade.

**Acceptance Criteria:**

**Given** que o MVP validou a métrica principal  
**When** a instrumentação evolui  
**Then** novas medições continuam agregadas e não invasivas  
**And** qualquer expansão preserva os limites de privacidade definidos no MVP.

## Epic 7: Fundação do Admin Interno

Servidores voluntários passam a acessar uma área interna segura, com autenticação e permissões alinhadas à estrutura de serviço do grupo.

### Story 7.1: Casca Inicial do Namespace /admin

As a servidor voluntário,  
I want uma área administrativa reservada e separada da home pública,  
So that a evolução interna não contamine o fluxo público.

**Acceptance Criteria:**

**Given** que a V2 será iniciada  
**When** o namespace `/admin` é criado  
**Then** ele existe como superfície isolada da home pública  
**And** sua ativação não cria dependências para o fluxo principal do site.

### Story 7.2: Autenticação de Servidores Voluntários

As a servidor voluntário,  
I want autenticar-me na área administrativa futura,  
So that eu acesse funções internas de forma segura.

**Acceptance Criteria:**

**Given** que a área administrativa existe  
**When** um servidor autorizado acessa `/admin`  
**Then** ele encontra autenticação segura compatível com HTTPS  
**And** usuários não autenticados não acessam recursos internos.

### Story 7.3: Acesso por Papel na Estrutura de Serviço

As a servidor voluntário,  
I want que minhas permissões reflitam meu papel de serviço,  
So that eu veja apenas as capacidades administrativas apropriadas.

**Acceptance Criteria:**

**Given** que existem diferentes papéis de serviço  
**When** o usuário autenticado acessa o admin  
**Then** as capacidades exibidas respeitam o papel configurado  
**And** a autorização não depende do fluxo público do MVP.

## Epic 8: Operação Interna de Dados da Reunião

Servidores voluntários gerenciam dados operacionais da reunião e conteúdo essencial por meio da área administrativa.

### Story 8.1: Gestão Interna dos Dados da Reunião

As a servidor voluntário,  
I want atualizar dados da reunião pela área administrativa,  
So that a operação deixe de depender do fluxo manual em arquivo quando a V2 chegar.

**Acceptance Criteria:**

**Given** que o admin já possui autenticação  
**When** o responsável edita dados da reunião  
**Then** consegue alterar `link`, `horário`, `ID`, `senha`, `tipo` e `status` em uma interface interna  
**And** a publicação mantém uma única fonte de verdade para o site.

### Story 8.2: Gestão de Conteúdo Público Essencial

As a servidor voluntário,  
I want editar conteúdos institucionais essenciais no admin,  
So that eu governe o site com menos dependência de edição manual em código.

**Acceptance Criteria:**

**Given** que a V2 inclui governança de conteúdo  
**When** o servidor edita blocos públicos essenciais  
**Then** consegue atualizar textos e links aprovados  
**And** o fluxo continua respeitando regras de anonimato e revisão.

## Epic 9: Registros, Migração e Dashboards Internos

O grupo migra registros operacionais para dentro do produto e passa a gerir atas, relatórios e dashboards internos.

### Story 9.1: Gestão Interna de Atas, Relatórios e Dashboards

As a servidor voluntário,  
I want acessar atas, relatórios e dashboards dentro do sistema,  
So that a operação deixe de depender tanto de ferramentas externas dispersas.

**Acceptance Criteria:**

**Given** que o admin já possui autenticação e base de dados interna  
**When** o usuário acessa as áreas internas  
**Then** encontra seções distintas para atas, relatórios e dashboards  
**And** essas áreas não afetam a disponibilidade da home pública.

### Story 9.2: Migração de Dados Operacionais Externos

As a grupo QuarenteNA,  
I want migrar dados operacionais de planilhas e ferramentas externas para registros internos,  
So that a plataforma se torne o repositório operacional principal.

**Acceptance Criteria:**

**Given** que existem dados relevantes em ferramentas externas  
**When** a migração é executada  
**Then** os registros são incorporados ao sistema interno com consistência mínima  
**And** o processo preserva rastreabilidade entre origem e destino.

### Story 9.3: Governança de Publicação de Dashboards Internos

As a servidor voluntário,  
I want controlar como dashboards e métricas internas são publicados e consultados,  
So that dados operacionais tenham governança clara dentro da plataforma.

**Acceptance Criteria:**

**Given** que dashboards internos já existem na V2  
**When** o usuário autorizado publica ou consulta métricas  
**Then** a operação respeita papéis, histórico básico e fronteira entre uso interno e exposição pública  
**And** nenhum dado sensível é empurrado para a home pública sem revisão explícita.

## Estado de implementação e rastreabilidade (histórico)

- **Épicos 1–5 (MVP público):** implementados entre março e maio de 2026;
  o `sprint-status.yaml` arquivado registra todas as stories como `done`, com
  code review final em `mvp-epics-1-5-code-review.md`.
- **Épicos 6–9:** permaneceram em `backlog` e **nunca foram implementados** —
  não houve `/admin`, banco de dados nem migração de planilhas.
- **Stories com arquivo preservado:** `1.1`, `2.1`–`2.5`, `3.2`–`3.4`, `5.1`,
  `5.2` e `5.4`. As demais stories foram implementadas a partir dos artefatos de
  planejamento e dos PRs (por exemplo #2–#25), sem arquivo de story versionado.
- **Correções posteriores:** os oito achados da revisão de maio de 2026 foram
  tratados no arquivamento de 18/10/2026 (issue
  [#28](https://github.com/grupoquarentena/site/issues/28)); o detalhe das
  mudanças de comportamento está em `architecture.md`.
- **Nunca publicado:** não há evidência de execução em produção; a home
  arquivada responde com `noindex, nofollow`.
