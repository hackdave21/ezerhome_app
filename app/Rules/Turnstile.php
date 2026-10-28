<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

/** Vérifie le jeton Cloudflare Turnstile. Inactive tant que TURNSTILE_SECRET n'est pas défini. */
class Turnstile implements ValidationRule
{
    public bool $implicit = true;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = config('services.turnstile.secret');

        if (! $secret) {
            return;
        }

        $ok = is_string($value) && $value !== '' && Http::asForm()
            ->timeout(5)
            ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $secret,
                'response' => $value,
                'remoteip' => request()->ip(),
            ])->json('success') === true;

        if (! $ok) {
            $fail('La vérification anti-robot a échoué. Veuillez réessayer.');
        }
    }
}
