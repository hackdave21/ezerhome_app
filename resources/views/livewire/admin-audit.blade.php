<div class="flex flex-col gap-5">
    @php $activities = $this->activities; @endphp

    <div>
        <h1 class="font-cooper font-normal text-2xl lg:text-[32px]">Journal d'audit</h1>
        <p class="text-[15px] text-ezer-muted">Qui a fait quoi et quand. Consultation seule : le journal n'est jamais modifiable.</p>
    </div>

    <div class="flex flex-wrap gap-2 items-end">
        <div class="flex flex-col gap-1">
            <label for="a-event" class="text-[13px] font-semibold">Action</label>
            <select id="a-event" wire:model.live="event" class="h-[44px] px-2 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
                <option value="tous">Toutes</option>
                @foreach ($this->events as $e)<option value="{{ $e }}">{{ $e }}</option>@endforeach
            </select>
        </div>
        <div class="flex flex-col gap-1">
            <label for="a-type" class="text-[13px] font-semibold">Ressource</label>
            <select id="a-type" wire:model.live="subjectType" class="h-[44px] px-2 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
                <option value="tous">Toutes</option>
                @foreach ($this->subjectTypes as $t)<option value="{{ $t }}">{{ class_basename($t) }}</option>@endforeach
            </select>
        </div>
        <div class="flex flex-col gap-1">
            <label for="a-du" class="text-[13px] font-semibold">Du</label>
            <input id="a-du" type="date" wire:model.live="from" class="h-[44px] px-2 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
        </div>
        <div class="flex flex-col gap-1">
            <label for="a-au" class="text-[13px] font-semibold">Au</label>
            <input id="a-au" type="date" wire:model.live="to" class="h-[44px] px-2 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
        </div>
    </div>

    @if ($activities->isEmpty())
        <div class="p-8 border border-ezer-green-border rounded-ezer text-center"><p class="text-ezer-muted">Aucune entrée pour ces filtres.</p></div>
    @else
        <ul class="flex flex-col gap-2 list-none">
            @foreach ($activities as $a)
                <li wire:key="act-{{ $a->id }}" class="flex flex-col gap-0.5 p-3 border border-ezer-green-border rounded-ezer">
                    <span class="text-[13px] text-ezer-muted">{{ $a->created_at->locale('fr')->translatedFormat('j M Y à H\hi') }} · {{ $a->causer?->name ?? 'Système' }}</span>
                    <span class="text-sm font-semibold">{{ $a->description }}@if ($a->event) <span class="text-ezer-muted font-normal">({{ $a->event }})</span>@endif</span>
                    @if ($a->subject_type)<span class="text-[13px] text-ezer-muted">{{ class_basename($a->subject_type) }} #{{ $a->subject_id }}</span>@endif
                </li>
            @endforeach
        </ul>

        <div>{{ $activities->links() }}</div>
    @endif
</div>
