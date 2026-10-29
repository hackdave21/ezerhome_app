<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListeningRequestStoreRequest;
use App\Models\ListeningRequest;
use App\Services\Listening\ListeningWorkflow;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/** Espace Consultant : formulaire de demande d'écoute et suivi de ses propres demandes. */
class ListeningController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', ListeningRequest::class);

        return view('espace.ecoute.index', [
            'demandes' => ListeningRequest::where('consultant_id', $request->user()->id)->latest('id')->get(),
        ]);
    }

    public function store(ListeningRequestStoreRequest $request, ListeningWorkflow $workflow): RedirectResponse
    {
        $demande = $workflow->create(
            $request->user(),
            $request->validated('description'),
            $request->validated('urgency'),
            $request->validated('preferred_channel'),
        );

        return redirect()->route('espace.ecoute.show', $demande)
            ->with('status', 'Votre demande a bien été envoyée.');
    }

    public function show(ListeningRequest $listeningRequest): View
    {
        $this->authorize('view', $listeningRequest);

        return view('espace.ecoute.show', ['demande' => $listeningRequest]);
    }
}
