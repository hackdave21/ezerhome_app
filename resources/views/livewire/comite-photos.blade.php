<div class="flex flex-col gap-5 lg:gap-6">
    <div class="flex flex-col gap-1">
        <h1 class="font-cooper font-normal text-[26px] leading-8 lg:text-[32px] lg:leading-[38px]">Photos des actions</h1>
        <span class="text-[15px] leading-[22px] text-ezer-muted">Pour illustrer le site et les rapports d'activité, dans le respect des personnes.</span>
    </div>

    <section aria-labelledby="ph-ajout" class="lg:max-w-[760px] flex flex-col gap-4 border border-ezer-green-border rounded-ezer p-4 lg:p-[28px]">
        <h2 id="ph-ajout" class="text-xl leading-7 font-semibold">Ajouter des photos</h2>

        @if ($saved)
            <p role="status" class="p-3.5 px-4 rounded-ezer bg-ezer-green-light border border-ezer-green text-[15px] font-semibold">Les photos sont enregistrées. Elles sont en cours de préparation pour le site.</p>
        @endif

        <label class="flex flex-col items-center justify-center gap-1.5 min-h-[96px] p-4 border-2 border-dashed border-ezer-borderMuted rounded-ezer text-ezer-green font-semibold text-center cursor-pointer hover:bg-ezer-green-pale transition-colors">
            <svg aria-hidden="true" class="w-[26px] h-[26px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4M7 9l5-5 5 5M4 20h16"></path></svg>
            <span wire:loading.remove wire:target="photos">Choisir des photos</span>
            <span wire:loading wire:target="photos">Envoi en cours…</span>
            <span class="text-[13px] font-normal text-ezer-muted">JPG ou PNG. Elles sont réduites automatiquement pour le site.</span>
            <input type="file" accept="image/jpeg,image/png" multiple wire:model="photos" class="sr-only">
        </label>

        @if ($errorsUpload)
            <ul role="alert" class="p-3.5 px-4 rounded-ezer bg-ezer-red-light border border-ezer-red-border text-ezer-red text-[15px] list-disc pl-8">
                @foreach ($errorsUpload as $erreur)<li>{{ $erreur }}</li>@endforeach
            </ul>
        @endif

        @if ($photos)
            <ul aria-label="Photos sélectionnées" class="grid grid-cols-2 gap-2.5">
                @foreach ($photos as $i => $photo)
                    <li class="flex flex-col gap-1.5" wire:key="photo-{{ $i }}">
                        <div role="img" aria-label="Aperçu : {{ $photo->getClientOriginalName() }}" class="aspect-[4/3] rounded-ezer bg-ezer-green-light flex items-center justify-center text-ezer-green overflow-hidden">
                            <img src="{{ $photo->temporaryUrl() }}" alt="" class="w-full h-full object-cover">
                        </div>
                        <span class="text-[13px] leading-[18px] truncate">{{ $photo->getClientOriginalName() }}</span>
                        <button type="button" wire:click="removePhoto({{ $i }})" class="self-start text-xs font-semibold text-ezer-green hover:underline bg-transparent border-0 p-0 cursor-pointer">Retirer</button>
                    </li>
                @endforeach
            </ul>
        @endif
        @error('photos')<span role="alert" class="text-sm font-semibold text-ezer-red">{{ $message }}</span>@enderror

        <div class="flex flex-col gap-1.5">
            <label for="ph-legende" class="text-[15px] font-semibold">Légende</label>
            <input id="ph-legende" type="text" wire:model="caption" class="w-full h-[52px] px-3.5 border border-ezer-borderMuted rounded-ezer text-base focus:border-ezer-green focus:outline-none">
            @error('caption')<span role="alert" class="text-sm font-semibold text-ezer-red">{{ $message }}</span>@enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="flex flex-col gap-1.5">
                <label for="ph-date" class="text-[15px] font-semibold">Date de l'action</label>
                <input id="ph-date" type="date" wire:model="actionDate" class="w-full h-[52px] px-3 border border-ezer-borderMuted rounded-ezer focus:border-ezer-green focus:outline-none">
            </div>
            <div class="flex flex-col gap-1.5">
                <label for="ph-lien" class="text-[15px] font-semibold">Demande liée <span class="font-normal text-ezer-muted">(facultatif)</span></label>
                <input id="ph-lien" type="text" wire:model="linkedRequest" placeholder="ASS-2026-…" class="w-full h-[52px] px-3.5 border border-ezer-borderMuted rounded-ezer text-base focus:border-ezer-green focus:outline-none">
            </div>
        </div>

        <div class="flex flex-col gap-1.5">
            <label id="ph-accord-label" class="flex items-start gap-3 p-4 rounded-ezer bg-white text-[15px] leading-[22px] cursor-pointer border-2 {{ $consent ? 'border-ezer-green' : 'border-ezer-dark' }}">
                <input id="ph-accord" type="checkbox" wire:model.live="consent" aria-describedby="ph-accord-aide" class="w-6 h-6 shrink-0 mt-0.5 accent-ezer-green">
                <span><strong class="font-semibold">Accord de publication obtenu</strong> (obligatoire). Les personnes reconnaissables sur ces photos, ou leurs parents pour les enfants, ont donné leur accord écrit pour leur publication.</span>
            </label>
            <span id="ph-accord-aide" class="text-[13px] leading-[18px] text-ezer-muted">Sans cet accord, les photos ne peuvent pas être enregistrées.</span>
            @error('consent')<span role="alert" class="text-sm font-semibold text-ezer-red">{{ $message }}</span>@enderror
        </div>

        <button id="ph-envoyer" type="button" wire:click="save" @disabled(! $consent)
                class="w-full lg:w-auto self-start min-h-[52px] px-6 rounded-ezer border-0 text-white text-base font-semibold {{ $consent ? 'bg-ezer-green hover:bg-ezer-green-dark cursor-pointer' : 'bg-ezer-muted' }}">Enregistrer les photos</button>
    </section>

    <section aria-labelledby="ph-galerie" class="flex flex-col gap-3">
        <h2 id="ph-galerie" class="text-xl leading-7 font-semibold">Photos enregistrées</h2>
        @if ($this->gallery->isEmpty())
            <p class="text-ezer-muted">Aucune photo pour le moment.</p>
        @else
            <ul class="grid grid-cols-2 lg:grid-cols-4 gap-3" wire:poll.5s="$refresh">
                @foreach ($this->gallery as $photo)
                    <li class="flex flex-col gap-1.5" wire:key="g-{{ $photo->id }}">
                        <div role="img" aria-label="{{ $photo->alt ?: $photo->title }}" class="aspect-[4/3] rounded-ezer bg-ezer-green-light flex items-center justify-center text-ezer-green overflow-hidden">
                            @if ($photo->isReady())
                                <img src="{{ $photo->url(thumb: true) }}" alt="" loading="lazy" class="w-full h-full object-cover">
                            @else
                                <span class="text-xs font-semibold">Traitement…</span>
                            @endif
                        </div>
                        @if ($photo->consent_confirmed)
                            <span class="self-start inline-flex items-center gap-1 text-xs leading-4 font-semibold px-2 py-0.5 rounded-full bg-ezer-green-light text-ezer-green border border-ezer-green">
                                <svg aria-hidden="true" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>
                                Accord de publication
                            </span>
                        @endif
                        <span class="text-sm leading-5">{{ $photo->caption ?: $photo->title }}</span>
                        <span class="text-xs leading-4 text-ezer-muted">Action du {{ ($photo->action_date ?? $photo->created_at)->locale('fr')->translatedFormat('j F Y') }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
</div>
