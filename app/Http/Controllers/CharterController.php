<?php

namespace App\Http\Controllers;

use App\Models\Charter;
use App\Models\CharterSignature;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CharterController extends Controller
{
    /** Lecture publique du texte en vigueur, sans signature. */
    public function publique(): View
    {
        $charter = Charter::current(Charter::LISTENING);
        abort_if($charter === null, 404);

        return view('site.charte', ['charter' => $charter]);
    }

    public function show(Request $request): View
    {
        $charter = Charter::current(Charter::LISTENING);
        abort_if($charter === null, 404);

        $user = $request->user();

        return view('espace.charte', [
            'charter' => $charter,
            'signature' => $user->currentCharterSignature(),
            'peutSigner' => $user->can('charter.sign'),
        ]);
    }

    public function sign(Request $request): RedirectResponse
    {
        abort_unless($request->user()->can('charter.sign'), 403);

        $request->validate(['charte' => ['accepted']], [
            'charte.accepted' => 'Cochez la case pour confirmer que vous avez lu la charte.',
        ]);

        $charter = Charter::current(Charter::LISTENING);
        abort_if($charter === null, 404);

        // La version signée est celle affichée : on refuse si une nouvelle version est parue entre-temps.
        if ((int) $request->input('charter_id') !== $charter->id) {
            return redirect()->route('espace.charte')
                ->withErrors(['charte' => 'Une nouvelle version de la charte vient d\'être publiée. Relisez-la avant de signer.']);
        }

        CharterSignature::firstOrCreate(
            ['user_id' => $request->user()->id, 'charter_id' => $charter->id],
            ['signed_at' => now(), 'ip' => $request->ip(), 'user_agent' => (string) $request->userAgent()],
        );

        $next = $request->session()->pull('charte_requise');

        return $next && str_starts_with($next, url('/'))
            ? redirect()->to($next)
            : redirect()->route('espace.onglet', 'ecoute')->with('status', 'Merci, votre signature est enregistrée.');
    }

    public function pdf(): Response
    {
        $charter = Charter::current(Charter::LISTENING);
        abort_if($charter === null, 404);

        return Pdf::loadView('espace.charte-pdf', ['charter' => $charter])
            ->download("charte-d-ecoute-v{$charter->version}.pdf");
    }
}
