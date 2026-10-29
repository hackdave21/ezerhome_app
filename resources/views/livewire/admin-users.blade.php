<div class="flex flex-col gap-5">
    @php $users = $this->users; $moi = auth()->id(); @endphp

    <div>
        <h1 class="font-cooper font-normal text-2xl lg:text-[32px]">Utilisateurs</h1>
        <p class="text-[15px] text-ezer-muted">Recherche, rôles, suspension et réactivation des comptes.</p>
    </div>

    <input type="search" wire:model.live.debounce.300ms="search" placeholder="Rechercher par nom, e-mail ou téléphone" class="h-[44px] px-3.5 border border-ezer-borderMuted rounded-ezer bg-white text-[15px] focus:border-ezer-green focus:outline-none max-w-[460px]">

    @if ($users->isEmpty())
        <div class="p-8 border border-ezer-green-border rounded-ezer text-center"><p class="text-ezer-muted">Aucun utilisateur trouvé.</p></div>
    @else
        <ul class="flex flex-col gap-3 list-none">
            @foreach ($users as $user)
                <li wire:key="u-{{ $user->id }}" class="flex flex-col gap-3 p-4 border border-ezer-green-border rounded-ezer">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="flex flex-col">
                            <span class="font-semibold">{{ $user->name }}</span>
                            <span class="text-[13px] text-ezer-muted">{{ $user->email ?? ($user->phone ? \App\Support\Phone::mask($user->phone) : '—') }}</span>
                        </span>
                        <span class="flex-1"></span>
                        @if ($user->status === \App\Enums\UserStatus::Active)
                            <span class="inline-flex items-center px-2.5 min-h-[28px] rounded-ezer bg-ezer-green-light text-ezer-green text-xs font-semibold">Actif</span>
                        @else
                            <span class="inline-flex items-center px-2.5 min-h-[28px] rounded-ezer bg-ezer-red-light text-ezer-red text-xs font-semibold">{{ ucfirst($user->status->value) }}</span>
                        @endif
                    </div>

                    {{-- Rôles --}}
                    <div class="flex flex-wrap gap-1.5">
                        @foreach ($this->roles() as $role)
                            @php $a = $user->hasRole($role); @endphp
                            <button type="button" wire:click="toggleRole({{ $user->id }}, '{{ $role }}')" aria-pressed="{{ $a ? 'true' : 'false' }}" class="{{ $a ? 'chip-actif' : 'chip-inactif' }} min-h-[36px] px-3 rounded-ezer text-xs font-semibold">{{ str_replace('_', ' ', $role) }}</button>
                        @endforeach
                    </div>

                    {{-- Suspension / réactivation --}}
                    @if ($user->id !== $moi)
                        @if ($suspendingId === $user->id)
                            <div class="flex flex-wrap items-end gap-2 border-t border-ezer-green-borderLight pt-3">
                                <div class="flex-1 min-w-[220px] flex flex-col gap-1">
                                    <label for="motif-{{ $user->id }}" class="text-[13px] font-semibold">Motif de la suspension</label>
                                    <input id="motif-{{ $user->id }}" type="text" wire:model="reason" class="h-[44px] px-3 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
                                    @error('reason')<span role="alert" class="text-xs font-semibold text-ezer-red">{{ $message }}</span>@enderror
                                </div>
                                <button type="button" wire:click="suspend" class="min-h-[44px] px-4 border-2 border-ezer-dark rounded-ezer bg-white text-sm font-semibold cursor-pointer">Confirmer la suspension</button>
                                <button type="button" wire:click="cancelSuspend" class="min-h-[44px] px-4 border border-ezer-borderMuted rounded-ezer bg-white text-sm font-semibold cursor-pointer">Annuler</button>
                            </div>
                        @elseif ($user->status === \App\Enums\UserStatus::Active)
                            <button type="button" wire:click="startSuspend({{ $user->id }})" class="self-start min-h-[40px] px-3.5 border-2 border-ezer-dark rounded-ezer bg-white text-sm font-semibold cursor-pointer">Suspendre</button>
                        @else
                            <button type="button" wire:click="reactivate({{ $user->id }})" class="self-start min-h-[40px] px-3.5 rounded-ezer bg-ezer-green text-white text-sm font-semibold hover:bg-ezer-green-dark transition-colors cursor-pointer">Réactiver</button>
                        @endif
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</div>
