# Sécurité — Phase 1 (étape 1.11)

État de la checklist de sécurité du cahier des charges. Chaque point renvoie à son implémentation ou à son action de déploiement.

## Checklist (1.11.1)

- [x] **HTTPS forcé** — `URL::forceScheme('https')` en production (`AppServiceProvider`). La redirection HTTP→HTTPS est assurée par Nginx (voir `docs/STAGING.md`).
- [x] **HSTS** — en-tête `Strict-Transport-Security` posé sur les réponses HTTPS (`SecurityHeaders`).
- [x] **Cookies `Secure` / `HttpOnly` / `SameSite`** — `config/session.php` : `secure` = vrai en production, `http_only` = vrai, `same_site` = `lax`.
- [x] **CSRF actif partout** — middleware web de Laravel ; tous les formulaires publics incluent `@csrf`.
- [x] **En-têtes de sécurité** — `SecurityHeaders` : CSP, `X-Frame-Options: DENY`, `X-Content-Type-Options: nosniff`, `Referrer-Policy`, `Permissions-Policy`. La route des médias garde sa CSP isolante (`sandbox`).
- [x] **Validation stricte des uploads** — type MIME réel, taille paramétrable, assainissement SVG (`MediaIngest`, `SvgSanitizer`, étape 1.5).
- [x] **Throttle** — connexion/OTP (`throttle:otp`), formulaires publics (`contact`, `assistance`, `dons` : `throttle:5,1`/`10,1`).
- [x] **Champs sensibles chiffrés** — cast `encrypted` : écoute (`description`), assistance (`full_name`, `phone`, `email`, `description`, `notes`), dons (`donor_name`, `donor_contact`).
- [x] **UUID dans les URLs sensibles** — demandes d'écoute, demandes d'assistance, paiements.
- [x] **Aucune donnée sensible dans logs / mails / SMS** — notifications construites à partir de valeurs non sensibles (référence, canal, statut) ; vérifié par tests. L'audit ne journalise jamais les champs chiffrés (`logOnly`).
- [ ] **`APP_DEBUG=false` en production** — à poser dans le `.env` de production (voir `docs/STAGING.md`, Annexe D).
- [ ] **`APP_KEY` sauvegardée séparément** — action de déploiement : sans elle, les données chiffrées sont irrécupérables.

> Les deux dernières cases sont des actions de déploiement (étape 1.12), pas du code.

## Tests (1.11.2)

- `tests/Feature/SecurityTest.php` — en-têtes de sécurité, HSTS, CSP des médias, absence de contenu sensible dans les mails.
- `tests/Feature/Permissions/PermissionMatrixTest.php` — matrice rôle × ressource (Annexe E), autorisé/refusé.
- Couverture par domaine : `ListeningRequestTest`, `AssistanceTest`, `DonationTest`, `MediaTest`, `BackOfficeTest`, `CharterTest`, `RolesAndPrivacyTest`.

Points clés déjà couverts par la suite :

- Création de demande d'écoute impossible sans charte signée.
- Le Comité ne voit que l'assistance ; aucun autre rôle n'y accède.
- Un paiement ne passe à `payé` que par une confirmation Admin tracée.
- Un don anonyme n'expose aucun nom (public + export CSV).
- Un média utilisé ne peut pas être supprimé.

## Performance (1.11.3)

- En production : `php artisan optimize` (config + routes + vues + events).
- Assets versionnés par Vite, compression Brotli/Gzip côté Nginx.
- Objectif : pages clés < 200–300 Ko hors polices, < 3 s en 3G simulée (à vérifier avec Lighthouse avant mise en production).
