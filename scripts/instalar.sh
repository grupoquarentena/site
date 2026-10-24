#!/usr/bin/env bash
set -Eeuo pipefail
RAIZ="$(cd "$(dirname "$0")/.." && pwd)"
cd "$RAIZ"
case "${1:-}" in
  --preparar)
    if [[ ! -f .env ]]; then cp .env.example .env; chmod 600 .env; echo 'Configure DB_PASSWORD e DB_TEST_PASSWORD em .env; execute --preparar novamente.'; exit 1; fi
    source "$RAIZ/scripts/_comum.sh"
    "${C[@]}" build app
    if [[ -s composer.lock ]]; then
      "${C[@]}" run --rm --no-deps app composer install --no-interaction --prefer-dist --no-progress
    else
      echo 'composer.lock ausente: primeira resolução pelo Composer; revise e versione o lockfile.'
      "${C[@]}" run --rm --no-deps app composer update --no-interaction --prefer-dist --no-progress
    fi
    "${C[@]}" run --rm --no-deps app composer audit --locked
    if [[ -z "$(env_valor APP_KEY)" ]]; then
      "${C[@]}" run --rm --no-deps app php artisan key:generate --no-interaction
    else
      echo 'APP_KEY existente preservada.'
    fi
    echo 'Preparação concluída. Nenhuma migration executada.'
    ;;
  --inicializar)
    source "$RAIZ/scripts/_comum.sh"
    if [[ ! -s composer.lock || ! -f vendor/autoload.php ]]; then
      echo 'Execute --preparar antes.' >&2; exit 1
    fi
    dbhost="$(env_valor DB_HOST)"
    dbconn="$(env_valor DB_CONNECTION)"
    dburl="$(env_valor DB_URL)"
    if [[ "$dbhost" != 'postgres' || "$dbconn" != 'pgsql' || -n "$dburl" ]]; then
      echo 'Proteção: inicialização somente para DB_CONNECTION=pgsql, DB_HOST=postgres e DB_URL vazio.' >&2; exit 1
    fi
    echo 'ATENÇÃO: irá executar migrations no banco Docker LOCAL definido em .env.'
    read -r -p 'Digite INICIALIZAR para continuar: ' confirma
    [[ "$confirma" == 'INICIALIZAR' ]] || { echo 'Cancelado.'; exit 1; }
    "${C[@]}" up -d postgres app
    "${C[@]}" exec -T app php artisan migrate --force
    echo 'Banco inicializado. Gere snapshot versionável: ./scripts/banco-atualizar.sh'
    ;;
  *)
    echo 'Uso: ./scripts/instalar.sh --preparar | --inicializar' >&2; exit 2
    ;;
esac
