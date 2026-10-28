<x-layouts.account title="La charte d'écoute — Ezer Home" active-tab="ecoute">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12 pb-12 lg:pb-18 py-6 lg:py-10 flex flex-col gap-6">

        @if ($signature)
            <p role="status" class="flex items-start gap-3 p-4 lg:px-5 rounded-ezer bg-ezer-green-light text-[15px]">
                <svg aria-hidden="true" class="shrink-0 mt-0.5 w-5 h-5 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2"></rect><path d="M8 11V8a4 4 0 0 1 8 0v3"></path></svg>
                Vous avez signé cette charte le {{ $signature->signed_at->locale('fr')->translatedFormat('j F Y') }}. Vous pouvez la relire à tout moment.
            </p>
        @else
            <p class="flex items-start gap-3 p-4 lg:px-5 rounded-ezer bg-ezer-green-light text-[15px]">
                <svg aria-hidden="true" class="shrink-0 mt-0.5 w-5 h-5 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2"></rect><path d="M8 11V8a4 4 0 0 1 8 0v3"></path></svg>
                L'écoute s'ouvre après la signature de la charte. Prenez le temps de la lire : elle vous protège.
            </p>
        @endif

        @if (session('status'))
            <p role="status" class="p-3.5 px-4 rounded-ezer bg-ezer-green-light border border-ezer-green text-[15px] font-semibold">{{ session('status') }}</p>
        @endif

        <div class="flex flex-col gap-1">
            <h1 class="font-cooper font-normal text-2xl lg:text-3xl">La charte d'écoute</h1>
            <p class="text-sm text-ezer-muted">Version {{ $charter->version }} · en vigueur depuis le {{ $charter->published_at->locale('fr')->translatedFormat('j F Y') }}</p>
        </div>

        <div role="region" aria-label="Texte complet de la charte d'écoute" tabindex="0"
             class="max-h-[360px] lg:max-h-[420px] overflow-y-auto p-5 lg:p-6 border border-ezer-green-border rounded-ezer flex flex-col gap-5 focus:outline-none focus-visible:border-ezer-green">
            {{-- Texte HTML de la version publiée (immuable). --}}
            {!! $charter->body !!}
        </div>

        <a href="{{ route('espace.charte.pdf') }}" class="self-start inline-flex items-center min-h-[44px] font-semibold text-ezer-green hover:underline">
            <svg aria-hidden="true" class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12"></path><path d="m7 11 5 5 5-5"></path><path d="M4 20h16"></path></svg>
            Télécharger la charte (PDF)
        </a>

        @if (! $signature && $peutSigner)
            <form method="POST" action="{{ route('espace.charte.sign') }}" class="flex flex-col gap-6">
                @csrf
                <input type="hidden" name="charter_id" value="{{ $charter->id }}">

                @if ($errors->any())
                    <p role="alert" class="text-sm font-semibold text-ezer-red">{{ $errors->first() }}</p>
                @endif

                <label id="charte-label" for="charte" class="flex items-start gap-3 p-4 px-5 border border-ezer-borderMuted rounded-ezer bg-white text-[15px] cursor-pointer has-[:checked]:border-2 has-[:checked]:border-ezer-green has-[:checked]:bg-ezer-green-light">
                    <input id="charte" name="charte" value="1" type="checkbox" class="w-6 h-6 shrink-0 accent-ezer-green">
                    J'ai lu la charte d'écoute (version {{ $charter->version }}) et je m'engage à la respecter.
                </label>

                <div class="flex flex-col gap-2.5 items-start">
                    <button id="signer" type="submit" disabled aria-disabled="true" class="w-full sm:w-auto inline-flex items-center justify-center min-h-[56px] px-7 rounded-ezer border-0 bg-ezer-muted text-white text-[17px] font-semibold cursor-not-allowed transition-colors">Signer la charte</button>
                    <p class="text-sm text-ezer-muted">La date et la version signées sont enregistrées. Vous retrouverez la charte dans « Profil et confidentialité ».</p>
                </div>
            </form>
        @endif
    </div>

    @if (! $signature && $peutSigner)
        @push('scripts')
        <script>
            (function () {
                var cgv = document.getElementById('charte');
                var btn = document.getElementById('signer');
                cgv.addEventListener('change', function () {
                    btn.disabled = !cgv.checked;
                    btn.setAttribute('aria-disabled', cgv.checked ? 'false' : 'true');
                    btn.classList.toggle('bg-ezer-green', cgv.checked);
                    btn.classList.toggle('hover:bg-ezer-green-dark', cgv.checked);
                    btn.classList.toggle('cursor-pointer', cgv.checked);
                    btn.classList.toggle('bg-ezer-muted', !cgv.checked);
                    btn.classList.toggle('cursor-not-allowed', !cgv.checked);
                });
            })();
        </script>
        @endpush
    @endif
</x-layouts.account>
