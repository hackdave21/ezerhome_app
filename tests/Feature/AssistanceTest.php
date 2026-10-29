<?php

use App\Enums\AssistanceStatus;
use App\Livewire\ComiteAssistanceFiche;
use App\Mail\AssistanceNotification;
use App\Models\AssistanceDisbursement;
use App\Models\AssistanceEvent;
use App\Models\AssistanceRequest;
use App\Models\MediaAsset;
use App\Models\MediaUsage;
use App\Models\User;
use App\Services\Assistance\TrackingNumberGenerator;
use App\Support\Phone;
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

/** Compte interne avec rôle et 2FA confirmée. */
function compteAssistance(string $role): User
{
    $user = User::factory()->create();
    $user->assignRole($role);
    $user->forceFill(['two_factor_secret' => encrypt('SECRET'), 'two_factor_confirmed_at' => now()])->save();

    return $user;
}

/** Données valides du formulaire public. */
function champsAssistance(array $override = []): array
{
    return array_merge([
        'full_name' => 'Ama Koffi',
        'telephone' => '90112233',
        'email' => 'ama@example.test',
        'category' => 'alimentation',
        'description' => 'Nous manquons de vivres pour la famille ce mois-ci.',
        'consentement' => '1',
        'website' => '', // honeypot vide
    ], $override);
}

// ------------------------------------------------------ dépôt public

it('dépose une demande, génère un numéro de suivi, historise et notifie', function () {
    $this->post('/assistance', champsAssistance())->assertRedirect(route('assistance'))->assertSessionHas('tracking_no');

    $demande = AssistanceRequest::first();
    expect($demande)->not->toBeNull()
        ->and($demande->tracking_no)->toMatch('/^ASS-\d{4}-\d{5}$/')
        ->and($demande->status)->toBe(AssistanceStatus::Recue)
        ->and($demande->consent_at)->not->toBeNull()
        ->and($demande->phone_hash)->toBe(Phone::hash('+22890112233'));

    // Un événement « reçue » est historisé.
    expect(AssistanceEvent::where('assistance_request_id', $demande->id)->where('status', 'recue')->exists())->toBeTrue();

    // Le demandeur est notifié (numéro de suivi), sans détail sensible.
    Mail::assertQueued(AssistanceNotification::class, fn ($m) => $m->type === 'received' && $m->vars['ref'] === $demande->tracking_no);
});

it('chiffre les données personnelles en base', function () {
    $this->post('/assistance', champsAssistance(['description' => 'Secret familial à protéger.']));

    $brut = DB::table('assistance_requests')->first();
    expect($brut->full_name)->not->toContain('Ama Koffi')
        ->and($brut->phone)->not->toContain('90112233')
        ->and($brut->description)->not->toContain('Secret familial');

    $demande = AssistanceRequest::first();
    expect($demande->full_name)->toBe('Ama Koffi')->and($demande->description)->toBe('Secret familial à protéger.');
});

// ------------------------------------------------------- anti-spam

it('bloque le honeypot sans créer de demande', function () {
    $this->post('/assistance', champsAssistance(['website' => 'je-suis-un-robot']))->assertSessionHasErrors('website');

    expect(AssistanceRequest::count())->toBe(0);
});

it('exige le consentement et un téléphone valide', function () {
    $this->post('/assistance', champsAssistance(['consentement' => null]))->assertSessionHasErrors('consentement');
    $this->post('/assistance', champsAssistance(['telephone' => '123']))->assertSessionHasErrors('telephone');

    expect(AssistanceRequest::count())->toBe(0);
});

// --------------------------------------------- numéro de suivi

it('génère des numéros uniques avec compteur annuel', function () {
    $gen = app(TrackingNumberGenerator::class);

    $n1 = $gen->next(2026);
    AssistanceRequest::create(['tracking_no' => $n1, 'full_name' => 'A', 'phone' => '+22890000001', 'phone_hash' => 'h1', 'category' => 'autre', 'description' => 'x', 'consent_at' => now()]);
    $n2 = $gen->next(2026);

    expect($n1)->toBe('ASS-2026-00001')->and($n2)->toBe('ASS-2026-00002');
    expect($gen->next(2027))->toBe('ASS-2027-00001');
});

// --------------------------------------------- suivi public

it('affiche le statut avec le bon numéro et téléphone, sinon rien', function () {
    $this->post('/assistance', champsAssistance());
    $demande = AssistanceRequest::first();

    $this->post('/assistance/suivi', ['tracking_no' => $demande->tracking_no, 'telephone' => '90112233'])
        ->assertOk()->assertSee($demande->status->label());

    // Mauvais téléphone : introuvable (pas de fuite).
    $this->post('/assistance/suivi', ['tracking_no' => $demande->tracking_no, 'telephone' => '90999999'])
        ->assertOk()->assertSee('Aucune demande ne correspond');
});

// ------------------------------------------------- cloisonnement

it('réserve l\'espace assistance au Comité et à l\'Admin', function () {
    $this->post('/assistance', champsAssistance());
    $demande = AssistanceRequest::first();

    $this->get(route('comite.demandes'))->assertRedirect('/login');
    $this->actingAs(compteAssistance('consultant'))->get(route('comite.demandes'))->assertForbidden();
    $this->actingAs(compteAssistance('therapeute'))->get(route('comite.demandes'))->assertForbidden();
    $this->actingAs(compteAssistance('comite'))->get(route('comite.demandes'))->assertOk();
    $this->actingAs(compteAssistance('admin'))->get(route('comite.demandes'))->assertOk();

    // Et la fiche d'une demande est interdite aux autres rôles.
    $this->actingAs(compteAssistance('therapeute'))->get(route('comite.demandes.show', $demande))->assertForbidden();
});

it('journalise chaque ouverture de fiche, sans contenu sensible', function () {
    $this->post('/assistance', champsAssistance(['full_name' => 'Nom Secret']));
    $demande = AssistanceRequest::first();
    $comite = compteAssistance('comite');

    $this->actingAs($comite)->get(route('comite.demandes.show', $demande))->assertOk();

    $activite = Activity::where('subject_type', AssistanceRequest::class)
        ->where('subject_id', $demande->id)->where('event', 'viewed')->first();

    expect($activite)->not->toBeNull()->and($activite->causer_id)->toBe($comite->id)
        ->and(json_encode($activite->properties))->not->toContain('Nom Secret');
});

// ------------------------------------------- changement de statut

it('change le statut, historise et notifie le demandeur', function () {
    $this->post('/assistance', champsAssistance());
    $demande = AssistanceRequest::first();

    Livewire::actingAs(compteAssistance('comite'))->test(ComiteAssistanceFiche::class, ['demande' => $demande])
        ->set('newStatus', 'acceptee')->set('statusNote', 'Dossier complet')->call('changeStatus')->assertHasNoErrors();

    $demande->refresh();
    expect($demande->status)->toBe(AssistanceStatus::Acceptee)
        ->and(AssistanceEvent::where('assistance_request_id', $demande->id)->where('status', 'acceptee')->exists())->toBeTrue();

    Mail::assertQueued(AssistanceNotification::class, fn ($m) => $m->type === 'status_acceptee');
});

it('empêche un comité de changer le statut sans la permission (lecture seule impossible ici)', function () {
    $this->post('/assistance', champsAssistance());
    $demande = AssistanceRequest::first();

    // Un consultant ne peut même pas monter le composant.
    Livewire::actingAs(compteAssistance('consultant'))->test(ComiteAssistanceFiche::class, ['demande' => $demande])->assertForbidden();
});

// ------------------------------------------------ doublons

it('détecte les doublons par téléphone', function () {
    $this->post('/assistance', champsAssistance());
    $this->post('/assistance', champsAssistance(['description' => 'Deuxième demande, même numéro.']));

    $premiere = AssistanceRequest::oldest('id')->first();

    $component = Livewire::actingAs(compteAssistance('comite'))->test(ComiteAssistanceFiche::class, ['demande' => $premiere]);
    expect($component->instance()->duplicates)->toHaveCount(1);
    $component->assertSee('Doublon possible');
});

// ------------------------------------------------ décaissement

it('enregistre un décaissement', function () {
    $this->post('/assistance', champsAssistance());
    $demande = AssistanceRequest::first();

    Livewire::actingAs(compteAssistance('comite'))->test(ComiteAssistanceFiche::class, ['demande' => $demande])
        ->set('amount', 15000)->set('disbursedOn', '2026-10-15')->set('disbursementLabel', 'Vivres')
        ->call('addDisbursement')->assertHasNoErrors();

    $d = AssistanceDisbursement::first();
    expect($d)->not->toBeNull()->and($d->amount)->toBe(15000)->and($demande->fresh()->totalDisbursed())->toBe(15000);
});

// --------------------------------- pièces justificatives (protection média)

it('rattache une pièce justificative et la protège de la suppression', function () {
    $this->post('/assistance', champsAssistance());
    $demande = AssistanceRequest::first();

    $piece = MediaAsset::create(['original_name' => 'recu.pdf', 'title' => 'Reçu', 'category' => 'assistance', 'status' => 'ready', 'path' => 'r.pdf']);

    Livewire::actingAs(compteAssistance('comite'))->test(ComiteAssistanceFiche::class, ['demande' => $demande])
        ->set('attachId', $piece->id)->call('attach')->assertHasNoErrors();

    expect(MediaUsage::where('media_asset_id', $piece->id)->where('model_type', AssistanceRequest::class)->count())->toBe(1)
        ->and($demande->attachments()->count())->toBe(1);
});
