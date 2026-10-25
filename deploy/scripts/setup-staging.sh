#!/usr/bin/env bash
#
# Provisionne l'environnement staging Ezer Home :
#   - Nginx + PHP-FPM
#   - protection Basic Auth
#   - certificat Let's Encrypt (HTTPS)
#   - configuration Laravel de base
#
# A executer en root sur le serveur (Ubuntu/Debian), apres avoir pointe le DNS
# du domaine vers l'IP du serveur.
#
# Exemple :
#   sudo DOMAIN=staging.ezerhome.org LE_EMAIL=contact@ezerhome.org \
#        BASIC_AUTH_PASSWORD='mot-de-passe-fort' ./deploy/scripts/setup-staging.sh

set -euo pipefail

DOMAIN="${DOMAIN:-staging.ezerhome.org}"
APP_DIR="${APP_DIR:-/var/www/ezer-home}"
PHP_FPM_SOCK="${PHP_FPM_SOCK:-/run/php/php8.3-fpm.sock}"
BASIC_AUTH_USER="${BASIC_AUTH_USER:-staging}"
BASIC_AUTH_PASSWORD="${BASIC_AUTH_PASSWORD:-}"
LE_EMAIL="${LE_EMAIL:-contact@ezerhome.org}"

REPO_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
NGINX_AVAILABLE="/etc/nginx/sites-available/ezer-staging"
NGINX_ENABLED="/etc/nginx/sites-enabled/ezer-staging"
HTPASSWD_FILE="/etc/nginx/.htpasswd-staging"
CERTBOT_WEBROOT="/var/www/certbot"

log() { printf '\n==> %s\n' "$1"; }

if [ "$(id -u)" -ne 0 ]; then
    echo "Ce script doit etre execute en root (sudo)." >&2
    exit 1
fi

log "Installation des paquets"
apt-get update
apt-get install -y nginx certbot python3-certbot-nginx apache2-utils

log "Preparation des dossiers"
mkdir -p "$CERTBOT_WEBROOT" "$APP_DIR"
chown -R www-data:www-data "$CERTBOT_WEBROOT"

log "Protection Basic Auth"
if [ -n "$BASIC_AUTH_PASSWORD" ]; then
    htpasswd -cb "$HTPASSWD_FILE" "$BASIC_AUTH_USER" "$BASIC_AUTH_PASSWORD"
elif [ -f "$HTPASSWD_FILE" ]; then
    echo "Le fichier $HTPASSWD_FILE existe deja, il est conserve."
else
    htpasswd -c "$HTPASSWD_FILE" "$BASIC_AUTH_USER"
fi
chmod 640 "$HTPASSWD_FILE"
chown root:www-data "$HTPASSWD_FILE"

log "Configuration Nginx HTTP (validation du domaine)"
sed -e "s|staging\.ezerhome\.org|${DOMAIN}|g" \
    -e "s|/var/www/ezer-home|${APP_DIR}|g" \
    "$REPO_DIR/deploy/nginx/staging-http.conf" > "$NGINX_AVAILABLE"
ln -sfn "$NGINX_AVAILABLE" "$NGINX_ENABLED"
rm -f /etc/nginx/sites-enabled/default
nginx -t
systemctl reload nginx

log "Obtention du certificat Let's Encrypt"
certbot certonly --webroot \
    -w "$CERTBOT_WEBROOT" \
    -d "$DOMAIN" \
    --email "$LE_EMAIL" \
    --agree-tos \
    --non-interactive \
    --keep-until-expiring

log "Activation de HTTPS"
sed -e "s|staging\.ezerhome\.org|${DOMAIN}|g" \
    -e "s|/var/www/ezer-home|${APP_DIR}|g" \
    -e "s|/run/php/php8.3-fpm.sock|${PHP_FPM_SOCK}|g" \
    "$REPO_DIR/deploy/nginx/staging-https.conf" > "$NGINX_AVAILABLE"
nginx -t
systemctl reload nginx

log "Verification du socket PHP-FPM"
if [ ! -S "$PHP_FPM_SOCK" ]; then
    echo "ATTENTION : $PHP_FPM_SOCK introuvable. Installe php8.3-fpm et ajuste PHP_FPM_SOCK." >&2
fi

log "Configuration Laravel"
if [ -d "$APP_DIR/.git" ]; then
    echo "Code deja present dans $APP_DIR."
else
    echo "Depose le code dans $APP_DIR (git clone) puis relance le script." >&2
    exit 1
fi

cd "$APP_DIR"
composer install --no-dev --optimize-autoloader

chown -R www-data:www-data storage bootstrap/cache

if [ ! -f .env ]; then
    cp .env.staging.example .env
    php artisan key:generate
    echo "" >&2
    echo "Edite $APP_DIR/.env (base de donnees, SMTP, Turnstile)" >&2
    echo "puis relance ce script pour finaliser." >&2
    exit 0
fi

if [ -f package-lock.json ] && command -v npm >/dev/null 2>&1; then
    npm ci
    npm run build
fi

php artisan migrate --force
php artisan storage:link || true
php artisan optimize

log "Termine"
echo "Staging : https://${DOMAIN} (utilisateur Basic Auth : ${BASIC_AUTH_USER})"
