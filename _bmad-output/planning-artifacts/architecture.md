# Architecture Decision Document

> **Documento histórico.** Arquitetura do MVP PHP (março de 2026), **nunca
> publicado em produção**. Consolidada em 18/10/2026 no arquivamento da branch
> `legado/site-2026` a partir de `architecture.md`. As seções finais registram o
> estado real do código arquivado, as correções do arquivamento de outubro de
> 2026 e os desvios entre o plano de março e a implementação. A plataforma atual
> é desenvolvida separadamente na branch `site-v2`.

## Project Context Analysis

### Project Classification

- Type: `web_app`
- Context: greenfield
- Complexity: low no MVP, com risco médio apenas na V2
- Delivery focus: landing page pública, SSR em PHP, sem banco no MVP

### Architectural Goal

Entregar uma arquitetura previsível, pequena e resiliente que priorize:

- acesso imediato à reunião
- simplicidade operacional
- SEO e acessibilidade reais
- manutenção manual segura pelos servidores do grupo

### Design Constraints

- Nada de SPA no MVP
- Nada de banco de dados ou `/admin` no MVP
- Nada de dependência crítica de terceiros para o caminho principal
- Fonte única de verdade obrigatória para dados da reunião
- Conteúdo público sujeito a revisão de anonimato

## Starter Template Evaluation

### Decision

**Não usar starter template externo.**

### Reasoning

- O produto é pequeno demais para justificar um boilerplate pesado.
- PHP SSR puro reduz acoplamento, dependências e risco operacional.
- Um esqueleto próprio do projeto mantém clareza máxima para futuros agentes e voluntários.

### Minimal Foundation to Create

- bootstrap único
- camada de dados simples
- helpers puros para status e links
- views server-rendered
- assets estáticos mínimos

## Core Architectural Decisions

### AD-01: Runtime and Rendering Model

O MVP será um monólito leve em PHP com renderização no servidor.

**Consequência:** o HTML crítico chega pronto ao navegador, com SEO e acessibilidade fortes por padrão.

### AD-02: Public Surface Area

O MVP terá uma superfície pública mínima:

- `/` ou `index.php` como home oficial
- `/out/{slug}` para redirecionamento opcional com medição agregada de links externos
- `/health` ou equivalente para verificação operacional simples

Não haverá `/admin` funcional nesta fase. O namespace pode ser reservado, mas não entregue.

### AD-03: Single Source of Truth for Meeting Data

Os dados da reunião serão centralizados em um único arquivo versionado:

`app/data/meeting.php`

Esse arquivo é a única origem autorizada para:

- timezone
- título curto da reunião
- link principal
- meeting ID
- senha
- tipo (`aberta` ou `fechada`)
- início e fim previstos
- status override opcional
- rótulo de atualização (`updated_at`)

### AD-04: Status Strategy

Para reduzir ambiguidade de `FR4`, o status seguirá este contrato:

1. Se `status_override` existir, ele vence.
2. Se `starts_at` e `ends_at` existirem, o sistema calcula:
   - `agora` quando o horário atual estiver dentro da janela
   - `mais tarde hoje` quando o início estiver no mesmo dia e ainda não começou
   - `próxima reunião` quando a próxima ocorrência estiver em outro dia ou sem janela atual
3. Se a agenda não estiver suficientemente detalhada, o servidor voluntário define `status_override` manualmente.

### AD-05: External Resource Resilience

WhatsApp, Looker Studio e diretórios são tratados como complementares.

- Eles nunca bloqueiam o carregamento da home.
- Seus links vivem em seção secundária.
- Toda falha deve preservar o CTA principal, o card da reunião e o contexto mínimo.

### AD-06: Anonymous Aggregate Analytics

A medição do MVP será agregada e mínima.

**Decisão:** utilizar logs do servidor e/ou redirecionamento interno para links externos, evitando rastreamento individual.

Eventos mínimos:

- `meeting_cta_click`
- `whatsapp_click`
- `report_click`
- `support_directory_click`

Nenhum evento conterá IP armazenado como dado analítico de produto, nome de usuário, cookie pessoal ou fingerprint.

### AD-07: Editorial Safety Boundary

Todo conteúdo público deve passar por checklist editorial antes de publicação:

- sem nomes completos de membros
- sem imagens identificáveis
- sem depoimentos identificáveis
- responsáveis públicos apenas por primeiro nome ou apelido

### AD-08: Future Admin Isolation

As necessidades futuras de `/admin` ficam isoladas do MVP:

- sem dependência técnica para a home pública
- sem entidades internas obrigatórias agora
- sem decisões prematuras de banco além de reserva de namespace e fronteiras conceituais

### AD-09: Accessibility as Architecture Constraint

Acessibilidade não será tratada como acabamento visual. Ela entra no contrato estrutural:

- markup semântico
- foco visível
- skip link
- conteúdo crítico navegável por teclado
- sem dependência obrigatória de JS para leitura da informação principal

### AD-10: Deployment Model

O sistema deve rodar em hospedagem tradicional com suporte a PHP e HTTPS.

Sem containers obrigatórios para o MVP. A arquitetura deve caber em deploy simples por arquivo/versionamento.

## Architecture Overview

```mermaid
flowchart TD
  Visitor[Navegador do visitante] --> Home[public/index.php]
  Home --> Bootstrap[app/bootstrap.php]
  Bootstrap --> MeetingData[app/data/meeting.php]
  Bootstrap --> ContentData[app/content/home.php]
  Bootstrap --> Status[app/support/meeting_status.php]
  Bootstrap --> Links[app/support/external_links.php]
  Bootstrap --> View[app/views/home.php]
  View --> CTA[CTA principal da reunião]
  View --> Support[Links complementares]
  Support --> Redirect[public/out/{slug}]
```

## Implementation Patterns & Consistency Rules

### Pattern 1: Data First, View Second

Views só recebem dados já resolvidos por helpers. Não fazem lógica de negócio relevante.

### Pattern 2: No Duplicated Meeting Facts

Nenhum template ou partial pode hardcodar `horário`, `ID`, `senha`, `tipo` ou `link`.

### Pattern 3: Fail Soft

Quando um recurso complementar estiver ausente:

- esconder ou degradar o bloco complementar
- nunca mover o CTA principal
- nunca substituir a informação da reunião por erro externo

### Pattern 4: Content Files Are Explicit

Conteúdo institucional curto vive em arquivos dedicados, por exemplo:

- `app/content/home.php`
- `app/content/about_na.php`
- `app/content/group_history.php`

### Pattern 5: Helpers Are Pure and Small

Helpers não escrevem em banco, não chamam APIs externas e não fazem side effects além de cálculo simples.

### Pattern 6: Privacy by Default

Qualquer funcionalidade futura que demande identificação deve ficar fora do MVP.

## Project Structure & Boundaries

### Proposed Directory Structure

```text
app/
  bootstrap.php
  config/
    site.php
  content/
    home.php
  data/
    meeting.php
  support/
    meeting_status.php
    analytics.php
    external_links.php
  views/
    home.php
    partials/
      hero_meeting_cta.php
      meeting_info_card.php
      support_links.php
      footer.php
public/
  index.php
  out.php
  assets/
    css/
    img/
```

### Boundary Definitions

- `app/data`: dados autoritativos e estáticos/versionados
- `app/content`: copy institucional revisada
- `app/support`: regras de apresentação e integração leve
- `app/views`: montagem final do HTML
- `public`: borda HTTP

### Source of Truth Contract

```php
<?php
return [
    'timezone' => 'America/Sao_Paulo',
    'meeting' => [
        'title' => 'Grupo QuarenteNA',
        'join_url' => 'https://...',
        'meeting_id' => '...',
        'password' => '...',
        'type' => 'aberta',
        'starts_at' => '2026-03-24 20:00:00',
        'ends_at' => '2026-03-24 21:30:00',
        'status_override' => null,
        'updated_at' => '2026-03-24T00:00:00-03:00',
    ],
    'support_links' => [
        'whatsapp' => 'https://...',
        'report' => 'https://...',
        'directory' => 'https://...',
    ],
];
```

### Manual Update Flow

1. Servidor voluntário altera `app/data/meeting.php`
2. Confere se tipo, horário, link, ID e senha estão consistentes
3. Confere checklist de anonimato do conteúdo público
4. Publica a atualização
5. Valida home e links essenciais

## Requirements Coverage and Validation

### MVP Requirements Covered by Architecture

- `FR1`-`FR6`: CTA principal, card da reunião, status e resiliência
- `FR18`-`FR20`: links complementares subordinados e redirecionáveis
- `FR21`-`FR25`: fonte única, atualização manual e governança de anonimato
- `FR26`-`FR28`: indexabilidade e medição agregada mínima
- `NFR1`-`NFR3`: SSR leve e critical path sem terceiros
- `NFR4`-`NFR6`: disponibilidade e graceful degradation
- `NFR7`-`NFR10`: privacidade e exposição pública segura
- `NFR11`-`NFR21`: acessibilidade, compatibilidade, SEO e maintainability

### Assumptions Resolved Here

- Fuso único do grupo: `America/Sao_Paulo`
- Status da reunião com cálculo simples e override manual
- Analytics sem plataforma externa obrigatória
- Arquivo PHP versionado como fonte única de verdade
- `/admin` explicitamente fora do MVP

### Risks and Controls

| Risk | Control |
| --- | --- |
| Informação da reunião desatualizada | Fonte única + fluxo manual curto + `updated_at` |
| Dependência de serviços externos | Links complementares e redirecionamento opcional |
| Perda de clareza mobile | HTML crítico priorizado e UX mobile-first |
| Quebra de anonimato | Checklist editorial obrigatório |
| Crescimento desordenado para V2 | Isolamento do namespace `/admin` |

## Handoff Guidance

### What UX and Backlog Should Assume

- Existe uma única home pública no MVP.
- O card da reunião é o núcleo da interface.
- Todo conteúdo secundário é subordinado à tarefa principal.
- A fonte única de verdade resolve as ambiguidades do PRD sem alterá-lo.

### What Must Not Be Implemented in MVP

- autenticação pública
- CMS
- banco de dados próprio
- dashboards internos nativos
- dependência de terceiros no fluxo principal

### Completion Status

Arquitetura definida para o MVP e preparada para alimentar épicos e stories. Os temas de V2 ficam delimitados, mas não destrinchados em implementação detalhada dentro desta fase.

## Estado do código arquivado (outubro de 2026)

O que a branch `legado/site-2026` contém de fato, depois do saneamento de outubro:

```text
app/
  bootstrap.php                 # valida o contrato, resolve status e apresentação
  content/home.php              # copy editorial da home
  data/meeting.php              # fonte única de verdade (dados operacionais removidos)
  support/
    analytics.php               # contador agregado anônimo (tmp/analytics.json, flock)
    editorial_governance.php    # regras editoriais, anonimato e checklist de lançamento
    external_links.php          # apresentação e validação de links de apoio
    meeting_contract.php        # validação do contrato e allowlists de URL
    meeting_presenter.php       # formatação para exibição
    meeting_status.php          # cálculo de status + relógio de teste (APP_ENV=test)
  views/
    home.php
    partials/ (hero_meeting_cta, meeting_info_card, meeting_types,
               about_na, about_group, group_history, support_links)
public/
  index.php                     # home SSR
  track.php                     # medição de cliques + redirecionamento por contrato
  assets/css/home.css
tests/run.php                   # suíte de verificações (PASS: 310 em 18/10/2026)
```

O runtime permanece PHP SSR puro, sem framework, sem banco de dados e sem
dependência crítica de terceiros, como planejado.

## Correções do arquivamento de outubro de 2026

Os oito achados da revisão de maio de 2026 (issue
[#28](https://github.com/grupoquarentena/site/issues/28)) foram tratados nos
commits de saneamento `b3e07f2` e `2201be7`, alterando o comportamento do código:

1. **Dados operacionais removidos da fonte única.** `join_url`, `meeting_id`,
   `password`, `starts_at` e `ends_at` ficaram nulos; o contrato passou a aceitar
   a ausência explicitamente e o presenter exibe "Não divulgado"/"Não divulgada"
   e "Sem agenda publicada", com CTA principal desativado.
2. **Novo estado `sem agenda ativa`.** Além de `agora`, `mais tarde hoje` e
   `próxima reunião`, o status reconhece o estado explícito de ausência de agenda
   (manual via `status_override` ou automático quando não há janela configurada).
3. **Validação do link da reunião.** Quando configurado, `join_url` deve ser
   `https`, domínio oficial do Zoom (`zoom.us`, `zoom.com`, `zoom.com.br`), sem
   credenciais embutidas e sem porta alternativa.
4. **Links de apoio validados por categoria.** `directory`/`report` restritos a
   `na.org.br`/`na.org`; `whatsapp` restrito a `chat.whatsapp.com`/`wa.me`;
   sempre `https`, sem credenciais e sem porta alternativa.
5. **Fim do redirecionamento aberto.** `track.php` não aceita destino arbitrário:
   resolve o destino pelo contrato a partir de identificadores conhecidos
   (`meeting`, `directory`, `report`, `whatsapp`); requisição inválida → `400`,
   destino aprovado → `302` com evento agregado.
6. **Contador de analytics com `flock`.** Leitura, incremento e escrita do
   `tmp/analytics.json` acontecem sob o mesmo lock exclusivo; incrementos
   concorrentes não se perdem.
7. **Relógio de teste isolado.** `SITE_TEST_NOW` só tem efeito com `APP_ENV=test`;
   nos demais ambientes o relógio real é usado.
8. **Checklist de lançamento sem aprovação automática.** Itens só ficam `ok` com
   evidência explícita informada; sem evidência permanecem `pending` — o MVP
   histórico nunca é apresentado como pronto para produção.

Além disso, a home e o `index.html` estático passaram a responder com
`noindex, nofollow` e a copy registra o caráter histórico do acervo.

## Desvios entre o plano de março e a implementação

- `public/out/{slug}` foi implementado como `public/track.php?event=...&target=...`,
  resolvendo o destino por identificador de contrato (mais restrito que o plano).
- O endpoint `/health` do plano não foi implementado no PHP.
- `app/config/site.php` não foi criado; a configuração do site vive no `bootstrap`.
- O conteúdo institucional ficou em um único `app/content/home.php` (não foram
  criados `about_na.php`/`group_history.php` separados).
- `editorial_governance.php` foi adicionado além do plano, para concentrar regras
  editoriais, checklist de anonimato e checklist de lançamento.
- O rodapé está inline em `views/home.php` (não existe partial `footer.php`).
- O deploy seguiu em hospedagem PHP simples; a configuração Docker criada depois
  é apenas ambiente local de execução/teste do acervo (não publicação).
