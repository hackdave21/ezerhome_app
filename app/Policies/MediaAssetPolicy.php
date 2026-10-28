<?php

namespace App\Policies;

use App\Models\MediaAsset;
use App\Models\User;

/**
 * Médiathèque : l'Admin gère tout ; le Comité ne gère que la catégorie « assistance » ;
 * les autres rôles n'ont aucun accès. Un fichier publiable est lisible par tous (site public).
 */
class MediaAssetPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['media.manage', 'media.manage-assistance']);
    }

    /** Lecture : publiable et prêt → tout le monde ; sinon, seulement ceux qui le gèrent. */
    public function view(?User $user, MediaAsset $asset): bool
    {
        return $asset->isPubliclyServed() || ($user !== null && $this->manages($user, $asset));
    }

    public function create(User $user, ?string $category = null): bool
    {
        return $category === null ? $this->viewAny($user) : $this->canManageCategory($user, $category);
    }

    public function update(User $user, MediaAsset $asset): bool
    {
        return $this->manages($user, $asset);
    }

    public function delete(User $user, MediaAsset $asset): bool
    {
        return $this->manages($user, $asset);
    }

    /** Catégories qu'un utilisateur peut utiliser (pour les listes et filtres). */
    public static function categoriesFor(User $user): array
    {
        return $user->can('media.manage')
            ? array_keys(MediaAsset::CATEGORIES)
            : ($user->can('media.manage-assistance') ? [MediaAsset::ASSISTANCE] : []);
    }

    private function manages(User $user, MediaAsset $asset): bool
    {
        return $this->canManageCategory($user, $asset->category);
    }

    private function canManageCategory(User $user, string $category): bool
    {
        return $user->can('media.manage')
            || ($user->can('media.manage-assistance') && $category === MediaAsset::ASSISTANCE);
    }
}
