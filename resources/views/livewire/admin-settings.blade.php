<div class="flex flex-col gap-5 max-w-[680px]">
    <div>
        <h1 class="font-cooper font-normal text-2xl lg:text-[32px]">Paramètres</h1>
        <p class="text-[15px] text-ezer-muted">Coordonnées, dons et conservation des données.</p>
    </div>

    @if ($saved)
        <p role="status" class="p-3 rounded-ezer bg-ezer-green-light text-ezer-green text-sm font-semibold">Paramètres enregistrés.</p>
    @endif

    <form wire:submit="save" class="flex flex-col gap-4">
        @foreach (\App\Livewire\AdminSettings::FIELDS as $key => [$label, $type])
            <div class="flex flex-col gap-1">
                <label for="set-{{ $loop->index }}" class="text-sm font-semibold">{{ $label }}</label>
                <input id="set-{{ $loop->index }}" type="{{ $type }}" wire:model="values.{{ $loop->index }}" class="h-[44px] px-3 border border-ezer-borderMuted rounded-ezer bg-white text-sm focus:border-ezer-green focus:outline-none">
            </div>
        @endforeach
        <button type="submit" class="self-start min-h-[48px] px-5 rounded-ezer bg-ezer-green text-white font-semibold hover:bg-ezer-green-dark transition-colors cursor-pointer">Enregistrer</button>
    </form>
</div>
