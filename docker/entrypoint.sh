#!/bin/sh
set -eu

# Docker Secrets só são lidos dentro do container; jamais gravar valores em logs.
for nome in APP_KEY DB_PASSWORD; do
    case "$nome" in
        APP_KEY) arquivo="${APP_KEY_FILE:-}" ;;
        DB_PASSWORD) arquivo="${DB_PASSWORD_FILE:-}" ;;
    esac
    if [ -n "$arquivo" ]; then
        if [ ! -r "$arquivo" ]; then echo "Segredo indisponível: ${nome}_FILE" >&2; exit 1; fi
        valor="$(cat "$arquivo")"
        export "$nome=$valor"
        unset valor
    fi
done
# Volumes gerenciados pelo Docker isolam escrita do Apache do NTFS/WSL.
# Aplicar somente aos diretórios de runtime, nunca chown no projeto inteiro.
if [ "$(id -u)" -eq 0 ]; then
    mkdir -p storage/app/private storage/app/public storage/framework/cache/data \
        storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
    chown -R www-data:www-data storage bootstrap/cache
    chmod -R u+rwX,g+rwX storage bootstrap/cache
fi
exec "$@"
