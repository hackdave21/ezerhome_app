<x-layouts.public title="Formations — Ezer Home" active="formation">

        <!-- BANDEAU -->
        <section class="bg-ezer-green-light">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-12 flex flex-col gap-3">
                <nav aria-label="Fil d'Ariane" class="text-sm">
                    <ol class="flex flex-wrap items-center gap-2 text-ezer-muted">
                        <li><a href="{{ url('/') }}" class="text-ezer-green hover:underline">Accueil</a></li>
                        <li aria-hidden="true">/</li>
                        <li aria-current="page">Formation</li>
                    </ol>
                </nav>
                <h1 class="font-cooper font-normal text-3xl lg:text-5xl">Se former</h1>
                <p class="text-base text-ezer-dark max-w-[640px]">Des formations courtes et pratiques, animées par des professionnels, pour développer vos compétences.</p>
            </div>
        </section>

        <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-12 flex flex-col gap-8">

            <!-- COMMENT S'INSCRIRE -->
            <section aria-labelledby="of-comment" class="flex flex-col gap-3.5">
                <h2 id="of-comment" class="text-xl font-semibold">Comment s'inscrire</h2>
                <ol class="flex flex-col sm:flex-row flex-wrap gap-3 list-none">
                    <li class="flex-1 basis-[240px] flex items-start gap-3 p-3.5 rounded-ezer bg-ezer-green-light text-[15px]">
                        <span aria-hidden="true" class="shrink-0 w-7 h-7 rounded-full border-2 border-ezer-green bg-white inline-flex items-center justify-center text-sm font-semibold">1</span>
                        <span>Choisissez une formation et consultez son programme.</span>
                    </li>
                    <li class="flex-1 basis-[240px] flex items-start gap-3 p-3.5 rounded-ezer bg-ezer-green-light text-[15px]">
                        <span aria-hidden="true" class="shrink-0 w-7 h-7 rounded-full border-2 border-ezer-green bg-white inline-flex items-center justify-center text-sm font-semibold">2</span>
                        <span>Inscrivez-vous depuis votre compte Ezer Home.</span>
                    </li>
                    <li class="flex-1 basis-[240px] flex items-start gap-3 p-3.5 rounded-ezer bg-ezer-green-light text-[15px]">
                        <span aria-hidden="true" class="shrink-0 w-7 h-7 rounded-full border-2 border-ezer-green bg-white inline-flex items-center justify-center text-sm font-semibold">3</span>
                        <span>Pour une formation tarifée, réglez par Mixx By Yas ou Moov Money. Le bureau confirme votre place.</span>
                    </li>
                </ol>
            </section>

            <!-- FILTRES + LISTE -->
            <section aria-labelledby="of-liste" class="flex flex-col gap-5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h2 id="of-liste" class="font-cooper font-normal text-2xl lg:text-3xl">Prochaines formations</h2>
                    <div role="group" aria-label="Filtrer les offres" class="flex flex-wrap gap-2">
                        <button type="button" data-filter="toutes" aria-pressed="true" class="min-h-[44px] px-4 rounded-ezer border-2 border-ezer-green bg-ezer-green text-white font-semibold text-[15px]">Toutes</button>
                        <button type="button" data-filter="gratuites" aria-pressed="false" class="min-h-[44px] px-4 rounded-ezer border border-ezer-borderMuted bg-white text-ezer-dark font-semibold text-[15px]">Gratuites</button>
                        <button type="button" data-filter="tarifees" aria-pressed="false" class="min-h-[44px] px-4 rounded-ezer border border-ezer-borderMuted bg-white text-ezer-dark font-semibold text-[15px]">Tarifées</button>
                    </div>
                </div>

                <p role="status" id="compte" class="text-sm text-ezer-muted">4 offres</p>

                <p id="vide" class="hidden p-6 rounded-ezer bg-ezer-green-light text-center">
                    Aucune offre ne correspond pour le moment.
                    <button type="button" data-filter="toutes" class="font-semibold text-ezer-green underline">Voir toutes les offres</button>
                </p>

                <ul id="liste" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 list-none">

                    <li data-type="tarifee" class="flex flex-col gap-3.5 p-6 border border-ezer-green-border rounded-ezer bg-white">
                        <div class="flex flex-wrap gap-2">
                            <span class="text-[13px] font-semibold px-2.5 py-0.5 rounded-full bg-white border border-ezer-dark">Tarifé</span>
                        </div>
                        <h3 class="text-xl font-semibold">Écoute active et relation d'aide</h3>
                        <dl class="flex flex-col gap-2 text-[15px]">
                            <div class="flex items-start gap-2.5">
                                <dt class="shrink-0 mt-0.5"><svg role="img" aria-label="Durée" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="#187d50" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v5l3 2"></path></svg></dt>
                                <dd>3 jours (18 h)</dd>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <dt class="shrink-0 mt-0.5"><svg role="img" aria-label="Intervenant" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="#187d50" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg></dt>
                                <dd>[Prénom Nom], psychologue</dd>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <dt class="shrink-0 mt-0.5"><svg role="img" aria-label="Dates" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="#187d50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M3 10h18M8 3v4M16 3v4"></path></svg></dt>
                                <dd>Session du [date] · Siège, Lomé</dd>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <dt class="shrink-0 mt-0.5"><svg role="img" aria-label="Places" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="#187d50" stroke-width="2" stroke-linecap="round"><circle cx="9" cy="8" r="3.5"></circle><path d="M2.5 20a6.5 6.5 0 0 1 13 0"></path><path d="M16 4.5a3.5 3.5 0 0 1 0 7M18 14a6.5 6.5 0 0 1 3.5 6"></path></svg></dt>
                                <dd>5 places disponibles sur 12</dd>
                            </div>
                        </dl>
                        <div class="mt-auto pt-3.5 border-t border-ezer-green-borderLight flex flex-wrap items-center justify-between gap-3">
                            <span class="text-[22px] font-semibold">15 000 FCFA</span>
                            <a href="{{ url('/formation/exemple') }}" aria-label="Voir le détail : Écoute active et relation d'aide" class="flex-1 basis-[160px] inline-flex items-center justify-center min-h-[48px] px-4 rounded-ezer bg-ezer-green text-white border-2 border-ezer-green font-semibold hover:bg-ezer-green-dark transition-colors">Voir le détail</a>
                        </div>
                    </li>

                    <li data-type="tarifee" class="flex flex-col gap-3.5 p-6 border border-ezer-green-border rounded-ezer bg-white">
                        <div class="flex flex-wrap gap-2">
                            <span class="text-[13px] font-semibold px-2.5 py-0.5 rounded-full bg-white border border-ezer-dark">Tarifé</span>
                        </div>
                        <h3 class="text-xl font-semibold">Gérer son stress au quotidien</h3>
                        <dl class="flex flex-col gap-2 text-[15px]">
                            <div class="flex items-start gap-2.5">
                                <dt class="shrink-0 mt-0.5"><svg role="img" aria-label="Durée" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="#187d50" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v5l3 2"></path></svg></dt>
                                <dd>1 journée (6 h)</dd>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <dt class="shrink-0 mt-0.5"><svg role="img" aria-label="Intervenant" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="#187d50" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg></dt>
                                <dd>[Prénom Nom], formateur·rice</dd>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <dt class="shrink-0 mt-0.5"><svg role="img" aria-label="Dates" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="#187d50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M3 10h18M8 3v4M16 3v4"></path></svg></dt>
                                <dd>Session du [date] · Siège, Lomé</dd>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <dt class="shrink-0 mt-0.5"><svg role="img" aria-label="Places" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="#187d50" stroke-width="2" stroke-linecap="round"><circle cx="9" cy="8" r="3.5"></circle><path d="M2.5 20a6.5 6.5 0 0 1 13 0"></path><path d="M16 4.5a3.5 3.5 0 0 1 0 7M18 14a6.5 6.5 0 0 1 3.5 6"></path></svg></dt>
                                <dd>14 places disponibles sur 20</dd>
                            </div>
                        </dl>
                        <div class="mt-auto pt-3.5 border-t border-ezer-green-borderLight flex flex-wrap items-center justify-between gap-3">
                            <span class="text-[22px] font-semibold">5 000 FCFA</span>
                            <a href="{{ url('/formation/exemple') }}" aria-label="Voir le détail : Gérer son stress au quotidien" class="flex-1 basis-[160px] inline-flex items-center justify-center min-h-[48px] px-4 rounded-ezer bg-ezer-green text-white border-2 border-ezer-green font-semibold hover:bg-ezer-green-dark transition-colors">Voir le détail</a>
                        </div>
                    </li>

                    <li data-type="gratuite" class="flex flex-col gap-3.5 p-6 border border-ezer-green-border rounded-ezer bg-white">
                        <div class="flex flex-wrap gap-2">
                            <span class="text-[13px] font-semibold px-2.5 py-0.5 rounded-full bg-ezer-green-light border border-ezer-green text-ezer-green">Gratuit</span>
                        </div>
                        <h3 class="text-xl font-semibold">Accompagner un proche en difficulté</h3>
                        <dl class="flex flex-col gap-2 text-[15px]">
                            <div class="flex items-start gap-2.5">
                                <dt class="shrink-0 mt-0.5"><svg role="img" aria-label="Durée" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="#187d50" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v5l3 2"></path></svg></dt>
                                <dd>2 demi-journées (6 h)</dd>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <dt class="shrink-0 mt-0.5"><svg role="img" aria-label="Intervenant" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="#187d50" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg></dt>
                                <dd>[Prénom Nom], psychologue</dd>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <dt class="shrink-0 mt-0.5"><svg role="img" aria-label="Dates" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="#187d50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M3 10h18M8 3v4M16 3v4"></path></svg></dt>
                                <dd>Session du [date] · Siège, Lomé</dd>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <dt class="shrink-0 mt-0.5"><svg role="img" aria-label="Places" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="#187d50" stroke-width="2" stroke-linecap="round"><circle cx="9" cy="8" r="3.5"></circle><path d="M2.5 20a6.5 6.5 0 0 1 13 0"></path><path d="M16 4.5a3.5 3.5 0 0 1 0 7M18 14a6.5 6.5 0 0 1 3.5 6"></path></svg></dt>
                                <dd>9 places disponibles sur 25</dd>
                            </div>
                        </dl>
                        <div class="mt-auto pt-3.5 border-t border-ezer-green-borderLight flex flex-wrap items-center justify-between gap-3">
                            <span class="text-[22px] font-semibold">Gratuit</span>
                            <a href="{{ url('/formation/exemple') }}" aria-label="Voir le détail : Accompagner un proche en difficulté" class="flex-1 basis-[160px] inline-flex items-center justify-center min-h-[48px] px-4 rounded-ezer bg-ezer-green text-white border-2 border-ezer-green font-semibold hover:bg-ezer-green-dark transition-colors">Voir le détail</a>
                        </div>
                    </li>

                    <li data-type="tarifee" class="flex flex-col gap-3.5 p-6 border border-ezer-green-border rounded-ezer bg-white">
                        <div class="flex flex-wrap gap-2">
                            <span class="text-[13px] font-semibold px-2.5 py-0.5 rounded-full bg-white border border-ezer-dark">Tarifé</span>
                            <span class="text-[13px] font-semibold px-2.5 py-0.5 rounded-full bg-ezer-dark text-white">Complet</span>
                        </div>
                        <h3 class="text-xl font-semibold">Prendre la parole en public</h3>
                        <dl class="flex flex-col gap-2 text-[15px]">
                            <div class="flex items-start gap-2.5">
                                <dt class="shrink-0 mt-0.5"><svg role="img" aria-label="Durée" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="#187d50" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v5l3 2"></path></svg></dt>
                                <dd>2 jours (12 h)</dd>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <dt class="shrink-0 mt-0.5"><svg role="img" aria-label="Intervenant" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="#187d50" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg></dt>
                                <dd>[Prénom Nom], formateur·rice</dd>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <dt class="shrink-0 mt-0.5"><svg role="img" aria-label="Dates" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="#187d50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="16" rx="2"></rect><path d="M3 10h18M8 3v4M16 3v4"></path></svg></dt>
                                <dd>Session du [date] · Siège, Lomé</dd>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <dt class="shrink-0 mt-0.5"><svg role="img" aria-label="Places" class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="#187d50" stroke-width="2" stroke-linecap="round"><circle cx="9" cy="8" r="3.5"></circle><path d="M2.5 20a6.5 6.5 0 0 1 13 0"></path><path d="M16 4.5a3.5 3.5 0 0 1 0 7M18 14a6.5 6.5 0 0 1 3.5 6"></path></svg></dt>
                                <dd>Complet</dd>
                            </div>
                        </dl>
                        <div class="mt-auto pt-3.5 border-t border-ezer-green-borderLight flex flex-wrap items-center justify-between gap-3">
                            <span class="text-[22px] font-semibold">10 000 FCFA</span>
                            <a href="{{ url('/formation/exemple') }}" aria-label="Liste d'attente : Prendre la parole en public" class="flex-1 basis-[160px] inline-flex items-center justify-center min-h-[48px] px-4 rounded-ezer bg-white text-ezer-green border-2 border-ezer-green font-semibold hover:bg-ezer-green-light transition-colors">Liste d'attente</a>
                        </div>
                    </li>

                </ul>
            </section>
        </div>
</x-layouts.public>
