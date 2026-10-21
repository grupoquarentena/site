#!/usr/bin/env bash
set -Eeuo pipefail
source "$(dirname "$0")/_comum.sh"
[[ -s composer.lock && -f vendor/autoload.php ]] || { echo 'Prepare dependências primeiro.' >&2; exit 1; }
# O diretório pai pertence ao usuário WSL, mesmo com caches gerados pelo container.
mkdir -p "$RAIZ/tmp"
# Gates comuns; banco SQLite descartável por padrão.
"${C[@]}" run --rm -T --no-deps app vendor/bin/pint --test
"${C[@]}" run --rm -T --no-deps app vendor/bin/phpstan analyse --configuration=ferramentas/phpstan.neon --no-progress
if [[ "${1:-}" == '--postgres' ]]; then
  # NUNCA ler DB_HOST/DB_URL do .env: banco fixo, efêmero e isolado.
  # Banco de testes é efêmero; limpar este serviço ao término do gate.
  limpar_pg_test() {
    "${C[@]}" --profile test stop postgres_test >/dev/null 2>&1 || true
    "${C[@]}" --profile test rm -f postgres_test >/dev/null 2>&1 || true
  }
  trap limpar_pg_test EXIT
  "${C[@]}" --profile test up -d --wait postgres_test
  "${C[@]}" run --rm -T --no-deps \
    -e APP_ENV=testing -e QUARENTENA_TEST_DRIVER=pgsql -e DB_CONNECTION=pgsql -e DB_URL= \
    -e APP_CONFIG_CACHE=/tmp/quarentena-tests-config.php \
    -e DB_HOST=postgres_test -e DB_PORT=5432 \
    -e SESSION_DRIVER=array -e CACHE_STORE=array -e QUEUE_CONNECTION=sync \
    app sh -ec '
      # env_file do Compose resolve corretamente aspas dotenv; não usar sed para senhas.
      export DB_DATABASE="${DB_TEST_DATABASE:?Banco de teste ausente}"
      export DB_USERNAME="${DB_TEST_USERNAME:?Usuario de teste ausente}"
      export DB_PASSWORD="${DB_TEST_PASSWORD:?Senha de teste ausente}"
      QUARENTENA_TEST_SCHEMA=direct php artisan test
      QUARENTENA_TEST_SCHEMA=snapshot php artisan test
    '
else
  [[ $# -eq 0 ]] || { echo 'Uso: testar.sh [--postgres]' >&2; exit 2; }
  "${C[@]}" run --rm -T --no-deps \
    -e APP_ENV=testing -e QUARENTENA_TEST_DRIVER=sqlite -e DB_CONNECTION=sqlite -e DB_DATABASE=:memory: -e DB_URL= \
    -e APP_CONFIG_CACHE=/tmp/quarentena-tests-config.php \
    -e SESSION_DRIVER=array -e CACHE_STORE=array -e QUEUE_CONNECTION=sync \
    app php artisan test
fi
