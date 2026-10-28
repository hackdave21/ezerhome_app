<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;

/** « Ne plus me demander le code sur cet appareil pendant 30 jours ». */
class TrustedDevice
{
    public const COOKIE = 'ezer_2fa_trusted';

    public const DAYS = 30;

    public static function cookieFor(User $user): Cookie
    {
        return cookie(self::COOKIE, self::token($user), self::DAYS * 24 * 60, null, null, null, true, false, 'lax');
    }

    public static function valid(Request $request, User $user): bool
    {
        $value = $request->cookie(self::COOKIE);

        return is_string($value) && hash_equals(self::token($user), $value);
    }

    /** Lié au secret 2FA : régénérer ou désactiver la 2FA invalide tous les appareils de confiance. */
    private static function token(User $user): string
    {
        return $user->getKey().'|'.hash_hmac('sha256', (string) $user->two_factor_secret, config('app.key'));
    }
}
