<?php

use App\Enums\ListeningRequestStatus;
use App\Livewire\AdminListeningFiche;
use App\Livewire\AdminListeningList;
use App\Livewire\TherapistDashboard;
use App\Mail\ListeningNotification;
use App\Models\Charter;
use App\Models\CharterSignature;
use App\Models\ListeningRequest;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Spatie\Activitylog\Models\Activity;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
    $this->seed(RolesAndPermissionsSeeder::class);
    Mail::fake();
});

/** Compte interne avec rôle et 2FA confirmée (thérapeute, admin). */
function compteEcoute(string $role): User
{
    $user = User::factory()->create();
    $user->assignRole($role);
    $user->forceFill(['two_factor_secret' => encrypt('SECRET'), 'two_factor_confirmed_at' => now()])->save();

    return $user;
}

/** Charte d'écoute publiée (version en vigueur). */
function charteEcoute(): Charter
{
    return Charter::create(['type' => Charter::LISTENING, 'version' => 1, 'body' => 'Texte de la charte', 'published_at' => now()]);
}

/** Consultant ayant signé la charte en vigueur. */
function consultantSignataire(): User
{
    $charter = Charter::current(Charter::LISTENING) ?? charteEcoute();
    $user = User::factory()->create();
    $user->assignRole('consultant');
    CharterSignature::create(['user_id' => $user->id, 'charter_id' => $charter->id, 'signed_at' => now(), 'ip' => '127.0.0.1', 'user_agent' => 'test']);

    return $user;
}

// ----------------------------------------------------- verrou charte

it('empêche de créer une demande sans charte signée', function () {
    charteEcoute();
    $consultant = User::factory()->create();
    $consultant->assignRole('consultant');

    // Le middleware redirige vers la signature de la charte.
    $this->actingAs($consultant)->get('/espace/ecoute')->assertRedirect(route('espace.charte'));

    // Et la policy refuse la création (défense en profondeur).
    expect($consultant->can('create', ListeningRequest::class))->toBeFalse();

    $this->actingAs($consultant)->post('/espace/ecoute', ['description' => 'Besoin de parler à quelqu\'un.'])
        ->assertRedirect(route('espace.charte'));

    expect(ListeningRequest::count())->toBe(0);
});

it('permet de créer une demande une fois la charte signée', function () {
    $consultant = consultantSignataire();

    $this->actingAs($consultant)->get('/espace/ecoute')->assertOk()->assertSee('Être écouté');

    $this->actingAs($consultant)->post('/espace/ecoute', [
        'description' => 'Je traverse une période difficile et je souhaite être écouté.',
        'urgency' => 'moyenne', 'preferred_channel' => 'whatsapp',
    ])->assertRedirect();

    $demande = ListeningRequest::first();
    expect($demande)->not->toBeNull()
        ->and($demande->consultant_id)->toBe($consultant->id)
        ->and($demande->status)->toBe(ListeningRequestStatus::Envoyee)
        ->and($demande->urgency)->toBe('moyenne');

    // L'Admin est notifié, sans le contenu de la demande.
    Mail::assertQueued(ListeningNotification::class, fn ($m) => $m->type === 'received');
});

// ----------------------------------------------------- chiffrement

it('chiffre la description en base', function () {
    $consultant = consultantSignataire();
    $secret = 'Information très personnelle et confidentielle.';

    $this->actingAs($consultant)->post('/espace/ecoute', ['description' => $secret])->assertRedirect();

    // En SQL brut, la description ne doit pas apparaître en clair.
    $brut = DB::table('listening_requests')->value('description');
    expect($brut)->not->toContain($secret)->not->toBe($secret);

    // Mais le modèle la déchiffre correctement.
    expect(ListeningRequest::first()->description)->toBe($secret);
});

// -------------------------------------------------- cloisonnement

it('ne laisse un consultant voir que ses propres demandes', function () {
    charteEcoute();
    $a = consultantSignataire();
    $b = consultantSignataire();

    $sienne = ListeningRequest::create(['consultant_id' => $a->id, 'description' => 'A']);
    $autre = ListeningRequest::create(['consultant_id' => $b->id, 'description' => 'B']);

    $this->actingAs($a)->get(route('espace.ecoute.show', $sienne))->assertOk();
    $this->actingAs($a)->get(route('espace.ecoute.show', $autre))->assertForbidden();

    expect($a->can('view', $sienne))->toBeTrue()->and($a->can('view', $autre))->toBeFalse();
});

it('refuse l\'espace écoute de l\'Admin aux rôles non autorisés', function () {
    charteEcoute();
    $demande = ListeningRequest::create(['consultant_id' => consultantSignataire()->id, 'description' => 'X']);

    $this->get(route('admin.ecoute'))->assertRedirect('/login');
    $this->actingAs(consultantSignataire())->get(route('admin.ecoute'))->assertForbidden();
    $this->actingAs(compteEcoute('therapeute'))->get(route('admin.ecoute'))->assertForbidden();
    $this->actingAs(compteEcoute('comite'))->get(route('admin.ecoute'))->assertForbidden();
    $this->actingAs(compteEcoute('admin'))->get(route('admin.ecoute'))->assertOk();

    // Un thérapeute non affecté ne voit pas la fiche.
    $this->actingAs(compteEcoute('therapeute'))->get(route('admin.ecoute.show', $demande))->assertForbidden();
});

// ----------------------------------------------------- journalisation

it('journalise chaque ouverture de fiche par l\'Admin', function () {
    charteEcoute();
    $admin = compteEcoute('admin');
    $demande = ListeningRequest::create(['consultant_id' => consultantSignataire()->id, 'description' => 'X']);

    $this->actingAs($admin)->get(route('admin.ecoute.show', $demande))->assertOk();

    $activite = Activity::where('subject_type', ListeningRequest::class)
        ->where('subject_id', $demande->id)->where('event', 'viewed')->first();

    expect($activite)->not->toBeNull()
        ->and($activite->causer_id)->toBe($admin->id)
        // Jamais de contenu chiffré dans le journal.
        ->and(json_encode($activite->properties))->not->toContain('X');
});

// -------------------------------------------- affectation manuelle

it('affecte manuellement une demande à un thérapeute validé et notifie sans contenu', function () {
    charteEcoute();
    $admin = compteEcoute('admin');
    $therapeute = compteEcoute('therapeute');
    $demande = ListeningRequest::create(['consultant_id' => consultantSignataire()->id, 'description' => 'Contenu secret']);

    Livewire::actingAs($admin)->test(AdminListeningFiche::class, ['demande' => $demande])
        ->set('therapistId', $therapeute->id)->call('assign')->assertHasNoErrors();

    $demande->refresh();
    expect($demande->status)->toBe(ListeningRequestStatus::Affectee)
        ->and($demande->assigned_therapist_id)->toBe($therapeute->id)
        ->and($demande->assigned_by)->toBe($admin->id)
        ->and($demande->assigned_at)->not->toBeNull();

    Mail::assertQueued(ListeningNotification::class, fn ($m) => $m->type === 'assigned_therapist');
    Mail::assertQueued(ListeningNotification::class, fn ($m) => $m->type === 'assigned_consultant');
});

it('n\'affiche comme affectables que les thérapeutes avec 2FA', function () {
    charteEcoute();
    $avec2fa = compteEcoute('therapeute');
    $sans2fa = User::factory()->create();
    $sans2fa->assignRole('therapeute');
    $demande = ListeningRequest::create(['consultant_id' => consultantSignataire()->id, 'description' => 'X']);

    $component = Livewire::actingAs(compteEcoute('admin'))->test(AdminListeningFiche::class, ['demande' => $demande]);

    expect($component->instance()->therapists->pluck('id')->all())->toContain($avec2fa->id)->not->toContain($sans2fa->id);
});

// ----------------------------------------- parcours thérapeute

it('laisse le thérapeute accepter puis clôturer', function () {
    charteEcoute();
    $therapeute = compteEcoute('therapeute');
    $demande = ListeningRequest::create([
        'consultant_id' => consultantSignataire()->id, 'description' => 'X',
        'assigned_therapist_id' => $therapeute->id, 'assigned_at' => now(), 'status' => ListeningRequestStatus::Affectee,
    ]);

    Livewire::actingAs($therapeute)->test(TherapistDashboard::class)
        ->call('accept', $demande->id);
    expect($demande->fresh()->status)->toBe(ListeningRequestStatus::EnCours);

    Livewire::actingAs($therapeute)->test(TherapistDashboard::class)
        ->call('close', $demande->id);
    expect($demande->fresh()->status)->toBe(ListeningRequestStatus::Cloturee)
        ->and($demande->fresh()->closed_at)->not->toBeNull();

    Mail::assertQueued(ListeningNotification::class, fn ($m) => $m->type === 'closed_consultant');
});

it('laisse le thérapeute décliner avec un motif obligatoire, ce qui renvoie au bureau', function () {
    charteEcoute();
    $therapeute = compteEcoute('therapeute');
    $demande = ListeningRequest::create([
        'consultant_id' => consultantSignataire()->id, 'description' => 'X',
        'assigned_therapist_id' => $therapeute->id, 'assigned_at' => now(), 'status' => ListeningRequestStatus::Affectee,
    ]);

    $component = Livewire::actingAs($therapeute)->test(TherapistDashboard::class)
        ->call('openReason', $demande->id)->call('release')->assertHasErrors('reason');

    $component->set('reason', 'Indisponible ce mois-ci')->call('release')->assertHasNoErrors();

    $demande->refresh();
    expect($demande->status)->toBe(ListeningRequestStatus::EnCoursAffectation)
        ->and($demande->assigned_therapist_id)->toBeNull()
        ->and($demande->decline_reason)->toBe('Indisponible ce mois-ci');

    Mail::assertQueued(ListeningNotification::class, fn ($m) => $m->type === 'declined_admin');
});

it('empêche un thérapeute d\'agir sur une demande qui n\'est pas la sienne', function () {
    charteEcoute();
    $autre = compteEcoute('therapeute');
    $demande = ListeningRequest::create([
        'consultant_id' => consultantSignataire()->id, 'description' => 'X',
        'assigned_therapist_id' => compteEcoute('therapeute')->id, 'status' => ListeningRequestStatus::Affectee,
    ]);

    Livewire::actingAs($autre)->test(TherapistDashboard::class)->call('accept', $demande->id)->assertForbidden();
});

// ----------------------------------------------------- statistiques

it('calcule des statistiques anonymisées', function () {
    charteEcoute();
    $consultant = consultantSignataire();
    ListeningRequest::create(['consultant_id' => $consultant->id, 'description' => 'A']);
    ListeningRequest::create(['consultant_id' => $consultant->id, 'description' => 'B', 'status' => ListeningRequestStatus::Cloturee, 'assigned_at' => now()]);

    $stats = Livewire::actingAs(compteEcoute('admin'))->test(AdminListeningList::class)->instance()->stats;

    expect($stats['total'])->toBe(2)->and($stats['cloturees'])->toBe(1)->and($stats['en_attente'])->toBe(1);
});
