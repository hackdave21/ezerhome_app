<x-layouts.public title="Formation — Écoute active et relation d'aide — Ezer Home" active="formation">

        <!-- BANDEAU -->
        <section class="bg-ezer-green-light">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-12 flex flex-col gap-3">
                <nav aria-label="Fil d'Ariane" class="text-sm">
                    <ol class="flex flex-wrap items-center gap-2 text-ezer-muted">
                        <li><a href="{{ url('/') }}" class="text-ezer-green hover:underline">Accueil</a></li>
                        <li aria-hidden="true">/</li>
                        <li><a href="{{ url('/formation') }}" class="text-ezer-green hover:underline">Formation</a></li>
                        <li aria-hidden="true">/</li>
                        <li aria-current="page">Écoute active et relation d'aide</li>
                    </ol>
                </nav>
                <span class="self-start text-[13px] font-semibold px-2.5 py-0.5 rounded-full bg-white border border-ezer-dark">Tarifé</span>
                <h1 class="font-cooper font-normal text-3xl lg:text-5xl">Écoute active et relation d'aide</h1>
                <p class="text-base max-w-[680px]">Apprenez à écouter vraiment : reformuler, accueillir les émotions et poser un cadre bienveillant dans la relation d'aide.</p>
            </div>
        </section>

        <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-12 flex flex-col lg:flex-row items-start gap-10 lg:gap-14">

            <!-- COLONNE CONTENU -->
            <div class="flex-1 min-w-0 flex flex-col gap-10 lg:gap-12">

                <!-- EN BREF -->
                <section aria-labelledby="od-bref" class="border border-ezer-green-border rounded-ezer p-5 lg:p-7 flex flex-col gap-4">
                    <h2 id="od-brief" class="font-cooper font-normal text-2xl lg:text-[28px]">En bref</h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="flex flex-col gap-0.5">
                            <dt class="text-sm text-ezer-muted">Durée</dt>
                            <dd class="font-semibold">3 jours (18 h)</dd>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <dt class="text-sm text-ezer-muted">Dates</dt>
                            <dd class="font-semibold">[dates de la session]</dd>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <dt class="text-sm text-ezer-muted">Horaires</dt>
                            <dd class="font-semibold">[9 h – 16 h]</dd>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <dt class="text-sm text-ezer-muted">Lieu</dt>
                            <dd class="font-semibold">Siège d'Ezer Home, Lomé</dd>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <dt class="text-sm text-ezer-muted">Intervenant</dt>
                            <dd class="font-semibold">[Prénom Nom], psychologue</dd>
                        </div>
                        <div class="flex flex-col gap-0.5">
                            <dt class="text-sm text-ezer-muted">Places</dt>
                            <dd class="font-semibold">5 disponibles sur 12</dd>
                        </div>
                    </dl>
                </section>

                <!-- CE QUE VOUS ALLEZ APPRENDRE -->
                <section aria-labelledby="od-apprendre" class="flex flex-col gap-4">
                    <h2 id="od-apprendre" class="font-cooper font-normal text-2xl lg:text-[28px]">Ce que vous allez apprendre</h2>
                    <ul class="flex flex-col gap-2.5 list-none">
                        <li class="flex items-start gap-2.5">
                            <svg aria-hidden="true" class="shrink-0 mt-1 w-5 h-5 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>
                            Pratiquer l'écoute active et la reformulation
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg aria-hidden="true" class="shrink-0 mt-1 w-5 h-5 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>
                            Accueillir les émotions sans les juger
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg aria-hidden="true" class="shrink-0 mt-1 w-5 h-5 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>
                            Poser un cadre clair et protecteur
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg aria-hidden="true" class="shrink-0 mt-1 w-5 h-5 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>
                            Savoir quand orienter vers un professionnel
                        </li>
                    </ul>
                </section>

                <!-- POUR QUI -->
                <section aria-labelledby="od-pourqui" class="flex flex-col gap-3">
                    <h2 id="od-pourqui" class="font-cooper font-normal text-2xl lg:text-[28px]">Pour qui ?</h2>
                    <p>Bénévoles, travailleurs sociaux, enseignants, responsables associatifs, ou toute personne qui accompagne d'autres personnes. Aucun prérequis.</p>
                </section>

                <!-- PROGRAMME -->
                <section aria-labelledby="od-programme" class="flex flex-col gap-4">
                    <h2 id="od-programme" class="font-cooper font-normal text-2xl lg:text-[28px]">Programme</h2>
                    <ol class="flex flex-col gap-3 list-none">
                        <li class="bg-ezer-green-light rounded-ezer p-5 flex flex-col gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wider text-ezer-green">Jour 1</span>
                            <h3 class="text-lg font-semibold">Les bases de l'écoute active</h3>
                            <p class="text-[15px] text-ezer-muted">Posture, attention, silences et questions ouvertes.</p>
                        </li>
                        <li class="bg-ezer-green-light rounded-ezer p-5 flex flex-col gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wider text-ezer-green">Jour 2</span>
                            <h3 class="text-lg font-semibold">Émotions et reformulation</h3>
                            <p class="text-[15px] text-ezer-muted">Accueillir ce qui est dit et ce qui ne l'est pas. Mises en situation.</p>
                        </li>
                        <li class="bg-ezer-green-light rounded-ezer p-5 flex flex-col gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wider text-ezer-green">Jour 3</span>
                            <h3 class="text-lg font-semibold">Cadre, limites et orientation</h3>
                            <p class="text-[15px] text-ezer-muted">Se protéger, protéger l'autre, et passer le relais quand c'est nécessaire.</p>
                        </li>
                    </ol>
                </section>

                <!-- INTERVENANT -->
                <section aria-labelledby="od-intervenant" class="flex flex-col gap-4">
                    <h2 id="od-intervenant" class="font-cooper font-normal text-2xl lg:text-[28px]">L'intervenant</h2>
                    <div class="flex flex-col sm:flex-row gap-4 items-start">
                        <span aria-hidden="true" class="shrink-0 w-16 h-16 rounded-full border-2 border-ezer-green text-ezer-muted inline-flex items-center justify-center">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>
                        </span>
                        <div class="flex flex-col gap-1">
                            <span class="text-xl font-semibold">[Prénom Nom]</span>
                            <span class="text-ezer-green font-semibold text-[15px]">Psychologue</span>
                            <p class="text-ezer-muted">[Courte présentation : parcours, expérience, spécialités.]</p>
                        </div>
                    </div>
                </section>

                <!-- INSCRIPTION ET PAIEMENT -->
                <section aria-labelledby="od-inscription" class="flex flex-col gap-4">
                    <h2 id="od-inscription" class="font-cooper font-normal text-2xl lg:text-[28px]">Inscription et paiement</h2>
                    <ul class="flex flex-col gap-2.5 list-none">
                        <li class="flex items-start gap-2.5"><span aria-hidden="true" class="shrink-0 mt-2 w-1.5 h-1.5 rounded-full bg-ezer-green"></span>Inscription depuis votre compte Ezer Home. Si vous n'en avez pas, vous le créerez à l'étape suivante.</li>
                        <li class="flex items-start gap-2.5"><span aria-hidden="true" class="shrink-0 mt-2 w-1.5 h-1.5 rounded-full bg-ezer-green"></span>Paiement par Mixx By Yas ou Moov Money, après votre inscription.</li>
                        <li class="flex items-start gap-2.5"><span aria-hidden="true" class="shrink-0 mt-2 w-1.5 h-1.5 rounded-full bg-ezer-green"></span>Le bureau vérifie le paiement et vous confirme votre place par SMS ou WhatsApp.</li>
                        <li class="flex items-start gap-2.5"><span aria-hidden="true" class="shrink-0 mt-2 w-1.5 h-1.5 rounded-full bg-ezer-green"></span>Annulation : [conditions d'annulation et de remboursement].</li>
                    </ul>
                </section>
            </div>

            <!-- ASIDE INSCRIPTION -->
            <aside class="lg:sticky lg:top-32 w-full lg:w-[320px] shrink-0 border border-ezer-green-border rounded-ezer p-6 flex flex-col gap-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-ezer-green">Inscription</p>
                <p class="font-cooper text-3xl">15 000 FCFA</p>
                <p class="text-sm text-ezer-muted">Pour les 3 jours de formation.</p>
                <p class="flex items-center gap-2 text-[15px]">
                    <svg aria-hidden="true" class="shrink-0 w-5 h-5 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="9" cy="8" r="3.5"></circle><path d="M2.5 20a6.5 6.5 0 0 1 13 0"></path><path d="M16 4.5a3.5 3.5 0 0 1 0 7M18 14a6.5 6.5 0 0 1 3.5 6"></path></svg>
                    5 places disponibles sur 12
                </p>
                <a href="{{ url('/commande/recapitulatif') }}" class="w-full inline-flex items-center justify-center min-h-[52px] rounded-ezer bg-ezer-green text-white font-semibold hover:bg-ezer-green-dark transition-colors">S'inscrire</a>
                <p class="text-sm text-ezer-muted">Un compte Ezer Home est nécessaire. Vous pourrez le créer à l'étape suivante.</p>
                <p class="pt-3 border-t border-ezer-green-borderLight text-sm">Une question ? <a href="https://wa.me/22800000000" class="font-semibold text-ezer-green hover:underline">Écrivez-nous sur WhatsApp</a></p>
            </aside>
        </div>

        <!-- BARRE D'INSCRIPTION MOBILE -->
        <div class="lg:hidden sticky bottom-0 z-40 bg-white border-t border-ezer-green-border px-4 py-3 flex items-center gap-3">
            <div class="flex flex-col">
                <span class="text-xs text-ezer-muted">Inscription</span>
                <span class="font-semibold">15 000 FCFA</span>
            </div>
            <a href="{{ url('/commande/recapitulatif') }}" class="ml-auto inline-flex items-center justify-center min-h-[48px] px-6 rounded-ezer bg-ezer-green text-white font-semibold">S'inscrire</a>
        </div>
</x-layouts.public>
