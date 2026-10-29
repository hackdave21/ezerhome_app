<div class="flex flex-col gap-5">
    @php $duplicates = $this->duplicates; @endphp

    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="font-cooper font-normal text-2xl lg:text-[30px]">{{ $demande->tracking_no }}</h1>
        <span class="inline-flex items-center px-3 min-h-[36px] rounded-ezer bg-ezer-green-light text-ezer-green font-semibold">{{ $demande->status->label() }}</span>
    </div>

    @if ($duplicates->isNotEmpty())
        <div role="alert" class="flex flex-col gap-1 p-3.5 rounded-ezer bg-ezer-red-light border border-ezer-red-border text-ezer-red text-[15px]">
            <strong class="font-semibold">Doublon possible : ce téléphone a déjà {{ $duplicates->count() }} autre(s) demande(s).</strong>
            <span class="flex flex-wrap gap-2">
                @foreach ($duplicates as $autre)
                    <a href="{{ route('comite.demandes.show', $autre) }}" class="underline font-semibold">{{ $autre->tracking_no }} ({{ $autre->status->label() }})</a>
                @endforeach
            </span>
        </div>
    @endif

    {{-- Coordonnées et demande --}}
    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="flex flex-col gap-0.5 p-4 border border-ezer-green-border rounded-ezer">
            <dt class="text-[13px] text-ezer-muted">Nom et prénoms</dt>
            <dd class="font-semibold">{{ $demande->full_name }}</dd>
        </div>
        <div class="flex flex-col gap-0.5 p-4 border border-ezer-green-border rounded-ezer">
            <dt class="text-[13px] text-ezer-muted">Téléphone</dt>
            <dd class="font-semibold">{{ \App\Support\Phone::format($demande->phone) }}</dd>
        </div>
        <div class="flex flex-col gap-0.5 p-4 border border-ezer-green-border rounded-ezer">
            <dt class="text-[13px] text-ezer-muted">E-mail</dt>
            <dd class="font-semibold">{{ $demande->email ?? '—' }}</dd>
        </div>
        <div class="flex flex-col gap-0.5 p-4 border border-ezer-green-border rounded-ezer">
            <dt class="text-[13px] text-ezer-muted">Type d'aide</dt>
            <dd class="font-semibold">{{ $demande->category->label() }}</dd>
        </div>
    </dl>

    <section class="flex flex-col gap-2">
        <h2 class="text-sm font-semibold text-ezer-muted">Situation décrite</h2>
        <p class="p-4 border border-ezer-green-border rounded-ezer whitespace-pre-line">{{ $demande->description }}</p>
    </section>

    {{-- Statut + note --}}
    <section class="flex flex-col gap-3 p-[18px] border border-ezer-green-border rounded-ezer">
        <h2 class="text-[17px] font-semibold">Changer le statut</h2>
        <div class="flex flex-wrap items-end gap-2">
            <div class="flex flex-col gap-1">
                <label for="st" class="text-[13px] font-semibold">Statut</label>
                <select id="st" wire:model="newStatus" class="h-[44px] px-2 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
                    @foreach (\App\Enums\AssistanceStatus::cases() as $cas)
                        <option value="{{ $cas->value }}">{{ $cas->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[200px] flex flex-col gap-1">
                <label for="st-note" class="text-[13px] font-semibold">Note (interne, facultatif)</label>
                <input id="st-note" type="text" wire:model="statusNote" class="h-[44px] px-3 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
            </div>
            <button type="button" wire:click="changeStatus" class="min-h-[44px] px-4 rounded-ezer bg-ezer-green text-white text-sm font-semibold hover:bg-ezer-green-dark transition-colors cursor-pointer">Enregistrer</button>
        </div>
        <p class="text-[13px] text-ezer-muted">Le demandeur est notifié à chaque changement de statut, sans aucun détail sensible.</p>
    </section>

    {{-- Décaissement --}}
    <section class="flex flex-col gap-3 p-[18px] border border-ezer-green-border rounded-ezer">
        <h2 class="text-[17px] font-semibold">Décaissement effectué</h2>
        @if ($demande->disbursements->isNotEmpty())
            <ul class="flex flex-col gap-1 list-none text-sm">
                @foreach ($demande->disbursements as $d)
                    <li class="flex justify-between gap-2 border-b border-ezer-green-borderLight pb-1">
                        <span>{{ number_format($d->amount, 0, ',', ' ') }} FCFA @if ($d->label)· {{ $d->label }}@endif</span>
                        <span class="text-ezer-muted">{{ $d->disbursed_on->locale('fr')->translatedFormat('j M Y') }} · {{ $d->responsible?->name ?? '—' }}</span>
                    </li>
                @endforeach
            </ul>
            <p class="text-sm font-semibold">Total : {{ number_format($demande->totalDisbursed(), 0, ',', ' ') }} FCFA</p>
        @endif
        <div class="flex flex-wrap items-end gap-2">
            <div class="flex flex-col gap-1">
                <label for="dd-montant" class="text-[13px] font-semibold">Montant (FCFA)</label>
                <input id="dd-montant" type="number" min="1" wire:model="amount" class="h-[44px] px-3 w-[140px] border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
                @error('amount')<span role="alert" class="text-xs font-semibold text-ezer-red">{{ $message }}</span>@enderror
            </div>
            <div class="flex flex-col gap-1">
                <label for="dd-date" class="text-[13px] font-semibold">Date</label>
                <input id="dd-date" type="date" wire:model="disbursedOn" class="h-[44px] px-2 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
                @error('disbursedOn')<span role="alert" class="text-xs font-semibold text-ezer-red">{{ $message }}</span>@enderror
            </div>
            <div class="flex-1 min-w-[160px] flex flex-col gap-1">
                <label for="dd-label" class="text-[13px] font-semibold">Libellé (facultatif)</label>
                <input id="dd-label" type="text" wire:model="disbursementLabel" class="h-[44px] px-3 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
            </div>
            <button type="button" wire:click="addDisbursement" class="min-h-[44px] px-4 rounded-ezer bg-ezer-green text-white text-sm font-semibold hover:bg-ezer-green-dark transition-colors cursor-pointer">Ajouter</button>
        </div>
        <p class="text-[13px] text-ezer-muted">Trace comptable simple, sans logique bancaire.</p>
    </section>

    {{-- Pièces justificatives --}}
    <section class="flex flex-col gap-3 p-[18px] border border-ezer-green-border rounded-ezer">
        <h2 class="text-[17px] font-semibold">Pièces justificatives</h2>
        @if ($demande->attachments->isNotEmpty())
            <ul class="flex flex-wrap gap-2 list-none">
                @foreach ($demande->attachments as $piece)
                    <li class="flex items-center gap-2 p-2 border border-ezer-green-border rounded-ezer">
                        <span class="text-sm">{{ $piece->title }}</span>
                        <button type="button" wire:click="detach({{ $piece->id }})" class="text-xs font-semibold text-ezer-red hover:underline cursor-pointer">Retirer</button>
                    </li>
                @endforeach
            </ul>
        @endif
        @if ($this->availableAttachments->isEmpty())
            <p class="text-sm text-ezer-muted">Ajoutez d'abord des fichiers dans la catégorie « assistance » de la <a href="{{ route('comite.photos') }}" class="text-ezer-green font-semibold hover:underline">médiathèque</a>.</p>
        @else
            <div class="flex flex-wrap items-end gap-2">
                <div class="flex flex-col gap-1">
                    <label for="piece" class="text-[13px] font-semibold">Rattacher une pièce</label>
                    <select id="piece" wire:model="attachId" class="h-[44px] px-2 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
                        <option value="">— Choisir —</option>
                        @foreach ($this->availableAttachments as $piece)
                            <option value="{{ $piece->id }}">{{ $piece->title }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" wire:click="attach" class="min-h-[44px] px-4 border-2 border-ezer-green rounded-ezer bg-white text-ezer-green text-sm font-semibold hover:bg-ezer-green-light transition-colors cursor-pointer">Rattacher</button>
            </div>
            @error('attachId')<span role="alert" class="text-xs font-semibold text-ezer-red">{{ $message }}</span>@enderror
        @endif
    </section>

    {{-- Historique --}}
    <section class="flex flex-col gap-3">
        <h2 class="text-[17px] font-semibold">Historique</h2>
        <div class="flex flex-col gap-2">
            <div class="flex flex-wrap items-end gap-2">
                <div class="flex-1 min-w-[220px] flex flex-col gap-1">
                    <label for="note" class="text-[13px] font-semibold">Ajouter une note interne</label>
                    <input id="note" type="text" wire:model="note" class="h-[44px] px-3 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
                    @error('note')<span role="alert" class="text-xs font-semibold text-ezer-red">{{ $message }}</span>@enderror
                </div>
                <button type="button" wire:click="addNote" class="min-h-[44px] px-4 border border-ezer-borderMuted rounded-ezer bg-white text-sm font-semibold cursor-pointer">Ajouter</button>
            </div>
            <ol class="flex flex-col gap-2 list-none">
                @foreach ($demande->events as $event)
                    <li class="flex flex-col gap-0.5 p-3 border border-ezer-green-border rounded-ezer">
                        <span class="text-[13px] text-ezer-muted">{{ $event->created_at?->locale('fr')->translatedFormat('j M Y à H\hi') }} · {{ $event->actor?->name ?? 'Système' }}</span>
                        @if ($event->status)<span class="text-sm font-semibold">Statut : {{ $event->status->label() }}</span>@endif
                        @if ($event->note)<span class="text-sm">{{ $event->note }}</span>@endif
                    </li>
                @endforeach
            </ol>
        </div>
    </section>
</div>
