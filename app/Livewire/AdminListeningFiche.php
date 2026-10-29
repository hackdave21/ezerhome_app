<?php

namespace App\Livewire;

use App\Models\ListeningRequest;
use App\Models\User;
use App\Services\Listening\ListeningWorkflow;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;

/** Fiche Admin d'une demande d'écoute : affectation manuelle, réaffectation et clôture. */
class AdminListeningFiche extends Component
{
    public ListeningRequest $demande;

    public ?int $therapistId = null;

    public function mount(): void
    {
        abort_unless(Gate::allows('view', $this->demande), 403);
        $this->therapistId = $this->demande->assigned_therapist_id;
    }

    /** Thérapeutes validés (rôle attribué, 2FA confirmée) disponibles pour une affectation manuelle. */
    #[Computed]
    public function therapists()
    {
        return User::role('therapeute')
            ->whereNotNull('two_factor_confirmed_at')
            ->orderBy('name')
            ->get();
    }

    public function assign(ListeningWorkflow $workflow): void
    {
        abort_unless(Gate::allows('assign', ListeningRequest::class), 403);

        $this->validate(['therapistId' => ['required', 'exists:users,id']], [
            'therapistId.required' => 'Choisissez un intervenant à qui affecter la demande.',
        ]);

        $therapist = User::role('therapeute')->findOrFail($this->therapistId);

        $workflow->assign($this->demande, $therapist, auth()->user());

        $this->demande->refresh();
    }

    public function close(ListeningWorkflow $workflow): void
    {
        abort_unless(Gate::allows('assign', ListeningRequest::class), 403);

        $workflow->close($this->demande);
        $this->demande->refresh();
    }

    public function render()
    {
        return view('livewire.admin-listening-fiche');
    }
}
