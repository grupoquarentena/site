# Code Review: MVP Epics 1-5

Data: 2026-05-27  
Escopo: `_bmad-output/implementation-artifacts` stories `1.1` ate `5.4`, implementacao em `app/`, `public/` e `tests/run.php`.
Issue: https://github.com/grupoquarentena/site/issues/28

## Achados

### Alta: dados operacionais da reuniao estao datados

Evidencia: `app/data/meeting.php:14` e `app/data/meeting.php:15` fixam a janela em `2026-03-24`, enquanto a data atual do ambiente e `2026-05-27`. O status automatico cai em `proxima reuniao`, mas o horario exibido continua apontando para uma data passada.

Impacto: Story `1.4` e `FR4` ficam parcialmente atendidos, porque o usuario recorrente pode entender que a proxima reuniao ainda e uma ocorrencia passada.

Recomendacao: atualizar os dados antes do lancamento ou criar modelo simples de recorrencia/fallback operacional para a reuniao regular.

### Alta: analytics existe, mas nao mede os cliques reais renderizados

Evidencia: `app/support/analytics.php:45` expoe `tracked_url()`, mas `app/views/partials/hero_meeting_cta.php:18` usa `join_url` diretamente e `app/views/partials/support_links.php` renderiza URLs externas diretamente.

Impacto: Story `5.3` fica incompleta na integracao HTML. O teste cobre helpers isolados, mas nao prova que a UI usa `track.php`.

Recomendacao: renderizar os links medidos via `tracked_url()` e proteger `public/track.php` antes de ativar isso em producao.

### Alta: endpoint de tracking permite open redirect

Evidencia: `public/track.php:15` aceita qualquer URL iniciada por `http://` ou `https://` e `public/track.php:22` redireciona para ela.

Impacto: o dominio oficial poderia ser usado como trampolim para phishing.

Recomendacao: validar o destino contra allowlist de dominios aprovados ou aceitar apenas destinos derivados do contrato de dados.

### Alta: CTA principal nao valida dominio ou esquema da URL

Evidencia: `app/support/meeting_contract.php:26` valida `join_url` apenas como string nao vazia e `app/views/partials/hero_meeting_cta.php:18` renderiza esse valor no `href`.

Impacto: um erro no fluxo manual pode publicar destino falso, inseguro ou com esquema indevido.

Recomendacao: exigir URL valida, esquema `https` e allowlist de dominios aprovados para reuniao.

### Media: links complementares aceitam qualquer dominio HTTP/HTTPS

Evidencia: `app/support/external_links.php:61` valida somente formato e esquema.

Impacto: diretório, relatório e WhatsApp podem apontar para destinos fora da governanca editorial.

Recomendacao: validar por tipo de link: dominio oficial de NA para diretorio, `chat.whatsapp.com` para WhatsApp e dominio aprovado para relatorios.

### Media: contador de analytics pode perder cliques concorrentes

Evidencia: `app/support/analytics.php:22` le o arquivo sem lock e `app/support/analytics.php:42` escreve com `LOCK_EX` apenas no final.

Impacto: duas requisicoes simultaneas podem sobrescrever incrementos.

Recomendacao: proteger leitura, incremento e escrita com `flock()` exclusivo no arquivo inteiro.

### Baixa: override de tempo por ambiente pode vazar para producao

Evidencia: `app/bootstrap.php:28` aceita `SITE_TEST_NOW` em qualquer ambiente.

Impacto: se a variavel for definida no servidor, o status da reuniao pode ficar congelado.

Recomendacao: aceitar `SITE_TEST_NOW` apenas quando `APP_ENV=test` ou equivalente.

### Baixa: readiness tecnico tem itens automaticos sem evidencia real

Evidencia: `app/support/analytics.php:83` marca itens como `true` sem executar auditoria real.

Impacto: Story `5.4` existe, mas pode passar uma falsa sensacao de prontidao.

Recomendacao: tornar esses itens manuais ou executar verificacoes reais antes de marcar `ok`.

## Cobertura Por Story

`1.1`: coberta. CTA unico e home SSR sem JavaScript obrigatorio foram preservados.  
`1.2`: coberta. Card mostra horario, ID, senha e tipo a partir da fonte unica.  
`1.3`: coberta. Tipo e explicacao ficam no bloco principal.  
`1.4`: parcial. Status existe, mas dados datados podem renderizar uma reuniao passada como proxima.  
`1.5`: coberta para links ausentes ou malformados; indisponibilidade real do destino externo nao e testada.  
`2.1`: coberta. Mensagem curta de acolhimento existe.  
`2.2`: coberta. Bloco "O que e NA" existe e permanece curto.  
`2.3`: coberta. Bloco sobre o grupo existe e evita identidades pessoais.  
`2.4`: coberta. Reuniao aberta e fechada sao explicadas.  
`2.5`: coberta. Historia resumida existe; claims numericos foram removidos no redesign.  
`3.1`: parcial. Suporte a relatorio existe, mas o contrato atual nao possui URL de relatorio.  
`3.2`: coberta. Link de diretorio existe.  
`3.3`: parcial. Suporte a WhatsApp existe, mas tracking real de clique nao esta integrado.  
`3.4`: coberta com melhoria aplicada. Links externos agora incluem texto acessivel oculto alem do indicador visual.  
`4.1`: coberta. Fonte unica em `app/data/meeting.php`.  
`4.2`: coberta. Guia e verificador operacional existem.  
`4.3`: coberta. Checklist e scanner basico existem.  
`4.4`: coberta. Regras editoriais e validacao de creditos existem.  
`5.1`: coberta. SEO basico e semantica existem.  
`5.2`: coberta com melhoria aplicada. Foco global e texto acessivel para links externos foram reforcados.  
`5.3`: parcial. Helpers existem, mas a UI nao usa tracking nos links renderizados.  
`5.4`: parcial. Checklist existe, mas algumas evidencias seguem manuais ou hardcoded.

## Verificacao Executada

`php tests/run.php` retornou `PASS: 225 verificacoes`.

## Arquivamento (2026-10-18)

O MVP foi arquivado na branch `legado/site-2026` (tag `site-2026-legado`), sem
publicacao em producao. Neste arquivamento foram tratados os oito achados da
issue #28: dados operacionais da reuniao removidos da fonte unica (link, ID e
senha), CTA desativado, destinacao de cliques resolvida por identificador de
contrato (sem open redirect), allowlists de dominio para Zoom e links de apoio,
contador de cliques com locking, gate de relogio restrito a `APP_ENV=test`,
checklist de prontidao apenas com evidencia explicita e `noindex` na pagina
estatica. A suite foi ampliada e passou a reportar `PASS: 309 verificacoes`.
