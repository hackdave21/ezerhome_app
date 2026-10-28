<?php

namespace App\Http\Controllers;

use App\Models\DataExport;
use App\Models\DeletionRequest;
use App\Support\Phone;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EspaceController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('espace.onglet', 'ecoute');
    }

    /** Onglets non encore ouverts en phase 1 : « bientôt disponible ». */
    public function bientot(string $onglet): View
    {
        abort_unless(isset(self::BIENTOT[$onglet]), 404);

        return view('espace.bientot', ['onglet' => $onglet, 'titre' => self::BIENTOT[$onglet]]);
    }

    public function profil(Request $request): View
    {
        $user = $request->user();

        return view('espace.profil', [
            'user' => $user,
            'telephone' => $user->phone ? Phone::mask($user->phone) : null,
            'appareils' => $user->activeSessionsCount(),
            'export' => DataExport::where('user_id', $user->id)->latest('id')->first(),
            'suppressionDemandee' => DeletionRequest::where('user_id', $user->id)->where('status', 'pending')->exists(),
        ]);
    }

    public function logoutOthers(Request $request): RedirectResponse
    {
        $request->user()->logoutEverywhereElse($request->session()->getId());

        return back()->with('status', 'Tous vos autres appareils ont été déconnectés.');
    }

    private const BIENTOT = [
        'ecoute' => 'Écoute',
        'formations' => 'Formations',
        'coaching' => 'Coaching',
    ];
}
