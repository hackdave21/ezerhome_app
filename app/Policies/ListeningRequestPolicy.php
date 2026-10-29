<?php

namespace App\Policies;

use App\Models\ListeningRequest;
use App\Models\User;

/**
 * Cloisonnement de l'écoute (matrice §3.2) :
 * - le consultant ne voit que ses propres demandes ;
 * - le thérapeute ne voit que les demandes qui lui sont affectées ;
 * - l'Admin voit tout, et chacun de ses accès à une fiche est journalisé ;
 * - personne d'autre n'y accède.
 */
class ListeningRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('listening.view-any') || $user->can('listening.view-own') || $user->can('listening.view-assigned');
    }

    public function view(User $user, ListeningRequest $request): bool
    {
        if ($user->can('listening.view-any')) {
            return true;
        }

        if ($user->can('listening.view-own') && $request->consultant_id === $user->id) {
            return true;
        }

        return $user->can('listening.view-assigned') && $request->assigned_therapist_id === $user->id;
    }

    /** Créer une demande : consultant, charte en vigueur signée (défense en profondeur). */
    public function create(User $user): bool
    {
        return $user->can('listening.request') && $user->hasSignedCurrentCharter();
    }

    /** Affecter, réaffecter, clôturer côté bureau : réservé à l'Admin. */
    public function assign(User $user): bool
    {
        return $user->can('assignments.manage');
    }

    /** Le thérapeute affecté accepte, décline, clôture ou demande une réaffectation. */
    public function act(User $user, ListeningRequest $request): bool
    {
        return $user->can('listening.view-assigned') && $request->assigned_therapist_id === $user->id;
    }
}
