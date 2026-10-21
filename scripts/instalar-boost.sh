#!/usr/bin/env bash
set -Eeuo pipefail
source "$(dirname "$0")/_comum.sh"
[[ -f vendor/autoload.php ]] || { echo 'Execute instalar.sh --preparar primeiro.' >&2; exit 1; }
# Preservar governança autoral mesmo se boost:install falhar.
backup="$(mktemp)"
cp AGENTS.md "$backup"
trap 'cp "$backup" "$RAIZ/AGENTS.md"; rm -f "$backup"' EXIT
"${C[@]}" run --rm --no-deps app php artisan boost:install
printf '\nBoost instalado; rode ./scripts/configurar-boost-opencode.sh somente se desejar MCP local.\n'
