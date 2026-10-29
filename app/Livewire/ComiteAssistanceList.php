<?php

namespace App\Livewire;

use App\Enums\AssistanceCategory;
use App\Enums\AssistanceStatus;
use App\Models\AssistanceRequest;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;

/** Liste des demandes d'assistance : filtres (statut, catégorie, période) et recherche par numéro. */
class ComiteAssistanceList extends Component
{
    public string $status = 'toutes';

    public string $category = 'toutes';

    public string $from = '';

    public string $to = '';

    public string $search = '';

    public function mount(): void
    {
        abort_unless(Gate::allows('viewAny', AssistanceRequest::class), 403);
    }

    #[Computed]
    public function requests()
    {
        return AssistanceRequest::query()
            ->when($this->status !== 'toutes', fn ($q) => $q->where('status', $this->status))
            ->when($this->category !== 'toutes', fn ($q) => $q->where('category', $this->category))
            ->when($this->from !== '', fn ($q) => $q->whereDate('created_at', '>=', $this->from))
            ->when($this->to !== '', fn ($q) => $q->whereDate('created_at', '<=', $this->to))
            ->when(trim($this->search) !== '', fn ($q) => $q->where('tracking_no', 'like', '%'.trim($this->search).'%'))
            ->latest('id')
            ->get();
    }

    #[Computed]
    public function statuses(): array
    {
        return AssistanceStatus::cases();
    }

    #[Computed]
    public function categories(): array
    {
        return AssistanceCategory::options();
    }

    public function render()
    {
        return view('livewire.comite-assistance-list');
    }
}
