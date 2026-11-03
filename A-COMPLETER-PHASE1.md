# Ezer Home — Ce qu'il reste à finaliser pour boucler la phase 1

> Document compagnon de [phase1.md](./phase1.md).
> Le **code** des lots 0 à 4 est en place et couvert par les tests (`php artisan test`).
> Ce qui suit liste ce qui **manque encore** pour une mise en ligne publique réelle : des
> **services externes** à ouvrir, des **contenus** à fournir par le client, de la **configuration**
> de déploiement, et quelques **branchements de code** laissés en attente volontairement.

Légende des responsables : 👤 **Client** · 🛠️ **Développeur** · ⚙️ **Ops / hébergement**

---

## 1. Services externes à ouvrir et à brancher

### 1.1 Paiement en ligne — Mixx By Yas et Moov Money  🛠️ 👤
- **En place** : l'architecture finale (`PaymentGateway`, `PaymentManager`) et la passerelle
  **manuelle** (`ManualGateway`) active. Un don crée un paiement `en_attente`, affiche les
  **instructions de versement** et attend la **confirmation Admin tracée**.
- **Manque** :
  - Ouverture des **comptes marchands** Mixx By Yas et Moov Money (délai long — à lancer
    immédiatement, cf. phase1.md §2).
  - **Documentation API + accès sandbox** des deux opérateurs (non vérifiés dans le cahier des charges).
  - Implémentation de `MixxGateway` et `MoovGateway` (classes à créer dans `app/Payments/`,
    à enregistrer dans `app/Payments/PaymentManager.php`) — **prévu en phase 2 (étape 2.7)**.
  - Renseigner les **numéros marchands** dans l'admin : *Paramètres* → `donations.merchant.mixx`
    et `donations.merchant.moov` (sinon la page d'instructions invite à contacter l'association).
- **Note** : en phase 1, tout don reste en **confirmation manuelle** ; c'est conforme au périmètre.

### 1.2 E-mails transactionnels (SMTP)  ⚙️
- **En place** : toutes les notifications sont des *Mailables* **en file d'attente** (contact,
  OTP, lien magique, export de données, écoute, assistance, rappel de dons).
- **Manque** :
  - Un **compte SMTP réel** et le renseignement de `MAIL_MAILER=smtp`, `MAIL_HOST`, `MAIL_PORT`,
    `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS` dans le `.env` de production.
    En l'état, `.env.example` pointe sur `MAIL_MAILER=log` (les mails ne partent pas).
  - Un **worker de file d'attente** actif (voir §3.2), sinon les mails restent en attente.

### 1.3 OTP et notifications par SMS / WhatsApp  🛠️ 👤
- **En place** : l'interface `App\Services\Otp\OtpSender` et une implémentation de substitution
  `LogSmsOtpSender` qui **n'envoie rien** (écrit seulement dans le journal en local/testing).
  `OTP_SMS_ENABLED=false` : en phase 1, la vérification se fait **par e-mail**.
- **Manque** :
  - **Choix d'une passerelle** SMS/WhatsApp et de son budget (👤).
  - Implémentation réelle de `OtpSender` pour cette passerelle, puis bind dans
    `app/Providers/AppServiceProvider.php` (remplacer `LogSmsOtpSender`).
  - Même chose pour les **notifications** d'assistance envoyées par SMS/WhatsApp : aujourd'hui,
    seul l'e-mail part réellement ; l'envoi SMS est seulement tracé en local
    (`AssistanceWorkflow::notify`). À brancher sur la même passerelle.
  - Renseigner `WHATSAPP_NUMBER` (bouton wa.me du header) et les clés de la passerelle.

### 1.4 Anti-spam — Cloudflare Turnstile  ⚙️
- **En place** : la règle de validation `Turnstile` et les widgets sur les formulaires publics
  (contact, assistance). **Inactive tant que la clé secrète est vide.**
- **Manque** : créer un compte Cloudflare Turnstile et renseigner `TURNSTILE_SITE_KEY` et
  `TURNSTILE_SECRET` dans le `.env` de production (sinon le honeypot + throttle protègent seuls).

### 1.5 Monitoring et sauvegardes  ⚙️ 🛠️
- **Manque** (non installés) :
  - **Sentry** ou **Flare** pour les erreurs (configurer le scrubbing des champs sensibles) :
    `composer require sentry/sentry-laravel`, puis `SENTRY_LARAVEL_DSN`.
  - **UptimeRobot** (disponibilité visée 99 %).
  - **Sauvegardes chiffrées** quotidiennes : `composer require spatie/laravel-backup`, copie
    hors serveur, et **test de restauration** (critère de recette, étape 1.12).
  - Pages d'erreur 500 / 404 / 503 personnalisées.

---

## 2. Contenus à fournir par le client  👤

Tous ces contenus sont **éditables depuis le back-office** (aucune intervention de code) mais sont
aujourd'hui des **textes provisoires marqués entre crochets** `[...]`.

- **Textes légaux réels** (back-office → *Pages et contenus*) : mentions légales, politique de
  confidentialité, CGU. Ils contiennent des champs `[NOM]`, `[adresse]`, `[durées de conservation]`…
- **Charte d'écoute** et **charte de l'intervenant** (version provisoire publiée) : fournir le texte
  définitif et le **publier comme nouvelle version** (`php artisan charters:publish`), ce qui
  réexigera une signature. ⚠️ **Bloquant** : la charte d'écoute doit être validée par le **juriste**
  (mention du partage des notes avec le bureau).
- **Organigramme** : liste des membres, photos, et **accord de publication** des photos.
- **Partenaires** : logos et **accord écrit** de publication (obligatoire avant activation).
- **Page « À quoi servent les dons »** et **FAQ** : à rédiger (back-office → *Pages et contenus*).
- **Paramètres** (back-office → *Paramètres*) : coordonnées définitives, **montants de don suggérés**,
  **numéros marchands** Mixx/Moov, **durées de conservation**, taille max d'upload.
- **Modèles de notifications** (back-office → *Notifications*) : à relire, aucun contenu sensible.
- **Grille tarifaire** formations/coaching et catégories d'offres (pour la phase 2).

---

## 3. Configuration et déploiement  ⚙️

Détail complet dans [phase1.md §1.12](./phase1.md) et [docs/STAGING.md](./docs/STAGING.md).

### 3.1 Variables d'environnement de production (`.env`)
- `APP_ENV=production`, **`APP_DEBUG=false`**, `APP_URL=https://…`, `LOG_LEVEL=warning`.
- **`APP_KEY` générée et sauvegardée séparément** — sans elle, toutes les données chiffrées
  (écoute, assistance, dons) sont **définitivement perdues**.
- Base de données, `SESSION_SECURE_COOKIE=true`, SMTP, Turnstile, passerelle SMS, Sentry.

### 3.2 Processus continus  ⚙️
- **Supervisor** pour `php artisan queue:work` (envoi des mails/notifications, traitement des médias).
  Sans lui, **rien ne part**.
- **Cron** pour le planificateur : `* * * * * php artisan schedule:run`. Il déclenche notamment le
  **rappel mensuel des dons réguliers** (`donations:monthly-reminder`, déjà planifié) et, plus tard,
  les sauvegardes et la purge (`model:prune`).

### 3.3 Serveur et réseau  ⚙️
- **HTTPS** (Let's Encrypt) + redirection HTTP→HTTPS côté Nginx. Le code force déjà le schéma HTTPS
  et envoie **HSTS** en production (middleware `SecurityHeaders`).
- Pare-feu (22/80/443), SSH par clé, mises à jour de sécurité, `fail2ban` recommandé.
- `php artisan optimize` au déploiement ; compression Brotli/Gzip.

### 3.4 Comptes initiaux  ⚙️ 👤
- Exécuter `RolesAndPermissionsSeeder` (idempotent).
- Créer les **comptes Admin initiaux** (mots de passe forts, **2FA dès la 1re connexion**).
- **Désactiver le super-admin technique** après mise en place. Aucun compte de démonstration en prod.
- En phase 1, attribuer manuellement le rôle `therapeute` (avec 2FA) aux premiers intervenants
  connus, pour permettre les affectations d'écoute.

---

## 4. Vérifications de recette encore à faire  🛠️ ⚙️

- **Performance** : vérifier avec Lighthouse/DevTools que les pages clés font **< 200–300 Ko**
  (hors polices) et chargent en **< 3 s en 3G simulée**. Non automatisé par la suite de tests.
- **Revue manuelle** du contenu de chaque e-mail/SMS réel (aucune donnée sensible) une fois les
  passerelles branchées.
- **Test de restauration** d'une sauvegarde sur un environnement de test.
- **Avis juridique IPDCP** obtenu avant d'ouvrir le volet écoute au public.

---

## 5. Fonctionnalités volontairement reportées (phase 2)

Hors périmètre de la phase 1 — rappelées ici pour mémoire :

- **Messagerie interne** écoute/accompagnement et **comptes rendus de séance** (lot 5).
- **Candidatures** d'intervenants et validation (en phase 1 : attribution manuelle du rôle).
- **Reçus de don en PDF** et **parcours institutionnel** des dons (étape 2.5).
- **Passerelle Mixx/Moov** automatique et **webhooks vérifiés** (étape 2.7).
- **Prélèvement automatique** des dons réguliers (en phase 1 : simple rappel mensuel).
- **Passerelle don → demande d'assistance** ciblée (hors V1).
- **Arbre SVG** de l'organigramme (phase 1 : grille par comité).

---

## 6. Petites améliorations possibles (non bloquantes)

- **Adresse IP dans le journal d'audit** : l'écran affiche qui/quoi/quand ; l'IP n'est pas encore
  capturée. Peut être ajoutée via un *tap* global sur `activitylog`.
- **Internationalisation** : les textes sont en français dans les vues ; l'extraction vers
  `lang/fr` (puis Éwé, Kabyè, anglais) reste à faire pour préparer l'i18n.

---

*Dès que les points des sections 1 à 4 sont traités, la phase 1 peut passer en recette puis en
production (v1.0), conformément à l'étape 1.12 de [phase1.md](./phase1.md).*
