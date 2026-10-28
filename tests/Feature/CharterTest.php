<?php

use App\Models\Charter;
use App\Models\CharterSignature;
use App\Models\User;
use Database\Seeders\CharterSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
    $this->seed([RolesAndPermissionsSeeder::class, CharterSeeder::class]);

    // Route type d'une demande d'écoute (le module arrive à l'étape 1.5) : protégée par le verrou.
    Route::middleware(['web', 'auth', 'charter.listening'])->post('/__demande-ecoute', fn () => 'créée');
});

function consultant(): User
{
    return tap(User::factory()->create(), fn ($u) => $u->assignRole('consultant'));
}

// ---------------------------------------------------------- verrou

it('empêche toute demande d\'écoute sans charte signée', function () {
    $this->actingAs(consultant())->post('/__demande-ecoute')->assertRedirect('/espace/charte');
});

it('refuse en 403 une requête JSON sans charte signée', function () {
    $this->actingAs(consultant())->postJson('/__demande-ecoute')->assertForbidden();
});

it('autorise la demande une fois la charte signée', function () {
    $user = consultant();
    $this->actingAs($user)->post('/espace/charte', ['charter_id' => Charter::current('listening')->id, 'charte' => '1']);

    $this->post('/__demande-ecoute')->assertOk()->assertSee('créée');
});

it('applique aussi le verrou dans la policy (défense en profondeur)', function () {
    $user = consultant();

    expect($user->can('request-listening'))->toBeFalse();

    $this->actingAs($user)->post('/espace/charte', ['charter_id' => Charter::current('listening')->id, 'charte' => '1']);

    expect($user->fresh()->can('request-listening'))->toBeTrue();
});

it('ne donne pas accès à l\'écoute à un rôle sans permission, même avec une signature', function () {
    $therapeute = tap(User::factory()->create(), fn ($u) => $u->assignRole('therapeute'));

    expect($therapeute->can('request-listening'))->toBeFalse();
});

// -------------------------------------------------------- signature

it('enregistre toutes les preuves de la signature', function () {
    $user = consultant();
    $charter = Charter::current('listening');

    $this->actingAs($user)
        ->withHeader('User-Agent', 'NavigateurTest/1.0')
        ->post('/espace/charte', ['charter_id' => $charter->id, 'charte' => '1'])
        ->assertRedirect('/espace/ecoute');

    $signature = CharterSignature::firstWhere('user_id', $user->id);

    expect($signature->charter_id)->toBe($charter->id)
        ->and($signature->signed_at)->not->toBeNull()
        ->and($signature->ip)->toBe('127.0.0.1')
        ->and($signature->user_agent)->toBe('NavigateurTest/1.0');
});

it('exige de cocher la case', function () {
    $user = consultant();

    $this->actingAs($user)->post('/espace/charte', ['charter_id' => Charter::current('listening')->id])
        ->assertSessionHasErrors('charte');

    expect(CharterSignature::count())->toBe(0);
});

it('ne signe qu\'une fois par version', function () {
    $user = consultant();
    $id = Charter::current('listening')->id;

    $this->actingAs($user)->post('/espace/charte', ['charter_id' => $id, 'charte' => '1']);
    $this->post('/espace/charte', ['charter_id' => $id, 'charte' => '1']);

    expect(CharterSignature::count())->toBe(1);
});

it('réserve la signature aux consultants', function () {
    $therapeute = tap(User::factory()->create(), fn ($u) => $u->assignRole('therapeute'));

    $this->actingAs($therapeute->forceFill([
        'two_factor_secret' => encrypt('X'), 'two_factor_confirmed_at' => now(),
    ]))->post('/espace/charte', ['charter_id' => Charter::current('listening')->id, 'charte' => '1'])->assertForbidden();
});

it('renvoie vers la page d\'origine après la signature', function () {
    $user = consultant();

    $this->actingAs($user)->withSession(['charte_requise' => url('/__demande-ecoute')])
        ->post('/espace/charte', ['charter_id' => Charter::current('listening')->id, 'charte' => '1'])
        ->assertRedirect(url('/__demande-ecoute'));
});

it('redirige l\'onglet Écoute vers la charte tant qu\'elle n\'est pas signée', function () {
    $this->actingAs(consultant())->get('/espace/ecoute')->assertRedirect('/espace/charte');
});

// -------------------------------------------------------- versionnage

it('exige une nouvelle signature quand une nouvelle version est publiée', function () {
    $user = consultant();
    $this->actingAs($user)->post('/espace/charte', ['charter_id' => Charter::current('listening')->id, 'charte' => '1']);
    expect($user->fresh()->hasSignedCurrentCharter())->toBeTrue();

    $v2 = Charter::publish('listening', '1.1', '<p>Nouvelle version</p>');

    expect($user->fresh()->hasSignedCurrentCharter())->toBeFalse();
    $this->post('/__demande-ecoute')->assertRedirect('/espace/charte');

    $this->get('/espace/charte')->assertOk()->assertSee('Version 1.1');
    $this->post('/espace/charte', ['charter_id' => $v2->id, 'charte' => '1']);

    expect($user->fresh()->hasSignedCurrentCharter())->toBeTrue()
        ->and(CharterSignature::where('user_id', $user->id)->count())->toBe(2); // l'historique est conservé
});

it('refuse de signer une version dépassée', function () {
    $user = consultant();
    $v1 = Charter::current('listening');
    Charter::publish('listening', '1.1', '<p>v2</p>');

    $this->actingAs($user)->post('/espace/charte', ['charter_id' => $v1->id, 'charte' => '1'])->assertSessionHasErrors('charte');

    expect(CharterSignature::count())->toBe(0);
});

it('rend une version publiée immuable', function () {
    $charter = Charter::current('listening');

    expect(fn () => $charter->update(['body' => 'modifié']))->toThrow(LogicException::class)
        ->and(fn () => $charter->delete())->toThrow(LogicException::class);
});

it('ne permet pas de modifier une signature', function () {
    $user = consultant();
    $this->actingAs($user)->post('/espace/charte', ['charter_id' => Charter::current('listening')->id, 'charte' => '1']);

    expect(fn () => CharterSignature::first()->update(['ip' => '1.2.3.4']))->toThrow(LogicException::class);
});

it('publie une version par la commande artisan, une seule fois', function () {
    $fichier = tempnam(sys_get_temp_dir(), 'charte');
    file_put_contents($fichier, '<p>Texte définitif</p>');

    $this->artisan('charters:publish', ['type' => 'listening', 'version' => '2.0', 'file' => $fichier])->assertSuccessful();
    expect(Charter::current('listening')->version)->toBe('2.0');

    $this->artisan('charters:publish', ['type' => 'listening', 'version' => '2.0', 'file' => $fichier])->assertFailed();
});

// --------------------------------------------------------- affichage

it('affiche la charte, le PDF et le profil', function () {
    $user = consultant();

    $this->actingAs($user)->get('/espace/charte')->assertOk()
        ->assertSee('La charte d\'écoute')->assertSee('Version 1.0')->assertSee('Signer la charte')
        ->assertSee('Notes de séance');

    $pdf = $this->get('/espace/charte/pdf');
    $pdf->assertOk();
    expect($pdf->headers->get('content-type'))->toContain('application/pdf');

    $this->get('/espace/profil')->assertSee('À signer (version 1.0)');

    $this->post('/espace/charte', ['charter_id' => Charter::current('listening')->id, 'charte' => '1']);

    $this->get('/espace/profil')->assertSee('version 1.0')->assertSee('Signée le');
    $this->get('/espace/charte')->assertSee('Vous avez signé cette charte');
});

it('affiche le texte de la charte au public sans signature', function () {
    $this->get('/charte')->assertOk()->assertSee('Confidentialité');
});

it('protège la page de signature', function () {
    $this->get('/espace/charte')->assertRedirect('/login');
});
