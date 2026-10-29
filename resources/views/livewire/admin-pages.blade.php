<div class="flex flex-col gap-5 max-w-[760px]">
    <div>
        <h1 class="font-cooper font-normal text-2xl lg:text-[32px]">Pages et contenus</h1>
        <p class="text-[15px] text-ezer-muted">Textes éditables du site. Le contenu est au format HTML.</p>
    </div>

    @if ($saved)
        <p role="status" class="p-3 rounded-ezer bg-ezer-green-light text-ezer-green text-sm font-semibold">Page enregistrée.</p>
    @endif

    @if ($editingSlug)
        <form wire:submit="save" class="flex flex-col gap-4 p-[18px] border border-ezer-green-border rounded-ezer">
            <h2 class="text-[17px] font-semibold">Modifier : {{ \App\Livewire\AdminPages::KNOWN[$editingSlug] }}</h2>
            <div class="flex flex-col gap-1">
                <label for="pg-titre" class="text-sm font-semibold">Titre</label>
                <input id="pg-titre" type="text" wire:model="title" class="h-[44px] px-3 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
                @error('title')<span role="alert" class="text-xs font-semibold text-ezer-red">{{ $message }}</span>@enderror
            </div>
            <div class="flex flex-col gap-1">
                <label for="pg-body" class="text-sm font-semibold">Contenu (HTML)</label>
                <textarea id="pg-body" rows="12" wire:model="body" class="px-3 py-2 border border-ezer-borderMuted rounded-ezer bg-white text-sm font-mono focus:border-ezer-green focus:outline-none"></textarea>
            </div>
            <label class="inline-flex items-center gap-2 text-sm cursor-pointer">
                <input type="checkbox" wire:model="isPublished" class="w-5 h-5 accent-ezer-green"> Publiée
            </label>
            <div class="flex gap-2">
                <button type="submit" class="min-h-[44px] px-4 rounded-ezer bg-ezer-green text-white text-sm font-semibold hover:bg-ezer-green-dark transition-colors cursor-pointer">Enregistrer</button>
                <button type="button" wire:click="cancel" class="min-h-[44px] px-4 border border-ezer-borderMuted rounded-ezer bg-white text-sm font-semibold cursor-pointer">Annuler</button>
            </div>
        </form>
    @else
        <ul class="flex flex-col gap-2 list-none">
            @foreach ($this->pages as $page)
                <li wire:key="pg-{{ $page['slug'] }}" class="flex flex-wrap items-center gap-3 p-4 border border-ezer-green-border rounded-ezer">
                    <span class="flex flex-col">
                        <span class="font-semibold text-sm">{{ $page['title'] }}</span>
                        <span class="text-[13px] text-ezer-muted">/{{ $page['slug'] }}</span>
                    </span>
                    <span class="flex-1"></span>
                    @if (! $page['exists'])
                        <span class="inline-flex items-center px-2.5 min-h-[28px] rounded-ezer bg-ezer-red-light text-ezer-red text-xs font-semibold">à créer</span>
                    @elseif (! $page['published'])
                        <span class="inline-flex items-center px-2.5 min-h-[28px] rounded-ezer bg-ezer-green-pale text-ezer-muted text-xs font-semibold">brouillon</span>
                    @else
                        <span class="inline-flex items-center px-2.5 min-h-[28px] rounded-ezer bg-ezer-green-light text-ezer-green text-xs font-semibold">publiée</span>
                    @endif
                    <button type="button" wire:click="edit('{{ $page['slug'] }}')" class="min-h-[40px] px-3.5 border border-ezer-borderMuted rounded-ezer bg-white text-sm font-semibold cursor-pointer">Modifier</button>
                </li>
            @endforeach
        </ul>
    @endif
</div>
