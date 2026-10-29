<div class="flex flex-col gap-6">
    @php
        $requests = $this->requests;
        $aAccepter = $requests->where('status', \App\Enums\ListeningRequestStatus::Affectee);
        $enCours = $requests->where('status', \App\Enums\ListeningRequestStatus::EnCours);
        $cloturees = $requests->where('status', \App\Enums\ListeningRequestStatus::Cloturee);
    @endphp

    <div>
        <h1 class="font-cooper font-normal text-2xl lg:text-[32px]">Tableau de bord</h1>
        <p class="text-[15px] text-ezer-muted">Vos demandes d'écoute. La messagerie avec les personnes accompagnées arrivera prochainement.</p>
    </div>

    {{-- Demandes à accepter ou décliner --}}
    <section class="flex flex-col gap-3">
        <h2 class="text-[17px] font-semibold">À accepter ou décliner</h2>
        @forelse ($aAccepter as $demande)
            <div wire:key="acc-{{ $demande->id }}" class="flex flex-col gap-3 p-4 border border-ezer-green-border rounded-ezer">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="font-semibold text-sm">{{ $demande->shortRef() }}</span>
                    @if ($demande->urgency)<span class="inline-flex items-center px-2.5 min-h-[28px] rounded-ezer bg-ezer-red-light text-ezer-red text-xs font-semibold">{{ $demande->urgencyLabel() }}</span>@endif
                    <span class="text-[13px] text-ezer-muted">affectée le {{ $demande->assigned_at?->locale('fr')->translatedFormat('j M Y') }}</span>
                </div>
                <p class="p-3 border border-ezer-green-border rounded-ezer whitespace-pre-line text-[15px]">{{ $demande->description }}</p>
                @if ($demande->channelLabel())<p class="text-[13px] text-ezer-muted">Canal souhaité : {{ $demande->channelLabel() }}</p>@endif

                @if ($reasonFor === $demande->id)
                    <div class="flex flex-col gap-2">
                        <label for="motif-{{ $demande->id }}" class="text-sm font-semibold">Motif de la déclinaison (transmis au bureau)</label>
                        <textarea id="motif-{{ $demande->id }}" wire:model="reason" rows="2" class="px-3 py-2 border border-ezer-borderMuted rounded-ezer text-sm focus:border-ezer-green focus:outline-none"></textarea>
                        @error('reason')<span role="alert" class="text-xs font-semibold text-ezer-red">{{ $message }}</span>@enderror
                        <div class="flex gap-2">
                            <button type="button" wire:click="release" class="min-h-[44px] px-4 border-2 border-ezer-dark rounded-ezer bg-white text-sm font-semibold cursor-pointer">Confirmer la déclinaison</button>
                            <button type="button" wire:click="cancelReason" class="min-h-[44px] px-4 border border-ezer-borderMuted rounded-ezer bg-white text-sm font-semibold cursor-pointer">Annuler</button>
                        </div>
                    </div>
                @else
                    <div class="flex flex-wrap gap-2">
                        <button type="button" wire:click="accept({{ $demande->id }})" class="min-h-[44px] px-4 rounded-ezer bg-ezer-green text-white text-sm font-semibold hover:bg-ezer-green-dark transition-colors cursor-pointer">Accepter</button>
                        <button type="button" wire:click="openReason({{ $demande->id }})" class="min-h-[44px] px-4 border-2 border-ezer-dark rounded-ezer bg-white text-sm font-semibold cursor-pointer">Décliner</button>
                    </div>
                @endif
            </div>
        @empty
            <p class="p-4 rounded-ezer bg-ezer-green-pale text-ezer-muted">Aucune demande en attente de votre réponse.</p>
        @endforelse
    </section>

    {{-- Accompagnements en cours --}}
    <section class="flex flex-col gap-3">
        <h2 class="text-[17px] font-semibold">Accompagnements en cours</h2>
        @forelse ($enCours as $demande)
            <div wire:key="enc-{{ $demande->id }}" class="flex flex-col gap-3 p-4 border border-ezer-green-border rounded-ezer">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="font-semibold text-sm">{{ $demande->shortRef() }}</span>
                    <span class="text-[13px] text-ezer-muted">accepté le {{ $demande->accepted_at?->locale('fr')->translatedFormat('j M Y') }}</span>
                </div>
                <p class="p-3 border border-ezer-green-border rounded-ezer whitespace-pre-line text-[15px]">{{ $demande->description }}</p>

                @if ($reasonFor === $demande->id)
                    <div class="flex flex-col gap-2">
                        <label for="motif-{{ $demande->id }}" class="text-sm font-semibold">Motif de la demande de réaffectation (transmis au bureau)</label>
                        <textarea id="motif-{{ $demande->id }}" wire:model="reason" rows="2" class="px-3 py-2 border border-ezer-borderMuted rounded-ezer text-sm focus:border-ezer-green focus:outline-none"></textarea>
                        @error('reason')<span role="alert" class="text-xs font-semibold text-ezer-red">{{ $message }}</span>@enderror
                        <div class="flex gap-2">
                            <button type="button" wire:click="release" class="min-h-[44px] px-4 border-2 border-ezer-dark rounded-ezer bg-white text-sm font-semibold cursor-pointer">Confirmer</button>
                            <button type="button" wire:click="cancelReason" class="min-h-[44px] px-4 border border-ezer-borderMuted rounded-ezer bg-white text-sm font-semibold cursor-pointer">Annuler</button>
                        </div>
                    </div>
                @else
                    <div class="flex flex-wrap gap-2">
                        <button type="button" wire:click="close({{ $demande->id }})" wire:confirm="Clôturer cet accompagnement ?" class="min-h-[44px] px-4 rounded-ezer bg-ezer-green text-white text-sm font-semibold hover:bg-ezer-green-dark transition-colors cursor-pointer">Clôturer</button>
                        <button type="button" wire:click="openReason({{ $demande->id }})" class="min-h-[44px] px-4 border-2 border-ezer-dark rounded-ezer bg-white text-sm font-semibold cursor-pointer">Demander une réaffectation</button>
                    </div>
                @endif
            </div>
        @empty
            <p class="p-4 rounded-ezer bg-ezer-green-pale text-ezer-muted">Aucun accompagnement en cours.</p>
        @endforelse
    </section>

    {{-- Clôturés --}}
    @if ($cloturees->isNotEmpty())
        <section class="flex flex-col gap-3">
            <h2 class="text-[17px] font-semibold">Clôturés</h2>
            <ul class="flex flex-col gap-2 list-none">
                @foreach ($cloturees as $demande)
                    <li wire:key="clo-{{ $demande->id }}" class="flex items-center justify-between gap-2 p-3 border border-ezer-green-border rounded-ezer text-sm">
                        <span class="font-semibold">{{ $demande->shortRef() }}</span>
                        <span class="text-[13px] text-ezer-muted">clôturé le {{ $demande->closed_at?->locale('fr')->translatedFormat('j M Y') }}</span>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif
</div>
