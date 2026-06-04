#!/usr/bin/env bash
# Quick SSH check before deploy. Usage: bash scripts/verify-ssh.sh
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
ENV_FILE="${DEPLOY_ENV:-$ROOT/deploy.env}"
[[ -f "$ENV_FILE" ]] && source "$ENV_FILE"

HOST="${SSH_HOST:-asherava}"
KEY="${DEPLOY_KEY:-$HOME/.ssh/asherava_deploy}"
REMOTE="${REMOTE_THEME_DIR:-/sites/asherava.com/files/wp-content/themes/asherava-jaxxon}"

echo "Host:     $HOST"
echo "Key:      $KEY"
echo "Theme:    $REMOTE"
echo ""

if [[ ! -f "$KEY" ]]; then
  echo "Missing private key: $KEY"
  exit 1
fi

if ! ssh-add -l 2>/dev/null | grep -q "$(ssh-keygen -lf "$KEY.pub" 2>/dev/null | awk '{print $2}')" 2>/dev/null; then
  echo "Key not in ssh-agent. Run:"
  echo "  ssh-add $KEY"
  echo ""
fi

echo "Testing SSH (you may be asked for passphrase once)..."
if ssh -o ConnectTimeout=12 "$HOST" "test -f '${REMOTE}/style.css' && echo 'Theme OK' || echo 'Theme path missing'"; then
  echo ""
  echo "Ready: bash scripts/deploy.sh"
else
  echo ""
  echo "Fix: SpinUpWP → Account → SSH Keys → add this public key:"
  cat "${KEY}.pub"
  exit 1
fi
