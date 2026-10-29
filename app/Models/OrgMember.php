<?php

namespace App\Models;

use App\Models\Concerns\TracksMediaUsage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Membre de l'organigramme. N'est pas forcément un compte utilisateur (user_id facultatif).
 * Audité : toute modification par l'Admin est journalisée.
 */
class OrgMember extends Model
{
    use LogsActivity;
    use TracksMediaUsage;

    public const BUREAU_EXECUTIF = 'bureau_executif';

    public const COMITE_ASSISTANCE = 'comite_assistance';

    public const COMITE_VALIDATION = 'comite_validation';

    public const AUTRE = 'autre';

    /** Instances, dans l'ordre d'affichage sur la page publique. */
    public const COMMITTEES = [
        self::BUREAU_EXECUTIF => 'Bureau exécutif',
        self::COMITE_ASSISTANCE => "Comité d'assistance",
        self::COMITE_VALIDATION => 'Comité de validation',
        self::AUTRE => 'Autre',
    ];

    protected $fillable = [
        'first_name', 'last_name', 'position', 'committee', 'media_id', 'parent_id',
        'bio', 'public_contact', 'sort_order', 'is_visible', 'user_id',
    ];

    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function photo(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'media_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Membres visibles, groupés par comité dans l'ordre d'affichage (pour la page publique). */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true)->orderBy('sort_order')->orderBy('id');
    }

    public function fullName(): string
    {
        return trim($this->last_name.' '.$this->first_name);
    }

    public function committeeLabel(): string
    {
        return self::COMMITTEES[$this->committee] ?? $this->committee;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['first_name', 'last_name', 'position', 'committee', 'media_id', 'is_visible', 'sort_order'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected function mediaUsageLabel(): string
    {
        return 'Notre équipe · '.$this->fullName();
    }

    protected function mediaUsageUrl(): ?string
    {
        return route('equipe');
    }
}
