#!/usr/bin/env bash
# Backward-compatible shortcut. Prefer: ./scripts/deploy-theme.sh
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
exec "$ROOT/scripts/deploy-theme.sh"
