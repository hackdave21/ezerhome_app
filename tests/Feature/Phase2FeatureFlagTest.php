<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
    $this->seed(RolesAndPermissionsSeeder::class);
    config(['features.phase2' => false]);
});

it('renvoie 404 sur les routes publiques Phase 2 quand le flag est desactive', function (string $url) {
    $this->get($url)->assertNotFound();
})->with([
    '/dons',
    '/a-quoi-servent-les-dons',
    '/candidature',
    '/formation',
    '/formation/exemple',
    '/coaching',
    '/coaching/exemple',
]);

it('renvoie 404 sur les routes admin et intervenant Phase 2 quand le flag est desactive', function (string $routeName, string $role) {
    $user = User::factory()->create();
    $user->assignRole($role);
    $user->forceFill(['two_factor_secret' => encrypt('SECRET'), 'two_factor_confirmed_at' => now()])->save();

    $this->actingAs($user)->get(route($routeName))->assertNotFound();
})->with([
    ['admin.dons', 'admin'],
    ['admin.dons.export', 'admin'],
    ['admin.candidatures', 'admin'],
    ['admin.reunions', 'admin'],
    ['intervenant.agenda', 'therapeute'],
    ['intervenant.messagerie', 'therapeute'],
    ['intervenant.offres', 'therapeute'],
    ['intervenant.inscrits', 'therapeute'],
]);

it('autorise l acces aux routes Phase 2 quand le flag est active', function () {
    config(['features.phase2' => true]);

    $this->get('/dons')->assertOk();
    $this->get('/a-quoi-servent-les-dons')->assertOk();
    $this->get('/candidature')->assertOk();

    $admin = User::factory()->create();
    $admin->assignRole('admin');
    $admin->forceFill(['two_factor_secret' => encrypt('SECRET'), 'two_factor_confirmed_at' => now()])->save();

    $this->actingAs($admin)->get(route('admin.dons'))->assertOk();
    $this->actingAs($admin)->get(route('admin.candidatures'))->assertOk();
    $this->actingAs($admin)->get(route('admin.reunions'))->assertOk();

    $therapeute = User::factory()->create();
    $therapeute->assignRole('therapeute');
    $therapeute->forceFill(['two_factor_secret' => encrypt('SECRET'), 'two_factor_confirmed_at' => now()])->save();

    $this->actingAs($therapeute)->get(route('intervenant.agenda'))->assertOk();
    $this->actingAs($therapeute)->get(route('intervenant.messagerie'))->assertOk();
    $this->actingAs($therapeute)->get(route('intervenant.offres'))->assertOk();
    $this->actingAs($therapeute)->get(route('intervenant.inscrits'))->assertOk();
});
