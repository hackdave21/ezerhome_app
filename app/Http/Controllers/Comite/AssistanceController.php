<?php

namespace App\Http\Controllers\Comite;

use App\Http\Controllers\Controller;
use App\Models\AssistanceRequest;
use Illuminate\Contracts\View\View;

/**
 * Espace Comité : liste et fiche des demandes d'assistance. Réservé au Comité et à l'Admin
 * (policy). Chaque ouverture de fiche est journalisée, sans jamais exposer les champs chiffrés.
 */
class AssistanceController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', AssistanceRequest::class);

        return view('comite.assistance.index');
    }

    public function show(AssistanceRequest $assistanceRequest): View
    {
        $this->authorize('view', $assistanceRequest);

        activity()
            ->performedOn($assistanceRequest)
            ->causedBy(auth()->user())
            ->event('viewed')
            ->log('Consultation d\'une fiche d\'assistance');

        return view('comite.assistance.show', ['demande' => $assistanceRequest]);
    }
}
