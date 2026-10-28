<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    public const CACHE_KEY = 'settings.all';

    /** Valeurs par défaut (coordonnées validées par le client). */
    public const DEFAULTS = [
        'contact.adresse' => '138, Boulevard du 13 Janvier, Hanoukopé, Lomé',
        'contact.telephone' => '+228 98 13 57 57',
        'contact.whatsapp' => '22898135757',
        'contact.email' => 'mail@ezerhome.org',
    ];

    protected $fillable = ['key', 'value'];

    protected static function booted(): void
    {
        $forget = fn () => Cache::forget(self::CACHE_KEY);

        static::saved($forget);
        static::deleted($forget);
    }

    public static function cached(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, fn () => static::pluck('value', 'key')->all());
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        return static::cached()[$key] ?? $default ?? (self::DEFAULTS[$key] ?? null);
    }

    public static function put(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
