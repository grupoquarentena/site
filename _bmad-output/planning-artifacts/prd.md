# Product Requirements Document - site

> **Documento histórico.** PRD do MVP PHP do Grupo QuarenteNA de Narcóticos
> Anônimos, concluído em 24/03/2026 e **nunca publicado em produção**. Consolidado
> em 18/10/2026 no arquivamento da branch `legado/site-2026` a partir de `prd.md`
> e do `prd-validation-report-2026-03-24.md` (resultados no apêndice final).
> Os requisitos administrativos FR29–FR33 são visão futura e **nunca foram
> implementados**; a plataforma atual é desenvolvida na branch `site-v2`.

**Author:** cirinho  
**Date:** 2026-03-24

## Executive Summary

O projeto `site` cria a presença digital oficial do Grupo QuarenteNA de Narcóticos Anônimos. O MVP será uma landing page pública em PHP, disponível 24 horas por dia, para reduzir a dependência de diretórios externos e mensagens dispersas e concentrar, em um único endereço, o acesso à reunião e o contexto básico do grupo.

O produto atende dois comportamentos centrais. Visitantes e recém-chegados precisam de acesso, confiança e acolhimento antes de entrar. Participantes recorrentes precisam apenas de um caminho rápido e confiável para a reunião. Por isso, a home deve dar destaque máximo ao link da reunião, com horário, ID, senha e tipo da reunião do dia, e complementar essa ação principal com conteúdo curto sobre NA, sobre o Grupo QuarenteNA, história resumida, relatório público, grupo de WhatsApp e links de apoio.

Quando o produto funcionar bem, o site deixará de ser apenas uma página informativa e passará a operar como ferramenta cotidiana do grupo e porta de entrada natural para quem encontra NA pela primeira vez.

### Diferencial de Serviço

O diferencial do site não é inovação tecnológica. O diferencial é serviço. Em vez de entregar apenas um link de Zoom, o site entrega acesso imediato, contexto, confiança e acolhimento em um ponto oficial, estável e sempre disponível.

A percepção de valor esperada é: `encontrei NA`. O visitante não apenas localiza a reunião; ele entende que encontrou um grupo real, ativo e acessível. Esse posicionamento diferencia o produto de diretórios instáveis, mensagens antigas e canais que só replicam dados logísticos.

## Project Classification

- **Project Type:** `web_app` com perfil de site público e informacional no MVP
- **Domain:** `general`
- **Domain Context:** comunidade, recovery-support, nonprofit
- **Complexity:** `low` no MVP, com aumento estrutural previsto na V2
- **Project Context:** `greenfield`, com migração planejada de ativos operacionais na evolução futura

## Success Criteria

### User Success

- O visitante encontra o link da reunião em segundos.
- O visitante entende horário, ID, senha e tipo da reunião sem ambiguidade.
- O recém-chegado entende o básico sobre NA e decide com confiança se vai participar.
- O participante recorrente usa o site como atalho diário para entrar na reunião.
- O público reconhece o site como ponto oficial, estável e confiável do grupo.
- O usuário sente que encontrou NA, não apenas um link.

### Business Success

- **Em 3 meses:** o site deve se estabelecer como canal oficial do Grupo QuarenteNA para divulgação e acesso à reunião, reduzir dependência de mensagens dispersas e começar a gerar relatos de participantes que chegaram pelo site.
- **Em 12 meses:** o site deve operar como presença digital cotidiana do grupo, consolidar descoberta orgânica por busca e sustentar a autonomia do grupo mesmo quando diretórios externos estiverem instáveis.

### Technical Success

- O MVP entrega uma experiência pública leve, responsiva, estável e simples de manter.
- O site funciona sem banco de dados, autenticação pública ou painel administrativo.
- O acesso principal à reunião permanece disponível mesmo quando recursos externos falharem.
- As informações da reunião ficam centralizadas em uma única fonte de verdade.
- A medição do uso é agregada, anônima e não invasiva.

### Measurable Outcomes

- Percentual de sessões da home que geram clique no link principal da reunião em até 30 segundos.
- Tempo mediano até o primeiro clique no link da reunião.
- Taxa de saída da home sem clique no link principal.
- Total de cliques no link da reunião.
- Proporção de acessos vindos de busca orgânica que chegam à home e clicam no link da reunião.
- Cliques no link do grupo de WhatsApp para membros.
- Cliques nos links de apoio para outras reuniões e diretórios.
- Disponibilidade do site ao longo do tempo.
- Relatos qualitativos de participantes que encontraram o grupo pelo site.

## Product Scope

### MVP - Minimum Viable Product

- Landing page pública em PHP.
- Botão principal da reunião em destaque absoluto.
- Horário, ID, senha e tipo da reunião do dia.
- Mensagem curta de acolhimento para visitas e recém-chegados.
- Conteúdo institucional curto sobre NA e sobre o Grupo QuarenteNA.
- História do grupo em versão resumida.
- Link para relatório público existente.
- Link do grupo de WhatsApp para membros.
- Link para diretório e reuniões de apoio.
- SEO básico, analytics anônimo, responsividade e estabilidade.
- Fonte simples e única para atualização das informações da reunião.

### Growth Features (Post-MVP)

- Expansão do conteúdo institucional e histórico.
- Organização mais estruturada de relatórios e páginas de apoio.
- Evolução da camada pública com melhor governança de conteúdo.
- Instrumentação analítica mais refinada, desde que preserve anonimato.
- Preparação gradual da base para futura administração interna.

### Vision (Future)

- Área `/admin` para servidores voluntários.
- Banco de dados próprio para substituir planilhas externas.
- Gestão interna de atas, relatórios, métricas e dashboards.
- Migração de processos hoje feitos em Google Drive e Looker Studio.
- Evolução do site para plataforma operacional leve do grupo.

## User Journeys

### Jornada 1: Visitante / Recém-chegado - Caminho de Sucesso

Uma pessoa chega ao site pela primeira vez, geralmente vinda do Google, de um diretório externo ou de uma indicação. Ela ainda não é membro e precisa, antes de tudo, entender o que é NA e se aquele espaço pode fazer sentido para sua situação.

Na home, encontra imediatamente o link da reunião, com horário, ID, senha e tipo da reunião do dia. Ao mesmo tempo, encontra uma mensagem curta de acolhimento e uma explicação simples sobre Narcóticos Anônimos e sobre o Grupo QuarenteNA. Isso reduz a insegurança inicial e transforma a experiência de “achei um link” em “entendi onde estou”.

O momento decisivo acontece quando a pessoa percebe que se trata de um grupo real, ativo e acessível e sente confiança suficiente para entrar na reunião.

### Jornada 2: Visitante / Recém-chegado - Dúvida ou Insegurança Antes de Entrar

Outra pessoa chega ao site em hesitação. Ela quer ajuda, mas não entende bem o que é NA, não sabe se pode participar e teme entrar em um espaço que não compreende.

Nessa jornada, o produto precisa responder rapidamente à pergunta: “o que é NA e este lugar é para mim?”. A reunião continua sendo o principal CTA, mas o site deve oferecer linguagem clara, explicação curta e sinais de legitimidade para reduzir a barreira emocional antes do clique.

A jornada é bem-sucedida quando a pessoa entende o básico sobre NA, confia no grupo e decide entrar, ou ao menos guarda o site como referência segura para voltar depois.

### Jornada 3: Participante Recorrente - Entrada Rápida na Reunião

Um membro frequente ou participante recorrente já conhece o Grupo QuarenteNA e acessa o site com um objetivo direto: entrar na reunião com rapidez, sem depender de mensagens antigas, diretórios externos ou grupos paralelos.

Ao abrir a home, ele vê imediatamente o link principal e as informações logísticas essenciais. Não precisa interpretar múltiplos caminhos nem procurar conteúdo secundário.

O sucesso dessa jornada está na ausência de atrito: acessar, confirmar que está tudo certo e entrar em segundos.

### Jornada 4: Servidor Voluntário - Atualização Manual das Informações no MVP

Como o MVP não inclui `/admin`, banco de dados ou CMS, a manutenção das informações públicas fica sob responsabilidade manual dos servidores do grupo.

O link da reunião tende a permanecer estável, o que reduz a necessidade de intervenção frequente. Ainda assim, os servidores precisam manter corretos o horário, o link, o ID, a senha e o tipo da reunião do dia a partir de uma única fonte de verdade.

Se houver mudança excepcional ou problema de acesso, a recuperação no MVP consiste em atualizar manualmente a informação pública e restabelecer rapidamente a confiabilidade do site como ponto oficial do grupo.

### Implicações de Produto

- Hierarquia visual extrema para o acesso à reunião.
- Conteúdo curto e claro explicando o que é NA para quem ainda não é membro.
- Linguagem acolhedora e legitimadora para reduzir insegurança antes do clique.
- Informações logísticas sempre visíveis: link, horário, ID, senha e tipo da reunião.
- Experiência sem fricção para participantes recorrentes.
- Processo operacional simples para atualização manual no MVP.
- Fonte única de verdade para os dados da reunião.
- Separação clara entre experiência pública do MVP e gestão interna reservada para a V2.

## Domain-Specific Requirements

### Anonimato e Comunicação Pública

- O site deve preservar o princípio de anonimato do grupo e evitar identificação completa de membros.
- Quando houver necessidade operacional de citar responsáveis ou servidores, usar apenas primeiro nome ou apelido.
- O site deve evitar fotos que identifiquem membros do grupo.
- Depoimentos públicos, se existirem, não podem identificar diretamente o membro.
- O conteúdo público deve explicar com clareza a diferença entre reunião aberta e reunião fechada.
- Reunião aberta deve ser apresentada como acessível ao público em geral, incluindo familiares e profissionais.
- Reunião fechada deve ser apresentada como destinada a membros e a visitas que tenham problemas com drogas.
- O conteúdo institucional deve deixar claro que o grupo não endossa instituições externas.
- A linguagem pública deve ser simples, acolhedora e compreensível para qualquer visitante.

### Resiliência Operacional

- O site deve continuar funcional em sua missão principal mesmo que Looker Studio, WhatsApp ou diretórios externos estejam indisponíveis.
- O núcleo mínimo sempre disponível deve incluir link da reunião, ID, senha, horário, tipo da reunião e contexto básico do grupo.
- O MVP deve priorizar boa experiência mobile e acessibilidade desde a primeira entrega.
- A estrutura do site deve minimizar o risco de exposição indevida de dados, nomes ou imagens.

### Integrações Públicas

- O Looker Studio deve ser tratado como recurso público complementar, não como dependência crítica para a função principal do site.
- O link do grupo de WhatsApp deve ser tratado como apoio à comunicação entre membros, sem substituir a função principal da home.
- Links para `na.org.br/virtual` e outras reuniões devem funcionar como referência complementar, não como ponto central da experiência.

### Mitigação de Riscos

- **Quebra de anonimato:** revisar nomes, imagens e depoimentos antes da publicação.
- **Conteúdo público confuso:** usar explicações curtas, linguagem simples e diferenciação clara entre tipos de reunião.
- **Dependência excessiva de serviços externos:** garantir que a home continue útil mesmo sem embeds, dashboards ou diretórios externos.
- **Informação desatualizada sobre reunião:** manter processo manual claro de atualização e fonte única de verdade no MVP.
- **Exposição indevida em relatórios públicos:** manter apenas dados agregados, sem elementos pessoais ou identificáveis.

## Web App Specific Requirements

### Project-Type Overview

O produto será implementado como um web app de perfil institucional e informacional, com arquitetura simples em PHP e renderização direta no servidor. O MVP não seguirá o modelo SPA. A prioridade é previsibilidade, simplicidade de manutenção, carregamento rápido e boa indexação por mecanismos de busca.

### Technical Architecture Considerations

- A arquitetura do MVP deve privilegiar baixa complexidade operacional, clareza estrutural e alta confiabilidade.
- O conteúdo essencial deve estar disponível sem autenticação, sem bloqueios críticos de script e sem dependência obrigatória de terceiros.
- A lógica dinâmica do MVP pode existir, mas deve ser simples e previsível, como cálculo do status da reunião com base em horário.
- O MVP não exige tempo real avançado, sincronização contínua ou infraestrutura de eventos.

### Browser Matrix

- Google Chrome
- Microsoft Edge
- Mozilla Firefox
- Apple Safari

O suporte deve priorizar versões recentes desses navegadores em desktop e mobile.

### Responsive Design

A experiência deve ser mobile first. A versão mobile é a referência principal de layout e navegação. A informação crítica da reunião deve aparecer com máxima clareza em telas pequenas, sem exigir zoom, rolagem excessiva ou interpretação complexa. A versão desktop deve manter a mesma prioridade de conteúdo, com melhor aproveitamento de espaço.

### Performance Targets

- O conteúdo crítico da home deve aparecer rapidamente e tornar a ação principal visível o quanto antes.
- A renderização da informação crítica da reunião não pode depender de recursos pesados, embeds obrigatórios ou scripts de terceiros.
- Elementos complementares, como relatórios públicos ou links externos, não devem degradar a experiência principal.

### SEO Strategy

- Títulos e descrições claros.
- Headings semânticos.
- Conteúdo textual suficiente para contextualizar o que é NA e o que é o Grupo QuarenteNA.
- Metadados básicos de compartilhamento.
- URL pública estável.
- Estrutura amigável para mecanismos de busca.

### Accessibility Level

- Contraste forte entre texto e fundo.
- Tipografia legível em telas pequenas.
- Navegação por teclado.
- Skip link para o conteúdo principal.
- Foco visível em links e botões.
- HTML semântico.
- Textos e CTAs claros.
- Áreas de toque adequadas no mobile.
- Suporte razoável a leitores de tela nos fluxos públicos principais.

### Implementation Considerations

- A implementação deve manter uma fonte única e simples para os dados da reunião, de forma que horário, link, ID, senha e tipo da reunião possam ser atualizados sem espalhar informação por múltiplos arquivos ou pontos da interface.
- O site deve permanecer útil mesmo que integrações externas falhem. Links para WhatsApp, relatórios públicos e diretórios externos devem ser tratados como complementos, nunca como pré-requisitos para que o visitante encontre e acesse a reunião.

## Project Scoping & Phased Development

### MVP Strategy & Philosophy

- **MVP Approach:** problem-solving MVP com foco em acesso confiável, acolhimento e utilidade imediata.
- **Resource Requirements:** 1 desenvolvedor com domínio de PHP, HTML, CSS, SEO técnico básico e integração leve; 1 ou mais servidores do grupo como responsáveis por conteúdo, revisão e atualização da informação da reunião.

O MVP existe para validar se o site consegue se tornar o ponto oficial, estável e cotidiano de acesso ao Grupo QuarenteNA.

### MVP Feature Set (Phase 1)

**Core User Journeys Supported**

- Visitante ou recém-chegado encontrando o grupo e entendendo o que é NA.
- Visitante com insegurança encontrando contexto suficiente para confiar e entrar.
- Participante recorrente acessando a reunião em segundos.
- Servidor voluntário atualizando manualmente a informação pública do MVP.

**Must-Have Capabilities**

- Landing page pública em PHP.
- Botão principal da reunião em destaque máximo.
- Link, horário, ID, senha e tipo da reunião do dia.
- Mensagem curta de acolhimento.
- Explicação curta sobre o que é NA.
- Explicação curta sobre o Grupo QuarenteNA.
- História resumida do grupo.
- Link para relatório público existente.
- Link do grupo de WhatsApp para membros.
- Link para diretório e outras reuniões de apoio.
- Mobile first, SEO básico, acessibilidade prática e analytics anônimo.
- Fonte única de verdade simples para os dados da reunião.

### Post-MVP Features

**Phase 2 (Post-MVP)**

- Expansão e melhor organização do conteúdo institucional.
- Estrutura pública mais organizada para relatórios e páginas de apoio.
- Melhor governança editorial e operacional do conteúdo.
- Instrumentação analítica mais refinada, mantendo anonimato.
- Preparação da base para futura administração interna.

**Phase 3 (Expansion)**

- Área `/admin` para servidores.
- Banco de dados próprio.
- Migração de planilhas externas para estrutura interna.
- Gestão de atas, relatórios, métricas e dashboards dentro do site.
- Substituição progressiva de painéis externos por dashboards nativos.

### Risk Mitigation Strategy

- **Technical Risks:** risco de informação desatualizada da reunião, dependência excessiva de serviços externos e perda de clareza mobile. Mitigar com fonte única de verdade, estrutura simples em PHP, prioridade para conteúdo crítico acima da dobra e função principal independente de terceiros.
- **Market Risks:** risco de o site não se tornar referência real para membros e novos visitantes. Mitigar com foco extremo no link da reunião, SEO básico desde o início, linguagem simples e proposta clara de valor público.
- **Resource Risks:** risco de baixa capacidade operacional para manter dados corretos sem `/admin`. Mitigar com escopo enxuto, manutenção manual simples e responsabilidade clara dos servidores do grupo pela atualização do conteúdo essencial.

## Functional Requirements

### Acesso Público à Reunião

- FR1: Visitantes podem acessar o link principal da reunião a partir do site público.
- FR2: Visitantes podem visualizar horário, ID, senha e tipo da reunião do dia antes de tentar entrar.
- FR3: Visitantes podem entender se a reunião atual é aberta ou fechada antes de participar.
- FR4: Participantes recorrentes podem confirmar se a reunião está acontecendo agora, mais tarde no dia ou no próximo horário previsto.
- FR5: Participantes recorrentes podem retornar rapidamente ao caminho principal de entrada da reunião a partir da home.
- FR6: Visitantes podem acessar as informações essenciais da reunião mesmo quando recursos complementares externos estiverem indisponíveis.

### Orientação Pública e Confiança

- FR7: Visitantes podem entender o que é Narcóticos Anônimos a partir do conteúdo público do site.
- FR8: Visitantes podem entender o que é o Grupo QuarenteNA e como ele se relaciona com NA.
- FR9: Visitantes podem avaliar se o grupo é relevante para sua situação antes de entrar na reunião.
- FR10: Público geral pode compreender a diferença entre reunião aberta e reunião fechada.
- FR11: Visitantes podem receber orientação acolhedora e não julgadora antes de decidir participar.
- FR12: Público geral pode reconhecer o site como ponto oficial de referência do grupo.

### Contexto do Grupo e Transparência Pública

- FR13: Público geral pode acessar uma história resumida do Grupo QuarenteNA.
- FR14: Público geral pode acessar relatórios públicos disponibilizados pelo grupo.
- FR15: Público geral pode acessar informações sobre a frequência regular das reuniões e o contexto público de funcionamento do grupo.
- FR16: Público geral pode acessar referências para outras reuniões e para o diretório oficial de reuniões virtuais como caminhos de apoio.
- FR17: Público geral pode acessar relatórios públicos sem exposição de informações pessoais identificáveis de membros.

### Caminhos de Apoio e Continuidade

- FR18: Participantes podem acessar o link do grupo de WhatsApp de membros a partir do site quando isso for apropriado.
- FR19: Visitantes podem acessar caminhos alternativos de apoio quando o Grupo QuarenteNA não for a única referência desejada.
- FR20: Público geral pode navegar para recursos externos aprovados sem perder a clareza sobre o caminho principal de entrada na reunião.

### Governança de Conteúdo e Anonimato

- FR21: Servidores voluntários podem manter as informações públicas da reunião a partir de uma fonte única e autoritativa.
- FR22: Servidores voluntários podem atualizar os dados públicos da reunião sem editar múltiplas cópias independentes da mesma informação.
- FR23: Servidores voluntários podem publicar conteúdo institucional preservando as regras de anonimato do grupo.
- FR24: Servidores voluntários podem referenciar responsáveis publicamente usando apenas primeiro nome ou apelido quando isso for necessário.
- FR25: Servidores voluntários podem revisar fotos, depoimentos e outros materiais públicos antes da publicação para evitar identificação indevida de membros.

### Descoberta e Medição Agregada

- FR26: Público geral pode descobrir o site por mecanismos de busca através de páginas públicas indexáveis.
- FR27: Grupo QuarenteNA pode medir acessos e eventos principais de forma agregada e sem identificar visitantes individualmente.
- FR28: Grupo QuarenteNA pode acompanhar, em nível agregado, interações com o link principal da reunião, o link do WhatsApp e os links de relatórios ou apoio.

### Plataforma Administrativa Futura

- FR29: Servidores voluntários podem autenticar-se em uma área administrativa futura.
- FR30: Servidores voluntários podem gerenciar dados da reunião por meio da área administrativa futura.
- FR31: Servidores voluntários podem gerenciar atas, relatórios e dashboards por meio da área administrativa futura.
- FR32: Servidores voluntários podem migrar dados operacionais de planilhas e ferramentas externas para registros internos gerenciados.
- FR33: Servidores voluntários podem acessar capacidades administrativas de acordo com seu papel na estrutura de serviço do grupo.

## Non-Functional Requirements

### Performance

- NFR1: O conteúdo crítico da home, incluindo acesso principal à reunião, deve estar disponível para uso em até 3 segundos em conexão móvel típica.
- NFR2: O acesso ao link principal da reunião não deve depender do carregamento bem-sucedido de recursos de terceiros.
- NFR3: A página inicial deve priorizar carregamento progressivo do conteúdo principal antes de elementos complementares.

### Reliability & Availability

- NFR4: A landing page pública deve perseguir disponibilidade mensal mínima de 99,5% no MVP.
- NFR5: O site deve continuar entregando link, horário, ID, senha, tipo da reunião e contexto básico do grupo mesmo quando recursos externos estiverem indisponíveis.
- NFR6: Falhas em relatórios públicos, diretórios externos ou links de apoio não podem impedir o acesso ao caminho principal da reunião.

### Security & Privacy

- NFR7: O MVP não deve exigir criação de conta, login público ou coleta de dados pessoais para acesso ao conteúdo aberto.
- NFR8: O site deve operar com transporte seguro via HTTPS em todas as páginas públicas sob controle do projeto.
- NFR9: Medição de uso deve ser agregada e não identificável, sem perfil individual de visitante no MVP.
- NFR10: O conteúdo público não deve expor nomes completos, fotos identificáveis ou depoimentos identificáveis de membros sem validação explícita segundo as regras de anonimato do grupo.

### Accessibility

- NFR11: Os fluxos públicos principais devem atender, no mínimo, a um nível prático equivalente a WCAG 2.1 AA para contraste, navegação por teclado, foco visível e estrutura semântica.
- NFR12: O site deve oferecer skip link para o conteúdo principal e permitir acesso ao CTA da reunião apenas com teclado.
- NFR13: O conteúdo textual crítico deve permanecer legível em telas móveis comuns sem exigir zoom ou rolagem horizontal.

### Compatibility & Responsive Use

- NFR14: O MVP deve funcionar adequadamente nas versões recentes de Chrome, Edge, Firefox e Safari, em desktop e mobile.
- NFR15: O layout deve ser utilizável a partir de larguras móveis pequenas e escalar de forma consistente para telas maiores.

### SEO & Discoverability

- NFR16: As páginas públicas devem ser rastreáveis por mecanismos de busca sem dependência de execução obrigatória de JavaScript para o conteúdo principal.
- NFR17: Cada página pública relevante deve possuir título, descrição e hierarquia semântica coerentes com o objetivo de descoberta orgânica.

### Integration & Resilience

- NFR18: Integrações ou links para WhatsApp, Looker Studio e diretórios externos devem falhar de forma graciosa, sem quebrar a home nem bloquear seu carregamento principal.
- NFR19: O caminho principal da reunião deve permanecer claro e acessível mesmo quando o usuário navegar para recursos externos de apoio.

### Maintainability

- NFR20: As informações da reunião no MVP devem ser atualizáveis a partir de uma única fonte de verdade.
- NFR21: Uma alteração de link, horário, ID, senha ou tipo da reunião não deve exigir manutenção em múltiplos pontos independentes da interface.

## Apêndice — Validação documental de 24/03/2026 (registro histórico)

O PRD foi submetido ao fluxo BMAD de validação em 24/03/2026; o relatório
original (`prd-validation-report-2026-03-24.md`) foi consolidado neste apêndice.
Resultado resumido:

- **Status geral:** `WARNING` — nenhum problema crítico; lacunas de detalhamento operacional.
- **Formato:** BMAD Standard (6/6 seções obrigatórias); densidade informacional aprovada.
- **Cobertura do Product Brief:** alta (~90%); 2 lacunas moderadas — contrato
  operacional da "reunião do dia" vs. grade semanal do brief, e direção visual
  pendente para a especificação de UX. Absorvidas depois por UX, arquitetura e épicos.
- **Mensurabilidade:** 9 violações no total (Warning) — FR4, FR9, FR18, FR19
  (funcionais) e NFR5, NFR6, NFR11, NFR18, NFR19 (não funcionais), pedindo
  contratos mais testáveis; NFR4 também citado em observações de template.
- **Rastreabilidade:** cadeias principais íntegras no MVP; FR29–FR33 (admin
  futuro) sem jornadas equivalentes — dependem de novo ciclo de descoberta.
- **Vazamento de implementação:** nenhum (0 violações).
- **Aderência ao perfil `web_app`:** 100% (browser matrix, responsividade, performance, SEO e acessibilidade presentes).
- **Qualidade SMART:** média 4,3/5; 87,9% dos FRs com todas as notas ≥ 3.
- **Qualidade holística:** 4/5 (Good); completude estimada em 92%.
- **Recomendação registrada:** não reabrir o PRD naquele momento; absorver as
  lacunas remanescentes em UX, arquitetura e backlog.

**Leitura correta deste registro:** trata-se de uma avaliação *documental*, de
março de 2026 — não de implantação real nem de certificação atual. O MVP nunca
foi publicado em produção; os requisitos de administração (FR29–FR33) nunca
foram implementados. O que foi implementado, corrigido e arquivado está
registrado em `epics.md` e `architecture.md`.
