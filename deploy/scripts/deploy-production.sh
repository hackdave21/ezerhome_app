#!/usr/bin/env bash
#
# Deploiement d'une nouvelle version en production (Hostinger).
# Appele par GitHub Actions via SSH, ou manuellement sur le serveur :
#   APP_DIR=/var/www/ezer-home bash deploy/scripts/deploy-production.sh

set -euo pipefail

APP_DIR="${APP_DIR:-/var/www/ezer-home}"
BRANCH="${BRANCH:-main}"
TARGET_SHA="${TARGET_SHA:-}"

cd "$APP_DIR"

echo "==> Recuperation du code ($BRANCH)"
git fetch origin "$BRANCH"
git checkout "$BRANCH"
git pull --ff-only origin "$BRANCH"

if [ -n "$TARGET_SHA" ]; then
    echo "==> Version demandee : $TARGET_SHA"
    git checkout "$TARGET_SHA"
fi

echo "==> Dependances PHP"
composer install --no-dev --optimize-autoloader --no-interaction

echo "==> Assets frontend"
if [ -f package-lock.json ] && command -v npm >/dev/null 2>&1; then
    npm ci
    npm run build
fi

echo "==> Migrations"
php artisan migrate --force

echo "==> Liens et caches"
php artisan storage:link || true
php artisan optimize

echo "==> Redemarrage des workers"
php artisan queue:restart || true

echo "Deploiement production termine."
