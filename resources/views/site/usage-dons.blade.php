<x-layouts.public title="À quoi servent les dons — Ezer Home" active="">

        <section class="bg-ezer-green-light">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-12 flex flex-col gap-3">
                <nav aria-label="Fil d'Ariane" class="text-sm">
                    <ol class="flex flex-wrap items-center gap-2 text-ezer-muted">
                        <li><a href="{{ url('/') }}" class="text-ezer-green hover:underline">Accueil</a></li>
                        <li aria-hidden="true">/</li>
                        <li aria-current="page">À quoi servent les dons</li>
                    </ol>
                </nav>
                <h1 class="font-cooper font-normal text-3xl lg:text-5xl">À quoi servent les dons</h1>
                <p class="text-base max-w-[640px]">Chaque don est enregistré, vérifié et utilisé pour les personnes que nous accompagnons. Voici comment.</p>
            </div>
        </section>

        @if ($page?->body)
            <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-14 flex flex-col gap-4">{{-- Texte HTML saisi par l'Admin --}}{!! $page->body !!}</div>
        @else
        <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-14 flex flex-col gap-14 lg:gap-16">

            <!-- RÉPARTITION -->
            <section aria-labelledby="ud-repartition" class="flex flex-col gap-5">
                <h2 id="ud-repartition" class="font-cooper font-normal text-2xl lg:text-3xl">Comment nous les répartissons</h2>
                <p class="text-ezer-muted">Répartition sur l'année [année], d'après nos comptes.</p>
                <ul class="grid grid-cols-1 md:grid-cols-3 gap-4 list-none">
                    <li class="flex flex-col gap-2 p-6 border border-ezer-green-border rounded-ezer bg-white">
                        <span class="font-cooper text-4xl text-ezer-green">[X] %</span>
                        <span class="text-lg font-semibold">Aide matérielle</span>
                        <p class="text-[15px] text-ezer-muted">Vivres, habillement, fournitures et frais de scolarité remis aux personnes accompagnées.</p>
                    </li>
                    <li class="flex flex-col gap-2 p-6 border border-ezer-green-border rounded-ezer bg-white">
                        <span class="font-cooper text-4xl text-ezer-green">[X] %</span>
                        <span class="text-lg font-semibold">Écoute gratuite</span>
                        <p class="text-[15px] text-ezer-muted">Ce qu'il faut pour que l'écoute reste gratuite et accessible à toutes et à tous.</p>
                    </li>
                    <li class="flex flex-col gap-2 p-6 border border-ezer-green-border rounded-ezer bg-white">
                        <span class="font-cooper text-4xl text-ezer-green">[X] %</span>
                        <span class="text-lg font-semibold">Fonctionnement</span>
                        <p class="text-[15px] text-ezer-muted">Local, déplacements, téléphone et communication.</p>
                    </li>
                </ul>
            </section>

            <!-- EXEMPLES -->
            <section aria-labelledby="ud-exemples" class="flex flex-col gap-5">
                <h2 id="ud-exemples" class="font-cooper font-normal text-2xl lg:text-3xl">Ce que permet votre don</h2>
                <ul class="grid grid-cols-1 md:grid-cols-3 gap-4 list-none">
                    <li class="flex flex-col gap-1 p-5 rounded-ezer bg-ezer-green-light">
                        <span class="font-cooper text-2xl">2 000 FCFA</span>
                        <span class="text-[15px]">Un repas complet pour une personne accompagnée</span>
                    </li>
                    <li class="flex flex-col gap-1 p-5 rounded-ezer bg-ezer-green-light">
                        <span class="font-cooper text-2xl">5 000 FCFA</span>
                        <span class="text-[15px]">Un kit de fournitures scolaires pour un enfant</span>
                    </li>
                    <li class="flex flex-col gap-1 p-5 rounded-ezer bg-ezer-green-light">
                        <span class="font-cooper text-2xl">10 000 FCFA</span>
                        <span class="text-[15px]">Un panier alimentaire de base pour une famille</span>
                    </li>
                </ul>
                <p class="text-[13px] text-ezer-muted">Exemples indicatifs : chaque don rejoint l'ensemble des actions de l'association.</p>
            </section>

            <!-- GESTION -->
            <section aria-labelledby="ud-gestion" class="flex flex-col lg:flex-row items-start gap-8 lg:gap-12">
                <div class="flex-1 basis-[420px] min-w-0 flex flex-col gap-4">
                    <h2 id="ud-gestion" class="font-cooper font-normal text-2xl lg:text-3xl">Comment nous gérons les dons</h2>
                    <ol class="flex flex-col gap-3.5 list-none">
                        <li class="flex items-start gap-3">
                            <span aria-hidden="true" class="shrink-0 w-7 h-7 rounded-full border-2 border-ezer-green inline-flex items-center justify-center text-sm font-semibold">1</span>
                            <span class="pt-0.5">Chaque don reçu est enregistré avec sa référence.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span aria-hidden="true" class="shrink-0 w-7 h-7 rounded-full border-2 border-ezer-green inline-flex items-center justify-center text-sm font-semibold">2</span>
                            <span class="pt-0.5">Le bureau vérifie chaque paiement à la main avant de le confirmer.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span aria-hidden="true" class="shrink-0 w-7 h-7 rounded-full border-2 border-ezer-green inline-flex items-center justify-center text-sm font-semibold">3</span>
                            <span class="pt-0.5">Le trésorier ou la trésorière tient les comptes de l'association.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span aria-hidden="true" class="shrink-0 w-7 h-7 rounded-full border-2 border-ezer-green inline-flex items-center justify-center text-sm font-semibold">4</span>
                            <span class="pt-0.5">Un bilan est présenté chaque année en assemblée générale et publié ici.</span>
                        </li>
                    </ol>
                </div>
                <aside class="flex-1 basis-[300px] min-w-0 border border-ezer-green-border rounded-ezer p-6 flex flex-col gap-2.5">
                    <h3 class="text-lg font-semibold">Nos documents</h3>
                    <a href="#" class="inline-flex items-center gap-2 min-h-[44px] font-semibold text-ezer-green hover:underline">
                        <svg aria-hidden="true" class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H6a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8z"></path><path d="M14 3v5h5"></path></svg>
                        Rapport d'activité [année] (PDF)
                    </a>
                    <a href="#" class="inline-flex items-center gap-2 min-h-[44px] font-semibold text-ezer-green hover:underline">
                        <svg aria-hidden="true" class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H6a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8z"></path><path d="M14 3v5h5"></path></svg>
                        Bilan financier [année] (PDF)
                    </a>
                    <a href="{{ url('/a-propos') }}" class="inline-flex items-center gap-2 min-h-[44px] font-semibold text-ezer-green hover:underline">
                        <svg aria-hidden="true" class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H6a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8z"></path><path d="M14 3v5h5"></path></svg>
                        Nos statuts
                    </a>
                </aside>
            </section>

            <!-- FAQ -->
            <section aria-labelledby="ud-faq" class="flex flex-col gap-3">
                <h2 id="ud-faq" class="text-xl font-semibold">Questions fréquentes</h2>
                <details class="border-b border-ezer-green-borderLight">
                    <summary class="cursor-pointer py-3.5 font-semibold">Y a-t-il un montant minimum pour donner ?</summary>
                    <p class="mb-4 text-[15px] text-ezer-muted">Non, vous pouvez donner dès <strong>100 FCFA</strong>. Chaque contribution, même modeste, permet de soutenir nos actions d'écoute et d'assistance.</p>
                </details>
                <details class="border-b border-ezer-green-borderLight">
                <summary class="cursor-pointer py-3.5 font-semibold">Puis-je donner sans créer de compte ?</summary>
                    <p class="mb-4 text-[15px] text-ezer-muted">Oui. Le don se fait sans compte, et vous pouvez rester anonyme.</p>
                </details>
                <details class="border-b border-ezer-green-borderLight">
                    <summary class="cursor-pointer py-3.5 font-semibold">Puis-je choisir à quoi sert mon don ?</summary>
                    <p class="mb-4 text-[15px] text-ezer-muted">[À définir : par exemple, un don peut être fléché vers l'aide matérielle sur simple demande.]</p>
                </details>
                <details class="border-b border-ezer-green-borderLight">
                    <summary class="cursor-pointer py-3.5 font-semibold">Est-ce que je reçois un reçu ?</summary>
                    <p class="mb-4 text-[15px] text-ezer-muted">[À définir : reçu envoyé par SMS ou e-mail après vérification du paiement, si vous avez laissé un contact.]</p>
                </details>
            </section>

            <!-- CTA -->
            <section class="bg-ezer-green text-white rounded-ezer p-6 lg:p-10 flex flex-col gap-3">
                <h2 class="font-cooper font-normal text-2xl lg:text-[28px]">Soutenir Ezer Home</h2>
                <p class="text-white/90">Par Mixx By Yas ou Moov Money, sans compte, <strong>dès 100 FCFA</strong>, anonymement si vous le souhaitez.</p>
                <a href="{{ url('/dons') }}" class="self-start inline-flex items-center justify-center min-h-[52px] px-6 rounded-ezer bg-white text-ezer-dark font-semibold hover:bg-gray-100 transition-colors">Faire un don</a>
            </section>
        </div>
        @endif
</x-layouts.public>
