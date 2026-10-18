# Product Brief: site

> **Documento histórico.** Brief de produto do MVP PHP do Grupo QuarenteNA de
> Narcóticos Anônimos, definido em 23/03/2026 e **nunca publicado em produção**.
> Consolidado em 18/10/2026 no arquivamento da branch `legado/site-2026` a partir
> de `product-brief-site-2026-03-23.md`. Preserva a visão, o público e o escopo
> da época; a plataforma atual é desenvolvida separadamente na branch `site-v2`.

**Autor:** cirinho · **Data original:** 2026-03-23 · **Consolidação:** 2026-10-18

## Executive Summary

O site do Grupo QuarenteNA de Narcóticos Anônimos será o ponto oficial de presença digital pública do grupo. O MVP terá formato de landing page, com foco em facilitar a entrada nas reuniões online, apresentar informações essenciais sobre Narcóticos Anônimos e consolidar a identidade pública do grupo em um endereço próprio.

O produto atenderá principalmente membros e recém-chegados, oferecendo acesso rápido ao link da reunião, ID, senha, grade semanal, informações sobre NA, história do grupo e relatórios públicos. Em uma segunda versão, o projeto evoluirá para uma área administrativa em `/admin`, construída sobre uma base leve em PHP e preparada para uso com banco de dados.

---

## Core Vision

### Problem Statement

O Grupo QuarenteNA ainda não possui um site próprio para funcionar como referência oficial e pública. Hoje, a descoberta do grupo acontece principalmente por diretórios externos e por divulgação em grupos restritos de WhatsApp, o que limita a autonomia do grupo para apresentar sua identidade, centralizar informações e orientar melhor quem chega pela primeira vez.

### Problem Impact

Sem um site próprio, membros e servidores dependem de canais dispersos para divulgar o grupo e repassar dados da reunião. Isso reduz a clareza para recém-chegados, dificulta a consulta pública de informações essenciais e enfraquece a presença digital do grupo como espaço confiável, permanente e fácil de encontrar.

### Why Existing Solutions Fall Short

Os canais atuais cumprem parte da função de descoberta, mas não resolvem a necessidade de uma presença pública própria. Diretórios externos priorizam listagem de reuniões, enquanto mensagens em WhatsApp não substituem uma página oficial, indexável, organizada e duradoura. Além disso, esses canais não oferecem um espaço estruturado para contexto institucional, história do grupo e relatórios públicos.

### Proposed Solution

Criar um site leve em PHP, sem framework pesado no MVP, com uma landing page que funcione como porta de entrada pública do Grupo QuarenteNA. Essa página exibirá o link direto da reunião, ID, senha, dias e horários, formatos por dia da semana, seção "Sobre NA", seção de história e seção de relatórios públicos integrados ao Looker Studio.

### Key Differentiators

- Ponto oficial e próprio do grupo.
- Entrada imediata na reunião com informações completas e claras.
- Foco simultâneo em acolher recém-chegados e servir membros.
- Relatórios públicos já integrados ao ecossistema do grupo.
- Base técnica leve no MVP, com evolução planejada para `/admin` com banco de dados.
- Direção visual sóbria, acessível e confiável, com base azul, texto branco e destaques em amarelo.

## Target Users

### Primary Users

#### 1. Visitante / Recém-chegado

Pessoa que chega ao Grupo QuarenteNA pela primeira vez, por curiosidade, necessidade de ajuda, indicação, diretórios públicos ou divulgação em WhatsApp. Pode ser alguém com problemas com drogas, alguém recém-ingressado em NA ou uma visita que deseja entender como funciona o grupo.

**Contexto e motivação**
Busca acolhimento, segurança emocional e uma mensagem de força, fé e esperança. Muitas vezes chega com medo, vergonha, dúvida ou receio de não saber como participar.

**Como vive o problema hoje**
Depende de mensagens espalhadas, diretórios externos e encaminhamentos informais. Nem sempre entende rapidamente se a reunião é aberta ou fechada, como entrar, o que esperar ou se precisa se expor.

**O que sucesso significa**
Encontrar o link da reunião sem esforço, sentir que está no lugar certo, entender minimamente o que é NA e perceber que existe acolhimento e possibilidade de recuperação.

#### 2. Participante Recorrente

Inclui membros frequentes do grupo e membros de outros grupos que acessam o QuarenteNA de forma recorrente ou esporádica para participar das reuniões.

**Contexto e motivação**
Já conhece a dinâmica do grupo ou de NA e quer principalmente praticidade. Seu objetivo principal é entrar na reunião com rapidez e confirmar informações logísticas do dia.

**Como vive o problema hoje**
Precisa procurar link, ID, senha e formato da reunião em mensagens antigas, diretórios ou canais paralelos.

**O que sucesso significa**
Abrir o site e encontrar imediatamente link, ID, senha, horário e tipo da reunião do dia, sem fricção e sem depender de outras fontes.

### Secondary Users

#### 1. Servidores Voluntários

Membros responsáveis por funções de serviço e administração do grupo.

**No MVP**
Têm uso secundário, consultando informações públicas e relatórios.

**Na V2**
Passam a utilizar a área `/admin` para atas, relatórios, dashboards e gestão operacional hoje distribuída em planilhas e Google Drive, com migração gradual para banco de dados.

### User Journey

#### Jornada do Visitante / Recém-chegado

**Descoberta**
Encontra o grupo por diretórios como `na.org.br/virtual`, divulgação em WhatsApp ou indicação de terceiros.

**Primeiro contato**
Chega ao site e vê imediatamente o link da reunião, o horário, o tipo da reunião e uma mensagem acolhedora.

**Momento de confiança**
Percebe que há clareza, acolhimento, anonimato e um caminho simples para participar.

**Momento de valor**
Entra na reunião e sente que encontrou um espaço possível de identificação, escuta e esperança.

#### Jornada do Participante Recorrente

**Descoberta**
Já conhece o grupo e passa a usar o site como referência prática.

**Uso principal**
Acessa a home de forma direta, muitas vezes diariamente, para clicar no link da reunião.

**Momento de valor**
Consegue entrar em segundos, com as informações do dia sempre disponíveis e confiáveis.

#### Jornada do Servidor Voluntário

**No MVP**
Consulta relatórios públicos e usa o site mais como referência institucional.

**Na V2**
Utiliza `/admin` para operar conteúdo e dados internos, sem interferir na simplicidade da experiência pública.

## Success Metrics

O principal sinal de sucesso de `site` é a pessoa encontrar rapidamente o link da reunião e clicar para entrar sem precisar de ajuda adicional. Para visitantes e recém-chegados, sucesso também significa encontrar o grupo pela internet, sentir confiança no site e entender com clareza como participar. Para participantes recorrentes, sucesso significa usar o site como referência rápida e diária para acessar a reunião.

No MVP, a medição deve ser leve, anônima e orientada à tarefa principal, evitando coleta de dados pessoais ou rastreamento invasivo.

### Business Objectives

**Em 3 meses**
- Estabelecer o site como ponto oficial e confiável de acesso ao Grupo QuarenteNA.
- Reduzir a dependência de mensagens dispersas para divulgação do link da reunião.
- Validar que visitantes e participantes recorrentes conseguem acessar a reunião diretamente pelo site.
- Iniciar descoberta orgânica do grupo por mecanismos de busca.

**Em 12 meses**
- Consolidar o site como canal digital oficial e recorrente do grupo, com uso frequente por membros e novos visitantes.
- Aumentar a entrada de novos participantes vindos de busca orgânica.
- Manter presença estável 24 horas por dia, independente de indisponibilidade de diretórios externos.
- Preparar a evolução para a V2 com `/admin`, migrando gradualmente processos hoje mantidos em Google Drive e planilhas.

### Key Performance Indicators

**Métrica Norteadora do MVP**
- Percentual de sessões da home que geram clique no link principal da reunião em até 30 segundos.

**KPIs principais do MVP**
- Total de cliques no link principal da reunião.
- Tempo mediano até o primeiro clique no link da reunião.
- Taxa de saída da home sem clique no link da reunião.
- Proporção de acessos que chegam por busca orgânica e clicam no link da reunião.
- Disponibilidade do site ao longo do tempo.

**KPIs secundários**
- Cliques no link do grupo de WhatsApp para membros.
- Cliques nos links de outras reuniões e diretórios de `na.org.br/virtual`.
- Visualizações da seção de relatórios públicos.
- Impressões, cliques e CTR orgânico em buscas relacionadas ao grupo.

**Métricas qualitativas complementares**
- Relatos espontâneos em reuniões de pessoas que encontraram o grupo pelo site.
- Feedback opcional e não identificável sobre como a pessoa encontrou o grupo.

**Princípios de medição**
- Coleta agregada e anônima.
- Sem identificação pessoal, conta de usuário ou perfil individual no MVP.
- Baseline inicial no primeiro mês para calibrar metas futuras.

## MVP Scope

### Core Features

- Landing page pública em PHP como ponto oficial de entrada do Grupo QuarenteNA.
- Destaque absoluto, acima da dobra, para:
  - botão principal de entrada na reunião;
  - horário;
  - ID;
  - senha;
  - tipo da reunião do dia.
- Mensagem curta de acolhimento para visitas, recém-chegados e público em geral.
- Bloco curto "Sobre NA".
- Bloco curto "Sobre o Grupo QuarenteNA".
- Bloco curto com a história do grupo em versão resumida.
- Link para relatório público já existente no Looker Studio.
- Link do grupo de WhatsApp para membros.
- Link para o diretório `na.org.br/virtual` e outras reuniões como apoio.
- SEO básico, analytics anônimo leve, responsividade e estabilidade.
- Fonte única de verdade simples para os dados da reunião no MVP, sem banco de dados.

### Out of Scope for MVP

- Área `/admin` para servidores.
- Autenticação, perfis de usuário e permissões.
- Banco de dados próprio.
- Migração das planilhas do Google para banco.
- Formulários de ata e fluxos internos de serviço.
- Dashboards nativos em código.
- CMS ou painel interno de edição.
- Automação avançada de conteúdo e métricas.
- Conteúdo institucional extenso ou múltiplos fluxos/páginas complexas.
- Dependência crítica de embeds ou integrações externas para a função principal do site.

### MVP Success Criteria

- O usuário encontra rapidamente o link da reunião e clica sem fricção.
- A home passa a funcionar como referência principal de entrada na reunião.
- Membros recorrentes começam a acessar o site de forma direta e frequente.
- Visitantes conseguem entender rapidamente o que é NA, o que é o grupo e como participar.
- O grupo reduz dependência de mensagens dispersas para divulgar a reunião.
- O site continua útil mesmo se serviços externos estiverem instáveis.

### Future Vision

- Área `/admin` dedicada aos servidores voluntários.
- Migração gradual das planilhas e fluxos hoje mantidos em Google Drive para banco de dados próprio.
- Gestão interna de atas, relatórios, métricas e dashboards dentro do site.
- Substituição progressiva de painéis externos por dashboards nativos em código.
- Evolução do site de landing pública para plataforma operacional leve do grupo.
