<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LogicException;

class Charter extends Model
{
    public const LISTENING = 'listening';

    public const PROVIDER = 'provider';

    protected $fillable = ['type', 'version', 'body', 'published_at'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        // Une version publiée ne s'édite jamais : on publie une nouvelle version.
        static::updating(function (Charter $charter) {
            if ($charter->getOriginal('published_at') !== null) {
                throw new LogicException('Une charte publiée est immuable : publiez une nouvelle version.');
            }
        });

        static::deleting(function (Charter $charter) {
            if ($charter->published_at !== null) {
                throw new LogicException('Une charte publiée ne peut pas être supprimée.');
            }
        });
    }

    /** Version en vigueur : la dernière publiée. */
    public static function current(string $type): ?self
    {
        return static::where('type', $type)
            ->whereNotNull('published_at')->where('published_at', '<=', now())
            ->orderByDesc('published_at')->orderByDesc('id')
            ->first();
    }

    /** Publie une nouvelle version ; les utilisateurs devront la signer à leur prochain accès. */
    public static function publish(string $type, string $version, string $body): self
    {
        return static::create([
            'type' => $type, 'version' => $version, 'body' => $body, 'published_at' => now(),
        ]);
    }

    public function signatures(): HasMany
    {
        return $this->hasMany(CharterSignature::class);
    }
}
