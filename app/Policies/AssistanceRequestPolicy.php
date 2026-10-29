<?php

namespace App\Policies;

use App\Models\AssistanceRequest;
use App\Models\User;

/**
 * Assistance : visible uniquement du Comité et de l'Admin (matrice §3.2) ; jamais des thérapeutes,
 * formateurs ou consultants. Chaque accès à une fiche par ces rôles est journalisé côté contrôleur.
 */
class AssistanceRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('assistance.view');
    }

    public function view(User $user, AssistanceRequest $request): bool
    {
        return $user->can('assistance.view');
    }

    public function updateStatus(User $user, AssistanceRequest $request): bool
    {
        return $user->can('assistance.update-status');
    }
}
