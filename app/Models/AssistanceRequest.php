<?php

namespace App\Models;

use App\Enums\AssistanceCategory;
use App\Enums\AssistanceStatus;
use App\Support\Phone;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Demande d'assistance déposée sans compte. Données personnelles chiffrées au repos ; le numéro de
 * téléphone est aussi conservé sous forme d'empreinte (phone_hash) pour le suivi et la détection de
 * doublons sans l'exposer. Visible uniquement du Comité et de l'Admin.
 */
class AssistanceRequest extends Model
{
    use LogsActivity;

    protected $fillable = [
        'uuid', 'tracking_no', 'full_name', 'phone', 'phone_hash', 'email',
        'category', 'description', 'notes', 'consent_at', 'status',
    ];

    protected function casts(): array
    {
        return [
            'full_name' => 'encrypted',
            'phone' => 'encrypted',
            'email' => 'encrypted',
            'description' => 'encrypted',
            'notes' => 'encrypted',
            'consent_at' => 'datetime',
            'status' => AssistanceStatus::class,
            'category' => AssistanceCategory::class,
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (AssistanceRequest $request) {
            $request->uuid ??= (string) Str::uuid();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function events(): HasMany
    {
        return $this->hasMany(AssistanceEvent::class)->latest('id');
    }

    public function disbursements(): HasMany
    {
        return $this->hasMany(AssistanceDisbursement::class)->latest('disbursed_on');
    }

    /** Pièces justificatives : médias liés via media_usages. */
    public function attachments(): BelongsToMany
    {
        return $this->belongsToMany(MediaAsset::class, 'media_usages', 'model_id', 'media_asset_id')
            ->wherePivot('model_type', self::class);
    }

    public function scopeForPhone(Builder $query, string $normalizedPhone): Builder
    {
        return $query->where('phone_hash', Phone::hash($normalizedPhone));
    }

    public function totalDisbursed(): int
    {
        return (int) $this->disbursements()->sum('amount');
    }

    public function getActivitylogOptions(): LogOptions
    {
        // Jamais de champ chiffré (nom, téléphone, description, notes) dans le journal.
        return LogOptions::defaults()
            ->logOnly(['status', 'category'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
