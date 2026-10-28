<?php

namespace App\Providers;

use App\Listeners\AssignDefaultRole;
use App\Policies\MediaPolicy;
use App\Services\Otp\LogSmsOtpSender;
use App\Services\Otp\OtpSender;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Passerelle SMS/WhatsApp : remplacer par l'implémentation réelle quand elle sera choisie.
        $this->app->bind(OtpSender::class, LogSmsOtpSender::class);
    }

    public function boot(): void
    {
        Event::listen(Registered::class, AssignDefaultRole::class);

        // Le modèle Media vient du package : on déclare sa policy explicitement.
        // (ListeningRequestPolicy et AssistanceRequestPolicy arrivent avec leurs modèles, étapes 1.4 et 1.8.)
        Gate::policy(Media::class, MediaPolicy::class);
    }
}
