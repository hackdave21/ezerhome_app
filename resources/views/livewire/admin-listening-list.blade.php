<div class="flex flex-col gap-5">
    @php $stats = $this->stats; $requests = $this->requests; @endphp

    <div>
        <h1 class="font-cooper font-normal text-2xl lg:text-[32px]">Demandes d'écoute</h1>
        <p class="text-[15px] text-ezer-muted">Affectez manuellement chaque demande à un intervenant validé. Aucune mise en relation automatique.</p>
    </div>

    {{-- Statistiques anonymisées --}}
    <dl class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="flex flex-col gap-0.5 p-4 border border-ezer-green-border rounded-ezer">
            <dt class="text-[13px] text-ezer-muted">Total</dt>
            <dd class="text-2xl font-semibold">{{ $stats['total'] }}</dd>
        </div>
        <div class="flex flex-col gap-0.5 p-4 border border-ezer-green-border rounded-ezer">
            <dt class="text-[13px] text-ezer-muted">À affecter</dt>
            <dd class="text-2xl font-semibold">{{ $stats['en_attente'] }}</dd>
        </div>
        <div class="flex flex-col gap-0.5 p-4 border border-ezer-green-border rounded-ezer">
            <dt class="text-[13px] text-ezer-muted">En cours</dt>
            <dd class="text-2xl font-semibold">{{ $stats['en_cours'] }}</dd>
        </div>
        <div class="flex flex-col gap-0.5 p-4 border border-ezer-green-border rounded-ezer">
            <dt class="text-[13px] text-ezer-muted">Délai moyen d'affectation</dt>
            <dd class="text-2xl font-semibold">{{ $stats['delai_affectation'] !== null ? $stats['delai_affectation'].' h' : '—' }}</dd>
        </div>
    </dl>

    {{-- Filtres --}}
    <div class="flex flex-wrap gap-2 items-end">
        <div class="flex flex-col gap-1">
            <label for="f-statut" class="text-[13px] font-semibold">Statut</label>
            <select id="f-statut" wire:model.live="status" class="h-[44px] px-2 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
                <option value="toutes">Tous</option>
                @foreach (\App\Enums\ListeningRequestStatus::cases() as $cas)
                    <option value="{{ $cas->value }}">{{ $cas->label() }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-col gap-1">
            <label for="f-urgence" class="text-[13px] font-semibold">Urgence</label>
            <select id="f-urgence" wire:model.live="urgency" class="h-[44px] px-2 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
                <option value="toutes">Toutes</option>
                @foreach (\App\Models\ListeningRequest::URGENCIES as $cle => $label)
                    <option value="{{ $cle }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Liste --}}
    @if ($requests->isEmpty())
        <div class="p-8 border border-ezer-green-border rounded-ezer text-center">
            <p class="text-ezer-muted">Aucune demande ne correspond à ces filtres.</p>
        </div>
    @else
        <ul class="flex flex-col gap-2 list-none">
            @foreach ($requests as $demande)
                <li wire:key="lr-{{ $demande->id }}">
                    <a href="{{ route('admin.ecoute.show', $demande) }}" class="flex flex-wrap items-center gap-3 p-4 border border-ezer-green-border rounded-ezer bg-white hover:bg-ezer-green-pale transition-colors">
                        <span class="font-semibold text-sm">{{ $demande->shortRef() }}</span>
                        @if ($demande->urgency)
                            <span class="inline-flex items-center px-2.5 min-h-[28px] rounded-ezer bg-ezer-red-light text-ezer-red text-xs font-semibold">{{ $demande->urgencyLabel() }}</span>
                        @endif
                        <span class="text-[13px] text-ezer-muted">{{ $demande->created_at->locale('fr')->translatedFormat('j M Y') }}</span>
                        <span class="flex-1"></span>
                        @if ($demande->therapist)
                            <span class="text-[13px] text-ezer-muted">{{ $demande->therapist->name }}</span>
                        @endif
                        <span class="inline-flex items-center px-3 min-h-[32px] rounded-ezer bg-ezer-green-light text-ezer-green text-[13px] font-semibold">{{ $demande->status->label() }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
