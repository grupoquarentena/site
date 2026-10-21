#!/usr/bin/env bash
set -Eeuo pipefail
source "$(dirname "$0")/_comum.sh"
[[ -s composer.lock ]] || { echo 'Fundação não preparada.' >&2; exit 1; }
[[ "$(sed -n 's/^DB_HOST=//p' .env | tail -1)" == 'postgres' && -z "$(sed -n 's/^DB_URL=//p' .env | tail -1)" ]] || { echo 'Somente banco Docker local postgres, sem DB_URL externo.' >&2; exit 1; }
mkdir -p database/schema docs/banco tmp
schema_tmp="$(mktemp "$RAIZ/tmp/pgsql-schema.XXXXXX")"
doc_tmp="$(mktemp "$RAIZ/tmp/db.XXXXXX")"
trap 'rm -f "$schema_tmp" "$doc_tmp"' EXIT
# Dump do PostgreSQL em execução: nunca simular o schema a partir do PRD.
"${C[@]}" exec -T postgres sh -ec 'exec pg_dump --schema-only --no-owner --no-privileges -U "$POSTGRES_USER" -d "$POSTGRES_DB"' > "$schema_tmp"
{
  printf '# Banco de dados — Grupo QuarenteNA\n\nCatálogo gerado do PostgreSQL local pelo script `scripts/banco-atualizar.sh`. Não editar manualmente.\n\n| Tabela | Coluna | Tipo | Nulo |\n|---|---|---|---|\n'
  "${C[@]}" exec -T postgres sh -ec 'exec psql -X -A -t -F "|" -U "$POSTGRES_USER" -d "$POSTGRES_DB"' <<'SQL' | awk -F '|' '{printf "| %s | %s | %s | %s |\n", $1, $2, $3, $4}'
SELECT table_name, column_name, data_type, is_nullable
FROM information_schema.columns
WHERE table_schema = 'public'
ORDER BY table_name, ordinal_position;
SQL
} > "$doc_tmp"
test -s "$schema_tmp" && test -s "$doc_tmp"
mv "$schema_tmp" database/schema/pgsql-schema.sql
mv "$doc_tmp" docs/banco/db.md
printf 'Snapshot e catálogo gerados do PostgreSQL real. Verifique git diff.\n'
