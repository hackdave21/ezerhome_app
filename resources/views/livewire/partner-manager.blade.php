<div class="flex flex-col gap-5">
    @php $partners = $this->partners; @endphp

    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="font-cooper font-normal text-xl lg:text-2xl">Partenaires</h2>
            <p class="text-sm text-ezer-muted">Un logo venu de la médiathèque, un lien, et l'accord écrit du partenaire avant toute publication.</p>
        </div>
        <button type="button" wire:click="create" class="min-h-[44px] px-4 rounded-ezer bg-ezer-green text-white text-sm font-semibold hover:bg-ezer-green-dark transition-colors cursor-pointer">Ajouter un partenaire</button>
    </div>

    @if ($saved)
        <p role="status" class="p-3 rounded-ezer bg-ezer-green-light text-ezer-green text-sm font-semibold">Modifications enregistrées.</p>
    @endif

    @if ($showForm)
        <form wire:submit="save" class="flex flex-col gap-3 p-[18px] border border-ezer-green-border rounded-ezer">
            <h3 class="text-[17px] font-semibold">{{ $editingId ? 'Modifier le partenaire' : 'Nouveau partenaire' }}</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="flex flex-col gap-1">
                    <label for="pm-name" class="text-sm font-semibold">Nom</label>
                    <input id="pm-name" type="text" wire:model="name" class="h-[44px] px-3 border border-ezer-borderMuted rounded-ezer text-sm focus:border-ezer-green focus:outline-none">
                    @error('name')<span role="alert" class="text-xs font-semibold text-ezer-red">{{ $message }}</span>@enderror
                </div>
                <div class="flex flex-col gap-1">
                    <label for="pm-url" class="text-sm font-semibold">Lien vers leur site (facultatif)</label>
                    <input id="pm-url" type="url" wire:model="url" placeholder="https://…" class="h-[44px] px-3 border border-ezer-borderMuted rounded-ezer text-sm focus:border-ezer-green focus:outline-none">
                    @error('url')<span role="alert" class="text-xs font-semibold text-ezer-red">{{ $message }}</span>@enderror
                </div>
            </div>

            {{-- Logo depuis la médiathèque (catégorie Partenaires) --}}
            <fieldset class="flex flex-col gap-2">
                <legend class="text-sm font-semibold">Logo (médiathèque · Partenaires)</legend>
                @if ($this->logos->isEmpty())
                    <p class="text-sm text-ezer-muted">Aucun logo dans la catégorie « Partenaires ». <a href="{{ route('admin.mediatheque') }}" class="text-ezer-green font-semibold hover:underline">Ajoutez-en dans la médiathèque</a>.</p>
                @else
                    <ul class="grid grid-cols-3 sm:grid-cols-5 gap-2 list-none">
                        <li>
                            <button type="button" wire:click="$set('media_id', null)" aria-pressed="{{ $media_id === null ? 'true' : 'false' }}"
                                    class="w-full aspect-square rounded-ezer bg-white text-xs font-semibold text-ezer-muted flex items-center justify-center {{ $media_id === null ? 'border-2 border-ezer-green' : 'border border-ezer-green-border' }}">Sans logo</button>
                        </li>
                        @foreach ($this->logos as $logo)
                            <li wire:key="pm-logo-{{ $logo->id }}">
                                <button type="button" wire:click="$set('media_id', {{ $logo->id }})" aria-pressed="{{ $media_id === $logo->id ? 'true' : 'false' }}"
                                        class="w-full aspect-square rounded-ezer bg-white overflow-hidden flex items-center justify-center p-1.5 {{ $media_id === $logo->id ? 'border-2 border-ezer-green' : 'border border-ezer-green-border' }}">
                                    <img src="{{ $logo->url(thumb: true) }}" alt="{{ $logo->alt }}" loading="lazy" class="max-w-full max-h-full object-contain">
                                </button>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </fieldset>

            <label class="flex items-start gap-2.5 text-sm cursor-pointer">
                <input type="checkbox" wire:model="approved" class="w-5 h-5 shrink-0 accent-ezer-green">
                <span><strong class="font-semibold">Accord écrit obtenu</strong> : le partenaire a autorisé par écrit l'affichage de son logo sur le site.</span>
            </label>
            <label class="flex items-start gap-2.5 text-sm cursor-pointer">
                <input type="checkbox" wire:model="is_active" class="w-5 h-5 shrink-0 accent-ezer-green">
                <span><strong class="font-semibold">Afficher sur le site</strong> : nécessite l'accord écrit ci-dessus.</span>
            </label>
            @error('is_active')<span role="alert" class="text-xs font-semibold text-ezer-red">{{ $message }}</span>@enderror

            <div class="flex flex-wrap gap-2">
                <button type="submit" class="min-h-[44px] px-4 rounded-ezer bg-ezer-green text-white text-sm font-semibold hover:bg-ezer-green-dark transition-colors cursor-pointer">Enregistrer</button>
                <button type="button" wire:click="cancel" class="min-h-[44px] px-4 border border-ezer-borderMuted rounded-ezer bg-white text-sm font-semibold cursor-pointer">Annuler</button>
            </div>
        </form>
    @endif

    @if ($partners->isEmpty() && ! $showForm)
        <div class="flex flex-col items-center gap-3 p-8 border border-ezer-green-border rounded-ezer text-center">
            <h3 class="text-lg font-semibold">Aucun partenaire pour l'instant.</h3>
            <p class="text-ezer-muted max-w-[460px]">Ajoutez vos partenaires : seuls ceux dont l'accord écrit est enregistré et le logo activé apparaîtront sur le site.</p>
        </div>
    @else
        <ul class="flex flex-col gap-2 list-none" x-data="ezerSortable(ids => $wire.reorder(ids))">
            @foreach ($partners as $partner)
                <li wire:key="pm-{{ $partner->id }}" draggable="true" data-id="{{ $partner->id }}"
                    class="flex items-center gap-3 p-2.5 border border-ezer-green-border rounded-ezer bg-white cursor-move"
                    :class="dragId === '{{ $partner->id }}' && 'opacity-50'">
                    <span aria-hidden="true" class="text-ezer-muted shrink-0">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><circle cx="9" cy="6" r="1.5"/><circle cx="15" cy="6" r="1.5"/><circle cx="9" cy="12" r="1.5"/><circle cx="15" cy="12" r="1.5"/><circle cx="9" cy="18" r="1.5"/><circle cx="15" cy="18" r="1.5"/></svg>
                    </span>
                    <span class="w-16 h-11 shrink-0 rounded-ezer bg-white border border-ezer-green-border overflow-hidden flex items-center justify-center p-1">
                        @if ($partner->logo && $partner->logo->isReady())
                            <img src="{{ $partner->logo->url(thumb: true) }}" alt="" loading="lazy" class="max-w-full max-h-full object-contain">
                        @else
                            <span class="text-[10px] text-ezer-muted">pas de logo</span>
                        @endif
                    </span>
                    <span class="flex-1 min-w-0">
                        <span class="block font-semibold text-sm truncate">{{ $partner->name }}</span>
                        <span class="block text-xs truncate">
                            @if ($partner->approved_at)
                                <span class="text-ezer-green font-semibold">accord écrit · {{ $partner->is_active ? 'affiché' : 'masqué' }}</span>
                            @else
                                <span class="text-ezer-red font-semibold">accord écrit manquant</span>
                            @endif
                        </span>
                    </span>
                    <span class="flex items-center gap-1 shrink-0">
                        <button type="button" wire:click="toggleActive({{ $partner->id }})" @disabled(! $partner->approved_at) class="min-h-[40px] px-2.5 border border-ezer-borderMuted rounded-ezer bg-white text-xs font-semibold cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed" title="{{ $partner->approved_at ? '' : 'Accord écrit requis' }}">{{ $partner->is_active ? 'Masquer' : 'Afficher' }}</button>
                        <button type="button" wire:click="edit({{ $partner->id }})" class="min-h-[40px] px-2.5 border border-ezer-borderMuted rounded-ezer bg-white text-xs font-semibold cursor-pointer">Modifier</button>
                        <button type="button" wire:click="delete({{ $partner->id }})" wire:confirm="Supprimer ce partenaire ?" class="min-h-[40px] px-2.5 border-2 border-ezer-dark rounded-ezer bg-white text-xs font-semibold cursor-pointer">Supprimer</button>
                    </span>
                </li>
            @endforeach
        </ul>
    @endif
</div>
