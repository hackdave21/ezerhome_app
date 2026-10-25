#!/usr/bin/env bash
#
# Deploiement d'une nouvelle version sur le staging.
# A executer en root ou avec un utilisateur disposant des droits sur APP_DIR.

set -euo pipefail

APP_DIR="${APP_DIR:-/var/www/ezer-home}"
BRANCH="${BRANCH:-develop}"

cd "$APP_DIR"

echo "==> Recuperation du code ($BRANCH)"
git fetch origin "$BRANCH"
git checkout "$BRANCH"
git pull --ff-only origin "$BRANCH"

echo "==> Dependances PHP"
composer install --no-dev --optimize-autoloader

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

echo "Deploiement staging termine."
