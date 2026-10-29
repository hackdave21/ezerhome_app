<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
    $this->seed(RolesAndPermissionsSeeder::class);
});

/** Compte interne avec 2FA confirmée (sinon le middleware 2FA redirige avant la vérification de rôle). */
function compteMatrice(?string $role): ?User
{
    if ($role === null) {
        return null;
    }

    $user = User::factory()->create();
    $user->assignRole($role);
    $user->forceFill(['two_factor_secret' => encrypt('SECRET'), 'two_factor_confirmed_at' => now()])->save();

    return $user;
}

/**
 * Matrice de permissions (Annexe E) : chaque ressource sensible, chaque rôle → 200 ou 403.
 * Le visiteur (null) est redirigé vers la connexion.
 */
dataset('acces', [
    // [route, rôle autorisé(s), rôles refusés]
    'assistance (comité+admin)' => ['comite.demandes', ['comite', 'admin'], ['consultant', 'therapeute', 'formateur_coach']],
    'écoute bureau (admin)' => ['admin.ecoute', ['admin'], ['consultant', 'therapeute', 'comite']],
    'médiathèque (admin)' => ['admin.mediatheque', ['admin'], ['comite', 'therapeute', 'consultant']],
    'photos assistance (comité+admin)' => ['comite.photos', ['comite', 'admin'], ['therapeute', 'consultant']],
    'organigramme (admin)' => ['admin.organigramme', ['admin'], ['comite', 'therapeute', 'consultant']],
    'dons (admin)' => ['admin.dons', ['admin'], ['comite', 'therapeute', 'consultant']],
    'utilisateurs (admin)' => ['admin.utilisateurs', ['admin'], ['comite', 'therapeute', 'consultant']],
    'journal audit (admin)' => ['admin.audit', ['admin'], ['comite', 'therapeute', 'consultant']],
    'paramètres (admin)' => ['admin.parametres', ['admin'], ['comite', 'therapeute', 'consultant']],
    'espace intervenant (thérapeute/coach)' => ['intervenant.dashboard', ['therapeute', 'formateur_coach'], ['consultant', 'comite']],
]);

it('applique la matrice des accès par rôle', function (string $route, array $autorises, array $refuses) {
    $url = route($route);

    // Visiteur non connecté : redirigé vers la connexion.
    $this->get($url)->assertRedirect('/login');

    foreach ($autorises as $role) {
        $this->actingAs(compteMatrice($role))->get($url)->assertOk();
    }

    foreach ($refuses as $role) {
        $this->actingAs(compteMatrice($role))->get($url)->assertForbidden();
    }
})->with('acces');
