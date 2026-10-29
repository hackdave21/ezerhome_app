<?php

use App\Enums\AssistanceStatus;
use App\Mail\AssistanceNotification;
use App\Mail\ListeningNotification;
use App\Models\AssistanceRequest;
use App\Models\ListeningRequest;
use App\Models\MediaAsset;
use App\Models\User;
use App\Services\Assistance\AssistanceWorkflow;
use App\Services\Listening\ListeningWorkflow;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutVite();
    $this->seed(RolesAndPermissionsSeeder::class);
});

// ------------------------------------------------- en-têtes de sécurité

it('ajoute les en-têtes de sécurité sur les pages web', function () {
    $response = $this->get('/');

    $response->assertOk()
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('X-Frame-Options', 'DENY')
        ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');

    expect($response->headers->get('Permissions-Policy'))->toContain('geolocation=()')
        ->and($response->headers->get('Content-Security-Policy'))->toContain("default-src 'self'")
        ->and($response->headers->get('Content-Security-Policy'))->toContain("frame-ancestors 'none'");

    // HSTS absent en HTTP.
    $response->assertHeaderMissing('Strict-Transport-Security');
});

it('envoie HSTS en HTTPS', function () {
    $this->get('https://localhost/')->assertHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
});

it('préserve la CSP isolante de la route des médias', function () {
    Storage::fake(MediaAsset::PUBLIC_DISK);
    $asset = MediaAsset::create([
        'original_name' => 'a.webp', 'title' => 'Image', 'alt' => 'Alt', 'category' => 'general',
        'status' => 'ready', 'path' => 'a.webp', 'mime' => 'image/webp', 'is_public' => true, 'disk' => MediaAsset::PUBLIC_DISK,
    ]);
    Storage::disk(MediaAsset::PUBLIC_DISK)->put('a.webp', 'x');

    $this->get($asset->url())->assertOk()
        ->assertHeader('Content-Security-Policy', "default-src 'none'; style-src 'unsafe-inline'; sandbox");
});

// ------------------------------------- pas de données sensibles dans les mails

it('n\'inclut aucun contenu sensible dans les notifications d\'écoute', function () {
    Mail::fake();
    $consultant = User::factory()->create();
    $consultant->assignRole('consultant');
    $therapeute = User::factory()->create();
    $therapeute->assignRole('therapeute');
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $secret = 'Confidence absolument privée du consultant';
    $demande = ListeningRequest::create(['consultant_id' => $consultant->id, 'description' => $secret]);

    app(ListeningWorkflow::class)->assign($demande, $therapeute, $admin);

    $contenus = collect(Mail::queued(ListeningNotification::class))->map(fn ($m) => $m->render());
    expect($contenus)->not->toBeEmpty();
    $contenus->each(fn ($html) => expect($html)->not->toContain($secret));
});

it('n\'inclut aucun contenu sensible dans les notifications d\'assistance', function () {
    Mail::fake();
    $secret = 'Situation familiale très sensible';
    $demande = AssistanceRequest::create([
        'tracking_no' => 'ASS-2026-00001', 'full_name' => 'Nom Prive', 'phone' => '+22890000000', 'phone_hash' => 'h',
        'email' => 'demandeur@example.test', 'category' => 'autre', 'description' => $secret, 'consent_at' => now(),
    ]);

    app(AssistanceWorkflow::class)->changeStatus($demande, AssistanceStatus::Acceptee, 'note interne', User::factory()->create());

    $contenus = collect(Mail::queued(AssistanceNotification::class))->map(fn ($m) => $m->render());
    expect($contenus)->not->toBeEmpty();
    $contenus->each(function ($html) use ($secret, $demande) {
        expect($html)->not->toContain($secret)->not->toContain($demande->full_name)->not->toContain('note interne');
    });
});
