#!/usr/bin/env bash
# Quick local deploy for Asherava theme edits.
# Uses the local SSH config alias that has already been verified: ssh asherava "echo OK"
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
SSH_HOST="${SSH_HOST:-asherava}"
WP_ROOT="${WP_ROOT:-/sites/asherava.com/files}"
REMOTE_THEME_DIR="${REMOTE_THEME_DIR:-${WP_ROOT}/wp-content/themes/asherava-jaxxon}"

cd "$ROOT"

echo "Deploying Asherava theme quick sync..."
echo "  local:  $ROOT"
echo "  remote: ${SSH_HOST}:${REMOTE_THEME_DIR}"

ssh "$SSH_HOST" "echo OK" >/dev/null

rsync -avz \
  --exclude '.DS_Store' \
  --exclude '.git' \
  --exclude 'data/' \
  --exclude 'deploy.env' \
  --exclude 'dist/' \
  --exclude 'node_modules/' \
  --exclude '*.zip' \
  functions.php \
  style.css \
  front-page.php \
  assets \
  inc \
  template-parts \
  woocommerce \
  "${SSH_HOST}:${REMOTE_THEME_DIR}/"

ssh "$SSH_HOST" "mkdir -p \"${REMOTE_THEME_DIR}/scripts\""
rsync -avz \
  scripts/launch-content-seed.php \
  scripts/launch-length-variations.php \
  scripts/update-3mm-rope-options.php \
  "${SSH_HOST}:${REMOTE_THEME_DIR}/scripts/"

ssh "$SSH_HOST" "cd \"${WP_ROOT}\" && wp cache flush && wp spinupwp cache purge-site"

echo "Done. WordPress object cache and SpinUpWP page cache were purged."
