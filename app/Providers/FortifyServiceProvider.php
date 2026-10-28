<?php

namespace App\Providers;

use App\Actions\Fortify\RedirectIfTwoFactorAuthenticatable;
use App\Http\Responses\LoginResponse;
use App\Http\Responses\TwoFactorLoginResponse;
use App\Models\User;
use App\Support\LoginGate;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
        $this->app->singleton(TwoFactorLoginResponseContract::class, TwoFactorLoginResponse::class);
    }

    public function boot(): void
    {
        // « 8 caractères minimum, dont au moins un chiffre » (maquette d'inscription).
        Password::defaults(fn () => Password::min(8)->numbers());

        Fortify::loginView(fn () => view('auth.connexion'));
        Fortify::twoFactorChallengeView(fn () => view('auth.2fa-code'));

        // Connexion par téléphone ou e-mail + mot de passe ; refus si le compte n'est pas actif.
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::findByIdentifier((string) $request->input('identifier'));

            if (! $user || ! $user->password || ! Hash::check((string) $request->input('password'), $user->password)) {
                return null;
            }

            return LoginGate::allow($user);
        });

        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower((string) $request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // Envois et vérifications de codes : par adresse IP et par identifiant.
        RateLimiter::for('otp', function (Request $request) {
            $id = Str::lower((string) $request->input('identifier', $request->session()->get('otp.identifier', '')));

            return [Limit::perMinute(10)->by($request->ip()), Limit::perMinute(5)->by('otp|'.$id)];
        });
    }
}
