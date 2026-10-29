<?php

namespace App\Http\Controllers;

use App\Models\Charter;
use App\Models\DataExport;
use App\Models\DeletionRequest;
use App\Support\Phone;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EspaceController extends Controller
{
    public function index(Request $request): RedirectResponse
    {
        // Le consultant arrive sur l'écoute ; les autres profils sur leur profil (leurs espaces
        // de travail — intervenant, comité, admin — sont distincts de l'espace personnel).
        return $request->user()->can('listening.request')
            ? redirect()->route('espace.ecoute')
            : redirect()->route('espace.profil');
    }

    /** Onglets non encore ouverts en phase 1 : « bientôt disponible ». */
    public function bientot(Request $request, string $onglet): View
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
            'charte' => $user->currentCharterSignature(),
            'charteVersion' => Charter::current(Charter::LISTENING),
            'suppressionDemandee' => DeletionRequest::where('user_id', $user->id)->where('status', 'pending')->exists(),
        ]);
    }

    public function logoutOthers(Request $request): RedirectResponse
    {
        $request->user()->logoutEverywhereElse($request->session()->getId());

        return back()->with('status', 'Tous vos autres appareils ont été déconnectés.');
    }

    private const BIENTOT = [
        'formations' => 'Formations',
        'coaching' => 'Coaching',
    ];
}
