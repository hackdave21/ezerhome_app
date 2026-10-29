<?php

use App\Http\Controllers\Admin\AdminListeningController;
use App\Http\Controllers\Auth\AccountStatusController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\TwoFactorSetupController;
use App\Http\Controllers\CharterController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EspaceController;
use App\Http\Controllers\IntervenantController;
use App\Http\Controllers\ListeningController;
use App\Http\Controllers\MediaFileController;
use App\Http\Controllers\PrivacyController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'site.accueil')->name('accueil');
Route::view('/a-propos', 'site.a-propos')->name('a-propos');
Route::view('/ecoute', 'site.ecoute')->name('ecoute');
Route::view('/formation', 'site.formations')->name('formation');
Route::view('/formation/{slug}', 'site.formation-detail')->name('formation.show');
Route::view('/coaching', 'site.coaching')->name('coaching');
Route::view('/coaching/{slug}', 'site.coaching-detail')->name('coaching.show');
Route::get('/charte', [CharterController::class, 'publique'])->name('charte');
Route::get('/equipe', [SiteController::class, 'equipe'])->name('equipe');
Route::get('/partenaires', [SiteController::class, 'partenaires'])->name('partenaires');
Route::get('/a-quoi-servent-les-dons', [SiteController::class, 'usageDons'])->name('usage-dons');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::get('/contact/institutionnel', [ContactController::class, 'institutionnel'])->name('contact.institutionnel');
Route::post('/contact', [ContactController::class, 'send'])->middleware('throttle:5,1')->name('contact.send');

Route::get('/{slug}', [SiteController::class, 'legal'])
    ->whereIn('slug', array_keys(SiteController::LEGAL))
    ->name('legal');

/*
|--------------------------------------------------------------------------
| Authentification (connexion et double authentification : routes Fortify)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->middleware('throttle:otp');

    Route::post('/connexion/code', [OtpController::class, 'requestLoginCode'])->middleware('throttle:otp')->name('login.code');
    Route::get('/connexion/lien', [OtpController::class, 'magicSent'])->name('login.magic.sent');
    Route::post('/connexion/lien/renvoyer', [OtpController::class, 'resendMagic'])->middleware('throttle:otp')->name('login.magic.resend');
    Route::get('/connexion/lien/{user}/{token}', [OtpController::class, 'magicLogin'])
        ->middleware(['signed', 'throttle:10,1'])->name('login.magic');

    Route::get('/mot-de-passe-oublie', [PasswordResetController::class, 'request'])->name('password.request');
    Route::post('/mot-de-passe-oublie', [PasswordResetController::class, 'send'])->middleware('throttle:otp')->name('password.send');
    Route::get('/nouveau-mot-de-passe', [PasswordResetController::class, 'form'])->name('password.new');
    Route::post('/nouveau-mot-de-passe', [PasswordResetController::class, 'update'])->middleware('throttle:otp')->name('password.update');

    Route::get('/compte-desactive', [AccountStatusController::class, 'deactivated'])->name('account.deactivated');
});

// Saisie du code : vérification, connexion, réinitialisation (visiteur) et suppression de compte (connecté).
Route::get('/verification', [OtpController::class, 'show'])->name('otp.show');
Route::post('/verification', [OtpController::class, 'verify'])->middleware('throttle:otp')->name('otp.verify');
Route::post('/verification/renvoyer', [OtpController::class, 'resend'])->middleware('throttle:otp')->name('otp.resend');

Route::middleware(['auth', 'auth.session', 'account.active'])->group(function () {
    Route::get('/double-authentification', [TwoFactorSetupController::class, 'show'])->name('two-factor.setup');
    Route::post('/double-authentification', [TwoFactorSetupController::class, 'confirm'])->name('two-factor.confirm');
    Route::get('/double-authentification/codes', [TwoFactorSetupController::class, 'codes'])->name('two-factor.codes');
    Route::post('/double-authentification/codes', [TwoFactorSetupController::class, 'regenerate'])->name('two-factor.regenerate');
    Route::delete('/double-authentification', [TwoFactorSetupController::class, 'disable'])->name('two-factor.disable');

    // Espace personnel : 2FA obligatoire pour thérapeute, formateur/coach, comité et admin.
    Route::middleware('two-factor.required')->prefix('espace')->group(function () {
        Route::get('/', [EspaceController::class, 'index'])->name('espace');
        Route::get('/profil', [EspaceController::class, 'profil'])->name('espace.profil');
        Route::get('/charte', [CharterController::class, 'show'])->name('espace.charte');
        Route::post('/charte', [CharterController::class, 'sign'])->name('espace.charte.sign');
        Route::get('/charte/pdf', [CharterController::class, 'pdf'])->name('espace.charte.pdf');

        // Écoute : demande et suivi. Verrou charte signée (middleware + policy, défense en profondeur).
        Route::middleware('charter.listening')->group(function () {
            Route::get('/ecoute', [ListeningController::class, 'index'])->name('espace.ecoute');
            Route::post('/ecoute', [ListeningController::class, 'store'])->name('espace.ecoute.store');
            Route::get('/ecoute/{listeningRequest}', [ListeningController::class, 'show'])->name('espace.ecoute.show');
        });

        Route::get('/{onglet}', [EspaceController::class, 'bientot'])->whereIn('onglet', ['formations', 'coaching'])->name('espace.onglet');
        Route::post('/appareils/deconnecter', [EspaceController::class, 'logoutOthers'])->name('espace.logout-others');
        Route::post('/export', [PrivacyController::class, 'requestExport'])->name('espace.export');
        Route::get('/export/{export}', [PrivacyController::class, 'download'])->name('espace.export.download');
        Route::post('/suppression', [PrivacyController::class, 'requestDeletion'])->name('espace.suppression');
    });
});

/*
|--------------------------------------------------------------------------
| Médiathèque : accès aux fichiers (URL stable) et espaces internes
|--------------------------------------------------------------------------
*/
Route::get('/medias/{asset}/{variant?}', [MediaFileController::class, 'show'])
    ->whereNumber('asset')->whereIn('variant', ['miniature'])->name('media.file');

Route::middleware(['auth', 'auth.session', 'account.active', 'two-factor.required'])->group(function () {
    Route::view('/admin/mediatheque', 'admin.mediatheque')->middleware('can:media.manage')->name('admin.mediatheque');
    Route::view('/admin/organigramme', 'admin.organigramme')->middleware('can:org.manage')->name('admin.organigramme');

    // Écoute (bureau) : liste et fiche journalisée, affectation manuelle.
    Route::middleware('can:listening.view-any')->group(function () {
        Route::get('/admin/ecoute', [AdminListeningController::class, 'index'])->name('admin.ecoute');
        Route::get('/admin/ecoute/{listeningRequest}', [AdminListeningController::class, 'show'])->name('admin.ecoute.show');
    });

    // Espace de travail du thérapeute / coach.
    Route::get('/intervenant', [IntervenantController::class, 'dashboard'])->middleware('can:listening.view-assigned')->name('intervenant.dashboard');

    Route::view('/comite/photos', 'comite.photos')->middleware('can:media.manage-assistance')->name('comite.photos');
});
