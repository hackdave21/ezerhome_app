<?php

namespace App\Http\Controllers;

use App\Enums\AssistanceCategory;
use App\Http\Requests\AssistanceRequestStoreRequest;
use App\Models\AssistanceRequest;
use App\Services\Assistance\AssistanceWorkflow;
use App\Support\Phone;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/** Volet Assistance public : dépôt de demande (sans compte) et suivi par numéro + téléphone. */
class AssistanceController extends Controller
{
    public function create(): View
    {
        return view('site.assistance', ['categories' => AssistanceCategory::options()]);
    }

    public function store(AssistanceRequestStoreRequest $request, AssistanceWorkflow $workflow): RedirectResponse
    {
        $demande = $workflow->create([
            'full_name' => $request->validated('full_name'),
            'phone' => $request->normalizedPhone(),
            'email' => $request->validated('email'),
            'category' => $request->validated('category'),
            'description' => $request->validated('description'),
        ]);

        // Le numéro de suivi est affiché à l'écran (et notifié par ailleurs).
        return redirect()->route('assistance')->with('tracking_no', $demande->tracking_no);
    }

    public function suivi(): View
    {
        return view('site.assistance-suivi', ['demande' => null, 'introuvable' => false]);
    }

    public function track(Request $request): View
    {
        $data = $request->validate([
            'tracking_no' => ['required', 'string', 'max:20'],
            'telephone' => ['required', 'string', 'max:30'],
        ]);

        $phone = Phone::normalize($data['telephone']);

        $demande = $phone
            ? AssistanceRequest::where('tracking_no', trim($data['tracking_no']))->forPhone($phone)->first()
            : null;

        return view('site.assistance-suivi', ['demande' => $demande, 'introuvable' => $demande === null]);
    }
}
