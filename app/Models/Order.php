<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Commande : posée dès la phase 1 pour l'architecture de paiement (achats de formations et de
 * coaching en phase 2). Non utilisée par l'interface en phase 1.
 */
class Order extends Model
{
    protected $fillable = ['uuid', 'user_id', 'amount', 'currency', 'status'];

    protected function casts(): array
    {
        return ['amount' => 'integer'];
    }

    protected static function booted(): void
    {
        static::creating(fn (Order $order) => $order->uuid ??= (string) Str::uuid());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
