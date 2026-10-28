<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

class CharterSignature extends Model
{
    protected $fillable = ['user_id', 'charter_id', 'signed_at', 'ip', 'user_agent'];

    protected function casts(): array
    {
        return ['signed_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        // Preuve de consentement : jamais modifiée ni supprimée à la main.
        static::updating(fn () => throw new LogicException('Une signature ne se modifie pas.'));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function charter(): BelongsTo
    {
        return $this->belongsTo(Charter::class);
    }
}
