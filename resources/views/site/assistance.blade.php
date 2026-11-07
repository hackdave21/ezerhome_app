<x-layouts.public title="Demander une assistance — Ezer Home" active="assistance" description="Demandez une assistance ponctuelle auprès du comité Ezer Home : accueil, écoute et soutien adaptés à votre situation.">

        <section class="bg-ezer-green-light">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-12 flex flex-col gap-3">
                <nav aria-label="Fil d'Ariane" class="text-sm">
                    <ol class="flex flex-wrap items-center gap-2 text-ezer-muted">
                        <li><a href="{{ url('/') }}" class="text-ezer-green hover:underline">Accueil</a></li>
                        <li aria-hidden="true">/</li>
                        <li aria-current="page">Assistance</li>
                    </ol>
                </nav>
                <div class="flex flex-wrap gap-2">
                    <span class="text-[13px] font-semibold px-2.5 py-0.5 rounded-full bg-ezer-green-light border border-ezer-green text-ezer-green">Gratuit</span>
                </div>
                <h1 class="font-cooper font-normal text-3xl lg:text-5xl">Demander une aide matérielle</h1>
                <p class="text-base max-w-[640px]">Logement, alimentation, habillement, scolarité : si vous traversez une période difficile, dites-nous ce dont vous avez besoin. Chaque demande est étudiée avec attention.</p>
            </div>
        </section>

        <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-14 flex flex-col gap-6">

            @if (session('tracking_no'))
                <section role="status" class="w-full max-w-[720px] mx-auto p-6 lg:p-10 border border-ezer-green-border rounded-ezer flex flex-col gap-6">
                    <span aria-hidden="true" class="w-16 h-16 rounded-full bg-ezer-green-light text-ezer-green inline-flex items-center justify-center">
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>
                    </span>
                    <div class="flex flex-col gap-2">
                        <h2 class="font-cooper font-normal text-3xl">Merci de votre confiance.</h2>
                        <p>Le comité d'assistance va l'étudier avec attention. Nous vous ferons un retour sous 24h.</p>
                    </div>

                    <div class="bg-ezer-green-light rounded-ezer p-6 flex flex-col gap-2">
                        <span class="text-xs font-semibold uppercase tracking-wider text-ezer-green">Votre numéro de suivi</span>
                        <span class="font-cooper text-3xl lg:text-4xl tracking-wide">{{ session('tracking_no') }}</span>
                        <span>Conservez-le : il vous sera demandé si vous nous contactez au sujet de votre demande.</span>
                    </div>

                    <div class="flex flex-col gap-3">
                        <h3 class="text-xl font-semibold">Et maintenant ?</h3>
                        <ol class="flex flex-col gap-3.5 list-none">
                            <li class="flex items-start gap-3">
                                <span aria-hidden="true" class="shrink-0 w-7 h-7 rounded-full border-2 border-ezer-green inline-flex items-center justify-center text-sm font-semibold">1</span>
                                <span>Une équipe lit votre demande.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span aria-hidden="true" class="shrink-0 w-7 h-7 rounded-full border-2 border-ezer-green inline-flex items-center justify-center text-sm font-semibold">2</span>
                                <span>Elle vous contacte sous 24 heures par appel ou WhatsApp, pour en parler avec vous.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span aria-hidden="true" class="shrink-0 w-7 h-7 rounded-full border-2 border-ezer-green inline-flex items-center justify-center text-sm font-semibold">3</span>
                                <span>En fonction des disponibilités, si une aide peut être apportée, elle vous explique comment elle vous sera remise.</span>
                            </li>
                        </ol>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ url('/') }}" class="flex-1 basis-[220px] inline-flex items-center justify-center min-h-[52px] px-6 rounded-ezer bg-ezer-green text-white font-semibold hover:bg-ezer-green-dark transition-colors">Retour à l'accueil</a>
                        <a href="https://wa.me/{{ setting('contact.whatsapp') }}" class="flex-1 basis-[220px] inline-flex items-center justify-center min-h-[52px] px-5 rounded-ezer bg-white text-ezer-green border-2 border-ezer-green font-semibold hover:bg-ezer-green-light transition-colors">Une question ? WhatsApp</a>
                    </div>
                    <a href="{{ route('assistance') }}" class="self-start min-h-[44px] p-0 border-0 bg-transparent text-ezer-green font-semibold text-[15px] underline">Faire une autre demande</a>
                </section>
            @else
                <section class="flex flex-col lg:flex-row items-start gap-8 lg:gap-12">

                    <!-- EXPLICATION -->
                    <aside aria-labelledby="as-comment" class="flex-1 basis-[300px] min-w-0 flex flex-col gap-5">
                        <h2 id="as-comment" class="text-xl font-semibold">Comment ça se passe</h2>
                        <ul class="flex flex-col gap-5 list-none">
                            <li class="flex items-start gap-3.5">
                                <span aria-hidden="true" class="shrink-0 w-10 h-10 rounded-ezer bg-ezer-green-light text-ezer-green inline-flex items-center justify-center">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20s-7.5-4.6-9.2-9A4.8 4.8 0 0 1 12 6.5 4.8 4.8 0 0 1 21.2 11C19.5 15.4 12 20 12 20z"></path></svg>
                                </span>
                                <span class="flex flex-col gap-0.5">
                                    <span class="font-semibold">Gratuit</span>
                                    <span class="text-[15px] text-ezer-muted">Aucun frais, à aucune étape. Pas besoin de créer de compte. Juste un formulaire à remplir ou un message sur WhatsApp.</span>
                                </span>
                            </li>
                            <li class="flex items-start gap-3.5">
                                <span aria-hidden="true" class="shrink-0 w-10 h-10 rounded-ezer bg-ezer-green-light text-ezer-green inline-flex items-center justify-center">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2"></rect><path d="M8 11V8a4 4 0 0 1 8 0v3"></path></svg>
                                </span>
                                <span class="flex flex-col gap-0.5">
                                    <span class="font-semibold">Confidentiel</span>
                                    <span class="text-[15px] text-ezer-muted">Seul EZER HOME lit votre demande. Elle n'est jamais rendue publique ni transmise sans votre accord.</span>
                                </span>
                            </li>
                            <li class="flex items-start gap-3.5">
                                <span aria-hidden="true" class="shrink-0 w-10 h-10 rounded-ezer bg-ezer-green-light text-ezer-green inline-flex items-center justify-center">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.5"></circle><path d="M2.5 20a6.5 6.5 0 0 1 13 0"></path><path d="M16 4.5a3.5 3.5 0 0 1 0 7M18 14a6.5 6.5 0 0 1 3.5 6"></path></svg>
                                </span>
                                <span class="flex flex-col gap-0.5">
                                    <span class="font-semibold">Traitée par le comité d'assistance</span>
                                    <span class="text-[15px] text-ezer-muted">Votre demande est étudiée et vous êtes recontacté(e) sous 24 heures, par appel ou WhatsApp.</span>
                                </span>
                            </li>
                        </ul>
                        <p class="p-4 rounded-ezer bg-ezer-green-light text-[15px]">Une situation urgente ? Appelez-nous au <a href="tel:{{ preg_replace('/[^+0-9]/', '', setting('contact.telephone')) }}" class="font-semibold text-ezer-green hover:underline">{{ setting('contact.telephone') }}</a>.</p>

                        <div class="border border-ezer-green-border rounded-ezer p-6 flex flex-col gap-2.5">
                            <h2 class="text-lg font-semibold">Suivre une demande</h2>
                            <p class="text-[15px]">Vous avez déjà un numéro de suivi ? Consultez l'état de votre demande.</p>
                            <a href="{{ route('assistance.suivi') }}" class="self-start inline-flex items-center justify-center min-h-[48px] px-5 rounded-ezer border-2 border-ezer-green text-ezer-green font-semibold hover:bg-ezer-green hover:text-white transition-colors">Suivre ma demande</a>
                        </div>
                    </aside>

                    <!-- FORMULAIRE -->
                    <div class="flex-1 basis-[520px] min-w-0 w-full p-6 lg:p-10 border border-ezer-green-border rounded-ezer bg-white">
                        <form id="as-form" method="POST" action="{{ route('assistance.store') }}" novalidate aria-labelledby="as-form-titre" class="flex flex-col gap-6">
                            @csrf
                            <div aria-hidden="true" style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden">
                                <label for="as-website">Ne pas remplir</label>
                                <input id="as-website" name="website" type="text" tabindex="-1" autocomplete="off">
                            </div>

                            <div class="flex flex-col gap-1">
                                <h2 id="as-form-titre" class="font-cooper font-normal text-2xl lg:text-[26px]">Votre demande</h2>
                                <p class="text-sm text-ezer-muted">Tous les champs sont obligatoires, sauf mention « facultatif ».</p>
                            </div>

                            @if ($errors->any())
                                <div role="alert" class="border-2 border-ezer-dark rounded-ezer p-4 lg:px-5 flex flex-col gap-2">
                                    <p class="flex items-center gap-2 font-semibold">
                                        <svg aria-hidden="true" class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v6M12 16.5v.5"></path></svg>
                                        Un point est à corriger
                                    </p>
                                    <ul class="pl-7 flex flex-col gap-1 text-[15px] list-disc">
                                        @foreach ($errors->all() as $erreur)
                                            <li>{{ $erreur }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="flex flex-col gap-1.5">
                                <label for="as-nom" class="text-[15px] font-semibold">Nom et prénoms</label>
                                <input id="as-nom" name="full_name" value="{{ old('full_name') }}" type="text" autocomplete="name" class="w-full h-[52px] px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none">
                            </div>

                            <div class="flex flex-col gap-1.5">
                                <label for="as-tel" class="text-[15px] font-semibold">Téléphone ou WhatsApp</label>
                                <span class="text-sm text-ezer-muted">Nous vous recontacterons à ce numéro, par appel ou WhatsApp.</span>
                                <div class="flex items-stretch border border-ezer-borderMuted rounded-ezer overflow-hidden bg-white">
                                    <span aria-hidden="true" class="inline-flex items-center px-3.5 bg-ezer-green-light font-semibold border-r border-ezer-borderMuted">+228</span>
                                    <input id="as-tel" name="telephone" value="{{ old('telephone') }}" type="tel" inputmode="numeric" autocomplete="tel-national" class="flex-1 min-w-0 h-[52px] px-4 border-0 bg-white focus:outline-none">
                                </div>
                            </div>

                            <div class="flex flex-col gap-1.5">
                                <label for="as-mail" class="text-[15px] font-semibold">E-mail <span class="font-normal text-ezer-muted">(facultatif)</span></label>
                                <input id="as-mail" name="email" value="{{ old('email') }}" type="email" autocomplete="email" class="w-full h-[52px] px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none">
                            </div>

                            <fieldset class="border-0 p-0 m-0 min-w-0 flex flex-col gap-2">
                                <legend class="p-0 mb-2 text-[15px] font-semibold">Type d'aide</legend>
                                <div class="grid grid-cols-2 lg:grid-cols-3 gap-2">
                                    @foreach ($categories as $cle => $label)
                                        <label class="flex items-center gap-2.5 min-h-[52px] px-3.5 py-2.5 border border-ezer-borderMuted rounded-ezer bg-white text-[15px] font-semibold cursor-pointer has-[:checked]:border-2 has-[:checked]:border-ezer-green has-[:checked]:bg-ezer-green-light">
                                            <input type="radio" name="category" value="{{ $cle }}" @checked(old('category') === $cle) class="w-5 h-5 shrink-0 accent-ezer-green">{{ $label }}
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>

                            <div class="flex flex-col gap-1.5">
                                <label for="as-desc" class="text-[15px] font-semibold">Décrivez votre situation</label>
                                <span class="text-sm text-ezer-muted">Expliquez ce qui se passe et ce dont vous avez besoin. Ne partagez que ce que vous souhaitez.</span>
                                <textarea id="as-desc" name="description" rows="6" class="w-full p-3 px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none resize-y">{{ old('description') }}</textarea>
                            </div>

                            <div class="flex flex-col gap-1.5">
                                <label class="flex items-start gap-3 p-3.5 px-4 border border-transparent rounded-ezer bg-ezer-green-light text-[15px] cursor-pointer">
                                    <input type="checkbox" name="consentement" value="1" @checked(old('consentement')) class="w-6 h-6 shrink-0 accent-ezer-green">
                                    J'accepte qu'Ezer Home utilise ces informations uniquement pour traiter ma demande d'assistance.
                                </label>
                                <a href="{{ url('/confidentialite') }}" class="self-start text-sm py-1.5 text-ezer-green hover:underline">Lire notre politique de confidentialité</a>
                            </div>

                            @if (config('services.turnstile.site_key'))
                                <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}"></div>
                            @endif

                            <div class="flex flex-col gap-2.5">
                                <button type="submit" class="self-start inline-flex items-center justify-center gap-2.5 min-h-[52px] px-7 rounded-ezer bg-ezer-green text-white font-semibold hover:bg-ezer-green-dark transition-colors">Envoyer ma demande</button>
                                <p class="flex items-center gap-2 text-sm text-ezer-muted">
                                    <svg aria-hidden="true" class="w-4 h-4 shrink-0 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2"></rect><path d="M8 11V8a4 4 0 0 1 8 0v3"></path></svg>
                                    Vos informations restent confidentielles.
                                </p>
                            </div>
                        </form>
                    </div>
                </section>
            @endif
        </div>

@if (config('services.turnstile.site_key'))
    @push('scripts')
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endpush
@endif
</x-layouts.public>
