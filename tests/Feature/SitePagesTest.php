<?php

use App\Models\Page;
use App\Models\Setting;
use Database\Seeders\PageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

beforeEach(fn () => $this->withoutVite());

it('affiche les pages publiques', function (string $url) {
    $this->get($url)->assertOk();
})->with([
    '/', '/a-propos', '/ecoute', '/formation', '/formation/exemple', '/coaching',
    '/coaching/exemple', '/equipe', '/partenaires', '/a-quoi-servent-les-dons', '/contact',
]);

it("n'expose plus de page tarifs", function () {
    $this->get('/tarifs')->assertNotFound();
});

it('affiche les coordonnées et le bouton WhatsApp dans le header', function () {
    $this->get('/')
        ->assertSee('138, Boulevard du 13 Janvier, Hanoukopé, Lomé')
        ->assertSee('+228 98 13 57 57')
        ->assertSee('mail@ezerhome.org')
        ->assertSee('https://wa.me/22898135757', false);
});

it('met les réglages en cache et les invalide à l\'enregistrement', function () {
    expect(setting('contact.telephone'))->toBe('+228 98 13 57 57');

    Setting::put('contact.telephone', '+228 90 00 00 00');

    expect(Cache::has(Setting::CACHE_KEY))->toBeFalse()
        ->and(setting('contact.telephone'))->toBe('+228 90 00 00 00');
});

it('génère les pages légales de la maquette avec leur sommaire', function () {
    $this->seed(PageSeeder::class);

    $this->get('/confidentialite')->assertOk()
        ->assertSee('Qui est responsable de vos données ?')
        ->assertSee('href="#donnees"', false)
        ->assertSee('Sommaire');
    $this->get('/mentions-legales')->assertOk()->assertSee('Éditeur du site');
    $this->get('/cgu')->assertOk()->assertSee('Droit applicable');
});

it('sert les pages légales publiées et masque les autres', function () {
    Page::create(['slug' => 'cgu', 'title' => 'CGU', 'body' => '<p>Texte CGU</p>', 'is_published' => true]);
    Page::create(['slug' => 'confidentialite', 'title' => 'Confidentialité', 'body' => 'x', 'is_published' => false]);

    $this->get('/cgu')->assertOk()->assertSee('Texte CGU', false);
    $this->get('/confidentialite')->assertNotFound();
});

it('affiche le texte édité de la page « à quoi servent les dons »', function () {
    Page::create(['slug' => 'a-quoi-servent-les-dons', 'title' => 'Dons', 'body' => '<p>Texte édité</p>']);

    $this->get('/a-quoi-servent-les-dons')->assertOk()->assertSee('Texte édité', false);
});
