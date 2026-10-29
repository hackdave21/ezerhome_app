<?php

namespace App\Models\Concerns;

use App\Models\MediaUsage;
use Illuminate\Database\Eloquent\Model;

/**
 * Enregistre dans « media_usages » le fichier utilisé par le modèle (photo de membre, logo de
 * partenaire…). Tant qu'un usage existe, la médiathèque refuse de supprimer le fichier et liste
 * les pages concernées. L'usage suit le modèle : il est recréé à chaque enregistrement et retiré
 * quand le modèle est supprimé ou que son média est détaché.
 *
 * @property-read string $media_usage_column  Colonne contenant l'identifiant du média.
 */
trait TracksMediaUsage
{
    public static function bootTracksMediaUsage(): void
    {
        static::saved(fn (Model $model) => $model->syncMediaUsage());
        static::deleted(fn (Model $model) => $model->clearMediaUsage());
    }

    /** Colonne portant l'identifiant du média (par défaut « media_id »). */
    protected function mediaUsageColumn(): string
    {
        return 'media_id';
    }

    /** Libellé affiché dans la liste « pages concernées » de la médiathèque. */
    abstract protected function mediaUsageLabel(): string;

    /** Lien vers la page où le fichier apparaît. */
    abstract protected function mediaUsageUrl(): ?string;

    public function syncMediaUsage(): void
    {
        $this->clearMediaUsage();

        $mediaId = $this->getAttribute($this->mediaUsageColumn());

        if ($mediaId !== null) {
            MediaUsage::create([
                'media_asset_id' => $mediaId,
                'model_type' => $this->getMorphClass(),
                'model_id' => $this->getKey(),
                'label' => $this->mediaUsageLabel(),
                'url' => $this->mediaUsageUrl(),
            ]);
        }
    }

    public function clearMediaUsage(): void
    {
        MediaUsage::where('model_type', $this->getMorphClass())
            ->where('model_id', $this->getKey())
            ->delete();
    }
}
