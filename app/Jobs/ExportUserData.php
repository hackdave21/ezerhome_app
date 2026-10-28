<?php

namespace App\Jobs;

use App\Mail\DataExportReady;
use App\Models\DataExport;
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
            // Les demandes, messages et inscriptions s'ajouteront ici avec les modules concernés.
        ];

        $path = 'exports/'.Str::uuid().'.json';
        Storage::disk('local')->put($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

        $this->export->update(['path' => $path, 'ready_at' => now(), 'expires_at' => now()->addDays(7)]);

        if ($user->email) {
            Mail::to($user->email)->send(new DataExportReady(route('espace.profil')));
        }
    }
}
