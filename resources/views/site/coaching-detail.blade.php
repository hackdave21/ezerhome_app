<x-layouts.public title="Coaching — Confiance en soi — Ezer Home" active="coaching">

        <section class="bg-ezer-green-light">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-12 flex flex-col gap-3">
                <nav aria-label="Fil d'Ariane" class="text-sm">
                    <ol class="flex flex-wrap items-center gap-2 text-ezer-muted">
                        <li><a href="{{ url('/') }}" class="text-ezer-green hover:underline">Accueil</a></li>
                        <li aria-hidden="true">/</li>
                        <li><a href="{{ url('/coaching') }}" class="text-ezer-green hover:underline">Coaching</a></li>
                        <li aria-hidden="true">/</li>
                        <li aria-current="page">Confiance en soi</li>
                    </ol>
                </nav>
                <span class="self-start text-[13px] font-semibold px-2.5 py-0.5 rounded-full bg-white border border-ezer-dark">Tarifé</span>
                <h1 class="font-cooper font-normal text-3xl lg:text-5xl">Confiance en soi</h1>
                <p class="text-base max-w-[680px]">Quatre séances individuelles pour mieux vous connaître, dépasser ce qui vous freine et oser agir.</p>
            </div>
        </section>

        <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-12 flex flex-col lg:flex-row items-start gap-10 lg:gap-14">

            <div class="flex-1 min-w-0 flex flex-col gap-10 lg:gap-12">

                <section aria-labelledby="od-bref" class="border border-ezer-green-border rounded-ezer p-5 lg:p-7 flex flex-col gap-4">
                    <h2 id="od-bref" class="font-cooper font-normal text-2xl lg:text-[28px]">En bref</h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="flex flex-col gap-0.5"><dt class="text-sm text-ezer-muted">Durée</dt><dd class="font-semibold">4 séances de 1 h</dd></div>
                        <div class="flex flex-col gap-0.5"><dt class="text-sm text-ezer-muted">Rythme</dt><dd class="font-semibold">Une séance toutes les 2 semaines</dd></div>
                        <div class="flex flex-col gap-0.5"><dt class="text-sm text-ezer-muted">Lieu</dt><dd class="font-semibold">Siège, Lomé, ou par appel</dd></div>
                        <div class="flex flex-col gap-0.5"><dt class="text-sm text-ezer-muted">Coach</dt><dd class="font-semibold">[Prénom Nom]</dd></div>
                        <div class="flex flex-col gap-0.5"><dt class="text-sm text-ezer-muted">Disponibilité</dt><dd class="font-semibold">2 créneaux ce mois-ci</dd></div>
                        <div class="flex flex-col gap-0.5"><dt class="text-sm text-ezer-muted">Prix</dt><dd class="font-semibold">Sur évaluation, à partir de 25 000 FCFA / séance</dd></div>
                    </dl>
                </section>

                <section aria-labelledby="od-apprendre" class="flex flex-col gap-4">
                    <h2 id="od-apprendre" class="font-cooper font-normal text-2xl lg:text-[28px]">Ce que vous allez travailler</h2>
                    <ul class="flex flex-col gap-2.5 list-none">
                        <li class="flex items-start gap-2.5">
                            <svg aria-hidden="true" class="shrink-0 mt-1 w-5 h-5 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>
                            Identifier vos forces et ce qui vous freine
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg aria-hidden="true" class="shrink-0 mt-1 w-5 h-5 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>
                            Fixer un objectif concret et atteignable
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg aria-hidden="true" class="shrink-0 mt-1 w-5 h-5 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>
                            Mettre en place des actions entre les séances
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg aria-hidden="true" class="shrink-0 mt-1 w-5 h-5 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>
                            Faire le point sur vos progrès
                        </li>
                    </ul>
                </section>

                <section aria-labelledby="od-pourqui" class="flex flex-col gap-3">
                    <h2 id="od-pourqui" class="font-cooper font-normal text-2xl lg:text-[28px]">Pour qui ?</h2>
                    <p>Toute personne qui souhaite reprendre confiance en elle, dans sa vie personnelle ou professionnelle. Aucun prérequis.</p>
                </section>

                <section aria-labelledby="od-deroule" class="flex flex-col gap-4">
                    <h2 id="od-deroule" class="font-cooper font-normal text-2xl lg:text-[28px]">Déroulé</h2>
                    <ol class="flex flex-col gap-3 list-none">
                        <li class="bg-ezer-green-light rounded-ezer p-5 flex flex-col gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wider text-ezer-green">Séance 1</span>
                            <h3 class="text-lg font-semibold">Faire connaissance</h3>
                            <p class="text-[15px] text-ezer-muted">Vous présentez votre situation et définissez votre objectif avec votre coach.</p>
                        </li>
                        <li class="bg-ezer-green-light rounded-ezer p-5 flex flex-col gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wider text-ezer-green">Séances 2 et 3</span>
                            <h3 class="text-lg font-semibold">Avancer pas à pas</h3>
                            <p class="text-[15px] text-ezer-muted">Exercices pratiques, mises en situation et petites actions à tester entre les séances.</p>
                        </li>
                        <li class="bg-ezer-green-light rounded-ezer p-5 flex flex-col gap-1">
                            <span class="text-xs font-semibold uppercase tracking-wider text-ezer-green">Séance 4</span>
                            <h3 class="text-lg font-semibold">Bilan et suite</h3>
                            <p class="text-[15px] text-ezer-muted">Vous mesurez le chemin parcouru et repartez avec des repères pour continuer.</p>
                        </li>
                    </ol>
                </section>

                <section aria-labelledby="od-intervenant" class="flex flex-col gap-4">
                    <h2 id="od-intervenant" class="font-cooper font-normal text-2xl lg:text-[28px]">Votre coach</h2>
                    <div class="flex flex-col sm:flex-row gap-4 items-start">
                        <span aria-hidden="true" class="shrink-0 w-16 h-16 rounded-full border-2 border-ezer-green text-ezer-muted inline-flex items-center justify-center">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>
                        </span>
                        <div class="flex flex-col gap-1">
                            <span class="text-xl font-semibold">[Prénom Nom]</span>
                            <span class="text-ezer-green font-semibold text-[15px]">Coach</span>
                            <p class="text-ezer-muted">[Courte présentation : parcours, expérience, spécialités.]</p>
                        </div>
                    </div>
                </section>

                <section aria-labelledby="od-inscription" class="flex flex-col gap-4">
                    <h2 id="od-inscription" class="font-cooper font-normal text-2xl lg:text-[28px]">Inscription et paiement</h2>
                    <ul class="flex flex-col gap-2.5 list-none">
                        <li class="flex items-start gap-2.5"><span aria-hidden="true" class="shrink-0 mt-2 w-1.5 h-1.5 rounded-full bg-ezer-green"></span>Inscription depuis votre compte Ezer Home. Si vous n'en avez pas, vous le créerez à l'étape suivante.</li>
                        <li class="flex items-start gap-2.5"><span aria-hidden="true" class="shrink-0 mt-2 w-1.5 h-1.5 rounded-full bg-ezer-green"></span>Paiement par Mixx By Yas ou Moov Money, après votre inscription.</li>
                        <li class="flex items-start gap-2.5"><span aria-hidden="true" class="shrink-0 mt-2 w-1.5 h-1.5 rounded-full bg-ezer-green"></span>Le bureau vérifie le paiement et vous confirme votre place par SMS ou WhatsApp.</li>
                        <li class="flex items-start gap-2.5"><span aria-hidden="true" class="shrink-0 mt-2 w-1.5 h-1.5 rounded-full bg-ezer-green"></span>Annulation : [conditions d'annulation et de remboursement].</li>
                        <li class="flex items-start gap-2.5"><span aria-hidden="true" class="shrink-0 mt-2 w-1.5 h-1.5 rounded-full bg-ezer-green"></span>Une séance découverte gratuite est possible avant de vous engager.</li>
                    </ul>
                </section>
            </div>

            <aside class="lg:sticky lg:top-32 w-full lg:w-[320px] shrink-0 border border-ezer-green-border rounded-ezer p-6 flex flex-col gap-3">
                <p class="text-xs font-semibold uppercase tracking-wider text-ezer-green">Inscription</p>
                <p class="font-cooper text-xl lg:text-2xl leading-tight">Sur évaluation<br><span class="text-lg">à partir de 25 000 FCFA / séance</span></p>
                <p class="text-sm text-ezer-muted">Le tarif est fixé après évaluation des besoins.</p>
                <p class="flex items-center gap-2 text-[15px]">
                    <svg aria-hidden="true" class="shrink-0 w-5 h-5 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="9" cy="8" r="3.5"></circle><path d="M2.5 20a6.5 6.5 0 0 1 13 0"></path><path d="M16 4.5a3.5 3.5 0 0 1 0 7M18 14a6.5 6.5 0 0 1 3.5 6"></path></svg>
                    2 créneaux disponibles
                </p>
                <a href="{{ url('/commande/recapitulatif') }}" class="w-full inline-flex items-center justify-center min-h-[52px] rounded-ezer bg-ezer-green text-white font-semibold hover:bg-ezer-green-dark transition-colors">S'inscrire</a>
                <p class="text-sm text-ezer-muted">Un compte Ezer Home est nécessaire. Vous pourrez le créer à l'étape suivante.</p>
                <p class="pt-3 border-t border-ezer-green-borderLight text-sm">Une question ? <a href="https://wa.me/22800000000" class="font-semibold text-ezer-green hover:underline">Écrivez-nous sur WhatsApp</a></p>
            </aside>
        </div>

        <div class="lg:hidden sticky bottom-0 z-40 bg-white border-t border-ezer-green-border px-4 py-3 flex items-center gap-3">
            <div class="flex flex-col">
                <span class="text-xs text-ezer-muted">Inscription</span>
                <span class="font-semibold">Sur évaluation, à partir de 25 000 FCFA / séance</span>
            </div>
            <a href="{{ url('/commande/recapitulatif') }}" class="ml-auto inline-flex items-center justify-center min-h-[48px] px-6 rounded-ezer bg-ezer-green text-white font-semibold">S'inscrire</a>
        </div>
</x-layouts.public>
