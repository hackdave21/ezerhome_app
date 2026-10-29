<div class="flex flex-col gap-5">
    @php $requests = $this->requests; @endphp

    <div>
        <h1 class="font-cooper font-normal text-2xl lg:text-[32px]">Demandes d'assistance</h1>
        <p class="text-[15px] text-ezer-muted">Espace confidentiel réservé au comité. Les coordonnées ne quittent jamais la plateforme.</p>
    </div>

    <div class="flex flex-wrap gap-2 items-end">
        <div class="flex flex-col gap-1">
            <label for="fa-recherche" class="text-[13px] font-semibold">Numéro de suivi</label>
            <input id="fa-recherche" type="search" wire:model.live.debounce.300ms="search" placeholder="ASS-2026-…" class="h-[44px] px-3 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
        </div>
        <div class="flex flex-col gap-1">
            <label for="fa-statut" class="text-[13px] font-semibold">Statut</label>
            <select id="fa-statut" wire:model.live="status" class="h-[44px] px-2 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
                <option value="toutes">Tous</option>
                @foreach ($this->statuses as $cas)
                    <option value="{{ $cas->value }}">{{ $cas->label() }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-col gap-1">
            <label for="fa-cat" class="text-[13px] font-semibold">Catégorie</label>
            <select id="fa-cat" wire:model.live="category" class="h-[44px] px-2 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
                <option value="toutes">Toutes</option>
                @foreach ($this->categories as $cle => $label)
                    <option value="{{ $cle }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-col gap-1">
            <label for="fa-du" class="text-[13px] font-semibold">Du</label>
            <input id="fa-du" type="date" wire:model.live="from" class="h-[44px] px-2 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
        </div>
        <div class="flex flex-col gap-1">
            <label for="fa-au" class="text-[13px] font-semibold">Au</label>
            <input id="fa-au" type="date" wire:model.live="to" class="h-[44px] px-2 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
        </div>
    </div>

    @if ($requests->isEmpty())
        <div class="p-8 border border-ezer-green-border rounded-ezer text-center">
            <p class="text-ezer-muted">Aucune demande ne correspond à ces filtres.</p>
        </div>
    @else
        <ul class="flex flex-col gap-2 list-none">
            @foreach ($requests as $demande)
                <li wire:key="ar-{{ $demande->id }}">
                    <a href="{{ route('comite.demandes.show', $demande) }}" class="flex flex-wrap items-center gap-3 p-4 border border-ezer-green-border rounded-ezer bg-white hover:bg-ezer-green-pale transition-colors">
                        <span class="font-semibold text-sm">{{ $demande->tracking_no }}</span>
                        <span class="inline-flex items-center px-2.5 min-h-[28px] rounded-ezer bg-ezer-green-pale text-ezer-green text-xs font-semibold">{{ $demande->category->label() }}</span>
                        <span class="text-[13px] text-ezer-muted">{{ $demande->created_at->locale('fr')->translatedFormat('j M Y') }}</span>
                        <span class="flex-1"></span>
                        <span class="inline-flex items-center px-3 min-h-[32px] rounded-ezer bg-ezer-green-light text-ezer-green text-[13px] font-semibold">{{ $demande->status->label() }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
