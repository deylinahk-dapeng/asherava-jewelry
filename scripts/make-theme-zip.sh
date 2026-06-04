#!/usr/bin/env bash
# Theme zip for WP Admin → Appearance → Themes → Add → Upload (no SSH).
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
OUT="${1:-$HOME/Downloads/asherava-jaxxon.zip}"
TMP="$(mktemp -d)"

trap 'rm -rf "$TMP"' EXIT

mkdir -p "$TMP/asherava-jaxxon"
rsync -a \
  --exclude '.git' \
  --exclude 'deploy.env' \
  --exclude 'scripts' \
  --exclude 'data' \
  --exclude '.venv-translate' \
  --exclude 'OMNISEND-SETUP.md' \
  --exclude 'DEPLOY-*.md' \
  --exclude '.DS_Store' \
  "$ROOT/" "$TMP/asherava-jaxxon/"

(cd "$TMP" && zip -rq "$OUT" asherava-jaxxon)
echo "Created: $OUT"
echo "WP: Appearance → Themes → Add New → Upload Theme → choose zip → Replace if asked"
