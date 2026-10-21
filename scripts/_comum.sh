#!/usr/bin/env bash
set -Eeuo pipefail
RAIZ="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$RAIZ"
if ! command -v docker >/dev/null 2>&1; then echo 'Docker não encontrado.' >&2; exit 1; fi
if [[ ! -f .env ]]; then echo 'Crie .env a partir de .env.example antes de continuar.' >&2; exit 1; fi
C=(docker compose --env-file "$RAIZ/.env" -f "$RAIZ/docker/compose.yml")

# Leitura simples de variáveis não citadas usadas exclusivamente por scripts operacionais.
# Remove CR final de arquivos editados em Windows sem modificar .env do usuário.
env_valor() {
  local nome="$1"
  sed -n "s/^${nome}=//p" "$RAIZ/.env" | tail -n 1 | tr -d '\r'
}
