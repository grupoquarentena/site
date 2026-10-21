#!/usr/bin/env bash
set -Eeuo pipefail
source "$(dirname "$0")/_comum.sh"
[[ -s composer.lock && -f vendor/autoload.php ]] || { echo 'Prepare dependências primeiro.' >&2; exit 1; }
# Gates comuns; banco SQLite descartável por padrão.
"${C[@]}" run --rm -T --no-deps app vendor/bin/pint --test
"${C[@]}" run --rm -T --no-deps app vendor/bin/phpstan analyse --configuration=ferramentas/phpstan.neon --no-progress
if [[ "${1:-}" == '--postgres' ]]; then
  # NUNCA ler DB_HOST/DB_URL do .env: banco fixo, efêmero e isolado.
  "${C[@]}" --profile test up -d --wait postgres_test
  db_teste="$(sed -n 's/^DB_TEST_DATABASE=//p' .env | tail -1)"
  user_teste="$(sed -n 's/^DB_TEST_USERNAME=//p' .env | tail -1)"
  senha_teste="$(sed -n 's/^DB_TEST_PASSWORD=//p' .env | tail -1)"
  [[ -n "$db_teste" && -n "$user_teste" && -n "$senha_teste" ]] || { echo 'Credenciais de teste ausentes.' >&2; exit 1; }
  "${C[@]}" run --rm -T --no-deps \
    -e APP_ENV=testing -e DB_CONNECTION=pgsql -e DB_URL= \
    -e DB_HOST=postgres_test -e DB_PORT=5432 \
    -e DB_DATABASE="$db_teste" -e DB_USERNAME="$user_teste" -e DB_PASSWORD="$senha_teste" \
    -e SESSION_DRIVER=array -e CACHE_STORE=array -e QUEUE_CONNECTION=sync \
    app sh -c 'php artisan migrate:fresh --force && php artisan test'
else
  [[ $# -eq 0 ]] || { echo 'Uso: testar.sh [--postgres]' >&2; exit 2; }
  "${C[@]}" run --rm -T --no-deps \
    -e APP_ENV=testing -e DB_CONNECTION=sqlite -e DB_DATABASE=:memory: -e DB_URL= \
    -e SESSION_DRIVER=array -e CACHE_STORE=array -e QUEUE_CONNECTION=sync \
    app php artisan test
fi
