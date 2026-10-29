<?php

namespace App\Services\Assistance;

use App\Enums\AssistanceStatus;
use App\Mail\AssistanceNotification;
use App\Models\AssistanceEvent;
use App\Models\AssistanceRequest;
use App\Models\User;
use App\Support\Phone;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Logique métier de l'assistance : dépôt public, changement de statut, notes internes et
 * décaissements. Chaque changement de statut est historisé (assistance_events) et notifie le
 * demandeur, sans aucun détail sensible.
 */
class AssistanceWorkflow
{
    public function __construct(private TrackingNumberGenerator $tracking) {}

    /**
     * Dépôt d'une demande publique. $phoneNormalized est le numéro déjà normalisé (+228…).
     *
     * @param  array{full_name: string, phone: string, email: ?string, category: string, description: string}  $data
     */
    public function create(array $data): AssistanceRequest
    {
        $request = AssistanceRequest::create([
            'tracking_no' => $this->tracking->next(),
            'full_name' => $data['full_name'],
            'phone' => $data['phone'],
            'phone_hash' => Phone::hash($data['phone']),
            'email' => $data['email'] ?: null,
            'category' => $data['category'],
            'description' => $data['description'],
            'consent_at' => now(),
            'status' => AssistanceStatus::Recue,
        ]);

        $this->logEvent($request, AssistanceStatus::Recue, null, null);
        $this->notify($request, 'received');

        return $request;
    }

    /** Changement de statut par le Comité ou l'Admin, historisé et notifié au demandeur. */
    public function changeStatus(AssistanceRequest $request, AssistanceStatus $status, ?string $note, User $actor): void
    {
        $request->update(['status' => $status]);
        $this->logEvent($request, $status, $note, $actor);

        if ($status !== AssistanceStatus::Recue) {
            $this->notify($request, 'status_'.$status->value);
        }
    }

    /** Ajout d'une note interne sans changement de statut (historisée). */
    public function addNote(AssistanceRequest $request, string $note, User $actor): void
    {
        $this->logEvent($request, null, $note, $actor);
    }

    public function addDisbursement(AssistanceRequest $request, int $amount, string $date, User $actor, ?string $label): void
    {
        $request->disbursements()->create([
            'amount' => $amount,
            'disbursed_on' => $date,
            'responsible_id' => $actor->id,
            'label' => $label,
        ]);
    }

    private function logEvent(AssistanceRequest $request, ?AssistanceStatus $status, ?string $note, ?User $actor): void
    {
        AssistanceEvent::create([
            'assistance_request_id' => $request->id,
            'status' => $status,
            'note' => $note ?: null,
            'actor_id' => $actor?->id,
            'created_at' => now(),
        ]);
    }

    /** E-mail si disponible (file d'attente) ; SMS/WhatsApp via la passerelle quand elle existera. */
    private function notify(AssistanceRequest $request, string $type): void
    {
        if ($request->email) {
            Mail::to($request->email)->queue(new AssistanceNotification($type, ['ref' => $request->tracking_no]));
        }

        // Pas de passerelle SMS en phase 1 : on trace l'intention en local/testing seulement.
        if (app()->environment('local', 'testing')) {
            Log::info("[Assistance] notification {$type} vers {$request->phone} (réf. {$request->tracking_no}).");
        }
    }
}
