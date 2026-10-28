<x-layouts.public title="Accueil — Ezer Home" active="accueil">

        <!-- SECTION 1 : HERO & ACCROCHE -->
        <section class="bg-ezer-green-light">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-10 lg:py-18 flex flex-wrap items-center justify-between gap-8 lg:gap-12">

                <div class="flex-1 min-w-[280px] lg:min-w-[500px] flex flex-col gap-4 lg:gap-5">
                    
                    <h1 class="font-cooper font-normal text-3xl sm:text-4xl lg:text-5xl leading-tight lg:leading-[56px] text-ezer-dark">
                        Un lieu pour déposer ce qui pèse.
                    </h1>
                    <p class="inline-flex items-center gap-2 text-sm text-ezer-muted font-medium pt-1">
                        <svg class="w-4 h-4 text-ezer-red shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20s-7.5-4.6-9.2-9A4.8 4.8 0 0 1 12 6.5 4.8 4.8 0 0 1 21.2 11C19.5 15.4 12 20 12 20z"></path></svg>
                        Sachez que vous n'êtes pas seul(e). Nous sommes là.
                    </p>
                    <p class="text-base lg:text-lg text-ezer-dark leading-relaxed max-w-[580px]">
                        EZER HOME accueille, écoute et accompagne toute personne en quête de sens, de repos ou de repères. Sur place à Lomé ou en ligne, peu importe votre lieu de résidence, et à la lumière de la Parole.
                    </p>
                    <p class="inline-flex items-center gap-2 text-sm text-ezer-muted font-medium pt-1">
                        <svg class="w-4 h-4 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="10" rx="2"></rect><path d="M8 11V8a4 4 0 0 1 8 0v3"></path></svg>
                        Écoute gratuite et confidentielle
                    </p>
                </div>

                <!-- Carte d'urgence latérale -->
                <div class="w-full lg:w-auto lg:max-w-[420px] bg-white rounded-ezer p-6 lg:p-8 flex flex-col gap-4 shadow-sm border border-ezer-green-border">
                    <h2 class="text-lg lg:text-xl font-semibold text-ezer-dark">Besoin de parler maintenant ?</h2>
                    <p class="text-sm lg:text-base text-ezer-muted">Un membre de l'équipe vous répond, en toute discrétion.</p>
                    <div class="flex flex-col gap-3">
                        <a href="https://wa.me/22898135757" target="_blank" class="flex items-center justify-center gap-2 min-h-[48px] px-4 rounded-ezer bg-ezer-green text-white font-semibold text-base hover:bg-ezer-green-dark transition-colors">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3.5 20.5l1.3-4A8.5 8.5 0 1 1 8 19.6z"></path><path d="M9 9.5c0 3 2.5 5.5 5.5 5.5l1-1.5-2-1-1 .8a4 4 0 0 1-2-2l.8-1-1-2z"></path></svg>
                            Écrire sur WhatsApp
                        </a>
                        <a href="tel:+22898135757" class="flex items-center justify-center gap-2 min-h-[48px] px-4 rounded-ezer border-2 border-ezer-green text-ezer-green font-semibold text-base hover:bg-ezer-green-light transition-colors">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"></path></svg>
                            Appeler le +228 98 13 57 57
                        </a>
                    </div>
                    <p class="text-xs text-ezer-muted">Permanence d'écoute urgente en dehors des rendez-vous : <strong><em>mercredi 17h – 20h GMT</em></strong> et <strong><em>samedi 09h – 12h GMT</em></strong>, sur WhatsApp au <strong><em>+228 98 13 57 57</em></strong>.</p>
                    <p class="text-xs text-ezer-red italic">NB : EZER HOME n'est pas un service d'urgence. En cas de danger immédiat, contactez les services d'urgence ou rendez-vous au centre de santé le plus proche.</p>
                </div>

            </div>
        </section>

        <!-- SECTION 2 : LES TROIS ENTRÉES -->
        <section class="max-w-[1400px] mx-auto px-6 lg:px-12 py-12 lg:py-18 flex flex-col gap-8 w-full">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <h2 class="font-cooper font-normal text-2xl lg:text-4xl text-ezer-dark">Par où souhaitez-vous commencer ?</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Entrée 1 : Écoute -->
                <a href="{{ url('/ecoute') }}" class="group flex flex-col justify-between p-6 lg:p-8 rounded-ezer border border-ezer-green-border bg-white hover:border-ezer-green transition-all shadow-sm">
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <span class="w-12 h-12 lg:w-14 lg:h-14 rounded-ezer bg-ezer-green-light text-ezer-green inline-flex items-center justify-center">
                                <svg class="w-6 h-6 lg:w-7 lg:h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 12a8 8 0 0 1-11.6 7.1L4 20.5l1.4-4.8A8 8 0 1 1 21 12z"></path></svg>
                            </span>
                            <span class="text-xs px-2.5 py-1 rounded-full border border-ezer-green bg-ezer-green-light text-ezer-green font-semibold">Gratuit</span>
                        </div>
                        <h3 class="text-xl lg:text-2xl font-semibold text-ezer-dark mt-2">Je veux être écouté(e)</h3>
                        <p class="text-sm lg:text-base text-ezer-muted">Un espace pour parler et être écouté(e) sans jugement et en toute confidentialité.</p>
                    </div>
                    <div class="inline-flex items-center gap-2 text-ezer-green font-semibold text-sm lg:text-base mt-6 group-hover:translate-x-1 transition-transform">
                        <span>Être écouté(e)</span>
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"></path></svg>
                    </div>
                </a>

                <!-- Entrée 2 : Formation & Coaching -->
                <a href="{{ url('/formation') }}" class="group flex flex-col justify-between p-6 lg:p-8 rounded-ezer border border-ezer-green-border bg-white hover:border-ezer-green transition-all shadow-sm">
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center justify-between">
                            <span class="w-12 h-12 lg:w-14 lg:h-14 rounded-ezer bg-ezer-green-light text-ezer-green inline-flex items-center justify-center">
                                <svg class="w-6 h-6 lg:w-7 lg:h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 19V5a2 2 0 0 1 2-2h14v14H6a2 2 0 0 0-2 2zm0 0a2 2 0 0 0 2 2h14"></path></svg>
                            </span>
                            <span class="text-xs px-2.5 py-1 rounded-full border border-ezer-dark bg-white text-ezer-dark font-semibold">Tarifé</span>
                        </div>
                    <h3 class="text-xl lg:text-2xl font-semibold text-ezer-dark mt-2">Je veux m'équiper</h3>
                    <p class="text-sm lg:text-base text-ezer-muted">Des formations pratiques et coaching pour développer vos compétences.</p>
                    </div>
                    <div class="inline-flex items-center gap-2 text-ezer-green font-semibold text-sm lg:text-base mt-6 group-hover:translate-x-1 transition-transform">
                        <span>Découvrir nos offres de formation et coaching</span>
                        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"></path></svg>
                    </div>
                </a>
            </div>
        </section>

        <!-- SECTION 3 : L'ASSOCIATION & LE BUREAU -->
        <section class="bg-ezer-green-light">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-12 lg:py-18 flex flex-wrap gap-8 lg:gap-12">

                <!-- Colonne Gauche : Présentation & Statuts -->
                <div class="flex-1 min-w-[280px] lg:min-w-[460px] flex flex-col gap-5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-ezer-green">L'association</p>
                    <h2 class="font-cooper font-normal text-2xl lg:text-4xl text-ezer-dark">Qui sommes-nous ?</h2>
                    <p class="text-base text-ezer-dark leading-relaxed max-w-[560px]">
                        Ezer Home est une association togolaise à but non lucratif. Elle offre une écoute bienveillante, une aide matérielle aux personnes dans le besoin, ainsi que des formations et un coaching pour s'équiper et avancer.
                    </p>

                    <!-- Tableau récapitulatif -->
                    <dl class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-white rounded-ezer p-5 border border-ezer-green-border">
                        <div class="flex flex-col gap-0.5">
                            <dt class="text-xs text-ezer-muted">Statut</dt>
                            <dd class="font-semibold text-sm">Association déclarée, récépissé n° [NUMÉRO]</dd>
                        </div>
                        <div class="flex flex-col gap-0.5 border-t sm:border-t-0 sm:border-l border-ezer-green-borderLight pt-2 sm:pt-0 sm:pl-3">
                            <dt class="text-xs text-ezer-muted">Siège</dt>
                            <dd class="font-semibold text-sm">[Quartier, rue], Lomé</dd>
                        </div>
                        <div class="flex flex-col gap-0.5 border-t sm:border-t-0 sm:border-l border-ezer-green-borderLight pt-2 sm:pt-0 sm:pl-3">
                            <dt class="text-xs text-ezer-muted">Création</dt>
                            <dd class="font-semibold text-sm">[ANNÉE]</dd>
                        </div>
                    </dl>

                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <a href="{{ url('/a-propos') }}" class="inline-flex items-center gap-2 min-h-[48px] px-5 rounded-ezer border-2 border-ezer-green text-ezer-green font-semibold text-sm lg:text-base hover:bg-white transition-colors">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 3H6a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8z"></path><path d="M14 3v5h5"></path></svg>
                            Lire nos statuts (PDF, [poids])
                        </a>
                        <a href="{{ url('/a-propos') }}" class="text-sm lg:text-[15px] font-semibold text-ezer-green underline">
                            En savoir plus sur l'association
                        </a>
                    </div>
                </div>

                <!-- Colonne Droite : Le Bureau -->
                <div class="flex-1 min-w-[280px] lg:min-w-[420px] flex flex-col gap-4">
                    <h3 class="text-xl font-semibold text-ezer-dark">Le bureau</h3>
                    <ul class="flex-1 grid grid-cols-1 sm:grid-cols-2 auto-rows-fr gap-5">
                        <li class="flex flex-col justify-between gap-4 bg-white rounded-ezer p-6 border border-ezer-green-border">
                            <span class="w-14 h-14 rounded-full border-2 border-ezer-green inline-flex items-center justify-center text-ezer-muted shrink-0">
                                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>
                            </span>
                            <div class="flex flex-col gap-0.5">
                                <span class="font-semibold text-base">[Prénom Nom]</span>
                                <span class="text-sm text-ezer-muted">Président(e)</span>
                            </div>
                        </li>
                        <li class="flex flex-col justify-between gap-4 bg-white rounded-ezer p-6 border border-ezer-green-border">
                            <span class="w-14 h-14 rounded-full border-2 border-ezer-green inline-flex items-center justify-center text-ezer-muted shrink-0">
                                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>
                            </span>
                            <div class="flex flex-col gap-0.5">
                                <span class="font-semibold text-base">[Prénom Nom]</span>
                                <span class="text-sm text-ezer-muted">Vice-président(e)</span>
                            </div>
                        </li>
                        <li class="flex flex-col justify-between gap-4 bg-white rounded-ezer p-6 border border-ezer-green-border">
                            <span class="w-14 h-14 rounded-full border-2 border-ezer-green inline-flex items-center justify-center text-ezer-muted shrink-0">
                                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>
                            </span>
                            <div class="flex flex-col gap-0.5">
                                <span class="font-semibold text-base">[Prénom Nom]</span>
                                <span class="text-sm text-ezer-muted">Secrétaire général(e)</span>
                            </div>
                        </li>
                        <li class="flex flex-col justify-between gap-4 bg-white rounded-ezer p-6 border border-ezer-green-border">
                            <span class="w-14 h-14 rounded-full border-2 border-ezer-green inline-flex items-center justify-center text-ezer-muted shrink-0">
                                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>
                            </span>
                            <div class="flex flex-col gap-0.5">
                                <span class="font-semibold text-base">[Prénom Nom]</span>
                                <span class="text-sm text-ezer-muted">Trésorier(ère)</span>
                            </div>
                        </li>
                    </ul>
                    <a href="{{ url('/equipe') }}" class="self-start text-sm font-semibold text-ezer-green underline pt-2">
                        Découvrir toute l'équipe
                    </a>
                </div>

            </div>
        </section>

        <!-- SECTION 4 : ASSISTANCE MATÉRIELLE & DONS -->
        <section class="max-w-[1400px] mx-auto px-6 lg:px-12 py-12 lg:py-18 grid grid-cols-1 md:grid-cols-2 gap-6 w-full">

            <!-- Carte 1 : Aide Matérielle -->
            <div class="rounded-ezer p-6 lg:p-10 border border-ezer-green-border border-t-4 border-t-ezer-green bg-white flex flex-col justify-between gap-5 shadow-sm">
                <div class="flex flex-col gap-4">
                    <div class="flex items-center justify-between">
                        <span class="w-12 h-12 rounded-ezer bg-ezer-green-light text-ezer-green inline-flex items-center justify-center">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 8 12 3 3 8v8l9 5 9-5z"></path><path d="M3 8l9 5 9-5M12 13v8"></path></svg>
                        </span>
                        <span class="text-xs px-2.5 py-1 rounded-full border border-ezer-green bg-ezer-green-light text-ezer-green font-semibold">Gratuit</span>
                    </div>
                    <h2 class="font-cooper font-normal text-2xl lg:text-3xl text-ezer-dark">Besoin d'Assistance ?</h2>
                    <p class="text-sm lg:text-base text-ezer-muted">Un soutien ponctuel pour le logement, la nourriture, les vêtements, les frais de scolarité, etc. : décrivez votre situation, nous l'étudierons avec attention.</p>
                    <p class="text-sm lg:text-base text-ezer-muted">Réponse sous [X] jours.</p>
                </div>
                <a href="{{ url('/assistance') }}" class="self-start inline-flex items-center justify-center min-h-[48px] px-6 rounded-ezer bg-ezer-green text-white font-semibold text-sm lg:text-base hover:bg-ezer-green-dark transition-colors">
                    Faire une demande d'assistance
                </a>
            </div>

            <!-- Carte 2 : Faire un Don -->
            <div class="rounded-ezer p-6 lg:p-10 bg-ezer-green text-white flex flex-col justify-between gap-5 shadow-sm">
                <div class="flex flex-col gap-4">
                    <svg class="w-9 h-9 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 20s-7.5-4.6-9.2-9A4.8 4.8 0 0 1 12 6.5 4.8 4.8 0 0 1 21.2 11C19.5 15.4 12 20 12 20z"></path></svg>
                    <h2 class="font-cooper font-normal text-2xl lg:text-3xl">Soutenir la mission</h2>
                    <p class="text-sm lg:text-base text-white/90">Vos dons financent l'assistance aux personnes dans le besoin.</p>

                    <!-- Suggestions de montants -->
                    <div class="grid grid-cols-3 gap-2 pt-1">
                        <a href="{{ url('/dons?m=2000') }}" class="flex items-center justify-center min-h-[44px] rounded-ezer border-2 border-white text-white font-semibold hover:bg-white hover:text-ezer-green transition-colors text-sm">2 000 F</a>
                        <a href="{{ url('/dons?m=5000') }}" class="flex items-center justify-center min-h-[44px] rounded-ezer border-2 border-white text-white font-semibold hover:bg-white hover:text-ezer-green transition-colors text-sm">5 000 F</a>
                        <a href="{{ url('/dons?m=10000') }}" class="flex items-center justify-center min-h-[44px] rounded-ezer border-2 border-white text-white font-semibold hover:bg-white hover:text-ezer-green transition-colors text-sm">10 000 F</a>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <div class="flex flex-wrap items-center gap-4">
                        <a href="{{ url('/dons') }}" class="inline-flex items-center justify-center min-h-[48px] px-6 rounded-ezer bg-white text-ezer-dark font-semibold text-sm lg:text-base hover:bg-gray-100 transition-colors">
                            Faire un don
                        </a>
                        <a href="{{ url('/a-quoi-servent-les-dons') }}" class="text-sm font-semibold text-white hover:underline">
                            À quoi servent les dons ?
                        </a>
                    </div>
                    <p class="text-xs text-white/80">Par Mixx By Yas ou Moov Money · Sans compte · Don anonyme possible</p>
                </div>
            </div>

        </section>

        <!-- SECTION 5 : REJOINDRE LE CLUB EZER -->
        <section id="club-ezer" class="bg-ezer-green-light">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-12 lg:py-18 flex flex-col items-center text-center gap-5">
                <h2 class="font-cooper font-normal text-2xl lg:text-4xl text-ezer-dark">Rejoindre le Club EZER</h2>
                <p class="text-base lg:text-lg text-ezer-muted max-w-[620px]">Rejoignez un groupe de parole régulier pour ne plus traverser seul les saisons difficiles.</p>
                <a href="https://wa.me/22898135757" target="_blank" class="w-full max-w-[520px] inline-flex items-center justify-center gap-2 min-h-[52px] px-6 rounded-ezer bg-ezer-green text-white font-semibold text-base hover:bg-ezer-green-dark transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3.5 20.5l1.3-4A8.5 8.5 0 1 1 8 19.6z"></path><path d="M9 9.5c0 3 2.5 5.5 5.5 5.5l1-1.5-2-1-1 .8a4 4 0 0 1-2-2l.8-1-1-2z"></path></svg>
                    Écrire sur WhatsApp
                </a>
            </div>
        </section>
</x-layouts.public>
