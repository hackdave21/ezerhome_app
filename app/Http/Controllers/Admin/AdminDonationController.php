<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/** Espace Admin : dons et paiements. Liste gérée par Livewire ; export CSV comptable ici. */
class AdminDonationController extends Controller
{
    public function index(): View
    {
        abort_unless(auth()->user()->can('donations.view'), 403);

        return view('admin.dons.index');
    }

    /** Export CSV comptable : aucun nom pour les dons anonymes. */
    public function export(): StreamedResponse
    {
        abort_unless(auth()->user()->can('donations.view'), 403);

        $dons = Donation::with('payment')->latest('id')->get();

        return response()->streamDownload(function () use ($dons) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Date', 'Reference', 'Montant_FCFA', 'Moyen', 'Statut', 'Type', 'Donateur', 'Contact']);

            foreach ($dons as $don) {
                $anonyme = $don->is_anonymous;
                fputcsv($out, [
                    $don->created_at->format('Y-m-d'),
                    $don->payment?->external_ref ?? '',
                    $don->amount,
                    $don->methodLabel(),
                    ($don->payment?->status ?? PaymentStatus::Initie)->label(),
                    $anonyme ? 'anonyme' : 'nominatif',
                    $anonyme ? '' : (string) $don->donor_name,
                    $anonyme ? '' : (string) $don->donor_contact,
                ]);
            }

            fclose($out);
        }, 'dons-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv']);
    }
}
