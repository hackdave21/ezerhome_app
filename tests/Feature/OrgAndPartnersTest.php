<?php

use App\Livewire\MediaLibrary;
use App\Livewire\OrgMemberManager;
use App\Livewire\PartnerManager;
use App\Models\MediaAsset;
use App\Models\MediaUsage;
use App\Models\OrgMember;
use App\Models\Partner;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Activitylog\Models\Activity;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
    $this->seed(RolesAndPermissionsSeeder::class);
});

/** Compte interne avec rôle et 2FA confirmée. */
function membreInterne(string $role): User
{
    $user = User::factory()->create();
    $user->assignRole($role);
    $user->forceFill(['two_factor_secret' => encrypt('SECRET'), 'two_factor_confirmed_at' => now()])->save();

    return $user;
}

/** Photo publiable prête (catégorie organigramme) ou logo (catégorie logo_partenaire). */
function mediaPublic(string $category): MediaAsset
{
    return MediaAsset::create([
        'original_name' => 'x.jpg', 'title' => 'Fichier', 'alt' => 'Alt', 'category' => $category,
        'status' => 'ready', 'path' => 'x.webp', 'mime' => 'image/webp', 'is_public' => true,
        'disk' => MediaAsset::PUBLIC_DISK,
    ]);
}

// ----------------------------------------------------------------- droits

it('réserve la gestion de l\'organigramme et des partenaires à l\'Admin', function () {
    $this->get('/admin/organigramme')->assertRedirect('/login');

    foreach (['consultant', 'therapeute', 'comite'] as $role) {
        $this->actingAs(membreInterne($role))->get('/admin/organigramme')->assertForbidden();
    }

    $this->actingAs(membreInterne('admin'))->get('/admin/organigramme')->assertOk()->assertSee('Organigramme et partenaires');
});

// ----------------------------------------------------------- organigramme

it('permet à l\'Admin de créer et modifier un membre sans toucher au code', function () {
    Livewire::actingAs(membreInterne('admin'))->test(OrgMemberManager::class)
        ->call('create')
        ->set('last_name', 'KOKOU')->set('first_name', 'Ama')->set('position', 'Présidente')
        ->set('committee', OrgMember::BUREAU_EXECUTIF)->call('save')->assertHasNoErrors();

    $membre = OrgMember::first();
    expect($membre->fullName())->toBe('KOKOU Ama')->and($membre->committee)->toBe('bureau_executif')->and($membre->is_visible)->toBeTrue();
});

it('exige les champs obligatoires du membre', function () {
    Livewire::actingAs(membreInterne('admin'))->test(OrgMemberManager::class)
        ->call('create')->call('save')->assertHasErrors(['last_name', 'first_name', 'position']);

    expect(OrgMember::count())->toBe(0);
});

it('masque un membre et ne l\'affiche plus sur la page publique', function () {
    $visible = OrgMember::create(['first_name' => 'Ama', 'last_name' => 'KOKOU', 'position' => 'Présidente', 'committee' => 'bureau_executif']);
    $masque = OrgMember::create(['first_name' => 'Yao', 'last_name' => 'MENSAH', 'position' => 'Membre', 'committee' => 'bureau_executif', 'is_visible' => false]);

    $this->get('/equipe')->assertOk()->assertSee('KOKOU Ama')->assertDontSee('MENSAH Yao');

    // Et l'Admin peut rebasculer la visibilité.
    Livewire::actingAs(membreInterne('admin'))->test(OrgMemberManager::class)->call('toggleVisible', $masque->id);
    expect($masque->fresh()->is_visible)->toBeTrue();

    expect($visible->fresh()->is_visible)->toBeTrue();
});

it('réordonne les membres par glisser-déposer', function () {
    $a = OrgMember::create(['first_name' => 'A', 'last_name' => 'Un', 'position' => 'P', 'committee' => 'bureau_executif', 'sort_order' => 0]);
    $b = OrgMember::create(['first_name' => 'B', 'last_name' => 'Deux', 'position' => 'P', 'committee' => 'bureau_executif', 'sort_order' => 1]);

    Livewire::actingAs(membreInterne('admin'))->test(OrgMemberManager::class)->call('reorder', [$b->id, $a->id]);

    expect($b->fresh()->sort_order)->toBe(0)->and($a->fresh()->sort_order)->toBe(1);
});

// -------------------------------------------------------------- partenaires

it('n\'affiche pas un partenaire sans accord écrit, même marqué actif', function () {
    Livewire::actingAs(membreInterne('admin'))->test(PartnerManager::class)
        ->call('create')->set('name', 'MCPRA')->set('is_active', true)->set('approved', false)
        ->call('save')->assertHasErrors('is_active');

    expect(Partner::count())->toBe(0);
});

it('publie un partenaire avec accord écrit et renseigne approved_at', function () {
    Livewire::actingAs(membreInterne('admin'))->test(PartnerManager::class)
        ->call('create')->set('name', 'MCPRA')->set('url', 'https://mcpra.example')
        ->set('approved', true)->set('is_active', true)->call('save')->assertHasNoErrors();

    $partenaire = Partner::first();
    expect($partenaire->is_active)->toBeTrue()->and($partenaire->approved_at)->not->toBeNull();

    $this->get('/partenaires')->assertOk()->assertSee('MCPRA');
});

it('empêche d\'activer un partenaire sans accord écrit enregistré', function () {
    $partenaire = Partner::create(['name' => 'Sans accord', 'is_active' => false]);

    Livewire::actingAs(membreInterne('admin'))->test(PartnerManager::class)->call('toggleActive', $partenaire->id);

    expect($partenaire->fresh()->is_active)->toBeFalse();
});

// --------------------------------------------- protection des fichiers utilisés

it('protège contre la suppression le logo utilisé par un partenaire', function () {
    $logo = mediaPublic(MediaAsset::LOGO_PARTENAIRE);
    $partenaire = Partner::create(['name' => 'MCPRA', 'media_id' => $logo->id, 'approved_at' => now(), 'is_active' => true]);

    // Un usage est enregistré : le fichier n'est plus « non utilisé ».
    expect(MediaUsage::where('media_asset_id', $logo->id)->count())->toBe(1)
        ->and($logo->usages()->first()->label)->toContain('MCPRA');

    // La médiathèque refuse de supprimer ce fichier utilisé.
    Livewire::actingAs(membreInterne('admin'))->test(MediaLibrary::class)
        ->call('select', $logo->id)->call('delete')->assertSet('deleteBlocked', true);

    expect(MediaAsset::find($logo->id))->not->toBeNull();

    // Détacher le partenaire libère le fichier.
    $partenaire->delete();
    expect(MediaUsage::where('media_asset_id', $logo->id)->count())->toBe(0);
});

it('protège la photo utilisée par un membre et suit le changement de média', function () {
    $photo1 = mediaPublic(MediaAsset::ORGANIGRAMME);
    $photo2 = mediaPublic(MediaAsset::ORGANIGRAMME);

    $membre = OrgMember::create(['first_name' => 'Ama', 'last_name' => 'KOKOU', 'position' => 'Présidente', 'committee' => 'bureau_executif', 'media_id' => $photo1->id]);
    expect(MediaUsage::where('media_asset_id', $photo1->id)->count())->toBe(1);

    // Changer de photo retire l'ancien usage et enregistre le nouveau.
    $membre->update(['media_id' => $photo2->id]);
    expect(MediaUsage::where('media_asset_id', $photo1->id)->count())->toBe(0)
        ->and(MediaUsage::where('media_asset_id', $photo2->id)->count())->toBe(1);
});

// ------------------------------------------------------------------- audit

it('journalise les modifications de l\'organigramme', function () {
    $membre = OrgMember::create(['first_name' => 'Ama', 'last_name' => 'KOKOU', 'position' => 'Présidente', 'committee' => 'bureau_executif']);
    $membre->update(['position' => 'Vice-présidente']);

    expect(Activity::where('subject_type', OrgMember::class)->where('subject_id', $membre->id)->exists())->toBeTrue();
});
