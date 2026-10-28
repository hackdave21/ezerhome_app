<?php

namespace App\Policies;

use App\Models\User;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/** Médiathèque : l'Admin gère tout ; le Comité ne gère que la catégorie « assistance ». */
class MediaPolicy
{
    public const ASSISTANCE_COLLECTION = 'assistance';

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['media.manage', 'media.manage-assistance']);
    }

    public function view(User $user, Media $media): bool
    {
        return $this->manages($user, $media);
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, Media $media): bool
    {
        return $this->manages($user, $media);
    }

    public function delete(User $user, Media $media): bool
    {
        return $this->manages($user, $media);
    }

    private function manages(User $user, Media $media): bool
    {
        return $user->can('media.manage')
            || ($user->can('media.manage-assistance') && $media->collection_name === self::ASSISTANCE_COLLECTION);
    }
}
