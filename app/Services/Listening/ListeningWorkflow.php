<?php

namespace App\Services\Listening;

use App\Enums\ListeningRequestStatus;
use App\Mail\ListeningNotification;
use App\Models\ListeningRequest;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

/**
 * Logique métier de l'écoute : création, affectation manuelle, réponse du thérapeute et clôture.
 * Centralise les transitions de statut, les notifications (file d'attente, sans contenu sensible)
 * et l'audit. Aucun algorithme de matching : l'affectation est toujours décidée par un humain.
 */
class ListeningWorkflow
{
    /** Création par le consultant. La charte est vérifiée en amont (middleware + policy). */
    public function create(User $consultant, string $description, ?string $urgency, ?string $channel): ListeningRequest
    {
        $request = ListeningRequest::create([
            'consultant_id' => $consultant->id,
            'description' => $description,
            'urgency' => $urgency ?: null,
            'preferred_channel' => $channel ?: null,
            'status' => ListeningRequestStatus::Envoyee,
        ]);

        // L'Admin est prévenu, sans aucun contenu de la demande.
        Mail::to(Setting::get('contact.email'))->queue(
            new ListeningNotification('received', ['ref' => $request->shortRef()])
        );

        return $request;
    }

    /** Affectation ou réaffectation manuelle à un thérapeute validé, par l'Admin. */
    public function assign(ListeningRequest $request, User $therapist, User $admin): void
    {
        $request->update([
            'assigned_therapist_id' => $therapist->id,
            'assigned_by' => $admin->id,
            'assigned_at' => now(),
            'accepted_at' => null,
            'decline_reason' => null,
            'status' => ListeningRequestStatus::Affectee,
        ]);

        Mail::to($therapist)->queue(new ListeningNotification('assigned_therapist', ['ref' => $request->shortRef()]));

        if ($request->consultant) {
            Mail::to($request->consultant)->queue(new ListeningNotification('assigned_consultant', [
                'ref' => $request->shortRef(),
                'canal' => $request->channelLabel() ?? 'votre canal de contact',
            ]));
        }
    }

    /** Le thérapeute accepte la demande qui lui est affectée. */
    public function accept(ListeningRequest $request): void
    {
        $request->update([
            'accepted_at' => now(),
            'status' => ListeningRequestStatus::EnCours,
        ]);
    }

    /**
     * Le thérapeute décline (avant acceptation) ou demande une réaffectation (après) : la demande
     * repart vers le bureau, sans intervenant, avec un motif tracé dans l'audit.
     */
    public function release(ListeningRequest $request, string $reason): void
    {
        $request->update([
            'assigned_therapist_id' => null,
            'assigned_at' => null,
            'accepted_at' => null,
            'decline_reason' => $reason,
            'status' => ListeningRequestStatus::EnCoursAffectation,
        ]);

        Mail::to(Setting::get('contact.email'))->queue(
            new ListeningNotification('declined_admin', ['ref' => $request->shortRef()])
        );
    }

    /** Clôture de l'accompagnement (Admin ou thérapeute affecté). */
    public function close(ListeningRequest $request): void
    {
        $request->update([
            'status' => ListeningRequestStatus::Cloturee,
            'closed_at' => now(),
        ]);

        if ($request->consultant) {
            Mail::to($request->consultant)->queue(
                new ListeningNotification('closed_consultant', ['ref' => $request->shortRef()])
            );
        }
    }
}
