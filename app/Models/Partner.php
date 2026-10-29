<?php

namespace App\Models;

use App\Models\Concerns\TracksMediaUsage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Partenaire. Publication possible uniquement avec accord écrit : « is_active » est refusé tant que
 * « approved_at » n'est pas renseigné (garanti par le composant d'administration et par un garde-fou
 * au niveau du modèle).
 */
class Partner extends Model
{
    use LogsActivity;
    use TracksMediaUsage;

    protected $fillable = ['name', 'media_id', 'url', 'approved_at', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        // Garde-fou : aucun partenaire publié sans accord écrit enregistré.
        static::saving(function (Partner $partner) {
            if ($partner->is_active && $partner->approved_at === null) {
                $partner->is_active = false;
            }
        });
    }

    public function logo(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'media_id');
    }

    /** Partenaires publiés (accord écrit + actif + logo prêt), dans l'ordre d'affichage. */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_active', true)->whereNotNull('approved_at')
            ->orderBy('sort_order')->orderBy('id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'media_id', 'url', 'approved_at', 'is_active', 'sort_order'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected function mediaUsageLabel(): string
    {
        return 'Nos partenaires · '.$this->name;
    }

    protected function mediaUsageUrl(): ?string
    {
        return route('partenaires');
    }
}
