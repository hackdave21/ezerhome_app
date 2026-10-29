<?php

namespace App\Livewire;

use App\Enums\ListeningRequestStatus;
use App\Models\ListeningRequest;
use App\Services\Listening\ListeningWorkflow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;

/** Tableau de bord du thérapeute : demandes à accepter/décliner, accompagnements en cours, clôturés. */
class TherapistDashboard extends Component
{
    /** Formulaire de motif (déclinaison ou demande de réaffectation) ouvert pour une demande. */
    public ?int $reasonFor = null;

    public string $reason = '';

    public function mount(): void
    {
        abort_unless(Auth::user()?->can('listening.view-assigned'), 403);
    }

    #[Computed]
    public function requests()
    {
        return ListeningRequest::where('assigned_therapist_id', Auth::id())
            ->with('consultant')
            ->latest('assigned_at')
            ->get();
    }

    private function mine(int $id): ListeningRequest
    {
        $demande = ListeningRequest::findOrFail($id);
        abort_unless(Gate::allows('act', $demande), 403);

        return $demande;
    }

    public function accept(int $id, ListeningWorkflow $workflow): void
    {
        $demande = $this->mine($id);

        if ($demande->status === ListeningRequestStatus::Affectee) {
            $workflow->accept($demande);
        }

        unset($this->requests);
    }

    public function openReason(int $id): void
    {
        $this->reasonFor = $id;
        $this->reason = '';
        $this->resetErrorBag();
    }

    public function cancelReason(): void
    {
        $this->reasonFor = null;
        $this->reason = '';
    }

    /** Décliner (avant acceptation) ou demander une réaffectation (après) : motif obligatoire, tracé. */
    public function release(ListeningWorkflow $workflow): void
    {
        $demande = $this->mine((int) $this->reasonFor);

        $this->validate(['reason' => ['required', 'string', 'min:5', 'max:300']], [
            'reason.required' => 'Indiquez un motif : il est transmis au bureau, jamais au consultant.',
        ]);

        $workflow->release($demande, $this->reason);

        $this->cancelReason();
        unset($this->requests);
    }

    public function close(int $id, ListeningWorkflow $workflow): void
    {
        $demande = $this->mine($id);

        if ($demande->status === ListeningRequestStatus::EnCours) {
            $workflow->close($demande);
        }

        unset($this->requests);
    }

    public function render()
    {
        return view('livewire.therapist-dashboard');
    }
}
