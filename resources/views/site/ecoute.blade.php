<x-layouts.public title="Écoute — Ezer Home" active="ecoute" :sobre-footer="true">

        <!-- BANDEAU -->
        <section class="bg-ezer-green-light">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-14 flex flex-col gap-3.5">
                <nav aria-label="Fil d'Ariane" class="text-sm">
                    <ol class="flex flex-wrap items-center gap-2 text-ezer-muted">
                        <li><a href="{{ url('/') }}" class="text-ezer-green hover:underline">Accueil</a></li>
                        <li aria-hidden="true">/</li>
                        <li aria-current="page">Écoute</li>
                    </ol>
                </nav>
                <span class="self-start text-[13px] font-semibold px-2.5 py-0.5 rounded-full bg-ezer-green-light border border-ezer-green text-ezer-green">Gratuit</span>
                <h1 class="font-cooper font-normal text-3xl lg:text-[44px] leading-[38px] lg:leading-[52px]">Écoute &amp; Prière</h1>
                <p class="text-[17px]">Un temps pour être entendu sans être jugé, et, si vous le désirez, pour prier ensemble. Ezer Home vous offre un temps de parole confidentiel, à votre rythme, avec une personne de son équipe d'écoute.</p>
            </div>
        </section>

        <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-10 lg:py-16 flex flex-col gap-12 lg:gap-14">

            <!-- CE QUE NOUS OFFRONS -->
            <section aria-labelledby="ec-offre" class="flex flex-col gap-5">
                <h2 id="ec-offre" class="font-cooper font-normal text-2xl lg:text-3xl">Ce que nous vous offrons</h2>
                <ul class="flex flex-wrap gap-5 list-none">
                    <li class="flex-1 basis-[200px] flex flex-col gap-2">
                        <span aria-hidden="true" class="w-11 h-11 rounded-ezer bg-ezer-green-light text-ezer-green inline-flex items-center justify-center">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a8 8 0 0 1-11.6 7.1L4 20.5l1.4-4.8A8 8 0 1 1 21 12z"></path></svg>
                        </span>
                        <span class="font-semibold">Un temps pour vous</span>
                        <span class="text-[15px] text-ezer-muted">Parler librement de ce qui vous pèse, à votre rythme.</span>
                    </li>
                    <li class="flex-1 basis-[200px] flex flex-col gap-2">
                        <span aria-hidden="true" class="w-11 h-11 rounded-ezer bg-ezer-green-light text-ezer-green inline-flex items-center justify-center">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2"></rect><path d="M8 11V8a4 4 0 0 1 8 0v3"></path></svg>
                        </span>
                        <span class="font-semibold">La confidentialité</span>
                        <span class="text-[15px] text-ezer-muted">Ce que vous dites reste entre vous et la personne qui vous écoute.</span>
                    </li>
                    <li class="flex-1 basis-[200px] flex flex-col gap-2">
                        <span aria-hidden="true" class="w-11 h-11 rounded-ezer bg-ezer-green-light text-ezer-green inline-flex items-center justify-center">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20s-7.5-4.6-9.2-9A4.8 4.8 0 0 1 12 6.5 4.8 4.8 0 0 1 21.2 11C19.5 15.4 12 20 12 20z"></path></svg>
                        </span>
                        <span class="font-semibold">Sans jugement</span>
                        <span class="text-[15px] text-ezer-muted">Vous êtes accueilli(e) tel(le) que vous êtes, sans avoir à vous justifier.</span>
                    </li>
                </ul>
            </section>

            <!-- DÉROULÉ -->
            <section aria-labelledby="ec-deroule" class="flex flex-col gap-6">
                <h2 id="ec-deroule" class="font-cooper font-normal text-2xl lg:text-3xl">Comment ça se passe</h2>
                <ol class="flex flex-col list-none">
                    <li class="flex gap-4">
                        <span aria-hidden="true" class="shrink-0 flex flex-col items-center">
                            <span class="w-9 h-9 rounded-full border-2 border-ezer-green bg-white inline-flex items-center justify-center font-semibold">1</span>
                            <span class="flex-1 w-0.5 bg-ezer-green min-h-4"></span>
                        </span>
                        <div class="flex flex-col gap-1 pt-1 pb-7">
                            <h3 class="text-lg font-semibold">Créer votre compte</h3>
                            <p class="text-[15px] text-ezer-muted">Un prénom ou un pseudonyme et un numéro de téléphone suffisent. Votre espace personnel reste privé.</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <span aria-hidden="true" class="shrink-0 flex flex-col items-center">
                            <span class="w-9 h-9 rounded-full border-2 border-ezer-green bg-white inline-flex items-center justify-center font-semibold">2</span>
                            <span class="flex-1 w-0.5 bg-ezer-green min-h-4"></span>
                        </span>
                        <div class="flex flex-col gap-1 pt-1 pb-7">
                            <h3 class="text-lg font-semibold">Signer la charte d'écoute</h3>
                            <p class="text-[15px] text-ezer-muted">Elle fixe les règles qui vous protègent : confidentialité, respect mutuel et limites de l'écoute.</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <span aria-hidden="true" class="shrink-0 flex flex-col items-center">
                            <span class="w-9 h-9 rounded-full border-2 border-ezer-green bg-white inline-flex items-center justify-center font-semibold">3</span>
                            <span class="flex-1 w-0.5 bg-ezer-green min-h-4"></span>
                        </span>
                        <div class="flex flex-col gap-1 pt-1 pb-7">
                            <h3 class="text-lg font-semibold">Faire votre demande</h3>
                            <p class="text-[15px] text-ezer-muted">Dites en quelques mots ce qui vous amène, vos disponibilités et comment vous préférez échanger : appel, WhatsApp ou rencontre.</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <span aria-hidden="true" class="shrink-0 flex flex-col items-center">
                            <span class="w-9 h-9 rounded-full border-2 border-ezer-green bg-white inline-flex items-center justify-center font-semibold">4</span>
                            <span class="flex-1 w-0.5 bg-ezer-green min-h-4"></span>
                        </span>
                        <div class="flex flex-col gap-1 pt-1 pb-7">
                            <h3 class="text-lg font-semibold">Être mis(e) en relation</h3>
                            <p class="text-[15px] text-ezer-muted">Une personne de l'équipe d'écoute vous est attribuée. Elle vous contacte sous [X] jours pour convenir d'un premier rendez-vous.</p>
                        </div>
                    </li>
                    <li class="flex gap-4">
                        <span aria-hidden="true" class="shrink-0">
                            <span class="w-9 h-9 rounded-full border-2 border-ezer-green bg-white inline-flex items-center justify-center font-semibold">5</span>
                        </span>
                        <div class="flex flex-col gap-1 pt-1 pb-7">
                            <h3 class="text-lg font-semibold">Se retrouver</h3>
                            <p class="text-[15px] text-ezer-muted">Vous vous retrouvez au rythme qui vous convient. Chaque rencontre dure environ [45 minutes]. Vous pouvez arrêter à tout moment.</p>
                        </div>
                    </li>
                </ol>
            </section>

            <!-- CHARTE -->
            <section aria-labelledby="ec-charte" class="border border-ezer-green-border rounded-ezer p-5 lg:p-8 flex flex-col gap-4">
                <h2 id="ec-charte" class="text-xl font-semibold">La charte d'écoute, en bref</h2>
                <ul class="flex flex-col gap-3 text-[15px] list-none">
                    <li class="flex gap-2.5">
                        <svg aria-hidden="true" class="shrink-0 mt-0.5 w-[18px] h-[18px] text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>
                        <span>Ce que vous partagez reste confidentiel, sauf danger grave pour vous ou pour une autre personne.</span>
                    </li>
                    <li class="flex gap-2.5">
                        <svg aria-hidden="true" class="shrink-0 mt-0.5 w-[18px] h-[18px] text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>
                        <span>La personne qui vous écoute ne vous juge pas et ne décide pas à votre place.</span>
                    </li>
                    <li class="flex gap-2.5">
                        <svg aria-hidden="true" class="shrink-0 mt-0.5 w-[18px] h-[18px] text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>
                        <span>Les échanges se font dans le respect mutuel, aux horaires convenus ensemble.</span>
                    </li>
                    <li class="flex gap-2.5">
                        <svg aria-hidden="true" class="shrink-0 mt-0.5 w-[18px] h-[18px] text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>
                        <span>L'écoute ne remplace ni un suivi médical, ni un service d'urgence.</span>
                    </li>
                </ul>
                <a href="{{ route('charte') }}" class="self-start font-semibold py-2.5 text-ezer-green hover:underline">Lire la charte complète</a>
            </section>

            <!-- URGENCE -->
            <aside aria-label="En cas d'urgence" class="flex items-start gap-3.5 border-2 border-ezer-dark rounded-ezer p-4 lg:px-5">
                <svg aria-hidden="true" class="shrink-0 mt-0.5 w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v6M12 16.5v.5"></path></svg>
                <p class="text-[15px]"><strong class="font-semibold">En cas de danger immédiat, n'attendez pas.</strong> Appelez [numéros d'urgence au Togo] ou rendez-vous au centre de santé le plus proche.</p>
            </aside>

            <!-- QUAND VOUS SEREZ PRÊT(E) -->
            <section aria-labelledby="ec-commencer" class="bg-ezer-green-light rounded-ezer p-5 lg:p-8 flex flex-col gap-4">
                <h2 id="ec-commencer" class="font-cooper font-normal text-2xl lg:text-3xl">Quand vous serez prêt(e)</h2>
                <p>Votre compte vous permet de signer la charte et de faire votre demande en toute discrétion. Vous pourrez la suivre depuis votre espace personnel.</p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ url('/register') }}" class="flex-1 basis-[220px] inline-flex items-center justify-center min-h-[52px] px-6 rounded-ezer bg-ezer-green text-white font-semibold hover:bg-ezer-green-dark transition-colors">Créer mon compte</a>
                    <a href="{{ url('/login') }}" class="flex-1 basis-[220px] inline-flex items-center justify-center min-h-[52px] px-5 rounded-ezer bg-white text-ezer-green border-2 border-ezer-green font-semibold hover:bg-ezer-green-light transition-colors">J'ai déjà un compte</a>
                </div>
                <p class="text-sm text-ezer-muted">Une question avant de commencer ? <a href="https://wa.me/22800000000" class="font-semibold text-ezer-green hover:underline">Écrivez-nous sur WhatsApp</a>.</p>
            </section>

            <!-- FAQ -->
            <section aria-labelledby="ec-faq" class="flex flex-col gap-3">
                <h2 id="ec-faq" class="text-xl font-semibold">Vos questions</h2>
                <details class="border-b border-ezer-green-borderLight">
                    <summary class="cursor-pointer py-3.5 min-h-6 font-semibold">Dois-je donner mon vrai nom ?</summary>
                    <p class="mb-4 text-[15px] text-ezer-muted">Non. Vous pouvez utiliser un prénom ou un pseudonyme. Seul un numéro de téléphone est nécessaire pour vous recontacter.</p>
                </details>
                <details class="border-b border-ezer-green-borderLight">
                    <summary class="cursor-pointer py-3.5 min-h-6 font-semibold">Qui peut lire ce que je partage ?</summary>
                    <p class="mb-4 text-[15px] text-ezer-muted">Uniquement la personne de l'équipe d'écoute qui vous accompagne, et la personne qui coordonne l'écoute pour vous attribuer quelqu'un.</p>
                </details>
                <details class="border-b border-ezer-green-borderLight">
                    <summary class="cursor-pointer py-3.5 min-h-6 font-semibold">Combien de temps dure l'accompagnement ?</summary>
                    <p class="mb-4 text-[15px] text-ezer-muted">Il n'y a pas de durée imposée. Vous décidez ensemble du rythme, et vous pouvez arrêter quand vous le souhaitez.</p>
                </details>
            </section>

        </div>
</x-layouts.public>
