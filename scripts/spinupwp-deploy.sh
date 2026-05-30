#!/usr/bin/env bash
# Paste this script into SpinUpWP → Site → Git → Deploy Script
# (or run manually on the server inside .../files after a git pull).
set -euo pipefail

THEME_SLUG="asherava-jaxxon"
DEST="wp-content/themes/${THEME_SLUG}"
mkdir -p "$DEST"

sync_item() {
  local item="$1"
  [[ -e "$item" ]] || return 0
  rsync -a --delete "$item" "${DEST}/"
}

sync_item style.css
sync_item functions.php
sync_item front-page.php
sync_item assets
sync_item inc
sync_item template-parts

# Remove theme files accidentally checked out at web root
for item in style.css functions.php front-page.php assets inc template-parts; do
  if [[ -e "./${item}" && "${item}" != "wp-content" ]]; then
    rm -rf "./${item}"
  fi
done

if command -v wp >/dev/null 2>&1; then
  wp cache flush --allow-root 2>/dev/null || true
fi

echo "Deployed theme to ${DEST}"
