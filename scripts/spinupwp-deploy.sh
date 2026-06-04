#!/usr/bin/env bash
# Paste into SpinUpWP → Site → Git → Deploy Script
set -euo pipefail

# Run from site root (/sites/asherava.com/) or from files/ — both work.
if [[ -d files && ! -e wp-config.php && ! -d wp-content ]]; then
  cd files
fi

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
sync_item woocommerce

for item in style.css functions.php front-page.php assets inc template-parts woocommerce; do
  if [[ -e "./${item}" && "${item}" != "wp-content" ]]; then
    rm -rf "./${item}"
  fi
done

if command -v wp >/dev/null 2>&1; then
  wp cache flush --path="$(pwd)" --allow-root 2>/dev/null || true
fi

echo "Deployed theme to ${DEST}"
