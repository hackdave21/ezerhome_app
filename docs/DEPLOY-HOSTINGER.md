# Deploiement automatique sur Hostinger

Le deploiement de production est declenche **automatiquement par GitHub Actions** apres une CI
verte sur la branche `main` (`.github/workflows/deploy-hostinger.yml`). Le workflow se connecte en
SSH au serveur Hostinger et execute `deploy/scripts/deploy-production.sh`.

## 1. Prerequis serveur

- Un hebergement Hostinger **avec acces SSH** (VPS ou offre mutualisee compatible SSH).
- PHP 8.3, Composer 2, Node.js 20+, MySQL/MariaDB.
- Le depot clone sur le serveur, par exemple dans `/var/www/ezer-home` (ou
  `/home/<utilisateur>/domains/ezerhome.org/app` en mutualise).
- Le fichier `.env` de production renseigne **une seule fois** sur le serveur
  (jamais commite) : `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL`, base de donnees, SMTP,
  Turnstile, `APP_KEY`.
- `storage/` et `bootstrap/cache/` accessibles en ecriture par l'utilisateur PHP.
- Le document root du site doit pointer sur le dossier `public/` de l'application.

Premier clone manuel sur le serveur :

```bash
git clone https://github.com/hackdave21/ezerhome_app.git /var/www/ezer-home
cd /var/www/ezer-home
cp .env.example .env
php artisan key:generate
# renseigner .env puis :
php artisan migrate --force
php artisan storage:link
```

## 2. Cle SSH dediee au deploiement

Sur le poste local (ou dans le terminal Hostinger) :

```bash
ssh-keygen -t ed25519 -C "github-actions-ezerhome" -f ~/.ssh/ezerhome_deploy -N ""
```

Ajouter la cle **publique** (`ezerhome_deploy.pub`) aux cles autorisees du serveur :

```bash
cat ~/.ssh/ezerhome_deploy.pub >> ~/.ssh/authorized_keys
```

## 3. Secrets GitHub a configurer

Dans le depot GitHub : **Settings > Secrets and variables > Actions > New repository secret**.

| Secret | Obligatoire | Exemple / description |
|---|---|---|
| `HOSTINGER_HOST` | oui | IP ou nom d'hote SSH du serveur |
| `HOSTINGER_USER` | oui | Utilisateur SSH de deploiement |
| `HOSTINGER_SSH_KEY` | oui | Contenu **prive** de `ezerhome_deploy` (cle complete) |
| `HOSTINGER_PORT` | non | Port SSH (defaut : `22`) |
| `HOSTINGER_APP_DIR` | oui | Dossier de l'application, ex. `/var/www/ezer-home` |
| `PRODUCTION_URL` | non | URL publique verifiee apres deploiement, ex. `https://ezerhome.org` |

Conseil : creer un **environnement GitHub `production`** (Settings > Environments) avec une
regle d'approbation manuelle si vous souhaitez valider chaque mise en production.

## 4. Fonctionnement

1. Un push sur `main` lance la CI (`ci.yml` : Pint + tests).
2. Si la CI est verte, `deploy-hostinger.yml` se declenche.
3. Le workflow se connecte en SSH et execute :
   - `git fetch` / `git checkout` / `git pull` sur `main` ;
   - `composer install --no-dev --optimize-autoloader` ;
   - `npm ci && npm run build` ;
   - `php artisan migrate --force` ;
   - `php artisan storage:link`, `php artisan optimize`, `php artisan queue:restart`.
4. Une verification HTTP de `PRODUCTION_URL` est faite si le secret est defini.

Le deploiement peut aussi etre lance manuellement : **Actions > Deploiement production
(Hostinger) > Run workflow**.

## 5. Processus continus a maintenir sur Hostinger

- **Cron** (planificateur Laravel) :
  ```
  * * * * * cd /var/www/ezer-home && php artisan schedule:run >> /dev/null 2>&1
  ```
- **Worker de file d'attente** (mails, notifications, medias). Sur un VPS Hostinger, utiliser
  Supervisor ; en mutualise, utiliser le cron Hostinger ou `queue:work --stop-when-empty` si
  disponible. Sans worker, les e-mails restent en attente.
- **HTTPS** : activer le certificat SSL Hostinger et forcer la redirection HTTP vers HTTPS.

## 6. Retour arriere

Le workflow accepte un SHA precis (`TARGET_SHA`). Pour revenir a une version precedente, relancer
`deploy/scripts/deploy-production.sh` avec le SHA souhaite, apres avoir sauvegarde la base :

```bash
cd /var/www/ezer-home
APP_DIR=/var/www/ezer-home TARGET_SHA=<sha-precedent> bash deploy/scripts/deploy-production.sh
```

Conserver une sauvegarde de la base avant chaque migration sensible.
