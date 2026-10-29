<div class="flex flex-col gap-5">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="font-cooper font-normal text-2xl lg:text-[30px]">Demande {{ $demande->shortRef() }}</h1>
        <span class="inline-flex items-center px-3 min-h-[36px] rounded-ezer bg-ezer-green-light text-ezer-green font-semibold">{{ $demande->status->label() }}</span>
    </div>

    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="flex flex-col gap-0.5 p-4 border border-ezer-green-border rounded-ezer">
            <dt class="text-[13px] text-ezer-muted">Consultant</dt>
            <dd class="font-semibold">{{ $demande->consultant?->name ?? '—' }}</dd>
        </div>
        <div class="flex flex-col gap-0.5 p-4 border border-ezer-green-border rounded-ezer">
            <dt class="text-[13px] text-ezer-muted">Reçue le</dt>
            <dd class="font-semibold">{{ $demande->created_at->locale('fr')->translatedFormat('j F Y à H\hi') }}</dd>
        </div>
        <div class="flex flex-col gap-0.5 p-4 border border-ezer-green-border rounded-ezer">
            <dt class="text-[13px] text-ezer-muted">Urgence</dt>
            <dd class="font-semibold">{{ $demande->urgencyLabel() ?? 'Non précisée' }}</dd>
        </div>
        <div class="flex flex-col gap-0.5 p-4 border border-ezer-green-border rounded-ezer">
            <dt class="text-[13px] text-ezer-muted">Canal souhaité</dt>
            <dd class="font-semibold">{{ $demande->channelLabel() ?? 'Peu importe' }}</dd>
        </div>
    </dl>

    <section class="flex flex-col gap-2">
        <h2 class="text-sm font-semibold text-ezer-muted">Demande du consultant</h2>
        <p class="p-4 border border-ezer-green-border rounded-ezer whitespace-pre-line">{{ $demande->description }}</p>
    </section>

    @if ($demande->decline_reason)
        <p class="p-3 rounded-ezer bg-ezer-red-light text-ezer-red text-sm"><strong>À réaffecter.</strong> Motif indiqué : {{ $demande->decline_reason }}</p>
    @endif

    {{-- Affectation manuelle --}}
    @if ($demande->status->isOpen())
        <section class="flex flex-col gap-3 p-[18px] border border-ezer-green-border rounded-ezer">
            <h2 class="text-[17px] font-semibold">{{ $demande->therapist ? 'Réaffecter' : 'Affecter à un intervenant' }}</h2>

            @if ($this->therapists->isEmpty())
                <p class="text-sm text-ezer-muted">Aucun intervenant validé (rôle « thérapeute » + double authentification) n'est encore disponible. Attribuez le rôle depuis la gestion des utilisateurs.</p>
            @else
                <div class="flex flex-wrap items-end gap-2">
                    <div class="flex flex-col gap-1">
                        <label for="aff-ther" class="text-[13px] font-semibold">Intervenant</label>
                        <select id="aff-ther" wire:model="therapistId" class="h-[44px] px-2 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
                            <option value="">— Choisir —</option>
                            @foreach ($this->therapists as $t)
                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" wire:click="assign" class="min-h-[44px] px-4 rounded-ezer bg-ezer-green text-white text-sm font-semibold hover:bg-ezer-green-dark transition-colors cursor-pointer">{{ $demande->therapist ? 'Réaffecter' : 'Affecter' }}</button>
                </div>
                @error('therapistId')<span role="alert" class="text-xs font-semibold text-ezer-red">{{ $message }}</span>@enderror
            @endif

            <button type="button" wire:click="close" wire:confirm="Clôturer cette demande d'écoute ?" class="self-start min-h-[44px] px-4 border-2 border-ezer-dark rounded-ezer bg-white text-sm font-semibold cursor-pointer">Clôturer la demande</button>
        </section>
    @else
        @php $dateCloture = $demande->closed_at ? ' le '.$demande->closed_at->locale('fr')->translatedFormat('j F Y') : ''; @endphp
        <p class="p-4 rounded-ezer bg-ezer-green-pale text-ezer-muted">Cette demande est clôturée{{ $dateCloture }}.</p>
    @endif
</div>
