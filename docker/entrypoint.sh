#!/bin/sh
set -e

echo "[QuarenteNA][Init] Iniciando container de teste do arquivo historico..."

APP_TMP_DIR="${APP_TMP_DIR:-/var/www/site/tmp}"

mkdir -p "$APP_TMP_DIR"

if [ "$(id -u)" = "0" ]; then
    chown -R www-data:www-data "$APP_TMP_DIR" || true
fi

echo "[QuarenteNA][Init] Iniciando Apache..."

if command -v docker-php-entrypoint >/dev/null 2>&1; then
    exec docker-php-entrypoint "$@"
fi

exec "$@"
