# Rascunho histórico do Site QuarenteNA (2025)

Primeiro protótipo do site do Grupo QuarenteNA, desenvolvido em PHP + MySQL +
CSS como página única (landing) com a grade semanal de reuniões calculada no
servidor. Ano de referência: **2025**; a data exata de desenvolvimento não foi
verificada.

**Este código não é destinado à produção.** Ele foi arquivado apenas como
registro histórico do projeto.

## Arquivos principais

- `index.php` — página única com a grade semanal, status calculado em PHP e
  integração opcional com o MySQL. Sem banco configurado, usa a agenda
  estática embutida.
- `conexao.php` — conexão PDO (padrão singleton) configurada somente por
  variáveis de ambiente; na ausência de configuração, `getPDO()` retorna
  `null` de forma controlada.
- `db.sql` — schema MySQL histórico (tabela `grupoquarentena_reunioes`) e seed
  da grade semanal. É um exemplo histórico: **não executar em produção** (os
  comandos destrutivos estão desativados no arquivo).
- `estilo.css` — estilos customizados (glassmorphism, animações e ajustes de
  acessibilidade) que complementam o Tailwind CSS carregado via CDN no HTML.

## Credenciais e links operacionais

Os arquivos foram sanitizados **antes do primeiro commit**: credenciais MySQL,
o link/ID/senha da sala de reunião em HTML e JSON-LD, e comandos destrutivos do
SQL foram removidos ou substituídos por valores não funcionais.

## Inspeção local

Requer apenas PHP 8+ (o banco é opcional):

```bash
php -l index.php
php -l conexao.php
php -S 127.0.0.1:8099
# abrir http://127.0.0.1:8099/index.php
```

Com `QUARENTENA_DB_HOST`, `QUARENTENA_DB_NAME`, `QUARENTENA_DB_USER` e
`QUARENTENA_DB_PASSWORD` definidas, o rascunho tenta ler a grade semanal do
MySQL; sem elas, usa a agenda estática.

## Referências históricas

- `legado/site-2026` — MVP PHP/BMAD de 2026 do site (nunca publicado; também
  arquivado, com tag `site-2026-legado`).
- `main` (e `dev`) — nova plataforma (Laravel/PostgreSQL), em desenvolvimento.

Este rascunho **não deve ser usado como base arquitetural** da nova plataforma.
