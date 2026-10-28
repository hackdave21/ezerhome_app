<x-layouts.public title="Coaching — Ezer Home" active="coaching">

        <!-- BANDEAU -->
        <section class="bg-ezer-green-light">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-12 flex flex-col gap-3">
                <nav aria-label="Fil d'Ariane" class="text-sm">
                    <ol class="flex flex-wrap items-center gap-2 text-ezer-muted">
                        <li><a href="{{ url('/') }}" class="text-ezer-green hover:underline">Accueil</a></li>
                        <li aria-hidden="true">/</li>
                        <li aria-current="page">Coaching</li>
                    </ol>
                </nav>
                <h1 class="font-cooper font-normal text-3xl lg:text-5xl">Être accompagné(e) dans la durée</h1>
                <p class="text-base max-w-[640px]">Un coaching personnalisé, avec le même coach, séance après séance, pour avancer sur un objectif qui compte pour vous.</p>
            </div>
        </section>

        <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-12 flex flex-col gap-8">

            <!-- COMMENT S'INSCRIRE -->
            <section aria-labelledby="co-comment" class="flex flex-col gap-3.5">
                <h2 id="co-comment" class="text-xl font-semibold">Comment s'inscrire</h2>
                <ol class="flex flex-col sm:flex-row flex-wrap gap-3 list-none">
                    <li class="flex-1 basis-[240px] flex items-start gap-3 p-3.5 rounded-ezer bg-ezer-green-light text-[15px]">
                        <span aria-hidden="true" class="shrink-0 w-7 h-7 rounded-full border-2 border-ezer-green bg-white inline-flex items-center justify-center text-sm font-semibold">1</span>
                        <span>Choisissez une formule, ou commencez par la séance découverte gratuite.</span>
                    </li>
                    <li class="flex-1 basis-[240px] flex items-start gap-3 p-3.5 rounded-ezer bg-ezer-green-light text-[15px]">
                        <span aria-hidden="true" class="shrink-0 w-7 h-7 rounded-full border-2 border-ezer-green bg-white inline-flex items-center justify-center text-sm font-semibold">2</span>
                        <span>Inscrivez-vous depuis votre compte Ezer Home.</span>
                    </li>
                    <li class="flex-1 basis-[240px] flex items-start gap-3 p-3.5 rounded-ezer bg-ezer-green-light text-[15px]">
                        <span aria-hidden="true" class="shrink-0 w-7 h-7 rounded-full border-2 border-ezer-green bg-white inline-flex items-center justify-center text-sm font-semibold">3</span>
                        <span>Réglez par Mixx By Yas ou Moov Money. Le bureau confirme votre premier rendez-vous.</span>
                    </li>
                </ol>
            </section>

            <!-- FILTRES + LISTE -->
            <section aria-labelledby="co-liste" class="flex flex-col gap-5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h2 id="co-liste" class="font-cooper font-normal text-2xl lg:text-3xl">Nos formules de coaching</h2>
                    <div role="group" aria-label="Filtrer les formules" class="flex flex-wrap gap-2">
                        <button type="button" data-filter="toutes" aria-pressed="true" class="min-h-[44px] px-4 rounded-ezer border-2 border-ezer-green bg-ezer-green text-white font-semibold text-[15px]">Toutes</button>
                        <button type="button" data-filter="complet" aria-pressed="false" class="min-h-[44px] px-4 rounded-ezer border border-ezer-borderMuted bg-white text-ezer-dark font-semibold text-[15px]">Encore disponibles</button>
                    </div>
                </div>

                <p role="status" id="compte" class="text-sm text-ezer-muted">4 formules</p>

                <p id="vide" hidden class="p-6 rounded-ezer bg-ezer-green-light text-center">
                    Aucun créneau disponible pour le moment.
                    <button type="button" data-filter="toutes" class="font-semibold text-ezer-green underline">Voir toutes les formules</button>
                </p>

                <ul id="liste" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 list-none">

                    <li data-type="complet" class="flex flex-col gap-3 p-6 border-2 border-ezer-green rounded-ezer bg-white">
                        <div class="flex flex-wrap gap-2">
                            <span class="text-[13px] font-semibold px-2.5 py-0.5 rounded-full bg-ezer-green-light border border-ezer-green text-ezer-green">Gratuit</span>
                        </div>
                        <h3 class="text-xl font-semibold">Séance découverte</h3>
                        <p class="text-[15px] text-ezer-muted">Pour faire connaissance, sans engagement</p>
                        <p class="font-cooper text-2xl">Gratuit</p>
                        <dl class="flex flex-col divide-y divide-ezer-green-borderLight text-sm">
                            <div class="flex justify-between gap-2 py-2"><dt class="text-ezer-muted">Séances</dt><dd class="font-semibold">1</dd></div>
                            <div class="flex justify-between gap-2 py-2"><dt class="text-ezer-muted">Durée d'une séance</dt><dd class="font-semibold">30 min</dd></div>
                            <div class="flex justify-between gap-2 py-2"><dt class="text-ezer-muted">Sur</dt><dd class="font-semibold">—</dd></div>
                        </dl>
                        <p class="flex items-center gap-2 text-sm">
                            <svg aria-hidden="true" class="shrink-0 w-4 h-4 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="9" cy="8" r="3.5"></circle><path d="M2.5 20a6.5 6.5 0 0 1 13 0"></path><path d="M16 4.5a3.5 3.5 0 0 1 0 7M18 14a6.5 6.5 0 0 1 3.5 6"></path></svg>
                            3 créneaux disponibles sur 6
                        </p>
                        <a href="{{ url('/coaching/exemple') }}" class="mt-auto inline-flex items-center justify-center min-h-[48px] rounded-ezer border-2 border-ezer-green text-ezer-green font-semibold hover:bg-ezer-green-light transition-colors">Voir le détail</a>
                    </li>

                    <li data-type="dispo" class="flex flex-col gap-3 p-6 border border-ezer-green-border rounded-ezer bg-white">
                        <div class="flex flex-wrap gap-2">
                            <span class="text-[13px] font-semibold px-2.5 py-0.5 rounded-full bg-white border border-ezer-dark">Tarifé</span>
                        </div>
                        <h3 class="text-xl font-semibold">Confiance en soi</h3>
                        <p class="font-cooper text-xl lg:text-2xl leading-tight">Sur évaluation<br><span class="text-lg">à partir de 25 000 FCFA / séance</span></p>
                        <p class="text-[15px] text-ezer-muted">Accompagnement individuel, selon les besoins identifiés à l'évaluation.</p>
                        <p class="flex items-center gap-2 text-sm">
                            <svg aria-hidden="true" class="shrink-0 w-4 h-4 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="9" cy="8" r="3.5"></circle><path d="M2.5 20a6.5 6.5 0 0 1 13 0"></path><path d="M16 4.5a3.5 3.5 0 0 1 0 7M18 14a6.5 6.5 0 0 1 3.5 6"></path></svg>
                            2 créneaux disponibles sur 4
                        </p>
                        <a href="{{ url('/coaching/exemple') }}" class="mt-auto inline-flex items-center justify-center min-h-[48px] rounded-ezer border-2 border-ezer-green text-ezer-green font-semibold hover:bg-ezer-green-light transition-colors">Voir le détail</a>
                    </li>

                    <li data-type="complet" class="flex flex-col gap-3 p-6 border border-ezer-green-border rounded-ezer bg-white">
                        <div class="flex flex-wrap gap-2">
                            <span class="text-[13px] font-semibold px-2.5 py-0.5 rounded-full bg-white border border-ezer-dark">Tarifé</span>
                            <span class="text-[13px] font-semibold px-2.5 py-0.5 rounded-full bg-ezer-dark text-white">Complet</span>
                        </div>
                        <h3 class="text-xl font-semibold">Accompagnement parental</h3>
                        <p class="font-cooper text-xl lg:text-2xl leading-tight">Sur évaluation<br><span class="text-lg">à partir de 25 000 FCFA / séance</span></p>
                        <p class="text-[15px] text-ezer-muted">Accompagnement individuel, selon les besoins identifiés à l'évaluation.</p>
                        <p class="flex items-center gap-2 text-sm">
                            <svg aria-hidden="true" class="shrink-0 w-4 h-4 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="9" cy="8" r="3.5"></circle><path d="M2.5 20a6.5 6.5 0 0 1 13 0"></path><path d="M16 4.5a3.5 3.5 0 0 1 0 7M18 14a6.5 6.5 0 0 1 3.5 6"></path></svg>
                            Aucun créneau ce mois-ci
                        </p>
                        <a href="{{ url('/coaching/exemple') }}" class="mt-auto inline-flex items-center justify-center min-h-[48px] rounded-ezer border-2 border-ezer-green text-ezer-green font-semibold hover:bg-ezer-green-light transition-colors">Voir le détail</a>
                    </li>

                    <li data-type="dispo" class="flex flex-col gap-3 p-6 border border-ezer-green-border rounded-ezer bg-white">
                        <div class="flex flex-wrap gap-2">
                            <span class="text-[13px] font-semibold px-2.5 py-0.5 rounded-full bg-white border border-ezer-dark">Tarifé</span>
                        </div>
                        <h3 class="text-xl font-semibold">Projet professionnel</h3>
                        <p class="font-cooper text-xl lg:text-2xl leading-tight">Sur évaluation<br><span class="text-lg">à partir de 25 000 FCFA / séance</span></p>
                        <p class="text-[15px] text-ezer-muted">Accompagnement individuel, selon les besoins identifiés à l'évaluation.</p>
                        <p class="flex items-center gap-2 text-sm">
                            <svg aria-hidden="true" class="shrink-0 w-4 h-4 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="9" cy="8" r="3.5"></circle><path d="M2.5 20a6.5 6.5 0 0 1 13 0"></path><path d="M16 4.5a3.5 3.5 0 0 1 0 7M18 14a6.5 6.5 0 0 1 3.5 6"></path></svg>
                            1 créneau disponible sur 3
                        </p>
                        <a href="{{ url('/coaching/exemple') }}" class="mt-auto inline-flex items-center justify-center min-h-[48px] rounded-ezer border-2 border-ezer-green text-ezer-green font-semibold hover:bg-ezer-green-light transition-colors">Voir le détail</a>
                    </li>

                </ul>
            </section>
        </div>
</x-layouts.public>
