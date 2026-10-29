<?php

use App\Enums\PaymentStatus;
use App\Livewire\AdminDonationList;
use App\Mail\DonationReminder;
use App\Models\Donation;
use App\Models\Payment;
use App\Models\User;
use App\Payments\ManualGateway;
use App\Payments\PaymentManager;
use App\Services\Donations\DonationWorkflow;
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

function compteDon(string $role): User
{
    $user = User::factory()->create();
    $user->assignRole($role);
    $user->forceFill(['two_factor_secret' => encrypt('SECRET'), 'two_factor_confirmed_at' => now()])->save();

    return $user;
}

function champsDon(array $override = []): array
{
    return array_merge([
        'amount' => '5000',
        'method' => 'mixx',
        'frequence' => 'ponctuel',
        'donor_name' => 'Kossi A.',
        'donor_contact' => 'kossi@example.test',
        'website' => '',
    ], $override);
}

// --------------------------------------------------- dépôt public

it('crée un don et un paiement en attente, puis redirige vers les instructions', function () {
    $this->post('/dons', champsDon())->assertRedirect();

    $don = Donation::first();
    $payment = Payment::first();

    expect($don)->not->toBeNull()->and($don->amount)->toBe(5000)->and($don->method)->toBe('mixx')
        ->and($payment->status)->toBe(PaymentStatus::EnAttente)
        ->and($payment->gateway)->toBe('manual')
        ->and($payment->external_ref)->toStartWith('DON-')
        ->and($don->payment_id)->toBe($payment->id);
});

it('n\'enregistre ni nom ni contact pour un don anonyme', function () {
    $this->post('/dons', champsDon(['is_anonymous' => '1', 'donor_name' => 'Kossi', 'donor_contact' => 'kossi@example.test']));

    $don = Donation::first();
    expect($don->is_anonymous)->toBeTrue()->and($don->donor_name)->toBeNull()->and($don->donor_contact)->toBeNull();
});

it('chiffre le nom du donateur en base', function () {
    $this->post('/dons', champsDon(['donor_name' => 'Nom Donateur Visible']));

    $brut = DB::table('donations')->value('donor_name');
    expect($brut)->not->toContain('Nom Donateur Visible');
    expect(Donation::first()->donor_name)->toBe('Nom Donateur Visible');
});

it('exige un montant valide et un moyen de paiement', function () {
    $this->post('/dons', champsDon(['amount' => '50']))->assertSessionHasErrors('amount');
    $this->post('/dons', champsDon(['method' => '']))->assertSessionHasErrors('method');
    expect(Donation::count())->toBe(0);
});

it('bloque le honeypot', function () {
    $this->post('/dons', champsDon(['website' => 'robot']))->assertSessionHasErrors('website');
    expect(Donation::count())->toBe(0);
});

// --------------------------------------------------- règle d'or

it('interdit de passer un paiement à payé sans confirmation tracée', function () {
    $this->post('/dons', champsDon());
    $payment = Payment::first();

    expect(fn () => $payment->update(['status' => PaymentStatus::Paye]))
        ->toThrow(LogicException::class);

    expect($payment->fresh()->status)->toBe(PaymentStatus::EnAttente);
});

it('confirme un paiement manuellement avec référence, tracé', function () {
    $this->post('/dons', champsDon());
    $payment = Payment::first();
    $admin = compteDon('admin');

    app(DonationWorkflow::class)->confirmManual($payment, 'TX-123456', $admin);

    $payment->refresh();
    expect($payment->status)->toBe(PaymentStatus::Paye)
        ->and($payment->external_ref)->toBe('TX-123456')
        ->and($payment->confirmed_by)->toBe($admin->id)
        ->and($payment->paid_at)->not->toBeNull();

    // L'action est journalisée.
    expect(Activity::where('subject_type', Payment::class)->where('subject_id', $payment->id)->exists())->toBeTrue();
});

// --------------------------------------------------- architecture

it('pose l\'interface PaymentGateway avec ManualGateway et refuse les passerelles de phase 2', function () {
    $manager = app(PaymentManager::class);

    expect($manager->gateway('manual'))->toBeInstanceOf(ManualGateway::class);
    expect(fn () => $manager->gateway('mixx'))->toThrow(InvalidArgumentException::class);
});

// --------------------------------------------------- admin

it('réserve les dons à l\'Admin et confirme via le composant', function () {
    $this->post('/dons', champsDon());
    $payment = Payment::first();

    $this->get(route('admin.dons'))->assertRedirect('/login');
    $this->actingAs(compteDon('consultant'))->get(route('admin.dons'))->assertForbidden();
    $this->actingAs(compteDon('comite'))->get(route('admin.dons'))->assertForbidden();
    $this->actingAs(compteDon('admin'))->get(route('admin.dons'))->assertOk();

    // Confirmation exige la référence.
    Livewire::actingAs(compteDon('admin'))->test(AdminDonationList::class)
        ->call('startConfirm', $payment->id)->call('confirm')->assertHasErrors('reference');

    Livewire::actingAs(compteDon('admin'))->test(AdminDonationList::class)
        ->call('startConfirm', $payment->id)->set('reference', 'TX-9')->call('confirm')->assertHasNoErrors();

    expect($payment->fresh()->status)->toBe(PaymentStatus::Paye);
});

it('exporte un CSV sans nom pour les dons anonymes', function () {
    $this->post('/dons', champsDon(['donor_name' => 'Nominatif Visible']));
    $this->post('/dons', champsDon(['is_anonymous' => '1', 'donor_name' => 'Cache']));

    $reponse = $this->actingAs(compteDon('admin'))->get(route('admin.dons.export'));
    $reponse->assertOk();
    $csv = $reponse->streamedContent();

    expect($csv)->toContain('Nominatif Visible')->not->toContain('Cache')->toContain('anonyme');
});

// --------------------------------------------------- rappel mensuel

it('envoie le rappel mensuel aux dons réguliers avec e-mail, hors anonymes', function () {
    $this->post('/dons', champsDon(['frequence' => 'regulier', 'donor_contact' => 'regulier@example.test']));
    $this->post('/dons', champsDon(['frequence' => 'ponctuel', 'donor_contact' => 'ponctuel@example.test']));
    $this->post('/dons', champsDon(['frequence' => 'regulier', 'is_anonymous' => '1']));

    $this->artisan('donations:monthly-reminder')->assertSuccessful();

    Mail::assertQueued(DonationReminder::class, 1);
    Mail::assertQueued(DonationReminder::class, fn ($m) => $m->hasTo('regulier@example.test'));
});

// --------------------------------------------------- instructions

it('affiche la page d\'instructions avec le montant et la référence', function () {
    $this->post('/dons', champsDon());
    $payment = Payment::first();

    $this->get(route('dons.instructions', $payment))->assertOk()
        ->assertSee($payment->external_ref)->assertSee('5 000');
});
