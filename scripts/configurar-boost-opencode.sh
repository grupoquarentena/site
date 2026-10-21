#!/usr/bin/env bash
set -Eeuo pipefail
cd "$(dirname "$0")/.."
if [[ -e opencode.json || -e opencode.jsonc ]]; then
  echo 'Já existe configuração OpenCode. Ajuste o MCP manualmente; nenhum arquivo sobrescrito.' >&2
  exit 1
fi
cat > opencode.json <<'JSON'
{
  "$schema": "https://opencode.ai/config.json",
  "mcp": {
    "laravel-boost": {
      "type": "local",
      "command": ["./scripts/artisan.sh", "boost:mcp"],
      "enabled": true
    }
  }
}
JSON
printf 'opencode.json criado localmente (ignorado pelo Git). Confirme o handshake do MCP.\n'
