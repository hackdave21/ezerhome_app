<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AssistanceStatus;
use App\Enums\ListeningRequestStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\AssistanceRequest;
use App\Models\Donation;
use App\Models\ListeningRequest;
use App\Models\Payment;
use Illuminate\Contracts\View\View;

/** Tableau de bord Admin : vue de pilotage (phase 1). Aucun contenu sensible, seulement des volumes. */
class DashboardController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->hasRole('admin'), 403);

        return view('admin.dashboard', [
            'ecouteEnAttente' => ListeningRequest::whereIn('status', [
                ListeningRequestStatus::Envoyee->value,
                ListeningRequestStatus::EnCoursAffectation->value,
            ])->count(),

            'assistanceEnCours' => AssistanceRequest::whereIn('status', [
                AssistanceStatus::Recue->value,
                AssistanceStatus::EnExamen->value,
                AssistanceStatus::Acceptee->value,
            ])->count(),

            'paiementsAConfirmer' => Payment::where('status', PaymentStatus::EnAttente->value)->count(),

            'donsDuMois' => Donation::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count(),
            'montantConfirmeMois' => (int) Payment::where('status', PaymentStatus::Paye->value)
                ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('amount'),
        ]);
    }
}
