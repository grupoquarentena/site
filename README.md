# Grupo QuarenteNA — MVP histórico do site (PHP)

Registro histórico do MVP do site do Grupo QuarenteNA (Narcóticos Anônimos),
desenvolvido entre março e maio de 2026 e **nunca publicado em produção**.
O código e a documentação estão arquivados na branch `legado/site-2026`
(tags anotadas `site-2026-legado`, snapshot de outubro, e
`site-2026-legado-completo`, acervo com documentação consolidada e ambiente
Docker local) apenas para consulta histórica.

> **Não implantar.** Este MVP não deve voltar ao ar: a reunião está arquivada
> (sem link, ID ou senha) e a plataforma nova é a branch `site-v2`.

## Finalidade e contexto

- Home institucional de página única, com CTA de reunião, card de informações
  e links de apoio (diretório oficial, relatório e WhatsApp de membros).
- Stack: PHP 8 com renderização server-side (SSR); **sem Laravel e sem banco
  de dados**. A reunião tem fonte única em `app/data/meeting.php`; cliques são
  contados de forma anônima e agregada em `tmp/analytics.json` (ignorado pelo
  Git e descartável).
- Estado atual: **sem agenda ativa**. Os dados operacionais da reunião foram
  removidos no arquivamento (18/10/2026); os campos nulos registram a ausência
  de agenda e o card mostra "Não divulgado"/"Não divulgada".
- Pendências abertas durante o MVP foram registradas na issue
  [grupoquarentena/site#28](https://github.com/grupoquarentena/site/issues/28)
  e resolvidas neste arquivamento.
- A plataforma nova está em desenvolvimento na branch `site-v2` (que depois
  será promovida a `main`/`dev`).

## Estrutura

- `app/bootstrap.php` — carrega suportes e contrato da reunião.
- `app/data/meeting.php` — fonte única de dados da reunião (arquivada e
  sanitizada).
- `app/support/` — contrato, status, presenter, links externos, analytics e
  governança editorial.
- `app/views/` (e `app/views/partials/`) — templates da home.
- `app/content/home.php` — copy editorial da home.
- `public/index.php` — entrada da home; `public/track.php` — medição anônima
  de cliques (o destino é resolvido pelo contrato a partir de um
  identificador; nenhuma URL arbitrária é aceita, eliminando open redirect).
- `public/assets/css/home.css` — estilos.
- `tests/run.php` — suíte de verificações automatizadas.
- `index.html` — página estática do marco inicial (março/2026), com
  `noindex, nofollow`.
- `_bmad-output/` — artefatos históricos do método BMAD: `planning-artifacts/`
  (brief, PRD, UX, arquitetura e épicos consolidados em outubro/2026) e
  `implementation-artifacts/` (stories, code review, sprint status).
- `docs/` — contexto de processo do MVP (`project-context.md` e
  `git-workflow.md`), preservado como registro histórico.
- `docker/` — ambiente local de execução e teste (PHP 8.2 + Apache, restrito a
  `127.0.0.1`); **não é configuração de publicação**.

## Testes

```bash
php -l public/index.php        # lint de qualquer arquivo PHP alterado
php tests/run.php              # suíte completa
```

Estado verificado em 18/10/2026: lint sem erros em 20 arquivos PHP e
`PASS: 310 verificacoes` na suíte (inclui allowlists de domínio para Zoom e
links de apoio, resolução de destino por contrato, gate de relógio de teste
via `APP_ENV=test`, concorrência do contador com `flock` e ausência de dados
operacionais reais no HEAD).

Smoke manual executado com `php -S 127.0.0.1:8099 -t public`:

- `GET /index.php` → 200;
- `GET /track.php` sem parâmetros válidos → 400;
- `GET /track.php?event=support_directory_click&target=directory` → 302 para
  o diretório oficial;
- alvo `meeting`/`whatsapp` (nulos no arquivamento) e alvos arbitrários → 400.

## Execução local com Docker

Ambiente **local de execução e teste** do acervo (PHP 8.2 + Apache), restrito
a `127.0.0.1`; não é configuração de publicação. Requisitos: Docker com
Compose.

```bash
docker compose -f docker/docker-compose.yml up --build   # construir e iniciar
curl -sS -o /dev/null -w '%{http_code}\n' http://127.0.0.1:8080/
docker compose -f docker/docker-compose.yml exec app php /var/www/site/tests/run.php
docker compose -f docker/docker-compose.yml down         # parar e remover
```

- Endereço local: `http://127.0.0.1:8080` (somente loopback).
- Volumes: `public/`, `app/` e `tests/` somente leitura; `tmp/` é o único
  gravável (contadores anônimos e descartáveis em `tmp/analytics.json`).
- Nenhuma reunião real está configurada (sem agenda ativa) e a home segue com
  `noindex, nofollow`.
- Artefatos de deploy do MVP (stack Swarm e `deploy.sh`) não foram
  incorporados ao acervo: são caminho de publicação de um projeto que nunca
  foi publicado e não deve ser.
- Imagem `quarentena-site:local`; `down` remove container e rede (a imagem
  permanece até ser removida manualmente).

Validação executada em 18/10/2026 (Docker 29.8.1, Compose v5.5.1): build,
healthcheck saudável, home `200` com estado sem agenda ativa, CSS `200`,
cabeçalhos de segurança presentes, `track.php` com `302` para o diretório
oficial e `400` para destinos arbitrários ou sanitizados, `app/` e `tests/`
inacessíveis via HTTP (`404`), suíte no container `PASS: 310 verificacoes` e
lint PHP 8.2 sem erros (20 arquivos).

## Limitações e riscos conhecidos

- **Histórico Git**: os dados operacionais antigos da reunião (URL, ID e
  senha) ainda existem em commits anteriores a este arquivamento e na tag
  histórica. Eles foram removidos do estado atual do código, mas não do
  histórico. Se a sala original ainda existir, rotacione/invalide as
  credenciais na origem. Não reutilize os dados antigos.
- Nunca publicado: não há evidências de execução em produção; o checklist em
  `app/support/analytics.php` só aprova itens com evidência explícita
  informada pelo chamador.
- Sem banco de dados e sem autenticação: os contadores são locais (`tmp/`) e
  não substituem analytics de produção.
- Links externos são validados por categoria (apenas HTTPS, sem credenciais
  embutidas e sem porta alternativa); a disponibilidade real de cada destino
  externo não é testada.
- O ambiente Docker usa `php:8.2-apache` por fidelidade ao MVP; o PHP 8.2 tem
  suporte de segurança até 31/12/2026 — após essa data, use o ambiente apenas
  para inspeção offline.

## Referência do arquivamento

- Branch: `legado/site-2026`
- Tags: `site-2026-legado` (snapshot de outubro) e `site-2026-legado-completo`
  (acervo com documentação consolidada e ambiente Docker local)
- Issues: [#28 — Problemas do MVP público](https://github.com/grupoquarentena/site/issues/28)
  e [#32 — Concluir e validar execução Docker do MVP PHP histórico](https://github.com/grupoquarentena/site/issues/32)
- Plataforma nova: branch `site-v2` (promoção posterior a `main`/`dev`)
