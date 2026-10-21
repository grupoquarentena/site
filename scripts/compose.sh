#!/usr/bin/env bash
set -Eeuo pipefail
source "$(dirname "$0")/_comum.sh"
exec "${C[@]}" "$@"
