# UX Design Specification site

> **Documento histórico.** Especificação de UX do MVP PHP público (março de
> 2026), **nunca publicado em produção**. Consolidada em 18/10/2026 no
> arquivamento da branch `legado/site-2026` a partir de
> `ux-design-specification.md`. A implementação final (maio de 2026) seguiu a
> paleta, os tokens e a hierarquia definidos aqui; como diferença de acabamento
> do refresh visual (PR #29), os títulos usam a pilha `Source Serif 4`/Georgia
> e o corpo usa pilha de fontes do sistema, sem download de webfont. Este
> documento preserva as decisões históricas e não substitui o design da nova
> plataforma.

**Author:** cirinho  
**Date:** 2026-03-24

## Project Understanding

### Problem to Solve

O site precisa funcionar como porta de entrada oficial para a reunião do Grupo QuarenteNA, reduzindo a dependência de diretórios externos e mensagens espalhadas. O primeiro trabalho da interface não é explicar tudo; é colocar a pessoa certa no lugar certo, com confiança suficiente para entrar na reunião.

### Primary Users

- Visitante ou recém-chegado que precisa entender rapidamente se pode participar e como entrar
- Participante recorrente que quer entrar na reunião em segundos
- Servidor voluntário que precisa manter o conteúdo público correto sem painel administrativo no MVP

### Assumptions Adopted to Avoid Blocking the Workflow

- O MVP terá uma única home pública, server-rendered, em PT-BR.
- O fuso de referência é `America/Sao_Paulo`.
- A informação crítica da reunião vem de uma única fonte de verdade, não espalhada na interface.
- WhatsApp, relatório público e diretórios externos são complementares e nunca podem competir com o CTA principal.
- A direção visual do brief será preservada: base azul, texto claro e destaques em amarelo.

## Core Experience Definition

### Core User Action

**Ação central do produto:** entrar na reunião.

Essa ação precisa ser realizável em segundos, sem leitura longa, sem navegação extra e sem depender de scripts de terceiros.

### Platform Requirements

- O conteúdo crítico deve aparecer acima da dobra em viewport móvel de 360px.
- O CTA principal deve ser identificável mesmo sem ler todo o restante da página.
- O site deve combinar linguagem acolhedora com aparência oficial e estável.
- O caminho principal precisa continuar funcional quando recursos externos falharem.

### Effortless Interaction Principles

- Primeiro acesso, depois contexto.
- O visitante nunca deve precisar `procurar` o link principal.
- Todo conteúdo secundário deve reforçar, e nunca desviar, o objetivo principal.
- A home deve parecer `ponto oficial do grupo`, não `landing promocional`.

## Desired Emotional Response

### Emotional Goals

- **Clareza imediata:** `entendi o que fazer`
- **Segurança:** `esse lugar parece legítimo e acolhedor`
- **Baixa fricção:** `consigo entrar agora`
- **Discrição respeitosa:** `não preciso me expor para entender se este espaço é para mim`

### Emotional Journey Map

**Entrada:** dúvida, urgência ou hábito  
**Primeiro contato:** alívio por ver um CTA claro  
**Leitura curta:** confiança por entender tipo da reunião e contexto básico  
**Ação:** clique seguro para entrar  
**Pós-clique:** sensação de ter encontrado um canal oficial e útil

### Micro-Emotions to Support

- `agora sei onde clicar`
- `isso parece sério e confiável`
- `não preciso descobrir tudo sozinho`
- `posso voltar aqui todo dia`

## UX Pattern Analysis & Inspiration

### Pattern Direction Adopted

Sem depender de referências externas específicas, a direção adotada é a de páginas utilitárias de alta prioridade:

- hierarquia extrema para a ação principal
- pouco ruído acima da dobra
- sinais rápidos de legitimidade
- conteúdo de apoio organizado em blocos curtos
- linguagem humana, sem jargão técnico nem burocrático

### Transferable Patterns

- Hero curto com CTA dominante
- Card de informação crítica com dados legíveis
- Chips ou etiquetas de status/tipo de reunião
- Blocos de conteúdo secundário com títulos autoexplicativos
- Links externos agrupados em seção subordinada

## Design System Choice

### System Strategy

Escolha: **design system customizado e leve**, sem dependência de biblioteca pesada.

### Why This Choice

- O produto é pequeno e institucional.
- O MVP precisa de confiabilidade, não de volume de componentes.
- A interface deve refletir identidade própria do grupo e não visual genérico de dashboard.

### Design Tokens

**Color roles**

- `--color-bg-deep`: azul profundo para fundo estrutural
- `--color-surface`: azul médio/sóbrio para cartões e seções
- `--color-text-primary`: quase branco
- `--color-text-secondary`: azul claro dessaturado
- `--color-accent`: amarelo quente para CTA e foco
- `--color-success`: verde só para status positivo, sem competir com o CTA
- `--color-border`: azul dessaturado para separação sutil

**Spacing**

- Escala simples de 4, 8, 12, 16, 24, 32, 48

**Radii**

- 8px para inputs menores
- 16px para cards principais

## Defining Experience

### Defining Experience Statement

**“Encontrei a reunião certa, no site oficial certo, sem me perder.”**

### Mental Model

O usuário deve ler a página como:

1. `Aqui é o Grupo QuarenteNA`
2. `Esta é a reunião de hoje`
3. `Consigo entrar agora`
4. `Se eu ainda tiver dúvida, aqui está o contexto mínimo`

### Content Hierarchy

1. CTA principal da reunião
2. Horário, ID, senha, tipo e status
3. Acolhimento e distinção aberta/fechada
4. O que é NA / o que é o grupo
5. História resumida
6. Links complementares

## Visual Foundation

### Brand Assessment

O brief já sugere um caminho visual seguro: azul como base, branco para leitura e amarelo como destaque. Isso comunica estabilidade, serviço e foco, sem perder calor humano.

### Typography System

- **Headings:** `Source Serif 4`
- **Body / UI text:** `Atkinson Hyperlegible`

Essa combinação mantém legibilidade forte e evita aparência excessivamente corporativa.

### Layout Principles

- Mobile first
- Coluna única para o conteúdo crítico
- Cards claros e respiráveis
- Nada acima da dobra que concorra com o CTA

## Design Directions

### Evaluated Directions

**Direção A: Portal Institucional Frio**  
Boa clareza, pouca empatia. Rejeitada.

**Direção B: Acolhimento Emocional Forte**  
Mais calor humano, mas arrisca diluir a urgência do CTA. Rejeitada como direção principal.

**Direção C: Porta Oficial Acolhedora**  
Mistura serviço, sobriedade e acolhimento. Escolhida.

### Chosen Direction

**Porta Oficial Acolhedora**

- CTA grande e inequívoco
- Tom humano e curto
- Sinais de legitimidade sem excesso de formalidade
- Visual sóbrio, estável e acessível

## User Journey Flows

### Journey 1: Recém-chegado pronto para entrar

1. Usuário chega por Google, diretório ou indicação.
2. Vê CTA principal, tipo da reunião e dados logísticos.
3. Lê acolhimento curto e entende que encontrou o grupo.
4. Clica no CTA e entra.

### Journey 2: Recém-chegado inseguro

1. Usuário chega hesitante.
2. Vê CTA, mas procura confirmação.
3. Lê diferença entre reunião aberta e fechada.
4. Lê explicação curta de NA e do grupo.
5. Decide entrar ou salvar a página como referência segura.

### Journey 3: Participante recorrente

1. Usuário abre a home diretamente.
2. Confirma status da reunião e dados principais.
3. Clica no CTA principal em poucos segundos.

### Journey 4: Servidor voluntário

1. Servidor atualiza a fonte única de verdade.
2. Revisa conteúdo público com checklist de anonimato.
3. Publica a alteração.
4. Confirma se a home mostra a informação correta.

## Component Strategy

### Core Components

- `HeroMeetingCTA`
- `MeetingInfoCard`
- `MeetingTypeBadge`
- `MeetingStatusPill`
- `WelcomeMessage`
- `NAIntroBlock`
- `GroupIntroBlock`
- `GroupHistoryBlock`
- `SupportLinksSection`
- `PublicReportCard`
- `ExternalLinkNotice`
- `SkipLink`
- `FooterTrustNote`

### Component Rules

- Componentes críticos devem funcionar sem JavaScript.
- Dados da reunião entram via props vindas da fonte única.
- Nenhum componente pode duplicar manualmente `horário`, `ID`, `senha`, `tipo` ou `status`.
- Links complementares devem ter estilo secundário.

## UX Consistency Patterns

### Pattern 1: Single Obvious Primary Action

Existe exatamente um CTA dominante na página: entrar na reunião.

### Pattern 2: Critical Data Always Visible

Horário, ID, senha, tipo e status aparecem juntos, em bloco único, antes de qualquer conteúdo longo.

### Pattern 3: Trust Before Depth

As respostas para `o que é`, `é para mim` e `esse grupo é real` vêm antes da história longa ou de links externos.

### Pattern 4: Graceful External Navigation

Quando o usuário sai para WhatsApp, relatório ou diretório, a interface deixa claro que ele está abrindo um recurso complementar.

### Pattern 5: Editorial Restraint

Nada de carrossel, pop-up, slider, contadores invasivos ou microinterações decorativas no MVP.

## Responsive Design & Accessibility

### Responsive Strategy

**Mobile 360-479px**

- Hero em coluna única
- CTA full width
- Reunião e acolhimento acima da dobra

**Tablet 480-959px**

- Hero ainda prioritário
- Pode dividir reunião e acolhimento em dois blocos próximos

**Desktop 960px+**

- CTA e card da reunião continuam primeiro
- Conteúdo complementar pode usar grid de duas colunas abaixo da dobra

### Accessibility Strategy

- Skip link visível ao foco
- Ordem de heading semântica e landmarks claros
- CTA acessível por teclado sem navegar por links secundários
- Foco com alto contraste
- Texto principal com contraste mínimo equivalente a WCAG AA
- Áreas de toque de pelo menos 44x44px
- Etiquetas de tipo/status não dependem só de cor

## UX Design Requirements

UX-DR1: A home deve exibir o CTA principal da reunião acima da dobra em viewport de 360px sem depender de JavaScript.

UX-DR2: O card principal da reunião deve exibir, no mesmo bloco visual, `horário`, `ID`, `senha`, `tipo da reunião` e `status`.

UX-DR3: O `status` da reunião deve suportar exatamente três estados no MVP: `agora`, `mais tarde hoje` e `próxima reunião`, com copy explícita.

UX-DR4: A diferença entre reunião `aberta` e `fechada` deve aparecer perto do contexto da reunião, sem exigir navegação para outra página.

UX-DR5: A mensagem de acolhimento deve caber em um bloco curto acima do conteúdo institucional e sem competir visualmente com o CTA.

UX-DR6: A seção `O que é NA` deve responder a essa pergunta em linguagem curta e compreensível para leigos.

UX-DR7: A seção `Sobre o Grupo QuarenteNA` deve reforçar legitimidade e frequência regular do grupo sem expor identidades pessoais.

UX-DR8: A história do grupo deve aparecer em versão resumida, claramente subordinada ao objetivo principal da página.

UX-DR9: Links para relatório, diretório e outras reuniões devem aparecer em uma seção complementar visualmente secundária ao CTA da reunião.

UX-DR10: O link de WhatsApp deve ser rotulado como recurso complementar para membros e não pode parecer o CTA principal.

UX-DR11: A home deve oferecer skip link, foco visível, navegação por teclado e landmarks semânticos nos fluxos principais.

UX-DR12: O conteúdo crítico deve permanecer legível em mobile sem zoom nem rolagem horizontal.

UX-DR13: Os estados de foco, hover e ativo do CTA principal devem ter contraste forte e feedback claro.

UX-DR14: Recursos externos indisponíveis não podem remover, empurrar ou obscurecer o card principal da reunião.

UX-DR15: A hierarquia visual de desktop deve preservar a mesma ordem de prioridade definida para mobile.

UX-DR16: A interface deve explicitar quando um link leva o usuário para fora do site oficial, usando rotulagem discreta.

UX-DR17: O sistema visual do MVP deve usar paleta sóbria baseada em azul, texto claro e acento amarelo para o CTA principal e estados de foco.

UX-DR18: A interface pública deve evitar fotos identificáveis, nomes completos e depoimentos identificáveis em todas as áreas visíveis do MVP.

## Workflow Completion

O documento resolve as lacunas que o PRD deixou implícitas para UX e prepara o downstream para arquitetura e épicos sem reabrir descoberta do MVP. As decisões foram fechadas com base no PRD, no brief e na conferência interna entre PM, UX, arquitetura e Scrum.
