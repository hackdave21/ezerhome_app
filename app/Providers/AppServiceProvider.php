<?php

namespace App\Providers;

use App\Listeners\AssignDefaultRole;
use App\Models\User;
use App\Services\Otp\LogSmsOtpSender;
use App\Services\Otp\OtpSender;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Passerelle SMS/WhatsApp : remplacer par l'implémentation réelle quand elle sera choisie.
        $this->app->bind(OtpSender::class, LogSmsOtpSender::class);
    }

    public function boot(): void
    {
        // En production, toutes les URLs générées sont en HTTPS (liens, assets, routes signées).
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Event::listen(Registered::class, AssignDefaultRole::class);

        // MediaAssetPolicy est découverte automatiquement. ListeningRequestPolicy et
        // AssistanceRequestPolicy arrivent avec leurs modèles (étapes 1.6 et 1.8).

        // Défense en profondeur du verrou « charte signée » : à appeler aussi depuis les policies d'écoute.
        Gate::define('request-listening', fn (User $user) => $user->can('listening.request') && $user->hasSignedCurrentCharter());
    }
}
