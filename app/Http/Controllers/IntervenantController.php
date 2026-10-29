<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

/**
 * Espace de travail du thérapeute / coach (cahier des charges §4.4). En phase 1 : tableau de bord
 * des demandes d'écoute affectées, avec acceptation, déclinaison, clôture et demande de réaffectation.
 * La messagerie interne arrive en lot 5.
 */
class IntervenantController extends Controller
{
    public function dashboard(Request $request): View
    {
        abort_unless($request->user()->can('listening.view-assigned'), 403);

        return view('intervenant.dashboard');
    }
}
