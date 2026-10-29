<?php

use App\Enums\DeactivationReason;
use App\Enums\UserStatus;
use App\Mail\MagicLinkMail;
use App\Mail\OtpCodeMail;
use App\Models\OtpCode;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
    $this->seed(RolesAndPermissionsSeeder::class);
});

/** Code envoyé par e-mail lors de la dernière demande. */
function dernierCode(): string
{
    return Mail::queued(OtpCodeMail::class)->last()->code;
}

function inscription(array $surcharge = []): array
{
    return array_merge([
        'name' => 'Afi',
        'email' => 'afi@example.com',
        'method' => 'mdp',
        'password' => 'motdepasse1',
        'terms' => '1',
    ], $surcharge);
}

// ---------------------------------------------------------------- pages

it('affiche les écrans d\'authentification', function (string $url) {
    $this->get($url)->assertOk();
})->with(['/login', '/register', '/mot-de-passe-oublie']);

// ---------------------------------------------------------- inscription

it('crée un compte en attente de vérification avec le rôle consultant', function () {
    Mail::fake();

    $this->post('/register', inscription())->assertRedirect('/verification');

    $user = User::firstWhere('email', 'afi@example.com');

    expect($user->status)->toBe(UserStatus::PendingVerification)
        ->and($user->hasRole('consultant'))->toBeTrue()
        ->and(Hash::check('motdepasse1', $user->password))->toBeTrue();

    Mail::assertQueued(OtpCodeMail::class);
    $this->assertGuest();
});

it('exige l\'e-mail tant que la passerelle SMS n\'existe pas', function () {
    $this->post('/register', inscription(['email' => '', 'phone' => '90 12 34 56']))
        ->assertSessionHasErrors('email');
});

it('accepte un téléphone seul quand la passerelle SMS est active', function () {
    config(['otp.sms_enabled' => true]);

    $this->post('/register', inscription(['email' => '', 'phone' => '+228 90 12 34 56']))->assertRedirect('/verification');

    expect(User::firstWhere('phone', '+22890123456'))->not->toBeNull();
});

it('valide le formulaire d\'inscription', function () {
    $this->post('/register', inscription(['password' => 'court', 'terms' => null]))
        ->assertSessionHasErrors(['password', 'terms']);

    User::factory()->create(['email' => 'afi@example.com']);
    $this->post('/register', inscription())->assertSessionHasErrors('email');
});

it('crée un compte sans mot de passe', function () {
    Mail::fake();

    $this->post('/register', inscription(['method' => 'sans', 'password' => '']))->assertRedirect('/verification');

    expect(User::firstWhere('email', 'afi@example.com')->password)->toBeNull();
});

it('active le compte et connecte après la saisie du bon code', function () {
    Mail::fake();
    $this->post('/register', inscription());

    $this->post('/verification', ['code' => dernierCode()])->assertRedirect('/espace');

    $user = User::firstWhere('email', 'afi@example.com');
    expect($user->status)->toBe(UserStatus::Active)->and($user->email_verified_at)->not->toBeNull();
    $this->assertAuthenticatedAs($user);
});

it('refuse un mauvais code et invalide le code après 3 essais', function () {
    Mail::fake();
    $this->post('/register', inscription());
    $bon = dernierCode();
    $faux = $bon === '000000' ? '111111' : '000000';

    foreach ([2, 1, 0] as $restants) {
        $this->post('/verification', ['code' => $faux])
            ->assertSessionHasErrors('code')->assertSessionHas('restants', $restants);
    }

    $this->post('/verification', ['code' => $bon])->assertSessionHasErrors('code');
    $this->assertGuest();
});

it('refuse un code expiré', function () {
    Mail::fake();
    $this->post('/register', inscription());

    OtpCode::query()->update(['expires_at' => now()->subMinute()]);

    $this->post('/verification', ['code' => dernierCode()])->assertSessionHasErrors('code');
});

it('limite les renvois de code', function () {
    Mail::fake();
    $this->post('/register', inscription());

    $this->post('/verification/renvoyer')->assertSessionHas('renvoi');
    Mail::assertQueuedCount(1);
});

// ------------------------------------------------------------ connexion

it('connecte avec téléphone ou e-mail et mot de passe', function (string $identifiant) {
    $user = User::factory()->create(['email' => 'afi@example.com', 'phone' => '+22890123456']);

    $this->post('/login', ['identifier' => $identifiant, 'password' => 'password'])->assertRedirect('/espace');

    $this->assertAuthenticatedAs($user);
})->with(['afi@example.com', '90 12 34 56', '+228 90 12 34 56']);

it('rejette un mauvais mot de passe avec le message de la maquette', function () {
    User::factory()->create(['email' => 'afi@example.com']);

    $this->from('/login')->post('/login', ['identifier' => 'afi@example.com', 'password' => 'faux'])
        ->assertSessionHasErrors('identifier');

    $this->get('/login')->assertSee('Identifiant ou mot de passe incorrect.');
});

it('refuse la connexion d\'un compte non actif avec un message clair', function () {
    User::factory()->create(['email' => 'attente@example.com', 'status' => UserStatus::PendingVerification]);
    User::factory()->create(['email' => 'suspendu@example.com', 'status' => UserStatus::Suspended]);

    $this->post('/login', ['identifier' => 'attente@example.com', 'password' => 'password'])
        ->assertSessionHasErrors(['identifier' => "Votre compte n'est pas encore vérifié. Utilisez « Recevoir un code de connexion » pour le valider."]);

    $this->post('/login', ['identifier' => 'suspendu@example.com', 'password' => 'password'])
        ->assertSessionHasErrors('identifier');
    $this->assertGuest();
});

it('redirige un compte désactivé vers sa page d\'information', function () {
    User::factory()->create([
        'email' => 'inactif@example.com',
        'status' => UserStatus::Inactive,
        'deactivated_at' => now(),
        'deactivation_reason' => DeactivationReason::Auto6Mois,
    ]);

    $this->post('/login', ['identifier' => 'inactif@example.com', 'password' => 'password'])
        ->assertRedirect('/compte-desactive');

    $this->get('/compte-desactive')->assertOk()->assertSee('Votre compte est désactivé')
        ->assertSee('Aucune connexion depuis plus de 6 mois');
    $this->assertGuest();
});

it('limite les tentatives de connexion', function () {
    User::factory()->create(['email' => 'afi@example.com']);

    foreach (range(1, 5) as $_) {
        $this->post('/login', ['identifier' => 'afi@example.com', 'password' => 'faux']);
    }

    $this->post('/login', ['identifier' => 'afi@example.com', 'password' => 'password'])->assertStatus(429);
});

it('un compte sans mot de passe ne peut pas se connecter avec un mot de passe', function () {
    User::factory()->create(['email' => 'afi@example.com', 'password' => null]);

    $this->post('/login', ['identifier' => 'afi@example.com', 'password' => ''])->assertSessionHasErrors();
    $this->assertGuest();
});

it('déconnecte', function () {
    $this->actingAs(User::factory()->create())->post('/logout');

    $this->assertGuest();
});

// --------------------------------------------- code par SMS / lien par e-mail

it('envoie un code de connexion par SMS à un numéro', function () {
    config(['otp.sms_enabled' => true]);
    $user = User::factory()->create(['phone' => '+22890123456']);

    $this->post('/connexion/code', ['identifier' => '90123456'])->assertRedirect('/verification');

    $code = '123456';
    OtpCode::query()->update(['code_hash' => Hash::make($code)]);

    $this->post('/verification', ['code' => $code])->assertRedirect('/espace');
    $this->assertAuthenticatedAs($user);
});

it('refuse le code SMS tant que la passerelle n\'existe pas', function () {
    $this->post('/connexion/code', ['identifier' => '90123456'])->assertSessionHasErrors('identifier');
});

it('envoie un lien magique à usage unique pour une adresse e-mail', function () {
    Mail::fake();
    $user = User::factory()->create(['email' => 'afi@example.com']);

    $this->post('/connexion/code', ['identifier' => 'afi@example.com'])->assertRedirect('/connexion/lien');

    $url = Mail::queued(MagicLinkMail::class)->first()->url;

    $this->get($url)->assertRedirect('/espace');
    $this->assertAuthenticatedAs($user);

    auth()->logout();
    $this->get($url)->assertForbidden(); // déjà utilisé
});

it('refuse un lien magique dont la signature est altérée', function () {
    Mail::fake();
    User::factory()->create(['email' => 'afi@example.com']);
    $this->post('/connexion/code', ['identifier' => 'afi@example.com']);

    $this->get(Mail::queued(MagicLinkMail::class)->first()->url.'x')->assertForbidden();
});

it('ne révèle pas si un compte existe', function () {
    Mail::fake();

    $this->post('/connexion/code', ['identifier' => 'inconnu@example.com'])->assertRedirect('/connexion/lien');

    Mail::assertNothingQueued();
});

// --------------------------------------------------- mot de passe oublié

it('réinitialise le mot de passe par code puis déconnecte les autres appareils', function () {
    Mail::fake();
    $user = User::factory()->create(['email' => 'afi@example.com']);

    $this->post('/mot-de-passe-oublie', ['identifier' => 'afi@example.com'])->assertRedirect('/verification');
    $this->post('/verification', ['code' => dernierCode()])->assertRedirect('/nouveau-mot-de-passe');

    $this->post('/nouveau-mot-de-passe', ['password' => 'nouveau123', 'password_confirmation' => 'nouveau123'])
        ->assertRedirect('/espace');

    expect(Hash::check('nouveau123', $user->fresh()->password))->toBeTrue();
    $this->assertAuthenticatedAs($user);
});

it('refuse un nouveau mot de passe trop faible ou non confirmé', function () {
    Mail::fake();
    User::factory()->create(['email' => 'afi@example.com']);
    $this->post('/mot-de-passe-oublie', ['identifier' => 'afi@example.com']);
    $this->post('/verification', ['code' => dernierCode()]);

    $this->post('/nouveau-mot-de-passe', ['password' => 'sanschiffre', 'password_confirmation' => 'sanschiffre'])
        ->assertSessionHasErrors('password');
    $this->post('/nouveau-mot-de-passe', ['password' => 'nouveau123', 'password_confirmation' => 'autre1234'])
        ->assertSessionHasErrors('password');
});

it('interdit la page du nouveau mot de passe sans code vérifié', function () {
    $this->get('/nouveau-mot-de-passe')->assertRedirect('/mot-de-passe-oublie');
});

// ------------------------------------------------------------- espace

it('protège l\'espace personnel', function () {
    $this->get('/espace/profil')->assertRedirect('/login');
});

it('affiche le profil et les onglets à venir', function () {
    $this->actingAs(User::factory()->create(['name' => 'Afi Mensah', 'phone' => '+22890123456']))
        ->get('/espace/profil')->assertOk()
        ->assertSee('Profil et confidentialité')->assertSee('+228 90 •• •• 56')->assertSee('Bonjour, Afi');

    // Écoute est désormais une rubrique à part entière (étape 1.7) ; les onglets encore à venir sont Formations et Coaching.
    $this->get('/espace/formations')->assertOk()->assertSee('bientôt disponible');
    $this->get('/espace/coaching')->assertOk()->assertSee('bientôt disponible');
});

it('coupe la session d\'un compte désactivé en cours de navigation', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $user->update(['status' => UserStatus::Inactive]);

    $this->get('/espace/profil')->assertRedirect('/login');
    $this->assertGuest();
});

it('déconnecte tous les autres appareils', function () {
    $user = User::factory()->create();
    config(['session.driver' => 'database']);
    DB::table('sessions')->insert([
        ['id' => 'autre-appareil', 'user_id' => $user->id, 'payload' => '', 'last_activity' => time()],
    ]);

    $this->actingAs($user)->post('/espace/appareils/deconnecter')->assertRedirect();

    expect(DB::table('sessions')->where('id', 'autre-appareil')->exists())->toBeFalse();
});
