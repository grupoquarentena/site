#!/usr/bin/env bash
set -Eeuo pipefail
cd "$(dirname "$0")/.."
if [[ -e opencode.json || -e opencode.jsonc ]]; then
  echo 'Já existe configuração OpenCode. Ajuste o MCP manualmente; nenhum arquivo sobrescrito.' >&2
  exit 1
fi
[[ $# -eq 0 || ( $# -eq 1 && "$1" == '--windows-wsl' ) ]] || {
  echo 'Uso: configurar-boost-opencode.sh [--windows-wsl]' >&2; exit 2;
}
# OpenCode v2: mcp.servers; o cliente Windows encaminha o stdio ao WSL.
python3 - "$PWD" "${1:-}" "${WSL_DISTRO_NAME:-Debian}" "$(id -un)" <<'PY'
import json
import pathlib
import sys

root, mode, distro, user = sys.argv[1:]
command = [str(pathlib.Path(root) / 'scripts/artisan.sh'), 'boost:mcp']
if mode == '--windows-wsl':
    command = ['wsl.exe', '-d', distro, '-u', user, '--', 'bash', *command]
config = {
    '$schema': 'https://opencode.ai/config.json',
    'skills': ['./.agents/skills'],
    'mcp': {'servers': {'laravel-boost': {'type': 'local', 'command': command}}},
}
with open('opencode.json', 'x') as target:
    json.dump(config, target, indent=2)
    target.write('\n')
PY
printf 'opencode.json criado localmente (ignorado pelo Git). Confirme o handshake do MCP.\n'
