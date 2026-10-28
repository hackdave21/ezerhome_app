<x-layouts.guest title="Activer la double authentification — Ezer Home">

                <div class="flex flex-col gap-2">
                    <h1 class="font-cooper font-normal text-3xl">Activer la double authentification</h1>
                    <p>Une protection en plus : à chaque connexion, un code qui change vous sera demandé. Comptez 2 minutes.</p>
                </div>

                @if (session('status'))<p role="status" class="p-3.5 px-4 rounded-ezer bg-ezer-green-light text-[15px] font-semibold">{{ session('status') }}</p>@endif
                <form method="POST" action="{{ route('two-factor.confirm') }}" novalidate class="flex flex-col gap-6">
                @csrf
                <ol class="flex flex-col gap-7 list-none">
                    <li class="flex gap-3.5">
                        <span aria-hidden="true" class="shrink-0 w-8 h-8 rounded-full border-2 border-ezer-green inline-flex items-center justify-center font-semibold">1</span>
                        <div class="flex flex-col gap-1 min-w-0">
                            <h2 class="text-[17px] font-semibold">Installez une application d'authentification</h2>
                            <p class="text-[15px] text-ezer-muted">Par exemple Google Authenticator ou Microsoft Authenticator. Elles sont gratuites.</p>
                        </div>
                    </li>

                    <li class="flex gap-3.5">
                        <span aria-hidden="true" class="shrink-0 w-8 h-8 rounded-full border-2 border-ezer-green inline-flex items-center justify-center font-semibold">2</span>
                        <div class="flex flex-col gap-3 min-w-0">
                            <h2 class="text-[17px] font-semibold">Scannez ce QR code avec l'application</h2>
                            <div class="self-start p-3 border border-ezer-green-border rounded-ezer bg-white">
                                {!! $qrSvg !!}
                            </div>
                            <div class="flex flex-col gap-1.5 p-3.5 px-4 rounded-ezer bg-ezer-green-light">
                                <span class="text-sm">Impossible de scanner ? Saisissez cette clé dans l'application :</span>
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <code class="font-mono text-base font-semibold tracking-widest break-all">{{ $cle }}</code>
                                    <button type="button" id="copier-cle" class="min-h-[44px] px-3.5 border-2 border-ezer-green rounded-ezer bg-white text-ezer-green font-semibold text-sm">Copier</button>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="flex gap-3.5">
                        <span aria-hidden="true" class="shrink-0 w-8 h-8 rounded-full border-2 border-ezer-green inline-flex items-center justify-center font-semibold">3</span>
                        <div class="flex flex-col gap-2 min-w-0">
                            <label for="act" class="text-[17px] font-semibold">Saisissez le code affiché</label>
                            <input id="act" name="code" required type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="6" placeholder="––––––" class="w-full h-[64px] px-4 text-center text-[28px] font-semibold tracking-[0.5em] border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none">
                            @error('code')<p role="alert" class="text-sm font-semibold text-ezer-red">{{ $message }}</p>@enderror
                        </div>
                    </li>
                </ol>

                <button type="submit" class="flex items-center justify-center min-h-[56px] rounded-ezer bg-ezer-green text-white text-[17px] font-semibold hover:bg-ezer-green-dark transition-colors cursor-pointer w-full">Activer</button>
                </form>

                @unless (auth()->user()->mustUseTwoFactor())
                <p class="text-center">
                    <a href="{{ route('espace.profil') }}" class="inline-flex items-center min-h-[44px] font-semibold text-ezer-green hover:underline">Plus tard</a>
                </p>
                @endunless

@push('scripts')
<script>
(function () {
    var btn = document.getElementById('copier-cle');
    btn.addEventListener('click', function () {
        try { if (navigator.clipboard) navigator.clipboard.writeText(@json(str_replace(' ', '', $cle))); } catch (e) {}
        btn.textContent = 'Copié';
        setTimeout(function () { btn.textContent = 'Copier'; }, 1800);
    });
})();
</script>
@endpush
</x-layouts.guest>
