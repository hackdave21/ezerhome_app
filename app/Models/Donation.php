<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Don. Les coordonnées du donateur (nom, contact) sont chiffrées et ne sont jamais renseignées
 * pour un don anonyme. Aucune donnée de paiement sensible n'est stockée ici.
 */
class Donation extends Model
{
    protected $fillable = [
        'uuid', 'amount', 'currency', 'method', 'is_anonymous',
        'donor_name', 'donor_contact', 'recurring', 'payment_id', 'receipt_no',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'is_anonymous' => 'boolean',
            'recurring' => 'boolean',
            'donor_name' => 'encrypted',
            'donor_contact' => 'encrypted',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Donation $donation) {
            $donation->uuid ??= (string) Str::uuid();
        });

        // Un don anonyme n'enregistre jamais de nom ni de contact.
        static::saving(function (Donation $donation) {
            if ($donation->is_anonymous) {
                $donation->donor_name = null;
                $donation->donor_contact = null;
            }
        });
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function methodLabel(): string
    {
        return PaymentMethod::tryFrom($this->method)?->label() ?? $this->method;
    }

    /** Nom à afficher en interne (jamais pour un don anonyme). */
    public function displayName(): string
    {
        return $this->is_anonymous ? 'Don anonyme' : ($this->donor_name ?: 'Donateur');
    }
}
