<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ListeningRequest;
use Illuminate\Contracts\View\View;

/**
 * Espace Admin : liste des demandes d'écoute et fiche. Chaque ouverture de fiche est journalisée
 * (qui, quand, quelle demande), sans jamais exposer le contenu chiffré dans le journal.
 */
class AdminListeningController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', ListeningRequest::class);

        return view('admin.ecoute.index');
    }

    public function show(ListeningRequest $listeningRequest): View
    {
        $this->authorize('view', $listeningRequest);

        // Traçabilité : l'accès de l'Admin à un dossier sensible est journalisé.
        activity()
            ->performedOn($listeningRequest)
            ->causedBy(auth()->user())
            ->event('viewed')
            ->log('Consultation de la fiche de demande d\'écoute');

        return view('admin.ecoute.show', ['demande' => $listeningRequest->load(['consultant', 'therapist', 'assignedBy'])]);
    }
}
