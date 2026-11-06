<?php

namespace App\Jobs;

use App\Mail\DataExportReady;
use App\Models\CharterSignature;
use App\Models\DataExport;
use App\Models\ListeningRequest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/** Prépare le fichier JSON des données d'un compte, puis prévient l'intéressé sans y joindre de contenu. */
class ExportUserData implements ShouldQueue
{
    use Queueable;

    public function __construct(public DataExport $export) {}

    public function handle(): void
    {
        $user = $this->export->user;

        $data = [
            'exporte_le' => now()->toIso8601String(),
            'profil' => [
                'nom' => $user->name,
                'email' => $user->email,
                'telephone' => $user->phone,
                'langue' => $user->locale,
                'contact_prefere' => $user->preferred_contact,
                'statut' => $user->status->value,
                'inscrit_le' => $user->created_at?->toIso8601String(),
                'email_verifie_le' => $user->email_verified_at?->toIso8601String(),
                'telephone_verifie_le' => $user->phone_verified_at?->toIso8601String(),
                'derniere_activite' => $user->last_activity_at?->toIso8601String(),
            ],
            'roles' => $user->getRoleNames()->all(),

            // Données de la phase 1 : demandes d'écoute et signatures de charte.
            // Les messages et inscriptions s'ajouteront avec les modules de la phase 2.
            'demandes_ecoute' => ListeningRequest::where('consultant_id', $user->id)
                ->orderBy('id')
                ->get()
                ->map(fn (ListeningRequest $demande) => [
                    'reference' => $demande->shortRef(),
                    'statut' => $demande->status->label(),
                    'urgence' => $demande->urgencyLabel(),
                    'canal_prefere' => $demande->channelLabel(),
                    'description' => $demande->description,
                    'creee_le' => $demande->created_at?->toIso8601String(),
                    'affectee_le' => $demande->assigned_at?->toIso8601String(),
                    'acceptee_le' => $demande->accepted_at?->toIso8601String(),
                    'cloturee_le' => $demande->closed_at?->toIso8601String(),
                ])
                ->all(),

            'signatures_charte' => CharterSignature::where('user_id', $user->id)
                ->with('charter')
                ->orderBy('id')
                ->get()
                ->map(fn (CharterSignature $signature) => [
                    'charte' => $signature->charter?->type,
                    'version' => $signature->charter?->version,
                    'signee_le' => $signature->signed_at?->toIso8601String(),
                ])
                ->all(),
        ];

        $path = 'exports/'.Str::uuid().'.json';
        Storage::disk('local')->put($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $this->export->update(['path' => $path, 'ready_at' => now(), 'expires_at' => now()->addDays(7)]);

        if ($user->email) {
            Mail::to($user->email)->send(new DataExportReady(route('espace.profil')));
        }
    }
}
