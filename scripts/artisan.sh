#!/usr/bin/env bash
set -Eeuo pipefail
source "$(dirname "$0")/_comum.sh"
# stdio limpo e sem pseudo-TTY para MCP; nunca executar Composer/PHP do host.
if [[ "${1:-}" == 'boost:mcp' ]]; then
  # MCP JSON-RPC usa stdio; não abrir pseudo-TTY.
  exec "${C[@]}" run --rm -T --no-deps app php artisan "$@"
fi
exec "${C[@]}" run --rm --no-deps app php artisan "$@"
