#!/usr/bin/env bash
# Sync local theme to server over SSH (rsync).
# Setup: see DEPLOY-SSH.md and deploy.env.example
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
ENV_FILE="${DEPLOY_ENV:-$ROOT/deploy.env}"

if [[ ! -f "$ENV_FILE" ]]; then
  echo "Missing $ENV_FILE"
  echo "Copy deploy.env.example to deploy.env and set SSH_HOST + REMOTE_THEME_DIR."
  exit 1
fi

# shellcheck source=/dev/null
source "$ENV_FILE"

: "${SSH_HOST:?Set SSH_HOST in deploy.env (SSH config Host alias or user@ip)}"
: "${REMOTE_THEME_DIR:?Set REMOTE_THEME_DIR in deploy.env}"

echo "Deploying theme from $ROOT"
echo "  -> ${SSH_HOST}:${REMOTE_THEME_DIR}/"

rsync -avz --delete \
  --exclude '.git' \
  --exclude 'deploy.env' \
  --exclude 'deploy.env.example' \
  --exclude 'scripts' \
  --exclude '.DS_Store' \
  "$ROOT/" "${SSH_HOST}:${REMOTE_THEME_DIR}/"

if [[ "${PURGE_CACHE:-0}" == "1" ]]; then
  echo "Purging caches..."
  ssh "$SSH_HOST" "cd \"$(dirname "$(dirname "$REMOTE_THEME_DIR")")\" 2>/dev/null || true; \
    command -v wp >/dev/null && wp cache flush --path=\"$(dirname "$REMOTE_THEME_DIR")/../..\" 2>/dev/null || \
    echo 'wp-cli not found; purge cache in SpinUpWP dashboard if needed.'"
fi

echo "Done. Check https://asherava.com/ and bump cache if styles look stale."
