<?php

use App\Mail\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

uses(RefreshDatabase::class);

beforeEach(fn () => $this->withoutVite());

function particulier(array $surcharge = []): array
{
    return array_merge([
        'profil' => 'particulier',
        'nom' => 'Ama Kodjo',
        'telephone' => '90 00 00 00',
        'objet' => 'Formation',
        'message' => 'Bonjour, je voudrais des informations.',
        'consentement' => '1',
    ], $surcharge);
}

function organisation(array $surcharge = []): array
{
    return array_merge([
        'profil' => 'organisation',
        'organisation' => 'Fondation Exemple',
        'type_organisation' => 'Fondation',
        'nom' => 'Kofi Mensah',
        'fonction' => 'Directeur',
        'email' => 'kofi@exemple.org',
        'message' => 'Nous souhaitons soutenir un projet.',
        'soutien' => ['Don financier', 'Projet mené ensemble'],
        'dossier' => '1',
        'consentement' => '1',
    ], $surcharge);
}

it('affiche les deux formulaires de la maquette', function () {
    $this->get('/contact')->assertOk()
        ->assertSee('id="ct-form"', false)->assertSee('id="ci-form"', false)
        ->assertSee('Soutien envisagé')->assertSee('Mécénat de compétences')
        ->assertSee('Entreprise');

    $this->get('/contact/institutionnel')->assertOk()->assertSee('Donateur institutionnel');
});

it('envoie le message d\'un particulier (e-mail facultatif)', function () {
    Mail::fake();

    $this->post('/contact', particulier())->assertRedirect('/contact')->assertSessionHas('contact_envoye');

    Mail::assertQueued(ContactMessage::class, fn ($m) => $m->hasTo('mail@ezerhome.org') && ! $m->hasReplyTo('x@y.z'));
});

it('envoie la demande d\'une organisation', function () {
    Mail::fake();

    $this->post('/contact', organisation())->assertRedirect('/contact/institutionnel');

    Mail::assertQueued(ContactMessage::class, fn ($m) => $m->hasReplyTo('kofi@exemple.org')
        && $m->data['soutien'] === ['Don financier', 'Projet mené ensemble']);
});

it('valide les champs propres à chaque profil', function () {
    $this->post('/contact', particulier(['telephone' => '']))->assertSessionHasErrors('telephone');
    $this->post('/contact', organisation(['fonction' => '', 'email' => '']))->assertSessionHasErrors(['fonction', 'email']);
    $this->post('/contact', organisation(['soutien' => ['Inconnu']]))->assertSessionHasErrors('soutien.0');
});

it('rejette un robot qui remplit le honeypot', function () {
    Mail::fake();

    $this->post('/contact', particulier(['website' => 'http://spam.test']))->assertSessionHasErrors('website');

    Mail::assertNothingQueued();
});

it('exige le consentement et un message valide', function () {
    $this->post('/contact', particulier(['consentement' => null, 'message' => 'court']))
        ->assertSessionHasErrors(['consentement', 'message']);
});

it('limite le nombre d\'envois', function () {
    Mail::fake();

    foreach (range(1, 5) as $_) {
        $this->post('/contact', particulier())->assertRedirect('/contact');
    }

    $this->post('/contact', particulier())->assertStatus(429);
});

it('vérifie Turnstile quand il est configuré', function () {
    config(['services.turnstile.secret' => 'secret-test']);
    Mail::fake();
    Http::fake(['challenges.cloudflare.com/*' => Http::response(['success' => false])]);

    $this->post('/contact', particulier(['cf-turnstile-response' => 'jeton']))
        ->assertSessionHasErrors('cf-turnstile-response');

    Mail::assertNothingQueued();
});
