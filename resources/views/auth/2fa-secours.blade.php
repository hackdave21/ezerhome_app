<x-layouts.guest title="Codes de secours — Ezer Home">

                <span aria-hidden="true" class="w-16 h-16 rounded-full bg-ezer-green-light text-ezer-green inline-flex items-center justify-center">
                    <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>
                </span>

                <div class="flex flex-col gap-2">
                    <h1 class="font-cooper font-normal text-3xl">Double authentification activée</h1>
                    <p>Dernière étape : gardez ces codes de secours en lieu sûr. Chacun vous permet de vous connecter une fois si vous perdez votre téléphone.</p>
                </div>

                <ul aria-label="Codes de secours" id="codes" class="p-4 grid grid-cols-2 gap-x-4 gap-y-2.5 border-2 border-dashed border-ezer-green rounded-ezer list-none">
                    @foreach ($codes as $code)
                    <li class="font-mono text-[17px] font-semibold tracking-widest text-center py-1.5">{{ $code }}</li>
                    @endforeach
                </ul>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <button type="button" id="telecharger-codes" class="min-h-[48px] border-2 border-ezer-green rounded-ezer bg-white text-ezer-green font-semibold">Télécharger</button>
                    <button type="button" id="copier-codes" class="min-h-[48px] border-2 border-ezer-green rounded-ezer bg-white text-ezer-green font-semibold">Copier</button>
                </div>

                <label class="flex items-start gap-3 text-[15px] cursor-pointer">
                    <input id="note" type="checkbox" class="w-6 h-6 shrink-0 accent-ezer-green">
                    J'ai noté ou téléchargé mes codes de secours.
                </label>

                <button id="terminer" type="button" disabled class="w-full min-h-[56px] rounded-ezer border-0 bg-ezer-muted text-white text-[17px] font-semibold cursor-not-allowed transition-colors">Terminer</button>

@push('scripts')
<script>
(function () {
    var CODES = @json($codes);
    var note = document.getElementById('note');
    var terminer = document.getElementById('terminer');

    note.addEventListener('change', function () {
        terminer.disabled = !note.checked;
        terminer.classList.toggle('bg-ezer-muted', !note.checked);
        terminer.classList.toggle('cursor-not-allowed', !note.checked);
        terminer.classList.toggle('bg-ezer-green', note.checked);
        terminer.classList.toggle('hover:bg-ezer-green-dark', note.checked);
    });

    terminer.addEventListener('click', function () { window.location.href = @json(route('espace.profil')); });

    document.getElementById('copier-codes').addEventListener('click', function () {
        var btn = this;
        try { if (navigator.clipboard) navigator.clipboard.writeText(CODES.join('\n')); } catch (e) {}
        btn.textContent = 'Copié';
        setTimeout(function () { btn.textContent = 'Copier'; }, 1800);
    });

    document.getElementById('telecharger-codes').addEventListener('click', function () {
        var a = document.createElement('a');
        a.href = URL.createObjectURL(new Blob(['Codes de secours Ezer Home\n\n' + CODES.join('\n') + '\n'], { type: 'text/plain' }));
        a.download = 'codes-de-secours-ezer-home.txt';
        a.click();
        URL.revokeObjectURL(a.href);
    });
})();
</script>
@endpush
</x-layouts.guest>
