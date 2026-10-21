#!/bin/sh
set -eu

# Docker Secrets só são lidos dentro do container; jamais gravar valores em logs.
for nome in APP_KEY DB_PASSWORD; do
    eval "arquivo=\${${nome}_FILE:-}"
    if [ -n "$arquivo" ]; then
        if [ ! -r "$arquivo" ]; then echo "Segredo indisponível: ${nome}_FILE" >&2; exit 1; fi
        valor="$(cat "$arquivo")"
        export "$nome=$valor"
        unset valor
    fi
done
exec "$@"
