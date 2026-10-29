<x-layouts.public title="Suivre ma demande — Ezer Home" active="assistance">

        <section class="bg-ezer-green-light">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-12 flex flex-col gap-3">
                <nav aria-label="Fil d'Ariane" class="text-sm">
                    <ol class="flex flex-wrap items-center gap-2 text-ezer-muted">
                        <li><a href="{{ url('/') }}" class="text-ezer-green hover:underline">Accueil</a></li>
                        <li aria-hidden="true">/</li>
                        <li><a href="{{ route('assistance') }}" class="text-ezer-green hover:underline">Assistance</a></li>
                        <li aria-hidden="true">/</li>
                        <li aria-current="page">Suivi</li>
                    </ol>
                </nav>
                <h1 class="font-cooper font-normal text-3xl lg:text-5xl">Suivre ma demande</h1>
                <p class="text-[17px] max-w-[680px]">Entrez votre numéro de suivi et votre téléphone pour connaître l'état de votre demande.</p>
            </div>
        </section>

        <div class="max-w-[680px] mx-auto px-6 lg:px-12 py-8 lg:py-14 flex flex-col gap-6">

            <form method="POST" action="{{ route('assistance.track') }}" class="flex flex-col gap-4 p-5 lg:p-6 border border-ezer-green-border rounded-ezer">
                @csrf
                <div class="flex flex-col gap-1.5">
                    <label for="su-num" class="text-[15px] font-semibold">Numéro de suivi</label>
                    <input id="su-num" name="tracking_no" value="{{ old('tracking_no') }}" type="text" placeholder="ASS-2026-00042" class="w-full h-[52px] px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none">
                    @error('tracking_no')<span role="alert" class="text-sm font-semibold text-ezer-red">{{ $message }}</span>@enderror
                </div>
                <div class="flex flex-col gap-1.5">
                    <label for="su-tel" class="text-[15px] font-semibold">Téléphone</label>
                    <div class="flex items-stretch border border-ezer-borderMuted rounded-ezer overflow-hidden bg-white">
                        <span aria-hidden="true" class="inline-flex items-center px-3.5 bg-ezer-green-light font-semibold border-r border-ezer-borderMuted">+228</span>
                        <input id="su-tel" name="telephone" value="{{ old('telephone') }}" type="tel" inputmode="tel" class="flex-1 min-w-0 h-[52px] px-4 border-0 bg-white focus:outline-none">
                    </div>
                    @error('telephone')<span role="alert" class="text-sm font-semibold text-ezer-red">{{ $message }}</span>@enderror
                </div>
                <button type="submit" class="self-start inline-flex items-center justify-center min-h-[52px] px-7 rounded-ezer bg-ezer-green text-white font-semibold hover:bg-ezer-green-dark transition-colors">Voir l'état</button>
            </form>

            @if ($demande)
                <div class="flex flex-col gap-3 p-5 rounded-ezer bg-ezer-green-light border border-ezer-green-border">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <span class="font-semibold">{{ $demande->tracking_no }}</span>
                        <span class="inline-flex items-center px-3 min-h-[32px] rounded-ezer bg-white text-ezer-green text-sm font-semibold">{{ $demande->status->label() }}</span>
                    </div>
                    <p>{{ $demande->status->publicHint() }}</p>
                </div>
            @elseif ($introuvable)
                <p role="alert" class="p-4 rounded-ezer bg-ezer-red-light border border-ezer-red-border text-ezer-red">Aucune demande ne correspond à ce numéro et ce téléphone. Vérifiez votre saisie.</p>
            @endif
        </div>
</x-layouts.public>
