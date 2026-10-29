<?php

namespace App\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

/** Journal d'audit (Admin) : consultation et filtres, en lecture seule. Aucune action de modification. */
class AdminAudit extends Component
{
    use WithPagination;

    public string $event = 'tous';

    public string $subjectType = 'tous';

    public string $from = '';

    public string $to = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('audit.view'), 403);
    }

    public function updating(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function events(): array
    {
        return Activity::query()->whereNotNull('event')->distinct()->orderBy('event')->pluck('event')->all();
    }

    #[Computed]
    public function subjectTypes(): array
    {
        return Activity::query()->whereNotNull('subject_type')->distinct()->pluck('subject_type')->all();
    }

    #[Computed]
    public function activities()
    {
        return Activity::query()
            ->with(['causer', 'subject'])
            ->when($this->event !== 'tous', fn ($q) => $q->where('event', $this->event))
            ->when($this->subjectType !== 'tous', fn ($q) => $q->where('subject_type', $this->subjectType))
            ->when($this->from !== '', fn ($q) => $q->whereDate('created_at', '>=', $this->from))
            ->when($this->to !== '', fn ($q) => $q->whereDate('created_at', '<=', $this->to))
            ->latest('id')
            ->paginate(30);
    }

    public function render()
    {
        return view('livewire.admin-audit');
    }
}
