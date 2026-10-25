# Ezer Home — Guide complet de création de la plateforme (Laravel)

> Guide de réalisation **étape par étape**, du premier `composer create-project` jusqu'à la préparation du déploiement en production.
> Basé sur le *Cahier des charges v1.1 — 29 septembre 2026*.
> **Lancement public visé : 15 octobre 2026** (phase 1, lots 0 à 4).
> Le projet est livré en **deux phases** : chaque phase se termine par sa propre recette et sa propre préparation de déploiement.

---

## Table des matières

1. [Présentation du projet](#1-présentation-du-projet)
2. [Découpage en deux phases](#2-découpage-en-deux-phases)
3. [Prérequis et outils](#3-prérequis-et-outils)
4. [Règles de travail (Git, code, sécurité)](#4-règles-de-travail)
5. [**PHASE 1 — Lancement public**](#phase-1--lancement-public-lots-0-à-4)
   - [Étape 1.0 — Initialisation du projet](#étape-10--initialisation-du-projet-lot-0)
   - [Étape 1.1 — Intégration du design et layout](#étape-11--intégration-du-design-et-layout-lot-0-et-1)
   - [Étape 1.2 — Site public (vitrine)](#étape-12--site-public-vitrine-lot-1)
   - [Étape 1.3 — Authentification, comptes, rôles](#étape-13--authentification-comptes-rôles-lot-2)
   - [Étape 1.4 — Chartes signées (consentement)](#étape-14--chartes-signées-lot-2)
   - [Étape 1.5 — Médiathèque de base](#étape-15--médiathèque-de-base-lot-2)
   - [Étape 1.6 — Organigramme et logos partenaires](#étape-16--organigramme-et-logos-partenaires-lot-2)
   - [Étape 1.7 — Demande d'écoute et affectation manuelle](#étape-17--demande-découte-et-affectation-manuelle-lot-2)
   - [Étape 1.8 — Volet Assistance et espace Comité](#étape-18--volet-assistance-et-espace-comité-lot-3)
   - [Étape 1.9 — Volet Dons et paiement manuel](#étape-19--volet-dons-et-paiement-manuel-lot-4)
   - [Étape 1.10 — Back-office Admin (Filament)](#étape-110--back-office-admin-filament)
   - [Étape 1.11 — Sécurité et tests de la phase 1](#étape-111--sécurité-et-tests-de-la-phase-1)
   - [Étape 1.12 — Préparation du déploiement phase 1](#étape-112--préparation-du-déploiement-phase-1)
6. [**PHASE 2 — Après lancement**](#phase-2--après-lancement-lots-5-à-8)
   - [Étape 2.0 — Pré-requis juridiques à lever](#étape-20--pré-requis-juridiques-à-lever-avant-le-lot-5)
   - [Étape 2.1 — Candidature intervenant](#étape-21--candidature-intervenant-lot-5)
   - [Étape 2.2 — Messagerie sécurisée](#étape-22--messagerie-sécurisée-lot-5)
   - [Étape 2.3 — Réunions, notes et suivi Admin](#étape-23--réunions-notes-et-suivi-admin-lot-5)
   - [Étape 2.4 — Catalogue Formation / Coaching](#étape-24--catalogue-formation--coaching-lot-6)
   - [Étape 2.5 — Donateurs institutionnels et reçus PDF](#étape-25--donateurs-institutionnels-et-reçus-pdf-lot-7)
   - [Étape 2.6 — Désactivation automatique à 6 mois](#étape-26--désactivation-automatique-à-6-mois-lot-7b)
   - [Étape 2.7 — Paiements automatisés Mixx / Moov](#étape-27--paiements-automatisés-mixx-by-yas--moov-money-lot-8)
   - [Étape 2.8 — PWA complète et mode hors-ligne](#étape-28--pwa-complète-et-mode-hors-ligne-lot-8)
   - [Étape 2.9 — Attestations PDF](#étape-29--attestations-pdf-optionnel)
   - [Étape 2.10 — Recette finale et préparation du déploiement final](#étape-210--recette-finale-et-préparation-du-déploiement-final)
7. [Annexes](#annexes)
   - [A. Arborescence cible du projet](#annexe-a--arborescence-cible)
   - [B. Ordre des migrations](#annexe-b--ordre-des-migrations)
   - [C. Tâches planifiées (scheduler)](#annexe-c--tâches-planifiées)
   - [D. Fichier `.env` type](#annexe-d--fichier-env-type)
   - [E. Matrice de tests de permissions](#annexe-e--matrice-de-tests-de-permissions)
   - [F. Points à valider avec le client](#annexe-f--points-à-valider-avec-le-client)

---

## 1. Présentation du projet

**Ezer Home** est une association togolaise qui propose cinq services sur un seul site :

| Volet | Nature | Accès |
|---|---|---|
| Écoute et accompagnement | Gratuit | Compte + charte signée |
| Formation | Payant | Compte + paiement |
| Coaching | Payant | Compte + paiement |
| Assistance (aide matérielle) | Gratuit | Formulaire, sans compte |
| Dons | — | Formulaire, sans compte |

**Responsabilités** : le développeur réalise le développement Laravel et l'hébergement ; le designer fournit les maquettes (intégration fidèle).
**Paiement** : comptes marchands Mixx By Yas et Moov Money, fonds versés directement à l'association.

### Stack technique

| Couche | Choix |
|---|---|
| Backend | Laravel 11/12, PHP 8.3, MySQL/MariaDB (ou PostgreSQL), Redis si le serveur le permet |
| Frontend | Blade + Livewire 3 + Alpine.js + Tailwind CSS (léger, pas de SPA) |
| Authentification | Laravel Fortify (ou Breeze) + 2FA TOTP |
| Rôles | `spatie/laravel-permission` + Policies |
| Audit | `spatie/laravel-activitylog` |
| Médias | `spatie/laravel-medialibrary` + Intervention Image |
| Back-office | Filament 3 (Admin + Comité) |
| PWA | manifest + service worker (Workbox ou maison) |
| Notifications | E-mail (SMTP), SMS/WhatsApp (passerelle à choisir) |
| Tests | Pest / PHPUnit |
| Qualité | Laravel Pint (PSR-12), GitHub Actions |

### Principes directeurs (à garder en tête à chaque étape)

1. **Cloisonnement strict** : aucun rôle n'a de vue globale sur les conversations.
2. **Chiffrement au repos** des champs sensibles (cast `encrypted`).
3. **Traçabilité** : tout accès Admin à un dossier sensible est journalisé.
4. **Légèreté** : mobile-first, réseau 2G/3G, peu de JS, images WebP.
5. **Aucune donnée sensible** dans les e-mails, SMS, notifications ou logs.

---

## 2. Découpage en deux phases

| | **PHASE 1 — Lancement public** | **PHASE 2 — Après lancement** |
|---|---|---|
| Lots | 0, 1, 2, 3, 4 | 5, 6, 7, 7b, 8 |
| Objectif | Mettre en ligne un site fonctionnel et sûr : vitrine, comptes, écoute (demande + affectation), assistance, dons | Compléter avec intervenants, messagerie, réunions/notes, formations payantes, désactivation auto, paiements automatisés, PWA complète |
| Paiement | **Confirmation manuelle uniquement** (bureau) | Webhooks Mixx / Moov + réconciliation |
| Messagerie / comptes rendus | **Non ouverts** (en attente de l'avis juridique IPDCP) | Ouverts après levée des points juridiques |
| Sortie de phase | Recette phase 1 + déploiement production v1.0 | Recette finale + déploiement production v2.0 |

> **Si le calendrier est serré** (lots 2 à 4 en ~2 semaines) : décaler le **2FA** et le **back-office Filament avancé** après le lancement. Ne **jamais** décaler : la charte signée, la sécurité de base et le cloisonnement des rôles.

### Vue d'ensemble des étapes

```
PHASE 1                                         PHASE 2
1.0  Initialisation ───────────────┐            2.0  Pré-requis juridiques
1.1  Design + layout               │            2.1  Candidature intervenant
1.2  Site vitrine                  │            2.2  Messagerie
1.3  Auth + rôles + 2FA            │            2.3  Réunions + notes + suivi
1.4  Chartes signées               ├─ Lots 0-4  2.4  Formation / Coaching
1.5  Médiathèque                   │            2.5  Institutionnels + reçus
1.6  Organigramme + partenaires    │            2.6  Désactivation 6 mois
1.7  Demande d'écoute              │            2.7  Paiements Mixx / Moov
1.8  Assistance + Comité           │            2.8  PWA complète
1.9  Dons + paiement manuel        │            2.9  Attestations
1.10 Back-office Filament          │            2.10 Recette + déploiement final
1.11 Sécurité + tests              │
1.12 Déploiement phase 1 ──────────┘
```

---

## 3. Prérequis et outils

### Poste de développement

- PHP 8.3 (extensions : `mbstring`, `xml`, `curl`, `zip`, `gd` ou `imagick`, `intl`, `bcmath`, `pdo_mysql`, `redis` optionnel)
- Composer 2
- Node.js 20+ et npm
- MySQL/MariaDB (ou PostgreSQL) en local
- Git
- Un éditeur avec support PHP (PhpStorm, VS Code + Intelephense)
- Optionnel : Laravel Herd, Laragon ou Docker (Laravel Sail)

### Comptes et démarches à lancer **immédiatement** (délais longs)

| Démarche | Pourquoi | Délai |
|---|---|---|
| Ouverture compte marchand **Mixx By Yas** | Paiement en ligne (phase 2) | Long |
| Ouverture compte marchand **Moov Money** | Paiement en ligne (phase 2) | Long |
| Obtention doc API + sandbox des deux opérateurs | Intégration lot 8 | Long |
| Choix passerelle SMS/WhatsApp | Notifications | Moyen |
| Nom de domaine + hébergement | Déploiement | Court |
| Compte SMTP (e-mails transactionnels) | Notifications | Court |
| Compte Cloudflare Turnstile | Anti-spam formulaires publics | Court |
| Compte Sentry/Flare + UptimeRobot | Monitoring | Court |
| Juriste togolais (IPDCP, charte, mentions) | Bloquant pour le volet écoute avancé | Long |
| Fiscaliste (reçus de don) | Format du reçu | Moyen |

> ⚠️ Les spécifications API Mixx/Moov **n'ont pas été vérifiées** dans le cahier des charges. L'intégration devra s'appuyer sur leur documentation officielle (ou sur un agrégateur si aucune API directe n'est proposée).

---

## 4. Règles de travail

### Git

- Dépôt **privé**. Branches : `main` (production), `develop` (intégration), `feature/<lot>-<sujet>`.
- Commits conventionnels : `feat:`, `fix:`, `chore:`, `test:`, `docs:`.
- Une *pull request* par fonctionnalité, CI verte obligatoire avant fusion.
- Ne **jamais** commiter : `.env`, clés, dumps de base, fichiers uploadés.

### Code

- PSR-12 via **Laravel Pint** (`./vendor/bin/pint`).
- Logique métier dans des classes dédiées (`app/Actions`, `app/Services`) — contrôleurs et composants Livewire minces.
- Un **Policy** par modèle sensible ; aucune vérification de rôle « en dur » dans les vues quand une Policy suffit.
- Identifiants **UUID** exposés dans les URLs pour les dossiers sensibles.
- Textes en français via fichiers de langue (`lang/fr`) pour garder l'i18n prête (Éwé, Kabyè, anglais plus tard).

### Environnements

| Environnement | Usage | Accès |
|---|---|---|
| `local` | Développement | Développeur |
| `staging` | Recette client | Protégé (auth basique ou IP) |
| `production` | Public | Public + HTTPS |

---

# PHASE 1 — Lancement public (lots 0 à 4)

**Objectif de la phase** : un site public complet, avec comptes, charte signée, demandes d'écoute envoyées à l'Admin, formulaire d'assistance traité par le Comité, dons avec confirmation manuelle, médiathèque et organigramme.

---

## Étape 1.0 — Initialisation du projet (Lot 0)

### 1.0.1 Créer le projet

```bash
composer create-project laravel/laravel ezer-home
cd ezer-home
git init
git checkout -b develop
```

### 1.0.2 Configurer l'environnement local

```bash
cp .env.example .env
php artisan key:generate
```

Éditer `.env` : `APP_NAME="Ezer Home"`, `APP_URL`, `APP_LOCALE=fr`, `APP_FALLBACK_LOCALE=fr`, `APP_TIMEZONE=Africa/Lome`, base de données, `QUEUE_CONNECTION=database` (ou `redis`), `SESSION_SECURE_COOKIE=true` en prod.

```bash
php artisan migrate
```

### 1.0.3 Installer les dépendances principales

```bash
# Frontend
composer require livewire/livewire
npm install -D tailwindcss postcss autoprefixer @tailwindcss/forms
npm install alpinejs

# Authentification
composer require laravel/fortify

# Rôles, audit, médias
composer require spatie/laravel-permission
composer require spatie/laravel-activitylog
composer require spatie/laravel-medialibrary
composer require intervention/image

# Back-office
composer require filament/filament:"^3.2" -W

# 2FA (si non couvert par Fortify) / PDF / qualité
composer require pragmarx/google2fa-laravel      # optionnel
composer require barryvdh/laravel-dompdf          # reçus PDF (utilisé plus tard)
composer require --dev pestphp/pest pestphp/pest-plugin-laravel laravel/pint
```

Publier les configurations/migrations :

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations"
php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"
php artisan queue:table
php artisan session:table
php artisan filament:install --panels
```

### 1.0.4 Mettre en place la qualité et la CI

1. Initialiser Pest : `./vendor/bin/pest --init`.
2. Créer `.github/workflows/ci.yml` : checkout → PHP 8.3 → `composer install` → `npm ci && npm run build` → `./vendor/bin/pint --test` → `php artisan test`.
3. Ajouter un hook `pre-commit` (optionnel) lançant Pint.

### 1.0.5 Hébergement et staging

- Provisionner le serveur (Ubuntu LTS recommandé) : Nginx, PHP-FPM 8.3, MySQL/MariaDB, Supervisor (queues), cron.
- Créer l'environnement **staging** protégé et activer **HTTPS** (Let's Encrypt).
- Accès SSH **par clé uniquement**, pare-feu (ports 22/80/443), mises à jour de sécurité automatiques.
- Choisir l'hébergement après avis juridique si possible (Togo ou pays offrant des garanties équivalentes).

### ✅ Critères de sortie de l'étape 1.0

- [ ] `php artisan test` passe (même vide)
- [ ] CI GitHub Actions verte
- [ ] Staging accessible en HTTPS
- [ ] `.env` absent du dépôt

---

## Étape 1.1 — Intégration du design et layout (Lot 0 et 1)

### 1.1.1 Récupérer les livrables du designer

- Maquettes (mobile d'abord), charte graphique, polices, logos **SVG**, icônes.
- Valider avec le designer : 1 seule police WOFF2 subsettée (ou polices système).

### 1.1.2 Configurer Tailwind

```bash
npx tailwindcss init -p
```

- Déclarer les couleurs, typographies et espacements de la charte dans `tailwind.config.js`.
- Limiter le *content* aux dossiers `resources/views`, `app/Livewire`.
- Vite : build avec versionnage des assets (par défaut).

### 1.1.3 Créer les layouts Blade

```
resources/views/
├── components/
│   ├── layouts/
│   │   ├── public.blade.php      # site public
│   │   ├── account.blade.php     # espace personnel
│   │   └── guest.blade.php       # login / inscription
│   ├── header.blade.php          # coordonnées + bouton WhatsApp toujours visibles
│   ├── footer.blade.php
│   ├── badge.blade.php           # « gratuit » / « tarifé »
│   └── ...
```

Exigences du **header** : adresse, téléphone, e-mail, **bouton WhatsApp** (lien `https://wa.me/<numéro>`) visibles en permanence.

### 1.1.4 Règles de performance dès le départ

- Images en **WebP** + `loading="lazy"` ; logos en **SVG**.
- Pas de vidéo ni d'image lourde sur les pages clés.
- Objectif : pages clés **< 200–300 Ko** hors polices.
- Responsive mobile-first, contrastes et libellés accessibles, navigation clavier.

### ✅ Critères de sortie de l'étape 1.1

- [ ] Layout public fidèle aux maquettes sur mobile et desktop
- [ ] Header avec coordonnées + WhatsApp
- [ ] Composants réutilisables (boutons, champs, badges, cartes)

---

## Étape 1.2 — Site public (vitrine) (Lot 1)

> Le lot 1 est « déjà en ligne côté client » : **intégrer en Laravel ou raccorder** au site existant. Ce guide suppose une intégration complète.

### 1.2.1 Routes et pages

| Page | Route | Remarques |
|---|---|---|
| Accueil | `/` | 3 entrées : « être écouté / me former / un accompagnement suivi » |
| À propos | `/a-propos` | Statuts, bureau, charte visibles |
| Écoute | `/ecoute` | Sobre, **sans** mise en avant d'offres payantes |
| Formation | `/formation` | Badge « tarifé » |
| Coaching | `/coaching` | Badge « tarifé » |
| Tarifs | `/tarifs` | |
| Assistance | `/assistance` | Formulaire (étape 1.8) |
| Dons | `/dons` | Formulaire (étape 1.9) |
| À quoi servent les dons | `/a-quoi-servent-les-dons` | Éditable par l'Admin |
| Notre équipe | `/equipe` | Organigramme (étape 1.6) |
| Contact | `/contact` | |
| Mentions légales / Confidentialité / CGU | `/mentions-legales`, `/confidentialite`, `/cgu` | Textes fournis par le client |

### 1.2.2 CMS léger

Créer la table `pages` (slug, title, body, is_published) et `settings` (clé/valeur) pour que l'Admin édite : pages, tarifs, FAQ, page « à quoi servent les dons », coordonnées.

```bash
php artisan make:model Page -m
php artisan make:model Setting -m
```

Mettre les paramètres en **cache** (`Cache::rememberForever`) et l'invalider à l'enregistrement.

### 1.2.3 Contact

- Formulaire de contact (nom, e-mail, message) avec **honeypot + throttle + Turnstile**.
- Envoi via **queue** (e-mail à l'association). Aucune donnée sensible loguée.

### ✅ Critères de sortie de l'étape 1.2

- [ ] Toutes les pages en place et éditables (là où prévu)
- [ ] Badges gratuit/tarifé corrects
- [ ] Section Écoute sobre
- [ ] Score de performance conforme (< 3 s en 3G simulée)

---

## Étape 1.3 — Authentification, comptes, rôles (Lot 2)

### 1.3.1 Table `users` et statuts de compte

Migration `users` (prévoir **dès maintenant** les champs du cycle de vie, utilisés en phase 2) :

```
id (UUID ou id + uuid), name, email (nullable, unique), phone (nullable, unique),
password (nullable si lien magique), email_verified_at, phone_verified_at,
two_factor_secret, two_factor_recovery_codes, two_factor_confirmed_at,
status (pending_verification | active | inactive | suspended),
deactivated_at, deactivation_reason (auto_6_mois | admin | demande_utilisateur),
reactivated_at, last_activity_at, locale
```

Créer une `enum UserStatus` et caster le champ dans le modèle.

### 1.3.2 Fortify : inscription, connexion, vérification, reset

Dans `FortifyServiceProvider` :

- Brancher les vues Blade/Livewire de connexion, inscription, reset, vérification.
- `Fortify::createUsersUsing(CreateNewUser::class)` : formulaire **minimal** (nom, contact e-mail et/ou téléphone, mot de passe **ou** lien magique).
- **Throttle** des tentatives (`RateLimiter::for('login', ...)`).
- Hachage **bcrypt/argon2** (`config/hashing.php`).
- Refuser la connexion si `status` ≠ `active` avec un message clair (cas `inactive`, `suspended`).

### 1.3.3 Vérification e-mail / téléphone

- E-mail : `MustVerifyEmail` natif.
- Téléphone : code OTP envoyé par SMS/WhatsApp **si la passerelle est disponible** ; sinon vérification e-mail seule en phase 1. Créer une interface `OtpSender` pour brancher la passerelle plus tard.

### 1.3.4 Lien magique (optionnel)

- Route signée à durée courte (`URL::temporarySignedRoute`), usage unique, envoyée par e-mail.
- Utile aussi pour la réactivation en phase 2.

### 1.3.5 Sécurité des sessions

- Cookies `Secure`, `HttpOnly`, `SameSite=Lax` (`config/session.php`).
- Déconnexion sur tous les appareils : `Auth::logoutOtherDevices()` + middleware `auth.session`.

### 1.3.6 Rôles et permissions (Spatie)

Créer un seeder `RolesAndPermissionsSeeder` :

```php
$roles = ['consultant', 'therapeute', 'formateur_coach', 'comite', 'admin', 'super_admin'];
```

Définir les **permissions** à partir de la matrice du cahier des charges (§3.2), par exemple :

```
profile.manage, charter.sign, listening.request, listening.view-own,
assistance.view, assistance.update-status, media.manage, media.manage-assistance,
org.manage, payments.confirm-manual, donations.view, users.manage,
audit.view, settings.manage, applications.review, assignments.manage ...
```

Règles :

- **Consultant** attribué **par défaut** à l'inscription (listener sur `Registered`).
- Les rôles se cumulent ; chaque rôle a son **espace de travail** propre.
- **Super-admin technique** : compte de maintenance **désactivé en production** hors intervention et **sans accès au contenu des messages** (aucune permission sur les ressources sensibles).

### 1.3.7 Policies

```bash
php artisan make:policy ListeningRequestPolicy --model=ListeningRequest
php artisan make:policy AssistanceRequestPolicy --model=AssistanceRequest
php artisan make:policy MediaPolicy --model=Media
# etc.
```

Chaque Policy applique la matrice §3.2. Exemple (assistance) :

```php
public function viewAny(User $user): bool
{
    return $user->hasAnyRole(['comite', 'admin']);
}
```

### 1.3.8 2FA TOTP

- Activer la fonctionnalité `Features::twoFactorAuthentication()` de Fortify.
- **Obligatoire** pour **Thérapeute, Formateur/Coach, Comité et Admin** (cahier des charges §4.2) : middleware `EnsureTwoFactorEnabled` qui redirige vers la configuration du 2FA tant qu'il n'est pas confirmé.
- Codes de récupération affichés une seule fois.

> Si le planning l'impose, ce point peut passer après le lancement — mais alors **limiter strictement** le nombre de comptes Admin/Comité au démarrage.

### 1.3.9 Espace personnel à onglets

Layout `account` avec onglets : **Écoute**, **Formations**, **Coaching**, **Profil et confidentialité**. En phase 1, seuls *Écoute* et *Profil* sont fonctionnels (les autres affichent « bientôt disponible » ou sont masqués).

### 1.3.10 Droit d'accès / suppression

Dans *Profil et confidentialité* :

- **Export de ses données** (JSON/PDF) via un job en queue, lien de téléchargement envoyé sans contenu sensible.
- **Demande de suppression de compte** : crée une demande traitée par l'Admin (la suppression réelle respecte les durées de conservation, voir §2.6).

### ✅ Critères de sortie de l'étape 1.3

- [ ] Inscription / connexion / reset / vérification fonctionnels
- [ ] Rôle `consultant` attribué automatiquement
- [ ] Policies en place, testées par rôle
- [ ] 2FA opérationnel pour Admin/Comité (ou reporté et documenté)
- [ ] Export de données disponible

---

## Étape 1.4 — Chartes signées (Lot 2)

> **Condition bloquante** : sans charte d'écoute signée, **aucune demande ni message** n'est possible.

### 1.4.1 Tables

```
charters:           id, type (listening|provider), version, body, published_at
charter_signatures: user_id, charter_id, signed_at, ip, user_agent
```

### 1.4.2 Versionnage

- L'Admin publie une **nouvelle version** (jamais d'édition d'une version publiée).
- Si la version change, une **nouvelle signature** est exigée au prochain accès.

### 1.4.3 Parcours de signature

1. Le consultant ouvre l'onglet *Écoute*.
2. Le texte complet de la charte est affiché (version, date).
3. Case à cocher « J'ai lu et j'accepte » + bouton de validation.
4. Enregistrement : **identifiant utilisateur, version, date/heure, IP, user-agent** (preuve de consentement).

### 1.4.4 Verrou technique

Créer un middleware `EnsureListeningCharterSigned` appliqué à toutes les routes de demande d'écoute et (phase 2) de messagerie. Ajouter aussi la vérification **dans la Policy** (défense en profondeur).

### 1.4.5 Contenu juridique

- La charte d'écoute doit préciser que **les notes de séance sont partagées avec le bureau** à des fins de suivi et de supervision (phase 2) — formulation **à valider par le juriste**.
- Les textes (charte d'écoute, charte intervenant, mentions légales, confidentialité) sont **fournis par le client**. Prévoir un texte provisoire clairement marqué, à remplacer avant mise en ligne.

### ✅ Critères de sortie de l'étape 1.4

- [ ] Signature enregistrée avec toutes les preuves
- [ ] Impossible de créer une demande sans charte signée (test automatisé)
- [ ] Nouvelle version → nouvelle signature exigée

---

## Étape 1.5 — Médiathèque de base (Lot 2)

### 1.5.1 Modèle de données

```
media:        id, disk, path, title, alt, category (assistance|logo_partenaire|organigramme|general),
              caption, is_public, consent_confirmed, uploaded_by, timestamps
media_usages: media_id, model_type, model_id
```

Utiliser `spatie/laravel-medialibrary` comme socle, avec les champs métier ci-dessus (ou une table `media` applicative liée à Medialibrary).

### 1.5.2 Disques de stockage

```php
// config/filesystems.php
'public_media'  => ['driver' => 'local', 'root' => storage_path('app/public/media'), 'url' => env('APP_URL').'/storage/media', 'visibility' => 'public'],
'private_media' => ['driver' => 'local', 'root' => storage_path('app/private/media'), 'visibility' => 'private'],
```

- Fichiers `is_public = true` → disque public.
- Fichiers privés → **servis uniquement via une route authentifiée** + Policy (`MediaController@show`), jamais par URL directe.

### 1.5.3 Upload (Livewire)

- Composant Livewire `MediaUploader` : glisser-déposer + sélection, envoi **multiple**.
- Formats : **JPG, PNG, WebP, SVG** (et **PDF** pour documents). Taille max **paramétrable** (ex. 2 Mo, via `settings`).
- **Contrôle du type MIME réel** (pas seulement l'extension) ; pour les SVG, **assainir** le contenu (supprimer scripts/handlers).

### 1.5.4 Traitement automatique

Job en queue (ou conversion Medialibrary) :

1. Redimensionnement (largeur max, ex. 1600 px).
2. Conversion **WebP**.
3. Génération de **miniatures**.
4. **Suppression des métadonnées EXIF** (géolocalisation) — indispensable pour la vie privée.

### 1.5.5 Gestion

- Recherche, filtres par catégorie, édition des métadonnées (titre, **texte alternatif**, légende, catégorie, publiable/privé).
- **Remplacement** d'un fichier sans casser les liens (conserver l'identifiant, remplacer le fichier).
- **Suppression bloquée** si le fichier est utilisé (consulter `media_usages`) avec **liste des pages concernées**.
- Archivage possible.

### 1.5.6 Droits

- **Admin** : tout.
- **Comité** : ajouter/modifier **uniquement** la catégorie *assistance*.
- Autres rôles : aucun accès.

### 1.5.7 Photos d'assistance

- Case **« accord de publication »** obligatoire (`consent_confirmed`) pour pouvoir passer une photo en *publiable*.
- Sur le site public : seules les photos *publiables*, **jamais avec un nom ou un contact**.

### ✅ Critères de sortie de l'étape 1.5

- [ ] Upload multiple → WebP + miniatures + EXIF supprimé
- [ ] Fichier privé inaccessible par URL directe
- [ ] Suppression d'un fichier utilisé refusée avec avertissement
- [ ] Comité limité à la catégorie assistance

---

## Étape 1.6 — Organigramme et logos partenaires (Lot 2)

### 1.6.1 Organigramme (`org_members`)

```
org_members: first_name, last_name, position, committee (bureau_executif|comite_assistance|comite_validation|autre),
             media_id, parent_id (nullable), bio, public_contact, sort_order, is_visible, user_id (nullable)
```

- Un membre **n'est pas forcément un utilisateur** ; le lien `user_id` est facultatif.
- Page publique `/equipe` : **grille par comité** (V1). Arbre SVG léger → second temps.
- Admin : **ajouter, modifier, réordonner (drag & drop), masquer, supprimer**.
- Activer l'**audit** (`LogsActivity`) sur `OrgMember`.
- Enregistrer l'usage de la photo dans `media_usages`.

### 1.6.2 Partenaires (`partners`)

```
partners: name, media_id (logo), url, approved_at, is_active, sort_order
```

- Logo choisi depuis la médiathèque (catégorie *logo_partenaire*).
- Publication **uniquement avec accord écrit** : champ `approved_at` obligatoire pour `is_active = true`.
- Ordre d'affichage, lien vers le site du partenaire, activation/désactivation.
- Bloc « Nos partenaires » sur la page d'accueil ou « à propos ».

### ✅ Critères de sortie de l'étape 1.6

- [ ] L'Admin gère membres et partenaires sans toucher au code
- [ ] Un membre masqué n'apparaît pas sur le site
- [ ] Le logo utilisé est protégé contre la suppression

---

## Étape 1.7 — Demande d'écoute et affectation manuelle (Lot 2)

> **Phase 1 : pas de messagerie, pas de comptes rendus.** On livre uniquement la demande, son suivi et l'affectation.

### 1.7.1 Table `listening_requests`

```
listening_requests: uuid, consultant_id, description (encrypted), urgency (nullable),
                    preferred_channel, status, assigned_therapist_id (nullable),
                    assigned_by (nullable), closed_at (nullable), timestamps
```

Statuts (enum) : `envoyee` → `en_cours_affectation` → `affectee` → `en_cours` → `cloturee`.

Dans le modèle :

```php
protected $casts = [
    'description' => 'encrypted',
    'status'      => ListeningRequestStatus::class,
];
```

### 1.7.2 Côté Consultant

- Formulaire : **texte libre** (pas de questionnaire clinique), niveau d'urgence optionnel, canal de contact préféré.
- Accessible **seulement si** charte signée (étape 1.4).
- Suivi du statut dans l'onglet *Écoute* ; URL basée sur l'**UUID**.
- Notification e-mail à l'Admin « Demande d'écoute reçue » (**sans le contenu**).

### 1.7.3 Côté Admin

- Liste des demandes (statut, urgence, date) ; ouverture d'une fiche **journalisée** (activity log : qui, quand, quelle demande).
- **Affectation manuelle** à un thérapeute **validé** (filtrage par disponibilité/charge ; **aucun algorithme de matching** en V1).
- Réaffectation, clôture.
- **Statistiques anonymisées** : nombre de demandes, délai moyen d'affectation.

> En phase 1, aucun thérapeute n'est encore validé via candidature (étape 2.1). L'Admin peut **attribuer manuellement** le rôle `therapeute` à des comptes connus (avec 2FA obligatoire) pour permettre les premières affectations.

#### 1.7.3b Côté Thérapeute (dashboard et actions)

Dès qu'un compte a le rôle `therapeute` (attribué manuellement en phase 1, ou par candidature en phase 2), il dispose d'un **espace de travail** (cahier des charges §4.4) :

- **Tableau de bord** : liste de ses personnes accompagnées, demandes en attente d'acceptation/déclinaison, fil de messages par personne, réunions à venir.
- **Accepter / décliner** une demande d'écoute qui lui a été affectée (la déclinaison est motivée).
- **Clôture d'un accompagnement** ou **demande de réaffectation motivée** — actions tracées dans l'audit.
- Programmation de réunions et notes de réunion : §4.5 (étape 2.3, lot 5).

> En phase 1, cet espace est opérationnel dès la première attribution manuelle du rôle `therapeute` (2FA obligatoire). La messagerie interne (fil de messages) arrive en lot 5.

### 1.7.4 Notifications

- Affectation → thérapeute et consultant (e-mail, SMS si disponible) **sans contenu sensible**.
- Toutes les notifications passent par la **queue** et utilisent des **modèles modifiables** par l'Admin.

### ✅ Critères de sortie de l'étape 1.7

- [ ] Création de demande impossible sans charte
- [ ] Description chiffrée en base (vérifier en SQL brut)
- [ ] Chaque ouverture de fiche par l'Admin est journalisée
- [ ] Un consultant ne voit que ses propres demandes

---

## Étape 1.8 — Volet Assistance et espace Comité (Lot 3)

### 1.8.1 Tables

```
assistance_requests: uuid, tracking_no, full_name, phone, email (nullable), category,
                     description (encrypted), consent_at, status, notes (internal), timestamps
assistance_events:   request_id, status, note, actor_id, created_at
assistance_disbursements (ou champs dédiés): request_id, amount, date, responsible_id
```

Catégories : logement, alimentation, habillement, frais de scolarité, fournitures, autre.
Statuts : `recue` → `en_examen` → `acceptee` → `close` (+ `refusee`).

### 1.8.2 Formulaire public (sans compte)

Champs : nom et prénoms, contact (**téléphone/WhatsApp obligatoire**, e-mail optionnel), type d'aide, description libre, **case de consentement** (enregistre `consent_at`).

**Anti-spam** (obligatoire) :

1. **Honeypot** (champ caché).
2. **Throttle** (`throttle:5,1` par IP).
3. **Cloudflare Turnstile** (captcha léger).

### 1.8.3 Numéro de suivi

Générer `ASS-AAAA-NNNNN` (ex. `ASS-2026-00042`) via un service avec compteur annuel **dans une transaction** (éviter les doublons). Affiché à l'écran et envoyé par SMS/WhatsApp/e-mail.

### 1.8.4 Espace Comité (Filament)

Créer une ressource Filament `AssistanceRequestResource` dans un panel *Comité* (ou dans le panel Admin avec accès conditionné) :

- Liste **filtrable** (statut, catégorie, période) + recherche.
- Fiche de la demande, **notes internes**, **historique** (`assistance_events`).
- Pièces justificatives ajoutées par le comité (via médiathèque).
- Champ **« décaissement effectué »** : montant, date, responsable (**sans logique bancaire**).
- **Détection de doublons** : alerte si le même téléphone a déjà une demande.
- Changement de statut → **notification au demandeur** (SMS/WhatsApp/e-mail) avec **message-type modifiable**.

### 1.8.5 Cloisonnement

- Visible **uniquement** du Comité et de l'Admin — **jamais** des thérapeutes ni formateurs.
- Chaque accès à une fiche est journalisé.
- **Page de suivi publique** (facultatif) : saisie du numéro de suivi + téléphone → statut simple, sans détail sensible.

### ✅ Critères de sortie de l'étape 1.8

- [ ] Formulaire protégé (honeypot + throttle + Turnstile)
- [ ] Numéro de suivi unique généré et notifié
- [ ] Un thérapeute ou consultant reçoit un **403** sur l'espace assistance
- [ ] Notification de changement de statut reçue par le demandeur

---

## Étape 1.9 — Volet Dons et paiement manuel (Lot 4)

### 1.9.1 Tables

```
donations: uuid, amount, currency (XOF), is_anonymous, donor_name (nullable), donor_contact (nullable),
           recurring (bool), payment_id (nullable), receipt_no (nullable), timestamps
orders:    user_id (nullable), amount, status, ...
payments:  order_id/donation_id, amount, status, gateway (manual|mixx|moov), external_ref,
           paid_at, confirmed_by, timestamps
```

Statuts de paiement : `initie` → `en_attente` → `paye` | `echoue` | `expire` | `rembourse`.

### 1.9.2 Formulaire (≤ 6 champs)

1. Montant (**montants suggérés avec impact concret** + champ libre).
2. Moyen de paiement (**Mixx By Yas** et **Moov Money** en premier ; carte plus tard).
3. Nom (facultatif).
4. Contact (facultatif).
5. Case **« don anonyme »**.
6. Ponctuel / régulier (présentés **à égalité**).

- Mention de **sécurité et de confidentialité** près du bouton de validation.
- **Don régulier = rappel mensuel** par notification en V1 (pas de prélèvement automatique).

### 1.9.3 Architecture de paiement dès la phase 1

Même si seul le manuel est actif, **poser l'architecture finale** pour ne pas refondre en phase 2 :

```php
interface PaymentGateway
{
    public function initiate(Payment $payment): PaymentInitiation;
    public function verify(Payment $payment): PaymentStatus;
    public function handleWebhook(Request $request): void;
}

class ManualGateway implements PaymentGateway { /* actif en phase 1 */ }
// MixxGateway et MoovGateway : créées en phase 2 (étape 2.7)
```

Lier la passerelle à un `PaymentManager` (resolver par `gateway`).

### 1.9.4 Flux manuel (V1 avant lancement)

1. Le donateur valide le formulaire → don créé + paiement `en_attente`, **instructions de versement** (numéro marchand de l'association, référence à indiquer).
2. Le bureau constate le versement sur le compte marchand.
3. L'**Admin confirme manuellement** (référence de transaction obligatoire) → statut `paye`, `confirmed_by`, `paid_at` ; action **journalisée**.
4. **Règle d'or** : un paiement ne passe à `paye` que par **confirmation Admin tracée** (ou, plus tard, **webhook vérifié**).

### 1.9.5 Don anonyme

- Aucune identité publiée.
- En interne : **trace comptable minimale** (montant, date, référence de transaction).
- Exports : aucun nom pour les dons anonymes.

### 1.9.6 Aucune donnée de paiement sensible

Ne stocker que : référence de transaction, montant, numéro **masqué** (ex. `90 ** ** 12`) si nécessaire.

### 1.9.7 Page « À quoi servent les dons »

Page éditable via le CMS (étape 1.2.2).

### 1.9.8 Admin — dons et paiements

- Liste des dons/transactions, filtres, **confirmation manuelle** (avec preuve/référence), remboursements notés.
- **Export CSV/Excel comptable** (`maatwebsite/excel` ou export CSV natif).
- Les reçus PDF et le parcours institutionnel sont livrés en phase 2 (étape 2.5).

### 1.9.9 Passerelle don → demande d'assistance (hors V1)

> Cahier des charges §4.8 : « *Passerelle don → demande d'assistance précise : hors V1* ».

- Un donateur peut, au moment du don ou par la suite, **orienter son don vers une catégorie d'assistance précise** (ex. logement, alimentation, scolarité).
- **Hors V1** : fonctionnalité réservée à la phase 2 (ou au besoin), à planifier avec le client. Elle n'est pas bloquante pour le lancement.

### ✅ Critères de sortie de l'étape 1.9

- [ ] Un don peut être créé, puis confirmé par l'Admin avec référence
- [ ] Un don anonyme n'expose aucun nom (public, exports)
- [ ] Impossible de passer un paiement à `paye` sans action tracée (test)
- [ ] Interface `PaymentGateway` posée avec `ManualGateway`

---

## Étape 1.10 — Back-office Admin (Filament)

Panel `/admin` (protégé : rôles + 2FA + éventuellement restriction IP).

### 1.10.1 Panels et accès

- Panel **Admin** : accès réservé au rôle `admin`.
- Panel (ou section) **Comité** : accès `comite` limité à l'assistance et à la médiathèque *assistance*.
- `canAccessPanel()` du modèle `User` vérifie rôle + statut `active` + 2FA confirmé.

### 1.10.2 Ressources Filament de la phase 1

| Ressource | Contenu |
|---|---|
| Tableau de bord | Demandes d'écoute en attente, assistance en cours, paiements à confirmer, dons du mois |
| Utilisateurs | Recherche, rôles, suspension/réactivation (avec motif) |
| Demandes d'écoute | Liste, affectation manuelle, clôture, stats anonymisées |
| Assistance | Voir étape 1.8 |
| Dons & paiements | Voir étape 1.9 |
| Médiathèque | Voir étape 1.5 |
| Organigramme / Partenaires | Voir étape 1.6 |
| CMS | Pages, tarifs, charte (versionnée), FAQ, page dons, **textes de notifications** |
| Paramètres | Durées de conservation, coordonnées, montants suggérés, modèles de messages, taille max upload |
| Journal d'audit | Consultable et filtrable (qui, quoi, quand, IP) — **lecture seule** |

### 1.10.3 Audit

- `spatie/laravel-activitylog` sur tous les modèles sensibles (`LogsActivity`).
- Journaliser explicitement les **lectures** de dossiers sensibles (`activity()->performedOn($model)->log('viewed')`).
- Le journal est **non modifiable** : aucune action d'édition/suppression exposée, et idéalement un utilisateur SQL applicatif sans droit `UPDATE/DELETE` sur `activity_log` si l'hébergement le permet.
- **Ne jamais** inclure le contenu des champs chiffrés dans les propriétés loguées (`logOnly([...])` explicite).

### ✅ Critères de sortie de l'étape 1.10

- [ ] Admin et Comité n'accèdent qu'à leurs ressources
- [ ] Journal d'audit alimenté et consultable
- [ ] Aucun contenu sensible dans `activity_log`

---

## Étape 1.11 — Sécurité et tests de la phase 1

### 1.11.1 Checklist sécurité

- [ ] **HTTPS** forcé + **HSTS**
- [ ] Cookies `Secure`/`HttpOnly`/`SameSite`
- [ ] **CSRF** actif partout (formulaires publics inclus)
- [ ] En-têtes de sécurité : **CSP**, `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy` (middleware `SecurityHeaders`)
- [ ] Validation stricte des uploads (MIME réel, taille, assainissement SVG)
- [ ] **Throttle** sur login, formulaires publics, OTP
- [ ] Champs sensibles chiffrés (`encrypted`)
- [ ] **UUID** dans les URLs sensibles
- [ ] Pas de données sensibles dans logs / mails / SMS (revue manuelle de chaque notification)
- [ ] `APP_DEBUG=false` en production
- [ ] `APP_KEY` **sauvegardée séparément** (sans elle, les données chiffrées sont perdues)

### 1.11.2 Tests automatisés (priorité aux permissions et aux paiements)

```bash
php artisan make:test Permissions/AssistanceAccessTest --pest
php artisan make:test Permissions/ListeningRequestAccessTest --pest
php artisan make:test Charter/CharterGateTest --pest
php artisan make:test Payments/ManualPaymentTest --pest
php artisan make:test Donations/AnonymousDonationTest --pest
php artisan make:test Media/MediaDeletionTest --pest
```

Tests minimum (voir [Annexe E](#annexe-e--matrice-de-tests-de-permissions)) :

- Chaque rôle × chaque ressource sensible → autorisé/refusé attendu.
- Impossible de créer une demande d'écoute sans charte signée.
- Le comité ne voit que l'assistance ; aucun autre rôle ne la voit.
- Un paiement ne passe à `paye` que via confirmation Admin tracée.
- Un don anonyme n'expose aucun nom.
- Un média utilisé ne peut pas être supprimé.
- Aucune donnée sensible dans les notifications (assertions sur le contenu des mails).

### 1.11.3 Performance

- `php artisan optimize` en production.
- Vérifier le poids des pages clés (< 200–300 Ko hors polices) et le temps de chargement (< 3 s en 3G simulée, Lighthouse/DevTools).

### ✅ Critères de sortie de l'étape 1.11

- [ ] Suite de tests verte en CI
- [ ] Checklist sécurité entièrement cochée
- [ ] Pages clés conformes aux objectifs de poids

---

## Étape 1.12 — Préparation du déploiement phase 1

### 1.12.1 Serveur de production

- Nginx + PHP-FPM 8.3 (OPcache activé), MySQL/MariaDB, **Supervisor** pour `queue:work`, **cron** pour le scheduler.
- Compression **Brotli/Gzip**, cache HTTP des assets versionnés.
- Pare-feu, SSH par clé, mises à jour de sécurité, **fail2ban** recommandé.

### 1.12.2 Variables d'environnement de production

Voir [Annexe D](#annexe-d--fichier-env-type). Points critiques : `APP_ENV=production`, `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true`, SMTP réel, clés Turnstile, `LOG_LEVEL=warning`.

### 1.12.3 Processus de déploiement (script ou outil type Deployer / GitHub Actions)

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan storage:link
php artisan optimize          # config + route + view + event cache
php artisan queue:restart
```

Cron (une seule ligne) :

```
* * * * * cd /var/www/ezer-home && php artisan schedule:run >> /dev/null 2>&1
```

Supervisor (extrait) :

```
[program:ezer-queue]
command=php /var/www/ezer-home/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
numprocs=1
user=www-data
```

### 1.12.4 Seeders de production

- Exécuter `RolesAndPermissionsSeeder` (idempotent).
- Créer les **comptes Admin initiaux** (mots de passe forts, 2FA activé dès la 1re connexion).
- **Désactiver le compte super-admin technique** après la mise en place.
- **Aucun** compte de démonstration en production.

### 1.12.5 Sauvegardes

- Sauvegarde **chiffrée quotidienne** de la base et du dossier `storage/app` (ex. `spatie/laravel-backup`).
- **Copie hors serveur**.
- **APP_KEY sauvegardée séparément**.
- **Test de restauration** réussi sur un environnement de test (critère de recette).

### 1.12.6 Monitoring

- **UptimeRobot** (disponibilité visée 99 %).
- **Sentry** ou **Flare** pour les erreurs (configurer pour **ne pas envoyer de données sensibles** : scrubbing des champs).
- Page d'erreur 500/404/503 personnalisées.

### 1.12.7 Recette phase 1 (staging)

Recette avec comptes de démonstration, sur staging :

- [ ] Chaque rôle n'accède qu'à ses propres données
- [ ] Impossible de faire une demande d'écoute sans charte signée
- [ ] Le comité ne voit que l'assistance ; aucun autre rôle ne la voit
- [ ] Un paiement ne passe à `paye` que par confirmation Admin tracée
- [ ] Un don anonyme n'expose aucun nom (public + exports)
- [ ] Aucune donnée sensible dans logs, mails, SMS
- [ ] Pages clés < 3 s en 3G simulée
- [ ] L'Admin peut ajouter/modifier/masquer/supprimer photos, logos partenaires et membres ; un fichier utilisé ne peut pas être supprimé sans avertissement
- [ ] Sauvegarde restaurée avec succès sur un environnement de test

### 1.12.8 Checklist de mise en production v1.0

- [ ] Textes légaux **réels** (charte d'écoute, mentions légales, confidentialité, CGU) en place
- [ ] Avis juridique obtenu **avant d'ouvrir le volet écoute** au public (sinon : limiter l'écoute à la demande + affectation, sans messagerie ni comptes rendus)
- [ ] DNS + HTTPS + HSTS actifs
- [ ] Monitoring et sauvegardes actifs
- [ ] Comptes Admin/Comité avec 2FA
- [ ] Super-admin technique désactivé
- [ ] Tag Git `v1.0.0` sur `main`
- [ ] Plan de retour arrière (rollback) : sauvegarde pré-déploiement + release précédente conservée

```bash
git checkout main && git merge --no-ff develop
git tag -a v1.0.0 -m "Ezer Home — Phase 1 (lancement public)"
git push origin main --tags
```

### ✅ FIN DE LA PHASE 1 — Livrables

Site public complet · comptes/rôles/2FA · chartes signées · médiathèque · organigramme · partenaires · demande d'écoute + affectation manuelle · assistance + Comité · dons avec confirmation manuelle · back-office Filament · audit · sauvegardes · monitoring.

---

# PHASE 2 — Après lancement (lots 5 à 8)

**Objectif de la phase** : ouvrir les intervenants, la messagerie, les réunions et notes, les offres payantes, les reçus, la désactivation automatique, les paiements automatisés et la PWA complète.

Travailler sur de nouvelles branches `feature/lot5-…`, `feature/lot6-…` depuis `develop`, avec déploiement **staging** avant chaque mise en production.

---

## Étape 2.0 — Pré-requis juridiques à lever avant le lot 5

> Le cahier des charges est explicite : **ne pas ouvrir la messagerie ni les comptes rendus** tant que le point IPDCP n'est pas tranché.

À obtenir avant de coder la messagerie et les notes :

- [ ] Avis du juriste togolais (loi n° 2019-014 du 29 octobre 2019, autorité **IPDCP**) : autorisation préalable pour données de santé/sensibles ?
- [ ] Formulation validée dans la **charte d'écoute** du partage des notes avec le bureau
- [ ] Personnes autorisées à lire les notes (Admin seul ? superviseur ?) et finalité déclarée
- [ ] **Durées de conservation** : messages, comptes rendus, demandes d'assistance
- [ ] Politique d'**anonymisation / purge** après désactivation (12 à 24 mois ?)
- [ ] Hébergement : confirmation que le serveur offre des garanties adéquates
- [ ] Charte de l'**intervenant** fournie

Ces décisions alimentent les `settings` (durées) et la charte **versionnée** (nouvelle version → nouvelle signature).

---

## Étape 2.1 — Candidature intervenant (Lot 5)

### 2.1.1 Table `provider_applications`

```
provider_applications: user_id, type (therapist|trainer|coach), bio (parcours), domains, availability,
                       motivation, documents (via médiathèque privée, PDF limité en taille),
                       status (pending|info_requested|approved|rejected|suspended),
                       reviewed_by, reviewed_at, reason
```

### 2.1.2 Parcours candidat

1. Depuis son espace : bouton **« Devenir thérapeute / coach »**.
2. Formulaire : parcours, domaines, disponibilités, motivation, **pièces PDF** (taille limitée, MIME contrôlé, disque privé).
3. Acceptation de la **charte de l'intervenant** (signature horodatée, comme l'étape 1.4).
4. Statut **en attente** + notification à l'Admin.

### 2.1.3 Parcours Admin

- Liste des candidatures, ouverture **journalisée**.
- **Valider** ou **refuser** (**motif obligatoire**, envoyé au candidat) ; possibilité de **demander un complément**.
- À la validation (Action `ApproveProviderApplication`) :
  1. Attribution du rôle (`therapeute` ou `formateur_coach`).
  2. **2FA forcé** à la prochaine connexion.
  3. Création de l'**espace de travail**.
  4. Notification au candidat.
- **Suspension / retrait du rôle** à tout moment → les accompagnements en cours sont signalés pour **réaffectation**.

> Décision client à trancher : Formateur et Coach = **un seul rôle « Intervenant »** avec sous-types, ou **deux rôles** distincts ? Le modèle ci-dessus supporte les deux.

### ✅ Critères de sortie de l'étape 2.1

- [ ] Un thérapeute **non validé ne voit aucun dossier** (test)
- [ ] Refus sans motif impossible
- [ ] Validation → rôle + 2FA forcé
- [ ] Retrait de rôle → accompagnements signalés pour réaffectation

---

## Étape 2.2 — Messagerie sécurisée (Lot 5)

### 2.2.1 Tables

```
conversations: uuid, consultant_id, provider_id, listening_request_id|enrollment_id, status
messages:      conversation_id, sender_id, body (encrypted), read_at, reported_at (nullable), timestamps
```

### 2.2.2 Règles

- Conversations **1-1** consultant ↔ intervenant **affecté** uniquement.
- **Texte uniquement** en V1 : pièces jointes **désactivées**.
- Horodatage, statut **lu / non lu**.
- **Charte signée obligatoire** (middleware + Policy).
- **Aucune vue globale** : même l'Admin n'a pas de lecteur de conversations (seulement le **signalement d'abus**).
- **Bouton « Signaler un abus »** → envoie un signalement à l'Admin (message concerné accessible **uniquement via ce signalement**, accès journalisé).

### 2.2.3 Temps réel léger

- **Polling Livewire** (`wire:poll.20s`) — pas de WebSocket en V1.
- Poll uniquement si l'onglet est visible (`wire:poll.visible`) pour économiser la bande passante.

### 2.2.4 Notifications

- E-mail « Nouveau message » **sans contenu** dans le corps (lien vers l'espace connecté uniquement).

### ✅ Critères de sortie de l'étape 2.2

- [ ] Message chiffré en base
- [ ] Impossible d'écrire sans charte signée / sans affectation
- [ ] Aucun contenu dans l'e-mail de notification
- [ ] Un thérapeute ne voit jamais les conversations d'un collègue

---

## Étape 2.3 — Réunions, notes et suivi Admin (Lot 5)

### 2.3.1 Tables

```
meetings:             uuid, provider_id, consultant_id, listening_request_id|enrollment_id, title, type (ecoute|coaching),
                      starts_at, duration_minutes, meeting_url, message, status, cancelled_reason, timestamps
meeting_notes:        meeting_id, author_id, content (encrypted), status (brouillon|cloturee), closed_at
meeting_note_addenda: meeting_note_id, author_id, content (encrypted), created_at
meeting_note_reviews: meeting_note_id, admin_id, comment (encrypted), created_at
```

Statuts de réunion : `programmee` → (`reportee`) → `terminee` | `annulee` | `absent`.

### 2.3.2 Programmation (côté intervenant)

- Depuis la fiche d'un consultant **qui lui est affecté** uniquement.
- Champs : titre, date, heure de début, durée, **lien de réunion** (Meet, Zoom, Jitsi, WhatsApp… le site **ne crée pas** la visioconférence), message facultatif, type.
- **Validation du lien** : `https://` uniquement ; **liste blanche d'hôtes** paramétrable par l'Admin ; refus des autres schémas (`javascript:`, `http:`, etc.).

```php
// Règle de validation (extrait)
'meeting_url' => ['required', 'url:https', new AllowedMeetingHost],
```

- **Détection de conflits d'horaires** (même intervenant **ou** même consultant).
- Le lien n'est visible que du **consultant concerné, de l'intervenant et de l'Admin** (Policy).

### 2.3.3 Notifications et rappels

- À la création/modification/annulation : notification au consultant (e-mail/SMS/WhatsApp) avec date, heure et **lien à jour**.
- La réunion apparaît dans l'espace du consultant (onglet Écoute/Coaching) et dans l'**agenda** de l'intervenant.
- **Rappels** à 24 h et 1 h avant (jobs planifiés + queue) au consultant **et** à l'intervenant.
- **Rappel à l'intervenant** si une réunion passée n'est pas clôturée (24 h après l'heure de fin) + résumé à l'Admin.

### 2.3.4 Notes de réunion

- Éditeur texte simple avec rubriques suggérées : points abordés, ressenti, actions convenues, prochaine étape.
- **Enregistrement automatique en brouillon** (Livewire `wire:model.debounce` + sauvegarde périodique, compatible faible connexion).
- Contenu **chiffré**, visible de l'intervenant auteur uniquement.
- Bouton **« Terminer la réunion »** (Action `CloseMeeting`) :
  1. Statut → `terminee`.
  2. Note **verrouillée** (`cloturee`).
  3. Note **transmise** à l'espace Admin.
- Après clôture : plus modifiable ; **complément daté** possible (`meeting_note_addenda`), historique conservé.

### 2.3.5 Suivi côté Admin

- Vue **« Suivi des réunions »** : date, intervenant, consultant, type, statut, note présente/absente ; filtres (période, intervenant, statut, consultant) ; recherche.
- Lecture de la note **uniquement après clôture**.
- **Lecture seule** + **commentaire de supervision** distinct (`meeting_note_reviews`).
- **Chaque ouverture d'une note par l'Admin est journalisée** (qui, quand, quelle note).
- **Indicateurs** : réunions par période, taux d'annulation/absence, notes manquantes, consultants sans réunion depuis X jours.
- **Export limité aux statistiques** ; **aucun export en masse** du contenu des notes.

### ✅ Critères de sortie de l'étape 2.3

- [ ] Un intervenant ne programme qu'avec un consultant qui lui est affecté
- [ ] Le consultant reçoit le lien et voit la réunion dans son espace
- [ ] Modification/annulation → nouvelle notification
- [ ] Note clôturée verrouillée ; complément daté conservé
- [ ] Chaque lecture Admin apparaît dans le journal d'audit
- [ ] Aucun export du contenu des notes

---

## Étape 2.4 — Catalogue Formation / Coaching (Lot 6)

### 2.4.1 Tables

```
offers:         type (atelier|module|parcours|seance_coaching|parcours_coaching), title, description,
                price (XOF), duration, provider_id, capacity, starts_at/dates, status (brouillon|publie|archive)
offer_contents: offer_id, type (pdf|texte|lien), title, file/url, position
enrollments:    user_id, offer_id, status (en_attente_reglement|actif|termine|annule), activated_at
orders / payments : (déjà posés en phase 1) liés aux inscriptions
```

### 2.4.2 Parcours consultant

1. Choix d'une offre → **commande** créée en `en_attente_reglement`.
2. **Règlement** (confirmation manuelle d'abord, passerelles automatiques à l'étape 2.7).
3. Une fois payé/confirmé → **accès débloqué** (Action `ActivateEnrollment`), notification « Paiement confirmé / accès débloqué ».
4. Contenu : leçons/documents (PDF, texte, liens externes). **Aucune vidéo hébergée** sur le site.
5. Pour les **coaching** : le **calendrier des séances** est accessible une fois l'inscription activée (calendrier éditable par l'intervenant, visible du consultant). La planification des séances réutilise le module réunions de l'étape 2.3 (lié à `enrollment_id`).

### 2.4.3 Espace Formateur / Coach

- Gestion de **ses offres** (création en brouillon ; **publication soumise à l'Admin**).
- Liste de **ses inscrits uniquement**.
- Dépôt de contenus (PDF taille limitée, liens).

### 2.4.4 Coaching

- Messagerie et réunions/notes **réutilisent** les modules 2.2 et 2.3 (liés à `enrollment_id`).
- Affectation d'un coach par l'Admin.

### 2.4.5 Places et dates

- Contrôle de la capacité (verrou transactionnel pour éviter le surbooking).
- Badge **« tarifé »** partout.

### ✅ Critères de sortie de l'étape 2.4

- [ ] Un contenu payant est inaccessible tant que le paiement n'est pas confirmé
- [ ] Un formateur ne voit que ses offres et ses inscrits
- [ ] Une offre ne passe en `publie` que par l'Admin
- [ ] Capacité respectée

---

## Étape 2.5 — Donateurs institutionnels et reçus PDF (Lot 7)

### 2.5.1 Parcours institutionnel

- **Formulaire de contact dédié** (organisation, interlocuteur, message) avec anti-spam.
- Gestion des **partenaires** (déjà posée en 1.6) : publication nom/logo **sur accord écrit**, activation/désactivation, ordre, lien.

### 2.5.2 Reçu de don (PDF numéroté)

- Sur demande du donateur (non anonyme).
- **Numérotation séquentielle** (`REC-AAAA-NNNNN`) générée dans une transaction.
- Génération PDF (DomPDF) en queue ; envoi par e-mail en pièce jointe (notification « Reçu de don »).
- **Sans mention fiscale** tant que le fiscaliste togolais n'a pas validé le format.
- Reçus conservés (re-téléchargeables depuis un lien signé).

### ✅ Critères de sortie de l'étape 2.5

- [ ] Reçu numéroté, unique, sans mention fiscale
- [ ] Impossible d'émettre un reçu pour un don non payé ou anonyme
- [ ] Partenaire non publié sans accord écrit enregistré

---

## Étape 2.6 — Désactivation automatique à 6 mois (Lot 7b)

### 2.6.1 Règle métier

Un consultant est **désactivé** (statut `inactive`, **jamais supprimé**) quand **toutes** ces conditions sont réunies :

1. Toutes ses demandes d'écoute sont **clôturées**.
2. Toutes ses inscriptions formation/coaching sont **terminées ou annulées**.
3. **Aucun paiement en attente**.
4. **6 mois** écoulés depuis la **plus récente** entre : dernière réunion, date de clôture de l'accompagnement.

> **Point de départ exact** : le cahier des charges (§4.12) est explicite — **le jour de départ des 6 mois est la date de clôture de l'accompagnement** (la plus récente entre la dernière réunion et la date de clôture). La durée du préavis et la durée avant purge/anonymisation restent **à confirmer avec le client et le juriste** — les rendre **paramétrables** (`settings`).

### 2.6.2 Commande planifiée

```bash
php artisan make:command DeactivateInactiveConsultants
php artisan make:command NotifyUpcomingDeactivations
```

```php
// routes/console.php (Laravel 11/12)
Schedule::command('accounts:notify-deactivation')->dailyAt('02:00');
Schedule::command('accounts:deactivate-inactive')->dailyAt('02:30');
```

Logique (extrait) :

```php
User::role('consultant')
    ->where('status', UserStatus::Active)
    ->whereDoesntHave('listeningRequests', fn ($q) => $q->where('status', '!=', 'cloturee'))
    ->whereDoesntHave('enrollments', fn ($q) => $q->whereIn('status', ['en_attente_reglement', 'actif']))
    ->whereDoesntHave('payments', fn ($q) => $q->whereIn('status', ['initie', 'en_attente']))
    ->get()
    ->filter(fn ($u) => $u->lastActivityReferenceDate()->addMonths($months)->isPast())
    ->each(fn ($u) => DeactivateConsultant::run($u, reason: 'auto_6_mois'));
```

### 2.6.3 Préavis

- Notification **30 jours avant** (e-mail/SMS/WhatsApp) : comment garder le compte actif (nouvelle demande ou contact bureau).
- Éviter les doubles envois (marqueur `deactivation_notice_sent_at`).

### 2.6.4 Effets de la désactivation

- **Connexion refusée** avec message clair et marche à suivre.
- Retrait des listes de l'intervenant, **notifications coupées**, fiche **archivée et verrouillée**.
- Messages, notes, charte signée, paiements **conservés** ; lecture seule pour l'Admin (**accès journalisé**).
- Renseigner `status`, `deactivated_at`, `deactivation_reason`.

### 2.6.5 Réactivation

- Par l'**Admin**, ou **automatiquement** lors d'une nouvelle demande d'écoute après **re-validation d'identité** (lien magique).
- **Nouvelle signature de la charte** si sa version a changé.
- Renseigner `reactivated_at`.

### 2.6.6 Anonymisation / purge définitive

- Paramètre de durée supplémentaire (ex. 12 à 24 mois après désactivation — **à fixer avec le juriste**).
- Commande planifiée `accounts:purge-expired` : anonymise/purge selon les durées configurées ; **journalise** l'opération (sans données personnelles).

### ✅ Critères de sortie de l'étape 2.6

- [ ] Compte désactivé après 6 mois de fin d'accompagnement (test avec date simulée via `Carbon::setTestNow`)
- [ ] Aucune suppression ; données intactes
- [ ] Connexion refusée avec message clair
- [ ] L'Admin peut réactiver

---

## Étape 2.7 — Paiements automatisés Mixx By Yas / Moov Money (Lot 8)

> **Prérequis** : comptes marchands ouverts, documentation API/sandbox, identifiants, procédure d'ajout d'URL de callback. S'appuyer **uniquement** sur la documentation officielle (ou un agrégateur).

### 2.7.1 Implémentations

```
app/Payments/
├── Contracts/PaymentGateway.php
├── Gateways/ManualGateway.php      (déjà fait)
├── Gateways/MixxGateway.php        (nouveau)
├── Gateways/MoovGateway.php        (nouveau)
├── PaymentManager.php
└── DTO/ (PaymentInitiation, PaymentStatus)
```

Configurer les identifiants dans `.env` + `config/payments.php` (jamais en dur).

### 2.7.2 Flux en ligne

1. Création du paiement (`initie`).
2. Appel à l'API de l'opérateur → **redirection** ou **push USSD** selon l'API (`en_attente`).
3. **Webhook / callback** de l'opérateur :
   - vérification de la **signature ou du secret**,
   - traitement **idempotent** (clé unique `external_ref` + statut),
   - mise à jour du statut,
   - **déblocage de l'accès** (inscription, don confirmé).
4. **Ne jamais** se fier à la seule redirection du navigateur.

### 2.7.3 Job de réconciliation

```php
Schedule::job(new ReconcilePendingPayments)->everyFiveMinutes();
```

- Interroge les paiements `en_attente` depuis plus de X minutes (`verify()`).
- **Expire** ceux qui dépassent la durée limite (`expire`).
- Journalise chaque changement de statut.

### 2.7.4 Rapprochement manuel

- Interface Admin : **import CSV du relevé marchand**, correspondance par référence, écarts signalés.
- `ManualGateway` conservé (paiement au siège, virement, espèces).

### 2.7.5 Sécurité

- Route webhook **exemptée de CSRF** mais protégée par vérification de signature + liste d'IP si l'opérateur la fournit.
- **Aucune donnée de paiement sensible** stockée ; numéro masqué.
- Logs des webhooks **sans données personnelles**.

### 2.7.6 Tests

- Tests de **webhook** (signature valide/invalide, rejeu, paiement déjà traité).
- Tests de **réconciliation** (paiement bloqué en attente → statut corrigé).
- Test : un paiement ne devient `paye` que par webhook vérifié ou confirmation Admin tracée.

### ✅ Critères de sortie de l'étape 2.7

- [ ] Paiement sandbox de bout en bout (initiation → webhook → déblocage)
- [ ] Rejeu de webhook sans double effet
- [ ] Réconciliation fonctionnelle
- [ ] Import CSV de rapprochement opérationnel

---

## Étape 2.8 — PWA complète et mode hors-ligne (Lot 8)

### 2.8.1 Manifest et icônes

- `public/manifest.webmanifest` : nom, `short_name`, couleurs, `display: standalone`, icônes 192/512 (maskable).
- Balises `<link rel="manifest">` et `theme-color` dans les layouts.

### 2.8.2 Service worker

- `public/sw.js` (Workbox ou maison) :
  - **Précache** : shell, CSS/JS versionnés, page **hors-ligne**.
  - **Cache des pages visitées** (stratégie *network-first* avec repli cache) pour les pages **publiques**.
  - Mise à jour propre (`skipWaiting` contrôlé + message « nouvelle version »).

### 2.8.3 Règle de confidentialité

> **Ne jamais mettre en cache hors-ligne les pages contenant des données sensibles** (espace personnel, messages, notes, assistance, admin).

Implémentation : liste d'exclusion dans le service worker (`/compte`, `/admin`, `/ecoute`, `/messages`, `/assistance/suivi`…) + en-têtes `Cache-Control: no-store` sur ces routes côté serveur.

### 2.8.4 Brouillons hors-ligne (IndexedDB + Background Sync)

- Formulaires (demande d'écoute, message) : brouillon conservé dans **IndexedDB**, renvoyé automatiquement au retour du réseau (**Background Sync**).
- Prévoir un **repli** (bouton « Réessayer ») quand Background Sync n'est pas supporté.
- Purger le brouillon local après envoi réussi et à la déconnexion.

### 2.8.5 Vérifications

- Lighthouse PWA (installable), test avion mode hors-ligne, test 3G simulée.
- Vérifier qu'**aucune** page sensible n'est présente dans les caches (DevTools → Application → Cache Storage).

### ✅ Critères de sortie de l'étape 2.8

- [ ] Application installable, page hors-ligne affichée
- [ ] Aucune page sensible en cache
- [ ] Brouillon envoyé automatiquement au retour du réseau

---

## Étape 2.9 — Attestations PDF (optionnel)

- Attestation de participation générée en PDF (DomPDF) après fin d'une formation/coaching.
- Numérotation, QR code de vérification (facultatif), modèle éditable par l'Admin.
- Génération en queue, envoi par e-mail, re-téléchargement via lien signé.

### ✅ Critère de sortie

- [ ] Attestation délivrée uniquement aux inscriptions `termine`

---

## Étape 2.10 — Recette finale et préparation du déploiement final

### 2.10.1 Recette complète (staging)

Rejouer la recette phase 1 **et** valider les critères de la phase 2 :

- [ ] Chaque rôle n'accède qu'à ses propres données (tests auto + test manuel, comptes de démo)
- [ ] Impossible d'écrire à un thérapeute sans charte signée
- [ ] Un thérapeute non validé ne voit aucun dossier
- [ ] Le comité ne voit que l'assistance ; aucun autre rôle ne la voit
- [ ] Paiement `paye` uniquement via **webhook vérifié** ou **confirmation Admin tracée**
- [ ] Don anonyme : aucun nom (public, exports partagés)
- [ ] Aucune donnée sensible dans logs, mails, SMS
- [ ] Pages clés < 3 s en 3G simulée
- [ ] Un thérapeute ne programme une réunion qu'avec un consultant qui lui est affecté ; le consultant reçoit le lien et voit la réunion
- [ ] Réunion modifiée/annulée → nouvelle notification
- [ ] Note clôturée verrouillée, visible dans le suivi Admin, **chaque lecture Admin journalisée**
- [ ] Consultant désactivé après 6 mois (inactif, rien supprimé, connexion refusée) ; l'Admin peut réactiver
- [ ] L'Admin gère photos, logos partenaires et organigramme ; fichier utilisé non supprimable sans avertissement
- [ ] Sauvegarde restaurée avec succès sur un environnement de test

### 2.10.2 Tests de charge et de robustesse

- Test de charge léger (k6/ab) sur pages publiques et formulaires.
- Vérifier le comportement des **queues** (échecs, reprises, `failed_jobs`).
- Vérifier le **scheduler** (toutes les tâches de l'[Annexe C](#annexe-c--tâches-planifiées) s'exécutent).

### 2.10.3 Revue sécurité finale

- Audit des dépendances : `composer audit`, `npm audit`.
- Relecture des Policies et des routes (`php artisan route:list`) : aucune route sensible sans middleware.
- Revue des notifications (aucun contenu sensible) et des logs applicatifs.
- Vérification des en-têtes (securityheaders.com) et du TLS (SSL Labs).
- Revue des accès : liste des comptes Admin/Comité/intervenants, 2FA actif, super-admin désactivé.

### 2.10.4 Documentation de livraison

- `docs/EXPLOITATION.md` : déploiement, rollback, sauvegarde/restauration, rotation des clés, ajout d'un Admin.
- `docs/ADMIN-GUIDE.md` : guide utilisateur du back-office (pour le bureau).
- `docs/INCIDENTS.md` : procédure en cas de fuite/incident de données (qui prévenir, délais, IPDCP).
- Contrat de **maintenance/hébergement (TMA)** : responsabilités de maintenance, sauvegardes et support après livraison.

### 2.10.5 Déploiement production v2.0

Même procédure que 1.12.3, avec en plus :

```bash
php artisan migrate --force          # migrations des lots 5 à 8
php artisan db:seed --class=RolesAndPermissionsSeeder --force   # nouvelles permissions
php artisan queue:restart
php artisan schedule:list            # vérifier les nouvelles tâches
```

Check-list :

- [ ] Sauvegarde **complète** pré-déploiement + test de restauration récent
- [ ] Identifiants **production** Mixx/Moov configurés ; URL de callback déclarées chez les opérateurs
- [ ] Webhooks testés en production avec un petit montant réel
- [ ] Service worker : nouvelle version publiée, anciens caches purgés
- [ ] Durées de conservation et de désactivation **définies dans les paramètres** conformément à l'avis juridique
- [ ] Monitoring (Sentry, UptimeRobot) actif avec alertes
- [ ] Communication aux utilisateurs (nouvelles fonctionnalités, nouvelle version de charte si besoin)
- [ ] Tag Git `v2.0.0`

```bash
git tag -a v2.0.0 -m "Ezer Home — Phase 2 (intervenants, réunions, formations, paiements, PWA)"
git push origin main --tags
```

### ✅ FIN DE LA PHASE 2 — Livrables

Candidatures intervenants · messagerie · réunions + notes + suivi Admin · catalogue formation/coaching · paiements Mixx/Moov automatisés · reçus PDF · désactivation automatique à 6 mois · PWA complète · documentation d'exploitation.

---

# Annexes

## Annexe A — Arborescence cible

```
ezer-home/
├── app/
│   ├── Actions/               # Logique métier (ApproveProviderApplication, CloseMeeting, ...)
│   ├── Console/Commands/      # DeactivateInactiveConsultants, PurgeExpiredAccounts, ...
│   ├── Enums/                 # UserStatus, ListeningRequestStatus, PaymentStatus, ...
│   ├── Filament/              # Resources Admin & Comité
│   ├── Http/Middleware/       # EnsureTwoFactorEnabled, EnsureListeningCharterSigned, SecurityHeaders
│   ├── Jobs/                  # ReconcilePendingPayments, ProcessMedia, SendReminder, ...
│   ├── Livewire/              # Composants (MediaUploader, Messaging, MeetingNotes, ...)
│   ├── Models/
│   ├── Notifications/
│   ├── Payments/              # PaymentGateway + Manual/Mixx/Moov
│   ├── Policies/
│   ├── Rules/                 # AllowedMeetingHost, ...
│   └── Services/              # TrackingNumberGenerator, ReceiptNumberGenerator, ...
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/               # RolesAndPermissionsSeeder, DemoSeeder (local/staging uniquement)
├── resources/
│   ├── css/ js/
│   └── views/                 # layouts, pages publiques, espaces, emails
├── lang/fr/
├── public/
│   ├── manifest.webmanifest   # phase 2
│   └── sw.js                  # phase 2
├── routes/                    # web.php, console.php, (webhooks.php)
├── tests/
│   ├── Feature/               # Permissions, Charter, Payments, Meetings, ...
│   └── Unit/
├── docs/                      # EXPLOITATION.md, ADMIN-GUIDE.md, INCIDENTS.md
└── .github/workflows/ci.yml
```

## Annexe B — Ordre des migrations

**Phase 1**

1. `users` (+ champs statut/2FA/cycle de vie), `sessions`, `jobs`, `failed_jobs`, `cache`
2. Spatie permission, activity_log, media
3. `charters`, `charter_signatures`
4. `pages`, `settings`
5. `media_usages` (et table `media` applicative si besoin)
6. `org_members`, `partners`
7. `listening_requests`
8. `assistance_requests`, `assistance_events` (+ décaissements)
9. `orders`, `payments`, `donations`

**Phase 2**

10. `provider_applications`
11. `conversations`, `messages`
12. `meetings`, `meeting_notes`, `meeting_note_addenda`, `meeting_note_reviews`
13. `offers`, `offer_contents`, `enrollments`
14. Champs de reçus/attestations, `contact_inquiries` (institutionnels)

> Astuce : poser en phase 1 les colonnes dont la phase 2 dépend (statut utilisateur, `payments.gateway`) pour éviter des migrations destructrices.

## Annexe C — Tâches planifiées

| Commande / Job | Fréquence | Phase | Rôle |
|---|---|---|---|
| `queue:work` (Supervisor) | Continu | 1 | Envoi notifications, traitement médias |
| `donations:monthly-reminder` | Mensuel | 1 | Rappel don régulier |
| `meetings:send-reminders` | Toutes les 5–15 min | 2 | Rappels 24 h et 1 h |
| `meetings:remind-unclosed` | Quotidien | 2 | Réunions non clôturées (+24 h) |
| `ReconcilePendingPayments` | Toutes les 5 min | 2 | Réconciliation / expiration paiements |
| `accounts:notify-deactivation` | Quotidien | 2 | Préavis 30 jours |
| `accounts:deactivate-inactive` | Quotidien | 2 | Désactivation à 6 mois |
| `accounts:purge-expired` | Quotidien | 2 | Anonymisation / purge après durée légale |
| `backup:run` + `backup:clean` | Quotidien | 1 | Sauvegardes chiffrées |
| `model:prune` | Quotidien | 1 | Purge des données techniques expirées |

Cron système : `* * * * * php artisan schedule:run`.

## Annexe D — Fichier `.env` type

```dotenv
APP_NAME="Ezer Home"
APP_ENV=production
APP_KEY=                      # sauvegardée séparément !
APP_DEBUG=false
APP_URL=https://www.exemple-ezerhome.org
APP_LOCALE=fr
APP_FALLBACK_LOCALE=fr
APP_TIMEZONE=Africa/Lome

LOG_CHANNEL=stack
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ezer_home
DB_USERNAME=
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

QUEUE_CONNECTION=database      # ou redis
CACHE_STORE=database           # ou redis

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=contact@exemple-ezerhome.org
MAIL_FROM_NAME="${APP_NAME}"

TURNSTILE_SITE_KEY=
TURNSTILE_SECRET_KEY=

SMS_DRIVER=                    # passerelle à choisir
SMS_API_KEY=
WHATSAPP_NUMBER=228XXXXXXXX    # bouton wa.me

SENTRY_LARAVEL_DSN=

# Phase 2 — paiements (valeurs fournies par les opérateurs)
MIXX_BASE_URL=
MIXX_MERCHANT_ID=
MIXX_SECRET=
MOOV_BASE_URL=
MOOV_MERCHANT_ID=
MOOV_SECRET=
```

## Annexe E — Matrice de tests de permissions

À automatiser avec Pest (`dataset` par rôle) :

| Ressource / Action | Consultant | Thérapeute | Formateur/Coach | Comité | Admin | Visiteur |
|---|---|---|---|---|---|---|
| Ses propres demandes d'écoute | ✔ | – | – | ✘ | – | ✘ |
| Demandes d'écoute d'un autre consultant | ✘ | ✘ (sauf affecté) | ✘ | ✘ | ✔ tracé | ✘ |
| Demande d'écoute sans charte signée | ✘ | – | – | – | – | ✘ |
| Demandes d'assistance | ✘ | ✘ | ✘ | ✔ | ✔ | ✘ |
| Changer statut assistance | ✘ | ✘ | ✘ | ✔ | ✔ | ✘ |
| Médiathèque (tout) | ✘ | ✘ | ✘ | catégorie assistance | ✔ | ✘ |
| Organigramme / partenaires | ✘ | ✘ | ✘ | ✘ | ✔ | lecture publique |
| Confirmer paiement manuel | ✘ | ✘ | ✘ | option | ✔ | ✘ |
| Dons et exports comptables | ✘ | ✘ | ✘ | ✘ | ✔ | ✘ |
| Journal d'audit | ✘ | ✘ | ✘ | ✘ | ✔ (lecture) | ✘ |
| *(Phase 2)* Dossiers d'un thérapeute collègue | ✘ | ✘ | ✘ | ✘ | ✔ tracé | ✘ |
| *(Phase 2)* Lien de réunion | concerné | auteur | auteur | ✘ | ✔ | ✘ |
| *(Phase 2)* Note de réunion | ✘ | auteur | auteur | ✘ | ✔ après clôture, tracé | ✘ |
| *(Phase 2)* Inscrits d'une offre | ✘ | ✘ | siens | ✘ | ✔ | ✘ |

**Lignes supplémentaires de la matrice du cahier des charges (§3.2)** — à ajouter à la matrice complète :

| Ressource / Action | Consultant | Thérapeute | Formateur/Coach | Comité | Admin | Visiteur |
|---|---|---|---|---|---|---|
| Gérer son profil et ses données | ✔ | ✔ | ✔ | ✔ | ✔ | – |
| Signer la charte, demander une écoute | ✔ | – | – | – | – | – |
| Voir ses propres échanges d'écoute | ✔ | – | – | – | – | – |
| Voir ses personnes accompagnées | – | ✔ (siennes) | ✔ (siennes) | – | ✔ (traçable) | – |
| Programmer une réunion (avec lien) | – | ✔ | ✔ | – | – | – |
| Prendre des notes de réunion | – | ✔ | ✔ | – | – | – |
| Lire les notes de réunion | – | ✔ (siennes) | ✔ (siennes) | – | ✔ (après clôture, tracé) | – |
| S'inscrire à une offre payante | ✔ | ✔ | ✔ | – | – | – |
| Gérer ses offres / ses inscrits | – | ✔ (siennes) | ✔ (siennes) | – | – | – |
| Valider/refuser une candidature d'intervenant | – | – | – | – | ✔ | – |
| Affecter un consultant à un thérapeute/coach | – | – | – | – | ✔ | – |
| Gérer contenus, tarifs, paramètres, rôles | – | – | – | – | ✔ | – |
| Gérer la médiathèque (photos, logos, documents) | – | – | – | ✔ (catégorie assistance) | ✔ | – |
| Gérer l'organigramme et les partenaires | – | – | – | – | ✔ | – |
| Suivre toutes les réunions et leurs notes | – | – | – | – | ✔ | – |
| Désactiver / réactiver un compte | – | – | – | – | ✔ | – |

Pour chaque ligne : test **autorisé** + test **refusé** (code 403/redirection), et vérification de l'**entrée d'audit** pour les accès Admin tracés.

## Annexe F — Points à valider avec le client

À trancher **avant de coder** les modules concernés :

- [ ] Rôle exact du **Comité de validation** (assistance seule, ou aussi paiements/candidatures ?)
- [ ] Formateur et Coach : un seul rôle « Intervenant » ou deux ?
- [ ] Supervision : un superviseur peut-il lire les comptes rendus ?
- [ ] Durées de conservation : messages, comptes rendus, demandes d'assistance
- [ ] Grille tarifaire, catégories d'offres, montants suggérés pour les dons
- [ ] Passerelles SMS/WhatsApp et budget
- [ ] Avis juridique IPDCP (données sensibles, hébergement) et avis fiscal sur les reçus
- [ ] Documentation API, sandbox et délais d'ouverture des comptes marchands Mixx/Moov
- [ ] Responsabilité de la maintenance, des sauvegardes et du support après livraison (TMA)
- [ ] Partage des notes de réunion avec l'Admin : formulation dans la charte, personnes autorisées, finalité
- [ ] Point de départ exact des 6 mois, durée du préavis, durée avant purge/anonymisation
- [ ] Liste des membres de l'organigramme, formats des photos, accord de publication des photos d'assistance
- [ ] Outil de visioconférence des intervenants et hôtes de liens autorisés
- [ ] Textes légaux (charte d'écoute, charte intervenant, mentions légales, confidentialité) à fournir par le client

---

*Ezer Home — README de réalisation · basé sur le Cahier des charges v1.1 (29 septembre 2026).*