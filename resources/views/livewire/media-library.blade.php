<div class="flex flex-col gap-5">
    @php
        $assets = $this->assets;
        $selected = $this->selected;
        $totals = $this->totals;
        $mo = $totals['bytes'] >= 1048576 ? number_format($totals['bytes'] / 1048576, 0, ',', ' ').' Mo' : number_format(max($totals['bytes'], 0) / 1024, 0, ',', ' ').' Ko';
        $maxMo = \App\Services\Media\MediaIngest::maxKilobytes() / 1024;
    @endphp

    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="font-cooper font-normal text-2xl lg:text-[32px]">Médiathèque</h1>
        <span class="text-[15px] text-ezer-muted">{{ $totals['count'] }} {{ $totals['count'] > 1 ? 'fichiers' : 'fichier' }} · {{ $mo }}</span>
    </div>

    <label x-data="{ over: false }"
           x-on:dragover.prevent="over = true" x-on:dragleave.prevent="over = false"
           x-on:drop.prevent="over = false; $refs.fichiers.files = $event.dataTransfer.files; $refs.fichiers.dispatchEvent(new Event('change', { bubbles: true }))"
           :class="over ? 'bg-ezer-green-light' : 'bg-ezer-green-pale'"
           class="flex flex-col items-center justify-center gap-1 min-h-[96px] p-4 border-2 border-dashed border-ezer-borderMuted rounded-ezer bg-ezer-green-pale text-ezer-green font-semibold text-center cursor-pointer">
        <svg aria-hidden="true" class="w-[26px] h-[26px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4M7 9l5-5 5 5M4 20h16"></path></svg>
        <span wire:loading.remove wire:target="uploads">Glissez-déposez vos fichiers ici, ou cliquez pour les choisir</span>
        <span wire:loading wire:target="uploads">Envoi en cours…</span>
        <span class="text-[13px] font-normal text-ezer-muted">Images JPG, PNG, WebP ou SVG, documents PDF · {{ $maxMo }} Mo maximum · les images sont converties et allégées automatiquement</span>
        <input type="file" multiple x-ref="fichiers" wire:model="uploads" accept="image/jpeg,image/png,image/webp,image/svg+xml,application/pdf" class="sr-only">
    </label>

    @if ($errorsUpload)
        <ul role="alert" class="p-3.5 px-4 rounded-ezer bg-ezer-red-light border border-ezer-red-border text-ezer-red text-[15px] list-disc pl-8">
            @foreach ($errorsUpload as $erreur)
                <li>{{ $erreur }}</li>
            @endforeach
        </ul>
    @endif

    <div class="flex flex-wrap gap-2 items-center">
        <label class="sr-only" for="me-search">Rechercher un fichier</label>
        <input id="me-search" type="search" wire:model.live.debounce.300ms="search" placeholder="Rechercher un fichier" class="flex-1 basis-[220px] h-[44px] px-3.5 border border-ezer-borderMuted rounded-ezer bg-white text-[15px] focus:border-ezer-green focus:outline-none">
        <div role="group" aria-label="Catégories" id="filtres-medias" class="flex flex-wrap gap-1.5">
            <button type="button" wire:click="setCategory('toutes')" aria-pressed="{{ $category === 'toutes' ? 'true' : 'false' }}" class="{{ $category === 'toutes' ? 'chip-actif' : 'chip-inactif' }} min-h-[44px] px-3 rounded-ezer font-semibold text-sm">Toutes</button>
            @foreach (\App\Models\MediaAsset::CATEGORIES as $cle => $label)
                @continue(! in_array($cle, $this->categories, true))
                <button type="button" wire:click="setCategory('{{ $cle }}')" aria-pressed="{{ $category === $cle ? 'true' : 'false' }}" class="{{ $category === $cle ? 'chip-actif' : 'chip-inactif' }} min-h-[44px] px-3 rounded-ezer font-semibold text-sm">{{ $label }}</button>
            @endforeach
        </div>
        <label class="inline-flex items-center gap-2 min-h-[44px] text-sm cursor-pointer">
            <input type="checkbox" wire:model.live="showArchived" class="w-5 h-5 accent-ezer-green"> Afficher les archives
        </label>
    </div>

    @if ($assets->isEmpty())
        <div class="flex flex-col items-center gap-3 p-8 border border-ezer-green-border rounded-ezer text-center">
            <h2 class="text-xl font-semibold">{{ $totals['count'] === 0 ? 'La médiathèque est vide.' : 'Aucun fichier ne correspond.' }}</h2>
            @if ($totals['count'] === 0)
                <p class="text-ezer-muted max-w-[460px]">Ajoutez vos images (JPG, PNG, WebP, SVG) ou documents PDF. Elles seront compressées pour les connexions lentes.</p>
            @endif
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 lg:items-start" wire:poll.5s="$refresh">
            <ul aria-label="Fichiers" id="grille-medias" class="grid grid-cols-2 lg:grid-cols-3 gap-2.5 list-none p-0 m-0 lg:col-span-2">
                @foreach ($assets as $asset)
                    @php $on = $selectedId === $asset->id; @endphp
                    <li wire:key="asset-{{ $asset->id }}">
                        <button type="button" wire:click="select({{ $asset->id }})" aria-pressed="{{ $on ? 'true' : 'false' }}"
                                class="w-full flex flex-col gap-1.5 p-1.5 rounded-ezer bg-white text-left hover:bg-ezer-green-pale transition-colors {{ $on ? 'border-2 border-ezer-green' : 'border border-ezer-green-border' }}">
                            <span aria-hidden="true" class="aspect-[4/3] rounded-ezer bg-ezer-green-light flex items-center justify-center text-ezer-green text-xs font-semibold overflow-hidden">
                                @if ($asset->isReady() && $asset->isImage())
                                    <img src="{{ $asset->url(thumb: true) }}" alt="" loading="lazy" class="w-full h-full object-cover">
                                @elseif ($asset->status === 'processing')
                                    Traitement…
                                @elseif ($asset->status === 'failed')
                                    Échec
                                @else
                                    PDF
                                @endif
                            </span>
                            <span class="text-[13px] leading-[18px] font-semibold truncate">{{ $asset->title }}</span>
                            <span class="text-xs leading-4 text-ezer-muted">
                                @if ($asset->isArchived()) Archivé
                                @elseif ($asset->usages_count > 0) Utilisé sur {{ $asset->usages_count }} {{ $asset->usages_count > 1 ? 'pages' : 'page' }}
                                @else Non utilisé @endif
                            </span>
                        </button>
                    </li>
                @endforeach
            </ul>

            @if ($selected)
                <aside aria-labelledby="me-detail" class="flex flex-col gap-3 p-[18px] border border-ezer-green-border rounded-ezer lg:col-span-3">
                    <h2 id="me-detail" class="m-0 text-[17px] leading-6 font-semibold">{{ $selected->title }}</h2>
                    <div class="aspect-video rounded-ezer bg-ezer-green-light flex items-center justify-center overflow-hidden">
                        @if ($selected->isReady() && $selected->isImage())
                            <img src="{{ $selected->url() }}" alt="{{ $selected->alt }}" class="w-full h-full object-contain">
                        @elseif ($selected->isReady())
                            <a href="{{ $selected->url() }}" target="_blank" rel="noopener" class="font-semibold text-ezer-green hover:underline">Ouvrir le PDF</a>
                        @else
                            <span class="text-sm text-ezer-muted">{{ $selected->status === 'failed' ? "Le traitement a échoué : remplacez le fichier." : 'Traitement en cours…' }}</span>
                        @endif
                    </div>
                    <span class="text-[13px] text-ezer-muted">
                        @if ($selected->width) {{ $selected->width }} × {{ $selected->height }} · @endif
                        {{ number_format($selected->size / 1024, 0, ',', ' ') }} Ko · ajouté par {{ $selected->uploader?->name ?? '—' }} le {{ $selected->created_at->locale('fr')->translatedFormat('j F Y') }} · accord de publication : {{ $selected->consent_confirmed ? 'oui' : 'non' }}
                    </span>

                    <div class="flex flex-col gap-1">
                        <label for="me-titre" class="text-sm font-semibold">Titre</label>
                        <input id="me-titre" type="text" wire:model="title" class="w-full h-[44px] px-3 border border-ezer-borderMuted rounded-ezer text-sm focus:border-ezer-green focus:outline-none">
                        @error('title')<span role="alert" class="text-xs font-semibold text-ezer-red">{{ $message }}</span>@enderror
                    </div>
                    <div class="flex flex-col gap-1">
                        <label for="me-alt" class="text-sm font-semibold">Texte alternatif{{ $selected->isImage() ? ' (obligatoire)' : '' }}</label>
                        <input id="me-alt" type="text" wire:model="alt" class="w-full h-[44px] px-3 border border-ezer-borderMuted rounded-ezer text-sm focus:border-ezer-green focus:outline-none">
                        <span class="text-xs text-ezer-muted">Décrit l'image pour les personnes qui ne la voient pas.</span>
                        @error('alt')<span role="alert" class="text-xs font-semibold text-ezer-red">{{ $message }}</span>@enderror
                    </div>
                    <div class="flex flex-col gap-1">
                        <label for="me-leg" class="text-sm font-semibold">Légende</label>
                        <input id="me-leg" type="text" wire:model="caption" class="w-full h-[44px] px-3 border border-ezer-borderMuted rounded-ezer text-sm focus:border-ezer-green focus:outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div class="flex flex-col gap-1">
                            <label for="me-cat" class="text-sm font-semibold">Catégorie</label>
                            <select id="me-cat" wire:model.live="editCategory" class="h-[44px] px-2 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
                                @foreach (\App\Models\MediaAsset::CATEGORIES as $cle => $label)
                                    @if (in_array($cle, $this->categories, true))<option value="{{ $cle }}">{{ $label }}</option>@endif
                                @endforeach
                            </select>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label for="me-credit" class="text-sm font-semibold">Crédit</label>
                            <input id="me-credit" type="text" wire:model="credit" class="w-full h-[44px] px-3 border border-ezer-borderMuted rounded-ezer text-sm focus:border-ezer-green focus:outline-none">
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label class="flex items-start gap-2.5 text-sm cursor-pointer">
                            <input type="checkbox" wire:model="isPublic" class="w-5 h-5 shrink-0 accent-ezer-green">
                            <span><strong class="font-semibold">Publiable sur le site</strong> : sans cette case, le fichier reste privé (visible seulement des personnes habilitées).</span>
                        </label>
                        @if ($editCategory === \App\Models\MediaAsset::ASSISTANCE)
                            <label class="flex items-start gap-2.5 text-sm cursor-pointer">
                                <input type="checkbox" wire:model="consent" class="w-5 h-5 shrink-0 accent-ezer-green">
                                <span><strong class="font-semibold">Accord de publication obtenu</strong> : les personnes reconnaissables, ou leurs parents pour les enfants, ont donné leur accord écrit.</span>
                            </label>
                        @endif
                        @error('isPublic')<span role="alert" class="text-xs font-semibold text-ezer-red">{{ $message }}</span>@enderror
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <button type="button" wire:click="save" class="min-h-[44px] px-3.5 border-0 rounded-ezer bg-ezer-green text-white text-sm font-semibold hover:bg-ezer-green-dark transition-colors cursor-pointer">{{ $saved ? 'Enregistré' : 'Enregistrer' }}</button>
                        <label class="min-h-[44px] px-3.5 inline-flex items-center border-2 border-ezer-green rounded-ezer bg-white text-ezer-green text-sm font-semibold hover:bg-ezer-green-light transition-colors cursor-pointer">
                            <span wire:loading.remove wire:target="replacement">Remplacer le fichier</span>
                            <span wire:loading wire:target="replacement">Envoi…</span>
                            <input type="file" wire:model="replacement" x-on:change.once="$wire.replaceFile()" accept="image/jpeg,image/png,image/webp,image/svg+xml,application/pdf" class="sr-only">
                        </label>
                        <button type="button" wire:click="toggleArchive" class="min-h-[44px] px-3.5 border border-ezer-borderMuted rounded-ezer bg-white text-sm font-semibold cursor-pointer">{{ $selected->isArchived() ? 'Désarchiver' : 'Archiver' }}</button>
                        <button type="button" wire:click="delete" class="min-h-[44px] px-3.5 border-2 border-ezer-dark rounded-ezer bg-white text-ezer-dark text-sm font-semibold hover:bg-ezer-green-pale transition-colors cursor-pointer">Supprimer</button>
                    </div>

                    @if ($deleteBlocked)
                        <div id="bloc-suppression" class="flex flex-col gap-2 p-3.5 border-2 border-ezer-dark rounded-ezer">
                            <h3 id="me-bloc" class="m-0 flex items-center gap-2 text-[15px] font-semibold">
                                <svg aria-hidden="true" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2"></rect><path d="M8 11V8a4 4 0 0 1 8 0v3"></path></svg>
                                Suppression impossible
                            </h3>
                            <p class="m-0 text-sm leading-5">Ce fichier est utilisé sur {{ $selected->usages->count() }} {{ $selected->usages->count() > 1 ? 'pages' : 'page' }}. Remplacez-le ou retirez-le de {{ $selected->usages->count() > 1 ? 'ces pages' : 'cette page' }}, puis réessayez.</p>
                            <ul class="m-0 p-0 list-none flex flex-col gap-1 text-sm">
                                @foreach ($selected->usages as $usage)
                                    <li class="flex justify-between gap-2"><span>{{ $usage->label }}</span>@if ($usage->url)<a href="{{ $usage->url }}" class="font-semibold text-ezer-green hover:underline">Ouvrir</a>@endif</li>
                                @endforeach
                            </ul>
                            <button type="button" wire:click="closeBlock" class="self-start min-h-[44px] px-3 border border-ezer-borderMuted rounded-ezer bg-white text-sm font-semibold cursor-pointer">Compris</button>
                        </div>
                    @endif
                </aside>
            @endif
        </div>
    @endif
</div>
