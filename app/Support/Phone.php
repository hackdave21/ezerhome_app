<?php

namespace App\Support;

/** Numéros togolais : stockés sous la forme +228XXXXXXXX (8 chiffres). */
class Phone
{
    public static function normalize(?string $value): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $value);

        if (str_starts_with($digits, '00228')) {
            $digits = substr($digits, 5);
        } elseif (str_starts_with($digits, '228') && strlen($digits) === 11) {
            $digits = substr($digits, 3);
        }

        return strlen($digits) === 8 ? '+228'.$digits : null;
    }

    /** « +228 90 •• •• 56 » : ne laisse apparaître que les deux premiers et derniers chiffres. */
    public static function mask(string $normalized): string
    {
        $d = substr($normalized, 4);

        return sprintf('+228 %s •• •• %s', substr($d, 0, 2), substr($d, -2));
    }

    public static function format(string $normalized): string
    {
        $d = substr($normalized, 4);

        return '+228 '.implode(' ', str_split($d, 2));
    }

    public static function isIdentifierEmail(string $identifier): bool
    {
        return str_contains($identifier, '@');
    }
}
