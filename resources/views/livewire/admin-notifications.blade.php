<div class="flex flex-col gap-5 max-w-[760px]">
    <div>
        <h1 class="font-cooper font-normal text-2xl lg:text-[32px]">Modèles de notifications</h1>
        <p class="text-[15px] text-ezer-muted">Textes envoyés automatiquement. Les champs <code>:ref</code> et <code>:canal</code> sont remplacés à l'envoi. N'y mettez jamais d'information confidentielle.</p>
    </div>

    @if ($saved)
        <p role="status" class="p-3 rounded-ezer bg-ezer-green-light text-ezer-green text-sm font-semibold">Modèles enregistrés.</p>
    @endif

    <form wire:submit="save" class="flex flex-col gap-5">
        @foreach ($this->templates() as $modele)
            <fieldset class="flex flex-col gap-2 p-4 border border-ezer-green-border rounded-ezer">
                <legend class="text-sm font-semibold px-1">{{ $modele['label'] }}</legend>
                <div class="flex flex-col gap-1">
                    <label for="sub-{{ $loop->index }}" class="text-[13px] font-semibold">Sujet</label>
                    <input id="sub-{{ $loop->index }}" type="text" wire:model="values.{{ $loop->index }}.subject" class="h-[44px] px-3 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
                </div>
                <div class="flex flex-col gap-1">
                    <label for="bod-{{ $loop->index }}" class="text-[13px] font-semibold">Corps</label>
                    <textarea id="bod-{{ $loop->index }}" rows="3" wire:model="values.{{ $loop->index }}.body" class="px-3 py-2 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none"></textarea>
                </div>
            </fieldset>
        @endforeach
        <button type="submit" class="self-start min-h-[48px] px-5 rounded-ezer bg-ezer-green text-white font-semibold hover:bg-ezer-green-dark transition-colors cursor-pointer">Enregistrer</button>
    </form>
</div>
