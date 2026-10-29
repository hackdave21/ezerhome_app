<?php

namespace App\Models;

use App\Enums\ListeningRequestStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Demande d'écoute d'un consultant. La description est chiffrée au repos et n'est jamais journalisée.
 * L'identifiant exposé dans les URLs est l'UUID (dossier sensible). L'affectation est manuelle (Admin),
 * sans algorithme de matching en V1.
 */
class ListeningRequest extends Model
{
    use LogsActivity;

    /** Niveaux d'urgence facultatifs proposés au consultant. */
    public const URGENCIES = [
        'faible' => 'Pas urgent',
        'moyenne' => 'Assez urgent',
        'elevee' => 'Urgent',
    ];

    /** Canaux de contact préférés. */
    public const CHANNELS = [
        'telephone' => 'Appel téléphonique',
        'whatsapp' => 'WhatsApp',
        'email' => 'E-mail',
        'sms' => 'SMS',
    ];

    protected $fillable = [
        'consultant_id', 'description', 'urgency', 'preferred_channel', 'status',
        'assigned_therapist_id', 'assigned_by', 'assigned_at', 'accepted_at', 'decline_reason', 'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'description' => 'encrypted',
            'status' => ListeningRequestStatus::class,
            'assigned_at' => 'datetime',
            'accepted_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ListeningRequest $request) {
            $request->uuid ??= (string) Str::uuid();
            $request->status ??= ListeningRequestStatus::Envoyee;
        });
    }

    /** URLs exposent l'UUID, jamais l'identifiant numérique. */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'consultant_id');
    }

    public function therapist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_therapist_id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', '!=', ListeningRequestStatus::Cloturee->value);
    }

    public function urgencyLabel(): ?string
    {
        return $this->urgency ? (self::URGENCIES[$this->urgency] ?? $this->urgency) : null;
    }

    public function channelLabel(): ?string
    {
        return $this->preferred_channel ? (self::CHANNELS[$this->preferred_channel] ?? $this->preferred_channel) : null;
    }

    /** Référence courte et non sensible pour les notifications et l'affichage (dérivée de l'UUID). */
    public function shortRef(): string
    {
        return 'EC-'.mb_strtoupper(substr($this->uuid, 0, 6));
    }

    public function getActivitylogOptions(): LogOptions
    {
        // On ne journalise JAMAIS la description (champ chiffré).
        return LogOptions::defaults()
            ->logOnly(['status', 'assigned_therapist_id', 'assigned_by', 'urgency', 'decline_reason'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
