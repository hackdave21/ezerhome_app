<?php

use App\Jobs\ExportUserData;
use App\Mail\DataExportReady;
use App\Mail\OtpCodeMail;
use App\Models\DataExport;
use App\Models\DeletionRequest;
use App\Models\MediaAsset;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Laravel\Fortify\Fortify;
use PragmaRX\Google2FA\Google2FA;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
    $this->seed(RolesAndPermissionsSeeder::class);
});

function avecRole(string $role, array $attrs = []): User
{
    $user = User::factory()->create($attrs);
    $user->assignRole($role);

    return $user;
}

/** Active et confirme la 2FA d'un compte ; retourne le secret en clair. */
function activer2fa(User $user): string
{
    test()->actingAs($user)->get('/double-authentification')->assertOk();
    $secret = Fortify::currentEncrypter()->decrypt($user->fresh()->two_factor_secret);

    test()->post('/double-authentification', ['code' => (new Google2FA)->getCurrentOtp($secret)])
        ->assertRedirect('/double-authentification/codes');

    return $secret;
}

/** Code de la fenêtre suivante : Fortify refuse de rejouer le code déjà utilisé à l'activation. */
function codeSuivant(string $secret): string
{
    $g = new Google2FA;

    return $g->oathTotp($secret, $g->getTimestamp() + 1);
}

// ----------------------------------------------------- rôles et permissions

it('crée les six rôles', function () {
    expect(Role::pluck('name')->sort()->values()->all())
        ->toBe(['admin', 'comite', 'consultant', 'formateur_coach', 'super_admin', 'therapeute']);
});

it('applique la matrice des permissions par rôle', function (string $role, string $permission, bool $attendu) {
    expect(avecRole($role)->can($permission))->toBe($attendu);
})->with([
    // Demandes d'assistance : comité et admin seulement
    ['comite', 'assistance.view', true],
    ['admin', 'assistance.update-status', true],
    ['consultant', 'assistance.view', false],
    ['therapeute', 'assistance.update-status', false],
    ['formateur_coach', 'assistance.view', false],
    // Médiathèque
    ['admin', 'media.manage', true],
    ['comite', 'media.manage', false],
    ['comite', 'media.manage-assistance', true],
    ['consultant', 'media.manage-assistance', false],
    // Organigramme, paiements, dons, audit : admin seulement
    ['admin', 'org.manage', true],
    ['comite', 'org.manage', false],
    ['admin', 'payments.confirm-manual', true],
    ['comite', 'payments.confirm-manual', false],
    ['admin', 'donations.view', true],
    ['consultant', 'donations.view', false],
    ['admin', 'audit.view', true],
    ['therapeute', 'audit.view', false],
    // Écoute
    ['consultant', 'listening.request', true],
    ['consultant', 'charter.sign', true],
    ['therapeute', 'listening.request', false],
    ['admin', 'listening.request', false],
    ['therapeute', 'listening.view-assigned', true],
    ['consultant', 'listening.view-assigned', false],
    // Réunions et notes
    ['therapeute', 'meetings.notes', true],
    ['formateur_coach', 'meetings.schedule', true],
    ['consultant', 'meetings.notes', false],
    ['admin', 'meetings.notes', false],
    ['admin', 'meetings.notes.read-closed', true],
    // Administration
    ['admin', 'users.manage', true],
    ['admin', 'accounts.deactivate', true],
    ['admin', 'applications.review', true],
    ['admin', 'assignments.manage', true],
    ['comite', 'applications.review', false],
    ['consultant', 'users.manage', false],
    // Profil : tous les rôles
    ['consultant', 'profile.manage', true],
    ['comite', 'profile.manage', true],
]);

it('le super-admin technique n\'a aucun accès aux contenus sensibles', function (string $permission) {
    expect(avecRole('super_admin')->can($permission))->toBeFalse();
})->with(['assistance.view', 'listening.view-any', 'listening.view-own', 'meetings.notes.read-closed', 'meetings.notes', 'donations.view', 'media.manage']);

it('cumule les rôles', function () {
    $user = avecRole('consultant');
    $user->assignRole('therapeute');

    expect($user->can('listening.request'))->toBeTrue()->and($user->can('meetings.notes'))->toBeTrue();
});

// ------------------------------------------------------------- policies

it('applique UserPolicy', function () {
    $consultant = avecRole('consultant');
    $autre = avecRole('consultant');
    $admin = avecRole('admin');

    expect($consultant->can('update', $consultant))->toBeTrue()
        ->and($consultant->can('view', $autre))->toBeFalse()
        ->and($consultant->can('viewAny', User::class))->toBeFalse()
        ->and($consultant->can('deactivate', $autre))->toBeFalse()
        ->and($admin->can('view', $autre))->toBeTrue()
        ->and($admin->can('deactivate', $autre))->toBeTrue()
        ->and($admin->can('deactivate', $admin))->toBeFalse();
});

it('applique MediaAssetPolicy : le comité ne gère que la catégorie assistance', function () {
    $assistance = new MediaAsset(['category' => 'assistance']);
    $autres = new MediaAsset(['category' => 'logo_partenaire']);

    $comite = avecRole('comite');
    $admin = avecRole('admin');
    $consultant = avecRole('consultant');

    expect($admin->can('update', $autres))->toBeTrue()
        ->and($comite->can('update', $assistance))->toBeTrue()
        ->and($comite->can('update', $autres))->toBeFalse()
        ->and($consultant->can('viewAny', MediaAsset::class))->toBeFalse()
        ->and($consultant->can('delete', $assistance))->toBeFalse();
});

// ------------------------------------------------------------------ 2FA

it('exige la 2FA pour thérapeute, formateur/coach, comité et admin', function (string $role) {
    $this->actingAs(avecRole($role))->get('/espace/profil')->assertRedirect('/double-authentification');
})->with(['therapeute', 'formateur_coach', 'comite', 'admin']);

it('n\'exige pas la 2FA d\'un consultant', function () {
    $this->actingAs(avecRole('consultant'))->get('/espace/profil')->assertOk();
});

it('active la 2FA avec un code valide et affiche les codes de secours une seule fois', function () {
    $user = avecRole('admin');

    $this->actingAs($user)->get('/double-authentification')->assertOk()->assertSee('Scannez ce QR code');
    $secret = Fortify::currentEncrypter()->decrypt($user->fresh()->two_factor_secret);

    $this->post('/double-authentification', ['code' => '000000'])->assertSessionHasErrors('code');

    $this->post('/double-authentification', ['code' => (new Google2FA)->getCurrentOtp($secret)])
        ->assertRedirect('/double-authentification/codes');

    expect($user->fresh()->hasConfirmedTwoFactor())->toBeTrue();

    $this->get('/double-authentification/codes')->assertOk()->assertSee('Double authentification activée');
    $this->get('/double-authentification/codes')->assertRedirect('/espace/profil'); // plus affichés

    $this->get('/espace/profil')->assertOk();
});

it('demande le code 2FA à la connexion, puis laisse passer', function () {
    $user = avecRole('consultant', ['email' => 'afi@example.com']);
    $secret = activer2fa($user);
    auth()->logout();

    $this->post('/login', ['identifier' => 'afi@example.com', 'password' => 'password'])->assertRedirect('/two-factor-challenge');
    $this->assertGuest();

    $this->get('/two-factor-challenge')->assertOk()->assertSee('Vérification en deux étapes');

    $this->post('/two-factor-challenge', ['code' => codeSuivant($secret)])->assertRedirect('/espace');
    $this->assertAuthenticatedAs($user);
});

it('accepte un code de secours à usage unique', function () {
    $user = avecRole('consultant', ['email' => 'afi@example.com']);
    activer2fa($user);
    $code = $user->fresh()->recoveryCodes()[0];
    auth()->logout();

    $this->post('/login', ['identifier' => 'afi@example.com', 'password' => 'password']);
    $this->post('/two-factor-challenge', ['recovery_code' => $code])->assertRedirect('/espace');

    expect($user->fresh()->recoveryCodes())->not->toContain($code);
});

it('mémorise l\'appareil de confiance pendant 30 jours', function () {
    $user = avecRole('consultant', ['email' => 'afi@example.com']);
    $secret = activer2fa($user);
    auth()->logout();

    $this->post('/login', ['identifier' => 'afi@example.com', 'password' => 'password']);
    $reponse = $this->post('/two-factor-challenge', ['code' => codeSuivant($secret), 'trust_device' => '1']);
    $reponse->assertCookie('ezer_2fa_trusted');

    $cookie = collect($reponse->headers->getCookies())->first(fn ($c) => $c->getName() === 'ezer_2fa_trusted');
    expect($cookie->getExpiresTime())->toBeGreaterThan(now()->addDays(29)->timestamp);

    auth()->logout();
    $this->flushSession();

    // Avec le cookie, plus de défi.
    $this->withUnencryptedCookie('ezer_2fa_trusted', $cookie->getValue());
    $this->withCookie('ezer_2fa_trusted', $cookie->getValue())
        ->post('/login', ['identifier' => 'afi@example.com', 'password' => 'password'])
        ->assertRedirect('/espace');
});

it('impose la 2FA aux comptes admin même connectés par lien magique', function () {
    $user = avecRole('admin');
    activer2fa($user);
    auth()->logout();

    $url = URL::temporarySignedRoute('login.magic', now()->addMinutes(5), ['user' => $user->id, 'token' => 't']);
    Cache::put("magic.{$user->id}.t", true, 300);

    $this->get($url)->assertRedirect('/two-factor-challenge');
    $this->assertGuest();
});

it('ne permet pas de désactiver la 2FA à un rôle qui l\'impose', function () {
    $user = avecRole('admin');
    activer2fa($user);

    $this->delete('/double-authentification')->assertForbidden();

    $consultant = avecRole('consultant');
    activer2fa($consultant);
    $this->delete('/double-authentification')->assertRedirect('/espace/profil');
    expect($consultant->fresh()->hasConfirmedTwoFactor())->toBeFalse();
});

// ------------------------------------------------- export et suppression

it('prépare l\'export des données en file d\'attente', function () {
    Queue::fake();
    $user = avecRole('consultant');

    $this->actingAs($user)->post('/espace/export')->assertRedirect();

    Queue::assertPushed(ExportUserData::class);
    expect(DataExport::where('user_id', $user->id)->exists())->toBeTrue();
});

it('génère le fichier JSON, prévient sans contenu sensible et le rend téléchargeable par son propriétaire seul', function () {
    Storage::fake('local');
    Mail::fake();
    $user = avecRole('consultant', ['name' => 'Afi Mensah', 'email' => 'afi@example.com']);
    $autre = avecRole('consultant');

    $export = DataExport::create(['user_id' => $user->id]);
    (new ExportUserData($export))->handle();

    $export->refresh();
    expect($export->isDownloadable())->toBeTrue();
    Storage::disk('local')->assertExists($export->path);
    expect(json_decode(Storage::disk('local')->get($export->path), true)['profil']['nom'])->toBe('Afi Mensah');

    Mail::assertQueued(DataExportReady::class, fn ($m) => $m->hasTo('afi@example.com') && ! str_contains($m->profilUrl, 'Afi'));

    $this->actingAs($autre)->get("/espace/export/{$export->id}")->assertNotFound();
    $this->actingAs($user)->get("/espace/export/{$export->id}")->assertOk();
});

it('refuse un export expiré', function () {
    $user = avecRole('consultant');
    $export = DataExport::create(['user_id' => $user->id, 'path' => 'exports/x.json', 'ready_at' => now()->subDays(8), 'expires_at' => now()->subDay()]);

    $this->actingAs($user)->get("/espace/export/{$export->id}")->assertNotFound();
});

it('enregistre la demande de suppression après confirmation par code', function () {
    Mail::fake();
    $user = avecRole('consultant', ['email' => 'afi@example.com']);

    $this->actingAs($user)->post('/espace/suppression')->assertRedirect('/verification');
    expect(DeletionRequest::count())->toBe(0);

    $this->post('/verification', ['code' => Mail::queued(OtpCodeMail::class)->last()->code])->assertRedirect('/espace/profil');

    expect(DeletionRequest::where('user_id', $user->id)->where('status', 'pending')->exists())->toBeTrue();
    $this->get('/espace/profil')->assertSee('demande de suppression');
});
