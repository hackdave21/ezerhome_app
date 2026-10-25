# Ezer Home — Phase 1 : Lancement public (lots 0 à 4)

> Document de réalisation de la **phase 1** uniquement.
> **Lancement public visé : 15 octobre 2026**
> Basé sur le *Cahier des charges v1.1 — 29 septembre 2026* et sur le [README](./README.md).
> La phase 2 (lots 5 à 8) est traitée séparément dans le README.

---

## Sommaire

1. [Objectif et périmètre de la phase 1](#1-objectif-et-périmètre-de-la-phase-1)
2. [Prérequis et démarches à lancer](#2-prérequis-et-démarches-à-lancer)
3. [Règles de travail](#3-règles-de-travail)
4. [Étape 1.0 — Initialisation du projet (Lot 0)](#étape-10--initialisation-du-projet-lot-0)
5. [Étape 1.1 — Intégration du design et layout (Lot 0 et 1)](#étape-11--intégration-du-design-et-layout-lot-0-et-1)
6. [Étape 1.2 — Site public / vitrine (Lot 1)](#étape-12--site-public--vitrine-lot-1)
7. [Étape 1.3 — Authentification, comptes, rôles (Lot 2)](#étape-13--authentification-comptes-rôles-lot-2)
8. [Étape 1.4 — Chartes signées (Lot 2)](#étape-14--chartes-signées-lot-2)
9. [Étape 1.5 — Médiathèque de base (Lot 2)](#étape-15--médiathèque-de-base-lot-2)
10. [Étape 1.6 — Organigramme et logos partenaires (Lot 2)](#étape-16--organigramme-et-logos-partenaires-lot-2)
11. [Étape 1.7 — Demande d'écoute et affectation manuelle (Lot 2)](#étape-17--demande-découte-et-affectation-manuelle-lot-2)
12. [Étape 1.8 — Volet Assistance et espace Comité (Lot 3)](#étape-18--volet-assistance-et-espace-comité-lot-3)
13. [Étape 1.9 — Volet Dons et paiement manuel (Lot 4)](#étape-19--volet-dons-et-paiement-manuel-lot-4)
14. [Étape 1.10 — Back-office Admin](#étape-110--back-office-admin)
15. [Étape 1.11 — Sécurité et tests de la phase 1](#étape-111--sécurité-et-tests-de-la-phase-1)
16. [Étape 1.12 — Préparation du déploiement phase 1](#étape-112--préparation-du-déploiement-phase-1)
17. [Annexes phase 1](#annexes-phase-1)

---

## 1. Objectif et périmètre de la phase 1

**Objectif** : mettre en ligne un site public complet et sûr : vitrine, comptes, charte signée, demandes d'écoute envoyées à l'Admin, formulaire d'assistance traité par le Comité, dons avec confirmation manuelle, médiathèque et organigramme.

| | **PHASE 1 — Lancement public** |
|---|---|
| Lots | 0, 1, 2, 3, 4 |
| Paiement | **Confirmation manuelle uniquement** (bureau) |
| Messagerie / comptes rendus | **Non ouverts** (en attente de l'avis juridique IPDCP) |
| Sortie de phase | Recette phase 1 + déploiement production v1.0 |

### Principes directeurs (à garder en tête à chaque étape)

1. **Cloisonnement strict** : aucun rôle n'a de vue globale sur les conversations.
2. **Chiffrement au repos** des champs sensibles (cast `encrypted`).
3. **Traçabilité** : tout accès Admin à un dossier sensible est journalisé.
4. **Légèreté** : mobile-first, réseau 2G/3G, peu de JS, images WebP.
5. **Aucune donnée sensible** dans les e-mails, SMS, notifications ou logs.

### Vue d'ensemble des étapes

```
PHASE 1
1.0  Initialisation ───────────────┐
1.1  Design + layout               │
1.2  Site vitrine                  │
1.3  Auth + rôles + 2FA            │
1.4  Chartes signées               ├─ Lots 0-4
1.5  Médiathèque                   │
1.6  Organigramme + partenaires    │
1.7  Demande d'écoute              │
1.8  Assistance + Comité           │
1.9  Dons + paiement manuel        │
1.10 Back-office Admin             │
1.11 Sécurité + tests              │
1.12 Déploiement phase 1 ──────────┘
```

> **Si le calendrier est serré** (lots 2 à 4 en ~2 semaines) : décaler le **2FA** et le **back-office Admin avancé** après le lancement. Ne **jamais** décaler : la charte signée, la sécurité de base et le cloisonnement des rôles.

---

## 2. Prérequis et démarches à lancer

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

## 3. Règles de travail

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
- Fichiers fournis : `deploy/nginx/`, `deploy/scripts/`, `.env.staging.example` — voir [docs/STAGING.md](./docs/STAGING.md).

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

## Étape 1.2 — Site public / vitrine (Lot 1)

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
- **Demande de suppression de compte** : crée une demande traitée par l'Admin (la suppression réelle respecte les durées de conservation, voir §2.6 du README).

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
- Programmation de réunions et notes de réunion : §4.5 (étape 2.3 du README, lot 5).

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

### 1.8.4 Espace Comité

Intégrer l'espace Comité au back-office existant (section dédiée, accès conditionné au rôle `comite`) :

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
- Les reçus PDF et le parcours institutionnel sont livrés en phase 2 (étape 2.5 du README).

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

## Étape 1.10 — Back-office Admin

Le design du back-office est **déjà fourni** : l'intégrer dans l'application (ne pas le recréer). Espace `/admin` (protégé : rôles + 2FA + éventuellement restriction IP).

### 1.10.1 Accès et cloisonnement

- Espace **Admin** : accès réservé au rôle `admin`.
- Espace (ou section) **Comité** : accès `comite` limité à l'assistance et à la médiathèque *assistance*.
- L'accès est contrôlé par un middleware / une Policy vérifiant rôle + statut `active` + 2FA confirmé.

### 1.10.2 Écrans du back-office (phase 1)

| Écran | Contenu |
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

Site public complet · comptes/rôles/2FA · chartes signées · médiathèque · organigramme · partenaires · demande d'écoute + affectation manuelle · assistance + Comité · dons avec confirmation manuelle · back-office Admin · audit · sauvegardes · monitoring.

---

# Annexes phase 1

## Annexe A — Arborescence cible (phase 1)

```
ezer-home/
├── app/
│   ├── Actions/               # Logique métier (actions de la phase 1)
│   ├── Enums/                 # UserStatus, ListeningRequestStatus, PaymentStatus, ...
│   ├── Http/Middleware/       # EnsureTwoFactorEnabled, EnsureListeningCharterSigned, SecurityHeaders
│   ├── Jobs/                  # ProcessMedia, notifications, exports
│   ├── Livewire/              # Composants (MediaUploader, formulaires, ...)
│   ├── Models/
│   ├── Notifications/
│   ├── Payments/              # PaymentGateway + Manual
│   ├── Policies/
│   ├── Services/              # TrackingNumberGenerator, ...
│   └── ...
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/               # RolesAndPermissionsSeeder, DemoSeeder (local/staging uniquement)
├── resources/
│   ├── css/ js/
│   └── views/                 # layouts, pages publiques, espaces, emails
├── lang/fr/
├── public/
├── routes/                    # web.php, console.php
├── tests/
│   ├── Feature/               # Permissions, Charter, Payments, Media, ...
│   └── Unit/
├── docs/                      # EXPLOITATION.md, ADMIN-GUIDE.md, INCIDENTS.md (phase 1)
└── .github/workflows/ci.yml
```

## Annexe B — Ordre des migrations (phase 1)

1. `users` (+ champs statut/2FA/cycle de vie), `sessions`, `jobs`, `failed_jobs`, `cache`
2. Spatie permission, activity_log, media
3. `charters`, `charter_signatures`
4. `pages`, `settings`
5. `media_usages` (et table `media` applicative si besoin)
6. `org_members`, `partners`
7. `listening_requests`
8. `assistance_requests`, `assistance_events` (+ décaissements)
9. `orders`, `payments`, `donations`

> Astuce : poser en phase 1 les colonnes dont la phase 2 dépend (statut utilisateur, `payments.gateway`) pour éviter des migrations destructrices.

## Annexe C — Tâches planifiées (phase 1)

| Commande / Job | Fréquence | Rôle |
|---|---|---|
| `queue:work` (Supervisor) | Continu | Envoi notifications, traitement médias |
| `donations:monthly-reminder` | Mensuel | Rappel don régulier |
| `backup:run` + `backup:clean` | Quotidien | Sauvegardes chiffrées |
| `model:prune` | Quotidien | Purge des données techniques expirées |

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
```

## Annexe E — Matrice de tests de permissions (phase 1)

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
| Gérer son profil et ses données | ✔ | ✔ | ✔ | ✔ | ✔ | – |
| Signer la charte, demander une écoute | ✔ | – | – | – | – | – |
| Voir ses propres échanges d'écoute | ✔ | – | – | – | – | – |
| Voir ses personnes accompagnées | – | ✔ (siennes) | ✔ (siennes) | – | ✔ (traçable) | – |
| Gérer contenus, tarifs, paramètres, rôles | – | – | – | – | ✔ | – |
| Gérer la médiathèque (photos, logos, documents) | – | – | – | ✔ (catégorie assistance) | ✔ | – |
| Gérer l'organigramme et les partenaires | – | – | – | – | ✔ | – |
| Désactiver / réactiver un compte | – | – | – | – | ✔ | – |

Pour chaque ligne : test **autorisé** + test **refusé** (code 403/redirection), et vérification de l'**entrée d'audit** pour les accès Admin tracés.

## Annexe F — Points à valider avec le client (phase 1)

À trancher **avant de coder** les modules concernés :

- [ ] Rôle exact du **Comité de validation** (assistance seule, ou aussi paiements/candidatures ?)
- [ ] Durées de conservation : demandes d'assistance
- [ ] Grille tarifaire, catégories d'offres, montants suggérés pour les dons
- [ ] Passerelles SMS/WhatsApp et budget
- [ ] Avis juridique IPDCP (données sensibles, hébergement) et avis fiscal sur les reçus
- [ ] Documentation API, sandbox et délais d'ouverture des comptes marchands Mixx/Moov
- [ ] Responsabilité de la maintenance, des sauvegardes et du support après livraison (TMA)
- [ ] Liste des membres de l'organigramme, formats des photos, accord de publication des photos d'assistance
- [ ] Textes légaux (charte d'écoute, mentions légales, confidentialité) à fournir par le client

---

*Ezer Home — Phase 1 (lots 0 à 4) · lancement public visé le 15 octobre 2026 · basé sur le Cahier des charges v1.1 (29 septembre 2026) et le README du projet.*
