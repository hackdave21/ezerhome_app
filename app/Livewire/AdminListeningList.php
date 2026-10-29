<?php

namespace App\Livewire;

use App\Enums\ListeningRequestStatus;
use App\Models\ListeningRequest;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;

/** Liste des demandes d'écoute pour l'Admin : filtres, recherche et statistiques anonymisées. */
class AdminListeningList extends Component
{
    public string $status = 'toutes';

    public string $urgency = 'toutes';

    public function mount(): void
    {
        abort_unless(Gate::allows('viewAny', ListeningRequest::class) && auth()->user()->can('listening.view-any'), 403);
    }

    #[Computed]
    public function requests()
    {
        return ListeningRequest::with(['therapist'])
            ->when($this->status !== 'toutes', fn ($q) => $q->where('status', $this->status))
            ->when($this->urgency !== 'toutes', fn ($q) => $q->where('urgency', $this->urgency))
            ->latest('id')
            ->get();
    }

    /** Statistiques anonymisées : volumes et délai moyen d'affectation (jamais de contenu). */
    #[Computed]
    public function stats(): array
    {
        $all = ListeningRequest::query();

        $delaiMoyenHeures = ListeningRequest::whereNotNull('assigned_at')
            ->get()
            ->avg(fn ($r) => $r->created_at->diffInHours($r->assigned_at));

        return [
            'total' => (clone $all)->count(),
            'en_attente' => (clone $all)->whereIn('status', [
                ListeningRequestStatus::Envoyee->value,
                ListeningRequestStatus::EnCoursAffectation->value,
            ])->count(),
            'en_cours' => (clone $all)->whereIn('status', [
                ListeningRequestStatus::Affectee->value,
                ListeningRequestStatus::EnCours->value,
            ])->count(),
            'cloturees' => (clone $all)->where('status', ListeningRequestStatus::Cloturee->value)->count(),
            'delai_affectation' => $delaiMoyenHeures ? round($delaiMoyenHeures, 1) : null,
        ];
    }

    public function render()
    {
        return view('livewire.admin-listening-list');
    }
}
