<div class="flex flex-col gap-5">
    @php $members = $this->members; @endphp

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="font-cooper font-normal text-xl lg:text-2xl">Organigramme</h2>
            <p class="text-sm text-ezer-muted">Ajoutez les membres, classez-les par comité et glissez-les pour changer l'ordre d'affichage.</p>
        </div>
        <button type="button" wire:click="create" class="min-h-[44px] px-4 rounded-ezer bg-ezer-green text-white text-sm font-semibold hover:bg-ezer-green-dark transition-colors cursor-pointer">Ajouter un membre</button>
    </div>

    @if ($saved)
        <p role="status" class="p-3 rounded-ezer bg-ezer-green-light text-ezer-green text-sm font-semibold">Modifications enregistrées.</p>
    @endif

    {{-- Formulaire d'ajout / modification --}}
    @if ($showForm)
        <form wire:submit="save" class="flex flex-col gap-3 p-[18px] border border-ezer-green-border rounded-ezer">
            <h3 class="text-[17px] font-semibold">{{ $editingId ? 'Modifier le membre' : 'Nouveau membre' }}</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="flex flex-col gap-1">
                    <label for="om-last" class="text-sm font-semibold">Nom</label>
                    <input id="om-last" type="text" wire:model="last_name" class="h-[44px] px-3 border border-ezer-borderMuted rounded-ezer text-sm focus:border-ezer-green focus:outline-none">
                    @error('last_name')<span role="alert" class="text-xs font-semibold text-ezer-red">{{ $message }}</span>@enderror
                </div>
                <div class="flex flex-col gap-1">
                    <label for="om-first" class="text-sm font-semibold">Prénoms</label>
                    <input id="om-first" type="text" wire:model="first_name" class="h-[44px] px-3 border border-ezer-borderMuted rounded-ezer text-sm focus:border-ezer-green focus:outline-none">
                    @error('first_name')<span role="alert" class="text-xs font-semibold text-ezer-red">{{ $message }}</span>@enderror
                </div>
                <div class="flex flex-col gap-1">
                    <label for="om-position" class="text-sm font-semibold">Fonction</label>
                    <input id="om-position" type="text" wire:model="position" placeholder="Président(e), Trésorier(ère)…" class="h-[44px] px-3 border border-ezer-borderMuted rounded-ezer text-sm focus:border-ezer-green focus:outline-none">
                    @error('position')<span role="alert" class="text-xs font-semibold text-ezer-red">{{ $message }}</span>@enderror
                </div>
                <div class="flex flex-col gap-1">
                    <label for="om-committee" class="text-sm font-semibold">Comité</label>
                    <select id="om-committee" wire:model="committee" class="h-[44px] px-2 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
                        @foreach (\App\Models\OrgMember::COMMITTEES as $cle => $label)
                            <option value="{{ $cle }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col gap-1">
                    <label for="om-contact" class="text-sm font-semibold">Contact public (facultatif)</label>
                    <input id="om-contact" type="text" wire:model="public_contact" class="h-[44px] px-3 border border-ezer-borderMuted rounded-ezer text-sm focus:border-ezer-green focus:outline-none">
                </div>
            </div>

            <div class="flex flex-col gap-1">
                <label for="om-bio" class="text-sm font-semibold">Présentation (facultatif)</label>
                <textarea id="om-bio" wire:model="bio" rows="3" class="px-3 py-2 border border-ezer-borderMuted rounded-ezer text-sm focus:border-ezer-green focus:outline-none"></textarea>
                @error('bio')<span role="alert" class="text-xs font-semibold text-ezer-red">{{ $message }}</span>@enderror
            </div>

            {{-- Photo depuis la médiathèque (catégorie Équipe) --}}
            <fieldset class="flex flex-col gap-2">
                <legend class="text-sm font-semibold">Photo (médiathèque · Équipe)</legend>
                @if ($this->photos->isEmpty())
                    <p class="text-sm text-ezer-muted">Aucune photo dans la catégorie « Équipe ». <a href="{{ route('admin.mediatheque') }}" class="text-ezer-green font-semibold hover:underline">Ajoutez-en dans la médiathèque</a>.</p>
                @else
                    <ul class="grid grid-cols-3 sm:grid-cols-5 gap-2 list-none">
                        <li>
                            <button type="button" wire:click="$set('media_id', null)" aria-pressed="{{ $media_id === null ? 'true' : 'false' }}"
                                    class="w-full aspect-square rounded-ezer bg-white text-xs font-semibold text-ezer-muted flex items-center justify-center {{ $media_id === null ? 'border-2 border-ezer-green' : 'border border-ezer-green-border' }}">Sans photo</button>
                        </li>
                        @foreach ($this->photos as $photo)
                            <li wire:key="om-photo-{{ $photo->id }}">
                                <button type="button" wire:click="$set('media_id', {{ $photo->id }})" aria-pressed="{{ $media_id === $photo->id ? 'true' : 'false' }}"
                                        class="w-full aspect-square rounded-ezer overflow-hidden {{ $media_id === $photo->id ? 'border-2 border-ezer-green' : 'border border-ezer-green-border' }}">
                                    <img src="{{ $photo->url(thumb: true) }}" alt="{{ $photo->alt }}" loading="lazy" class="w-full h-full object-cover">
                                </button>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </fieldset>

            <label class="inline-flex items-center gap-2 text-sm cursor-pointer">
                <input type="checkbox" wire:model="is_visible" class="w-5 h-5 accent-ezer-green"> Visible sur la page publique
            </label>

            <div class="flex flex-wrap gap-2">
                <button type="submit" class="min-h-[44px] px-4 rounded-ezer bg-ezer-green text-white text-sm font-semibold hover:bg-ezer-green-dark transition-colors cursor-pointer">Enregistrer</button>
                <button type="button" wire:click="cancel" class="min-h-[44px] px-4 border border-ezer-borderMuted rounded-ezer bg-white text-sm font-semibold cursor-pointer">Annuler</button>
            </div>
        </form>
    @endif

    {{-- Listes par comité, avec glisser-déposer --}}
    @forelse (\App\Models\OrgMember::COMMITTEES as $cle => $label)
        @php $group = $members[$cle] ?? collect(); @endphp
        @continue($group->isEmpty())
        <section class="flex flex-col gap-2">
            <h3 class="text-[15px] font-semibold uppercase tracking-wide text-ezer-muted">{{ $label }}</h3>
            <ul class="flex flex-col gap-2 list-none"
                x-data="ezerSortable(ids => $wire.reorder(ids))">
                @foreach ($group as $member)
                    <li wire:key="om-{{ $member->id }}" draggable="true" data-id="{{ $member->id }}"
                        class="flex items-center gap-3 p-2.5 border border-ezer-green-border rounded-ezer bg-white cursor-move"
                        :class="dragId === '{{ $member->id }}' && 'opacity-50'">
                        <span aria-hidden="true" class="text-ezer-muted shrink-0">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><circle cx="9" cy="6" r="1.5"/><circle cx="15" cy="6" r="1.5"/><circle cx="9" cy="12" r="1.5"/><circle cx="15" cy="12" r="1.5"/><circle cx="9" cy="18" r="1.5"/><circle cx="15" cy="18" r="1.5"/></svg>
                        </span>
                        <span class="w-11 h-11 shrink-0 rounded-ezer bg-ezer-green-light overflow-hidden flex items-center justify-center text-ezer-green">
                            @if ($member->photo && $member->photo->isReady())
                                <img src="{{ $member->photo->url(thumb: true) }}" alt="" loading="lazy" class="w-full h-full object-cover">
                            @else
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>
                            @endif
                        </span>
                        <span class="flex-1 min-w-0">
                            <span class="block font-semibold text-sm truncate">{{ $member->fullName() }}</span>
                            <span class="block text-xs text-ezer-muted truncate">{{ $member->position }}@unless ($member->is_visible) · <span class="text-ezer-red font-semibold">masqué</span>@endunless</span>
                        </span>
                        <span class="flex items-center gap-1 shrink-0">
                            <button type="button" wire:click="toggleVisible({{ $member->id }})" class="min-h-[40px] px-2.5 border border-ezer-borderMuted rounded-ezer bg-white text-xs font-semibold cursor-pointer" title="{{ $member->is_visible ? 'Masquer' : 'Afficher' }}">{{ $member->is_visible ? 'Masquer' : 'Afficher' }}</button>
                            <button type="button" wire:click="edit({{ $member->id }})" class="min-h-[40px] px-2.5 border border-ezer-borderMuted rounded-ezer bg-white text-xs font-semibold cursor-pointer">Modifier</button>
                            <button type="button" wire:click="delete({{ $member->id }})" wire:confirm="Supprimer ce membre de l'organigramme ?" class="min-h-[40px] px-2.5 border-2 border-ezer-dark rounded-ezer bg-white text-xs font-semibold cursor-pointer">Supprimer</button>
                        </span>
                    </li>
                @endforeach
            </ul>
        </section>
    @empty
    @endforelse

    @if ($members->flatten()->isEmpty() && ! $showForm)
        <div class="flex flex-col items-center gap-3 p-8 border border-ezer-green-border rounded-ezer text-center">
            <h3 class="text-lg font-semibold">Aucun membre pour l'instant.</h3>
            <p class="text-ezer-muted max-w-[460px]">Ajoutez les membres du bureau exécutif et des comités : ils apparaîtront sur la page « Notre équipe ».</p>
        </div>
    @endif
</div>
