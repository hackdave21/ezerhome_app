<?php

use App\Enums\UserStatus;
use App\Livewire\AdminAudit;
use App\Livewire\AdminNotifications;
use App\Livewire\AdminPages;
use App\Livewire\AdminSettings;
use App\Livewire\AdminUsers;
use App\Models\ListeningRequest;
use App\Models\Page;
use App\Models\Setting;
use App\Models\User;
use App\Support\NotificationTemplates;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Activitylog\Models\Activity;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
    $this->seed(RolesAndPermissionsSeeder::class);
});

function bo(string $role): User
{
    $user = User::factory()->create();
    $user->assignRole($role);
    $user->forceFill(['two_factor_secret' => encrypt('SECRET'), 'two_factor_confirmed_at' => now()])->save();

    return $user;
}

// --------------------------------------------- tableau de bord + redirection

it('ouvre le tableau de bord Admin et redirige chaque rôle vers son espace', function () {
    $this->actingAs(bo('admin'))->get(route('admin.dashboard'))->assertOk()->assertSee('Tableau de bord');

    // Redirection post-connexion (/espace) selon le rôle.
    $this->actingAs(bo('admin'))->get('/espace')->assertRedirect(route('admin.dashboard'));
    $this->actingAs(bo('comite'))->get('/espace')->assertRedirect(route('comite.demandes'));
    $this->actingAs(bo('therapeute'))->get('/espace')->assertRedirect(route('intervenant.dashboard'));
});

it('réserve le tableau de bord et les écrans Admin aux rôles autorisés', function () {
    foreach (['admin.dashboard', 'admin.utilisateurs', 'admin.parametres', 'admin.notifications', 'admin.cms', 'admin.audit'] as $route) {
        $this->actingAs(bo('comite'))->get(route($route))->assertForbidden();
        $this->actingAs(bo('consultant'))->get(route($route))->assertForbidden();
    }

    $this->actingAs(bo('admin'))->get(route('admin.utilisateurs'))->assertOk();
    $this->actingAs(bo('admin'))->get(route('admin.audit'))->assertOk();
});

// --------------------------------------------- utilisateurs

it('gère les rôles et la suspension avec motif, tracée', function () {
    $admin = bo('admin');
    $cible = User::factory()->create(['name' => 'Cible Test']);

    $c = Livewire::actingAs($admin)->test(AdminUsers::class);
    $c->call('toggleRole', $cible->id, 'therapeute');
    expect($cible->fresh()->hasRole('therapeute'))->toBeTrue();

    $c->call('startSuspend', $cible->id)->call('suspend')->assertHasErrors('reason');
    $c->set('reason', 'Comportement signalé')->call('suspend')->assertHasNoErrors();

    expect($cible->fresh()->status)->toBe(UserStatus::Suspended);
    expect(Activity::where('subject_type', User::class)->where('subject_id', $cible->id)->where('event', 'suspended')->exists())->toBeTrue();

    $c->call('reactivate', $cible->id);
    expect($cible->fresh()->status)->toBe(UserStatus::Active);
});

it('empêche un admin de se suspendre lui-même', function () {
    $admin = bo('admin');

    Livewire::actingAs($admin)->test(AdminUsers::class)
        ->call('startSuspend', $admin->id)->set('reason', 'x')->call('suspend')->assertForbidden();

    expect($admin->fresh()->status)->toBe(UserStatus::Active);
});

// --------------------------------------------- paramètres & notifications

it('enregistre les paramètres', function () {
    $iEmail = array_search('contact.email', array_keys(AdminSettings::FIELDS), true);
    $iMax = array_search('media.max_mb', array_keys(AdminSettings::FIELDS), true);

    Livewire::actingAs(bo('admin'))->test(AdminSettings::class)
        ->set("values.$iEmail", 'nouveau@ezerhome.org')
        ->set("values.$iMax", '3')
        ->call('save')->assertHasNoErrors();

    expect(Setting::get('contact.email'))->toBe('nouveau@ezerhome.org')->and(Setting::get('media.max_mb'))->toBe('3');
});

it('enregistre les modèles de notifications', function () {
    $i = collect(NotificationTemplates::all())->search(fn ($m) => $m['base'] === 'notif.listening.received');

    Livewire::actingAs(bo('admin'))->test(AdminNotifications::class)
        ->set("values.$i.subject", 'Sujet personnalisé')
        ->call('save')->assertHasNoErrors();

    expect(Setting::get('notif.listening.received.subject'))->toBe('Sujet personnalisé');
});

// --------------------------------------------- CMS

it('crée et met à jour une page de contenu', function () {
    Livewire::actingAs(bo('admin'))->test(AdminPages::class)
        ->call('edit', 'a-quoi-servent-les-dons')
        ->set('title', 'À quoi servent les dons')
        ->set('body', '<p>Transparence totale.</p>')
        ->call('save')->assertHasNoErrors();

    $page = Page::where('slug', 'a-quoi-servent-les-dons')->first();
    expect($page)->not->toBeNull()->and($page->is_published)->toBeTrue()->and($page->body)->toContain('Transparence');
});

// --------------------------------------------- journal d'audit

it('consulte le journal d\'audit sans exposer de contenu chiffré', function () {
    $admin = bo('admin');
    $consultant = User::factory()->create();
    $consultant->assignRole('consultant');
    $demande = ListeningRequest::create(['consultant_id' => $consultant->id, 'description' => 'Contenu ultra confidentiel']);

    // Génère une entrée « viewed ».
    $this->actingAs($admin)->get(route('admin.ecoute.show', $demande))->assertOk();

    $c = Livewire::actingAs($admin)->test(AdminAudit::class);
    expect($c->instance()->activities->total())->toBeGreaterThan(0);
    $c->assertDontSee('Contenu ultra confidentiel');
});
