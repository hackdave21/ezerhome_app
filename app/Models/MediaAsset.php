<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class MediaAsset extends Model
{
    public const ASSISTANCE = 'assistance';

    public const LOGO_PARTENAIRE = 'logo_partenaire';

    public const ORGANIGRAMME = 'organigramme';

    public const GENERAL = 'general';

    /** Libellés des catégories, tels que dans la maquette. */
    public const CATEGORIES = [
        self::ASSISTANCE => "Actions d'assistance",
        self::ORGANIGRAMME => 'Équipe',
        self::LOGO_PARTENAIRE => 'Partenaires',
        self::GENERAL => 'Documents',
    ];

    public const PUBLIC_DISK = 'public_media';

    public const PRIVATE_DISK = 'private_media';

    protected $fillable = [
        'disk', 'path', 'thumb_path', 'original_name', 'title', 'alt', 'caption', 'credit', 'category',
        'mime', 'size', 'width', 'height', 'is_public', 'consent_confirmed', 'action_date', 'linked_request',
        'status', 'incoming_path', 'uploaded_by', 'archived_at',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'consent_confirmed' => 'boolean',
            'action_date' => 'date',
            'archived_at' => 'datetime',
            'size' => 'integer',
        ];
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function usages(): HasMany
    {
        return $this->hasMany(MediaUsage::class);
    }

    public function isImage(): bool
    {
        return str_starts_with((string) $this->mime, 'image/');
    }

    public function isReady(): bool
    {
        return $this->status === 'ready' && $this->path !== null;
    }

    public function isArchived(): bool
    {
        return $this->archived_at !== null;
    }

    /** Un fichier est servi au public s'il est publiable, prêt et non archivé. */
    public function isPubliclyServed(): bool
    {
        return $this->is_public && $this->isReady() && ! $this->isArchived();
    }

    public function categoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    /** URL stable (l'identifiant ne change jamais, même quand on remplace le fichier). */
    public function url(bool $thumb = false): string
    {
        return route('media.file', array_filter(['asset' => $this->id, 'variant' => $thumb ? 'miniature' : null]));
    }

    public function diskFor(bool $public): string
    {
        return $public ? self::PUBLIC_DISK : self::PRIVATE_DISK;
    }

    /** Supprime les fichiers physiques (final, miniature, envoi en attente). */
    public function deleteFiles(): void
    {
        foreach ([[$this->disk, $this->path], [$this->disk, $this->thumb_path], [self::PRIVATE_DISK, $this->incoming_path]] as [$disk, $path]) {
            if ($path) {
                Storage::disk($disk)->delete($path);
            }
        }
    }

    /** Déplace les fichiers vers le disque public ou privé selon is_public. */
    public function syncDisk(): void
    {
        $target = $this->diskFor($this->is_public);

        if ($this->disk === $target || ! $this->isReady()) {
            return;
        }

        foreach (['path', 'thumb_path'] as $column) {
            if ($this->{$column}) {
                $contents = Storage::disk($this->disk)->get($this->{$column});
                Storage::disk($target)->put($this->{$column}, $contents);
                Storage::disk($this->disk)->delete($this->{$column});
            }
        }

        $this->update(['disk' => $target]);
    }
}
