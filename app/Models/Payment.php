<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use LogicException;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Paiement. Règle d'or : un paiement ne passe à « payé » que par une confirmation tracée
 * (confirmed_by + paid_at), garantie ici au niveau du modèle. Aucune donnée de paiement sensible
 * n'est stockée : seulement la référence de transaction et, au besoin, un numéro masqué.
 */
class Payment extends Model
{
    use LogsActivity;

    protected $fillable = [
        'uuid', 'donation_id', 'order_id', 'amount', 'currency',
        'status', 'gateway', 'external_ref', 'masked_number', 'confirmed_by', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'status' => PaymentStatus::class,
            'paid_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Payment $payment) {
            $payment->uuid ??= (string) Str::uuid();
        });

        // Règle d'or : aucun passage à « payé » sans confirmation tracée.
        static::saving(function (Payment $payment) {
            if ($payment->status === PaymentStatus::Paye && ($payment->confirmed_by === null || $payment->paid_at === null)) {
                throw new LogicException('Un paiement ne peut passer à « payé » que par une confirmation Admin tracée.');
            }
        });
    }

    public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class);
    }

    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function isPaid(): bool
    {
        return $this->status === PaymentStatus::Paye;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'external_ref', 'confirmed_by', 'paid_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
