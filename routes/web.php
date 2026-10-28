<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'site.accueil')->name('accueil');
Route::view('/a-propos', 'site.a-propos')->name('a-propos');
Route::view('/ecoute', 'site.ecoute')->name('ecoute');
Route::view('/formation', 'site.formations')->name('formation');
Route::view('/formation/{slug}', 'site.formation-detail')->name('formation.show');
Route::view('/coaching', 'site.coaching')->name('coaching');
Route::view('/coaching/{slug}', 'site.coaching-detail')->name('coaching.show');
Route::view('/equipe', 'site.equipe')->name('equipe');
Route::view('/partenaires', 'site.partenaires')->name('partenaires');
Route::get('/a-quoi-servent-les-dons', [SiteController::class, 'usageDons'])->name('usage-dons');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::get('/contact/institutionnel', [ContactController::class, 'institutionnel'])->name('contact.institutionnel');
Route::post('/contact', [ContactController::class, 'send'])->middleware('throttle:5,1')->name('contact.send');

Route::get('/{slug}', [SiteController::class, 'legal'])
    ->whereIn('slug', array_keys(SiteController::LEGAL))
    ->name('legal');
