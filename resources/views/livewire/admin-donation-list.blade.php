<div class="flex flex-col gap-5">
    @php $donations = $this->donations; $totals = $this->totals; @endphp

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="font-cooper font-normal text-2xl lg:text-[32px]">Dons et paiements</h1>
            <p class="text-[15px] text-ezer-muted">Confirmez chaque versement manuellement, avec sa référence de transaction.</p>
        </div>
        <a href="{{ route('admin.dons.export') }}" class="min-h-[44px] inline-flex items-center px-4 rounded-ezer border-2 border-ezer-green text-ezer-green text-sm font-semibold hover:bg-ezer-green-light transition-colors">Export CSV</a>
    </div>

    <dl class="grid grid-cols-2 gap-3">
        <div class="flex flex-col gap-0.5 p-4 border border-ezer-green-border rounded-ezer">
            <dt class="text-[13px] text-ezer-muted">Dons confirmés</dt>
            <dd class="text-2xl font-semibold">{{ $totals['confirmed_count'] }}</dd>
        </div>
        <div class="flex flex-col gap-0.5 p-4 border border-ezer-green-border rounded-ezer">
            <dt class="text-[13px] text-ezer-muted">Total confirmé</dt>
            <dd class="text-2xl font-semibold">{{ number_format($totals['confirmed_amount'], 0, ',', ' ') }} FCFA</dd>
        </div>
    </dl>

    <div class="flex flex-wrap gap-2 items-end">
        <div class="flex flex-col gap-1">
            <label for="d-statut" class="text-[13px] font-semibold">Statut</label>
            <select id="d-statut" wire:model.live="status" class="h-[44px] px-2 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
                <option value="toutes">Tous</option>
                @foreach ($this->statuses as $cas)<option value="{{ $cas->value }}">{{ $cas->label() }}</option>@endforeach
            </select>
        </div>
        <div class="flex flex-col gap-1">
            <label for="d-moyen" class="text-[13px] font-semibold">Moyen</label>
            <select id="d-moyen" wire:model.live="method" class="h-[44px] px-2 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
                <option value="toutes">Tous</option>
                @foreach ($this->methods as $m)<option value="{{ $m->value }}">{{ $m->label() }}</option>@endforeach
            </select>
        </div>
    </div>

    @if ($donations->isEmpty())
        <div class="p-8 border border-ezer-green-border rounded-ezer text-center"><p class="text-ezer-muted">Aucun don ne correspond à ces filtres.</p></div>
    @else
        <ul class="flex flex-col gap-2 list-none">
            @foreach ($donations as $don)
                @php $p = $don->payment; @endphp
                <li wire:key="don-{{ $don->id }}" class="flex flex-col gap-3 p-4 border border-ezer-green-border rounded-ezer">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="font-semibold text-sm">{{ $p?->external_ref ?? '—' }}</span>
                        <span class="text-sm">{{ number_format($don->amount, 0, ',', ' ') }} FCFA</span>
                        <span class="inline-flex items-center px-2.5 min-h-[28px] rounded-ezer bg-ezer-green-pale text-ezer-green text-xs font-semibold">{{ $don->methodLabel() }}</span>
                        @if ($don->is_anonymous)
                            <span class="inline-flex items-center px-2.5 min-h-[28px] rounded-ezer bg-ezer-green-pale text-ezer-muted text-xs font-semibold">Anonyme</span>
                        @else
                            <span class="text-[13px] text-ezer-muted">{{ $don->donor_name ?: '—' }}</span>
                        @endif
                        @if ($don->recurring)<span class="text-[13px] text-ezer-muted">· régulier</span>@endif
                        <span class="text-[13px] text-ezer-muted">{{ $don->created_at->locale('fr')->translatedFormat('j M Y') }}</span>
                        <span class="flex-1"></span>
                        <span class="inline-flex items-center px-3 min-h-[32px] rounded-ezer bg-ezer-green-light text-ezer-green text-[13px] font-semibold">{{ ($p?->status ?? \App\Enums\PaymentStatus::Initie)->label() }}</span>
                    </div>

                    @if ($p && ! $p->isPaid() && $p->status !== \App\Enums\PaymentStatus::Rembourse)
                        @if ($confirmingId === $p->id)
                            <div class="flex flex-wrap items-end gap-2 border-t border-ezer-green-borderLight pt-3">
                                <div class="flex-1 min-w-[200px] flex flex-col gap-1">
                                    <label for="ref-{{ $p->id }}" class="text-[13px] font-semibold">Référence de transaction</label>
                                    <input id="ref-{{ $p->id }}" type="text" wire:model="reference" class="h-[44px] px-3 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
                                    @error('reference')<span role="alert" class="text-xs font-semibold text-ezer-red">{{ $message }}</span>@enderror
                                </div>
                                <button type="button" wire:click="confirm" class="min-h-[44px] px-4 rounded-ezer bg-ezer-green text-white text-sm font-semibold hover:bg-ezer-green-dark transition-colors cursor-pointer">Confirmer le paiement</button>
                                <button type="button" wire:click="cancelConfirm" class="min-h-[44px] px-4 border border-ezer-borderMuted rounded-ezer bg-white text-sm font-semibold cursor-pointer">Annuler</button>
                            </div>
                        @else
                            <div class="flex gap-2 border-t border-ezer-green-borderLight pt-3">
                                <button type="button" wire:click="startConfirm({{ $p->id }})" class="min-h-[40px] px-3.5 rounded-ezer bg-ezer-green text-white text-sm font-semibold hover:bg-ezer-green-dark transition-colors cursor-pointer">Confirmer manuellement</button>
                            </div>
                        @endif
                    @elseif ($p && $p->isPaid())
                        <div class="flex flex-wrap items-center gap-2 border-t border-ezer-green-borderLight pt-3 text-[13px] text-ezer-muted">
                            <span>Confirmé par {{ $p->confirmedBy?->name ?? '—' }} le {{ $p->paid_at?->locale('fr')->translatedFormat('j M Y') }}</span>
                            <button type="button" wire:click="refund({{ $p->id }})" wire:confirm="Noter ce don comme remboursé ?" class="text-ezer-red font-semibold hover:underline cursor-pointer">Noter un remboursement</button>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</div>
