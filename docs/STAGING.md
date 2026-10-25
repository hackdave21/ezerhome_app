# Environnement de staging (recette client)

Staging protege par HTTPS (Let's Encrypt) et identifiants Basic Auth.

## Prerequis

- Serveur Ubuntu 22.04/24.04 avec acces SSH par cle.
- DNS : enregistrement `A` de `staging.ezerhome.org` vers l'IP du serveur.
- PHP 8.3 + extensions (`mbstring`, `xml`, `curl`, `zip`, `gd`, `intl`, `bcmath`, `pdo_mysql`).
- MySQL/MariaDB, Nginx, Composer, Node 20+ (build des assets).
- Ports 80 et 443 ouverts (pare-feu).

## 1. Deposer le code

```bash
sudo mkdir -p /var/www/ezer-home
sudo chown -R "$USER":www-data /var/www/ezer-home
git clone <url-du-depot> /var/www/ezer-home
cd /var/www/ezer-home
git checkout develop
chmod +x deploy/scripts/*.sh
```

## 2. Lancer le provisionnement

```bash
sudo DOMAIN=staging.ezerhome.org \
     LE_EMAIL=contact@ezerhome.org \
     BASIC_AUTH_USER=staging \
     BASIC_AUTH_PASSWORD='un-mot-de-passe-fort' \
     ./deploy/scripts/setup-staging.sh
```

Le script :

1. installe Nginx, Certbot et `apache2-utils` ;
2. cree `/etc/nginx/.htpasswd-staging` (Basic Auth) ;
3. configure Nginx en HTTP puis obtient le certificat Let's Encrypt ;
4. active la configuration HTTPS avec protection Basic Auth ;
5. installe les dependances PHP, copie `.env.staging.example` vers `.env`, genere `APP_KEY` ;
6. execute les migrations et optimise les caches.

> A la premiere execution, le script s'arrete apres avoir cree `.env` : renseigne la base de donnees, le SMTP et Turnstile, puis relance-le.

## 3. Variables d'environnement

`.env.staging.example` sert de modele. Points critiques :

```dotenv
APP_ENV=staging
APP_DEBUG=false
APP_URL=https://staging.ezerhome.org
SESSION_SECURE_COOKIE=true
DB_DATABASE=ezer_home_staging
```

Ne jamais commiter `.env` (deja ignore par Git).

## 4. Verifications

```bash
curl -I https://staging.ezerhome.org          # 401 sans identifiants (Basic Auth)
curl -u staging:motdepasse -I https://staging.ezerhome.org   # 200
sudo nginx -t
sudo systemctl status nginx php8.3-fpm
sudo certbot certificates
```

- `Strict-Transport-Security` present dans les en-tetes.
- Redirection HTTP vers HTTPS active.
- Le renouvellement ACME reste accessible sans Basic Auth.

## 5. Renouvellement du certificat

Certbot installe un timer systemd :

```bash
systemctl list-timers | grep certbot
sudo certbot renew --dry-run
```

Le challenge ACME reste accessible sans Basic Auth (`/.well-known/acme-challenge/`).

## 6. Deploiement d'une nouvelle version

```bash
sudo BRANCH=develop /var/www/ezer-home/deploy/scripts/deploy-staging.sh
```

Le script fait : `git pull` → `composer install --no-dev` → `npm ci && npm run build` → `migrate --force` → `optimize` → `queue:restart`.

## 7. Acces et cloisonnement

- Le staging est **prive** : Basic Auth obligatoire, en plus des comptes applicatifs.
- Ne jamais y mettre de donnees reelles de production (utiliser des comptes de demonstration).
- `APP_DEBUG=false` et `SESSION_SECURE_COOKIE=true` obligatoires.
- Restreindre l'acces SSH par cle et limiter les ports avec le pare-feu.

## 8. Retour arriere

```bash
cd /var/www/ezer-home
git log --oneline -5
git checkout <commit-precedent>
sudo -u www-data php artisan optimize
```

Conserver une sauvegarde de la base avant chaque migration sensible.
