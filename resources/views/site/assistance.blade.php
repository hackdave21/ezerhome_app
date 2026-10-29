<x-layouts.public title="Demander une assistance — Ezer Home" active="assistance">

        <section class="bg-ezer-green-light">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-12 flex flex-col gap-3">
                <nav aria-label="Fil d'Ariane" class="text-sm">
                    <ol class="flex flex-wrap items-center gap-2 text-ezer-muted">
                        <li><a href="{{ url('/') }}" class="text-ezer-green hover:underline">Accueil</a></li>
                        <li aria-hidden="true">/</li>
                        <li aria-current="page">Assistance</li>
                    </ol>
                </nav>
                <h1 class="font-cooper font-normal text-3xl lg:text-5xl">Demander une assistance</h1>
                <p class="text-[17px] max-w-[680px]">Besoin d'un soutien matériel ? Remplissez ce formulaire, sans créer de compte. Votre demande est confidentielle et traitée par notre comité.</p>
            </div>
        </section>

        <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-14 flex flex-col lg:flex-row items-start gap-10 lg:gap-14">
            <div class="flex-1 basis-[560px] min-w-0 w-full flex flex-col gap-6">

                @if (session('tracking_no'))
                    <div role="status" class="flex flex-col gap-2 p-5 rounded-ezer bg-ezer-green-light border border-ezer-green-border">
                        <h2 class="text-lg font-semibold text-ezer-green">Demande envoyée</h2>
                        <p>Votre numéro de suivi est : <strong class="font-semibold text-xl">{{ session('tracking_no') }}</strong></p>
                        <p class="text-[15px]">Conservez-le. Vous pouvez suivre votre demande à tout moment depuis la <a href="{{ route('assistance.suivi') }}" class="font-semibold text-ezer-green hover:underline">page de suivi</a>.</p>
                    </div>
                @endif

                @if ($errors->any())
                    <ul role="alert" class="p-3.5 px-4 rounded-ezer bg-ezer-red-light border border-ezer-red-border text-ezer-red text-[15px] list-disc pl-8">
                        @foreach ($errors->all() as $erreur)
                            <li>{{ $erreur }}</li>
                        @endforeach
                    </ul>
                @endif

                <form method="POST" action="{{ route('assistance.store') }}" novalidate class="flex flex-col gap-5">
                    @csrf
                    {{-- Honeypot anti-robot --}}
                    <div aria-hidden="true" style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden">
                        <label for="as-website">Ne pas remplir</label>
                        <input id="as-website" name="website" type="text" tabindex="-1" autocomplete="off">
                    </div>

                    <p class="text-sm text-ezer-muted">Tous les champs sont obligatoires, sauf mention « facultatif ».</p>

                    <div class="flex flex-col gap-1.5">
                        <label for="as-nom" class="text-[15px] font-semibold">Nom et prénoms</label>
                        <input id="as-nom" name="full_name" value="{{ old('full_name') }}" type="text" autocomplete="name" class="w-full h-[52px] px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1.5">
                            <label for="as-tel" class="text-[15px] font-semibold">Téléphone ou WhatsApp</label>
                            <div class="flex items-stretch border border-ezer-borderMuted rounded-ezer overflow-hidden bg-white">
                                <span aria-hidden="true" class="inline-flex items-center px-3.5 bg-ezer-green-light font-semibold border-r border-ezer-borderMuted">+228</span>
                                <input id="as-tel" name="telephone" value="{{ old('telephone') }}" type="tel" inputmode="tel" autocomplete="tel" class="flex-1 min-w-0 h-[52px] px-4 border-0 bg-white focus:outline-none">
                            </div>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="as-mail" class="text-[15px] font-semibold">E-mail <span class="font-normal text-ezer-muted">(facultatif)</span></label>
                            <input id="as-mail" name="email" value="{{ old('email') }}" type="email" autocomplete="email" class="w-full h-[52px] px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none">
                        </div>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="as-cat" class="text-[15px] font-semibold">Type d'aide</label>
                        <select id="as-cat" name="category" class="w-full h-[52px] px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none">
                            <option value="">Choisissez</option>
                            @foreach ($categories as $cle => $label)
                                <option value="{{ $cle }}" @selected(old('category') === $cle)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <label for="as-desc" class="text-[15px] font-semibold">Décrivez votre situation</label>
                        <textarea id="as-desc" name="description" rows="6" class="w-full p-3 px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none resize-y">{{ old('description') }}</textarea>
                    </div>

                    <label class="flex items-start gap-3 text-[15px] cursor-pointer">
                        <input type="checkbox" name="consentement" value="1" @checked(old('consentement')) class="w-6 h-6 shrink-0 accent-ezer-green">
                        J'accepte qu'Ezer Home traite ces informations pour étudier ma demande d'aide. <a href="{{ url('/confidentialite') }}" class="font-semibold text-ezer-green hover:underline">Confidentialité</a>
                    </label>

                    @if (config('services.turnstile.site_key'))
                        <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}"></div>
                    @endif

                    <button type="submit" class="self-start w-full sm:w-auto inline-flex items-center justify-center min-h-[52px] px-7 rounded-ezer bg-ezer-green text-white font-semibold hover:bg-ezer-green-dark transition-colors">Envoyer ma demande</button>
                </form>
            </div>

            <aside class="flex-1 basis-[320px] min-w-0 w-full flex flex-col gap-5">
                <div class="bg-ezer-green-light rounded-ezer p-6 flex flex-col gap-3">
                    <h2 class="text-lg font-semibold">Suivre une demande</h2>
                    <p class="text-[15px]">Vous avez déjà un numéro de suivi ? Consultez l'état de votre demande.</p>
                    <a href="{{ route('assistance.suivi') }}" class="self-start inline-flex items-center justify-center min-h-[48px] px-5 rounded-ezer border-2 border-ezer-green text-ezer-green font-semibold hover:bg-ezer-green hover:text-white transition-colors">Suivre ma demande</a>
                </div>
                <div class="border border-ezer-green-border rounded-ezer p-6 flex flex-col gap-2.5">
                    <h2 class="text-lg font-semibold">Confidentialité</h2>
                    <p class="text-[15px]">Vos informations ne sont visibles que par le comité d'assistance. Elles sont chiffrées et ne sont jamais publiées.</p>
                </div>
            </aside>
        </div>

@if (config('services.turnstile.site_key'))
    @push('scripts')
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endpush
@endif
</x-layouts.public>
