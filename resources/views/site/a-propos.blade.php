<x-layouts.public title="À propos d'Ezer Home" active="a-propos">

        <section class="bg-ezer-green-light">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-12 flex flex-col gap-3">
                <nav aria-label="Fil d'Ariane" class="text-sm">
                    <ol class="flex flex-wrap items-center gap-2 text-ezer-muted">
                        <li><a href="{{ url('/') }}" class="text-ezer-green hover:underline">Accueil</a></li>
                        <li aria-hidden="true">/</li>
                        <li aria-current="page">À propos</li>
                    </ol>
                </nav>
                <h1 class="font-cooper font-normal text-3xl lg:text-5xl">À propos d'Ezer Home</h1>
                <p class="text-base max-w-[640px]">Une association togolaise qui accompagne, assiste et forme les personnes qui traversent une période difficile.</p>
            </div>
        </section>

        <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-14 flex flex-col gap-14 lg:gap-16">

            <!-- MISSION ET VALEURS -->
            <section class="flex flex-col lg:flex-row items-start gap-8 lg:gap-12">
                <div class="flex-1 basis-[420px] min-w-0 flex flex-col gap-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-ezer-green">Notre mission</p>
                    <h2 class="font-cooper font-normal text-2xl lg:text-4xl">Personne ne devrait traverser seul une période difficile</h2>
                    <p>Dans un contexte où de nombreuses personnes traversent des épreuves morales, spirituelles, familiales, professionnelles ou matérielles sans trouver de lieu où être écoutées et accompagnées, des hommes et des femmes animés par les valeurs bibliques d'amour du prochain, d'accueil et de compassion ont résolu d'unir leurs efforts.</p>
                    <p>Le nom EZER, terme hébreu qui désigne le secours apporté à celui qui ne peut s'en sortir seul, ou aide, exprime leur vocation : offrir un lieu d'écoute, de relation d'aide, d'assistance et de formation, où chacun est reçu avec dignité, sans jugement ni discrimination, pour retrouver le repos, la restauration et la force de se relever.</p>
                    <p>L'association a pour but d'offrir à toute personne en quête de sens, de repères ou de soutien un espace d'écoute et de relation d'aide fondé sur les valeurs bibliques, ainsi qu'une assistance et des formations, en vue de sa restauration et de son relèvement, sans distinction d'origine, de sexes, de religion ou de condition sociale.</p>
                    <p>Ezer Home réunit cinq services : une écoute gratuite et confidentielle, une assistance matérielle sans compte, des formations, un coaching personnalisé, et la collecte de dons qui rend tout cela possible.</p>
                </div>
                <ul class="flex-1 basis-[300px] min-w-0 flex flex-col gap-3 list-none">
                    <li class="bg-ezer-green-light rounded-ezer p-5 flex flex-col gap-1">
                        <span class="font-semibold">Bienveillance</span>
                        <span class="text-[15px] text-ezer-muted">Accueillir chacun avec respect, sans jugement.</span>
                    </li>
                    <li class="bg-ezer-green-light rounded-ezer p-5 flex flex-col gap-1">
                        <span class="font-semibold">Confidentialité</span>
                        <span class="text-[15px] text-ezer-muted">Protéger ce qui nous est confié.</span>
                    </li>
                    <li class="bg-ezer-green-light rounded-ezer p-5 flex flex-col gap-1">
                        <span class="font-semibold">Dignité</span>
                        <span class="text-[15px] text-ezer-muted">Aider sans jamais rabaisser.</span>
                    </li>
                </ul>
            </section>


            <!-- NOTRE APPROCHE -->
            <section id="notre-approche" class="flex flex-col gap-8">
                <div class="flex flex-col gap-3">
                    <p class="text-xs font-semibold uppercase tracking-wider text-ezer-green">Notre approche</p>
                    <h2 class="font-cooper font-normal text-2xl lg:text-4xl text-ezer-dark">Une relation d'aide enracinée dans l'Écriture</h2>
                    <p class="text-base lg:text-lg text-ezer-muted max-w-[880px]">Quatre attitudes guident chaque accompagnant d'EZER HOME. Chacune s'appuie sur un texte biblique et se traduit par une pratique concrète.</p>
                    <p class="text-base lg:text-lg text-ezer-dark font-semibold"><strong>Notre conviction : comme Élie dans 1 Rois 19:1-18, nous croyons que Dieu vous accorde d'abord le repos, puis la restauration, et enfin le relèvement.</strong></p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <article class="rounded-ezer border border-ezer-green-border bg-white p-6 lg:p-8 flex flex-col gap-3 shadow-sm hover:border-ezer-green transition-colors">
                        <span class="w-10 h-10 rounded-full bg-ezer-green-light text-ezer-green inline-flex items-center justify-center font-bold text-lg">1</span>
                        <h3 class="text-xl lg:text-2xl font-semibold text-ezer-dark">Écouter avant de parler</h3>
                        <p class="text-ezer-muted">« Que tout homme soit prompt à écouter, lent à parler, lent à se mettre en colère. » — <strong>JACQUES 1:19</strong></p>
                        <p class="text-ezer-dark italic">Écoute active, reformulation, silences respectés. La personne termine son histoire avant que nous ne disions quoi que ce soit.</p>
                    </article>
                    <article class="rounded-ezer border border-ezer-green-border bg-white p-6 lg:p-8 flex flex-col gap-3 shadow-sm hover:border-ezer-green transition-colors">
                        <span class="w-10 h-10 rounded-full bg-ezer-green-light text-ezer-green inline-flex items-center justify-center font-bold text-lg">2</span>
                        <h3 class="text-xl lg:text-2xl font-semibold text-ezer-dark">Accueillir sans juger</h3>
                        <p class="text-ezer-muted">« Accueillez-vous donc les uns les autres, comme Christ vous a accueillis, pour la gloire de Dieu. » — <strong>ROMAINS 15:7</strong></p>
                        <p class="text-ezer-dark italic">Chacun est reçu tel qu'il est. Le changement vient après l'accueil, jamais comme condition pour être accueilli.</p>
                    </article>
                    <article class="rounded-ezer border border-ezer-green-border bg-white p-6 lg:p-8 flex flex-col gap-3 shadow-sm hover:border-ezer-green transition-colors">
                        <span class="w-10 h-10 rounded-full bg-ezer-green-light text-ezer-green inline-flex items-center justify-center font-bold text-lg">3</span>
                        <h3 class="text-xl lg:text-2xl font-semibold text-ezer-dark">Porter ensemble</h3>
                        <p class="text-ezer-muted">« Portez les fardeaux les uns des autres, et vous accomplirez ainsi la loi de Christ. » — <strong>GALATES 6:2</strong></p>
                        <p class="text-ezer-dark italic">Nous ne prenons pas la place de la personne. Nous marchons avec elle, à son rythme, et elle reste maîtresse de ses choix.</p>
                    </article>
                    <article class="rounded-ezer border border-ezer-green-border bg-white p-6 lg:p-8 flex flex-col gap-3 shadow-sm hover:border-ezer-green transition-colors">
                        <span class="w-10 h-10 rounded-full bg-ezer-green-light text-ezer-green inline-flex items-center justify-center font-bold text-lg">4</span>
                        <h3 class="text-xl lg:text-2xl font-semibold text-ezer-dark">Relever avec douceur</h3>
                        <p class="text-ezer-muted">« Il ne brisera pas le roseau cassé, et il n'éteindra pas la mèche qui brûle encore ; il annoncera la justice selon la vérité. » — <strong>ÉSAÏE 42:3</strong></p>
                        <p class="text-ezer-dark italic">Ne rien forcer de ce qui est fragile. Nous protégeons la petite flamme qui reste, pour qu'elle reprenne.</p>
                    </article>
                </div>
            </section>

            <!-- LE PARCOURS D'ACCOMPAGNEMENT -->
            <section id="le-parcours" class="bg-ezer-green-light rounded-ezer p-6 lg:p-10 flex flex-col gap-8">
                <div class="flex flex-col gap-3">
                    <p class="text-xs font-semibold uppercase tracking-wider text-ezer-green">Le parcours d'accompagnement</p>
                    <h2 class="font-cooper font-normal text-2xl lg:text-4xl text-ezer-dark">Sur le chemin d'Emmaüs</h2>
                    <p class="text-base lg:text-lg text-ezer-muted max-w-[880px]">Deux disciples abattus marchent vers Emmaüs. Jésus les rejoint, les écoute, éclaire leur histoire, puis les laisse repartir relevés (Luc 24:13-35). Notre accompagnement suit ce même chemin.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                    <article class="rounded-ezer border border-ezer-green-border bg-white p-6 flex flex-col gap-4 shadow-sm">
                        <span class="w-10 h-10 rounded-full bg-ezer-green text-white inline-flex items-center justify-center font-bold">1</span>
                        <h3 class="text-lg lg:text-xl font-semibold text-ezer-dark">S'approcher</h3>
                        <p class="text-ezer-muted text-sm">« Jésus s'approcha, et fit route avec eux. » — <strong>Luc 24:15</strong></p>
                        <p class="text-ezer-dark italic text-sm">Vous nous écrivez ou passez nous voir. Nous convenons d'un premier rendez-vous, sur place ou en ligne.</p>
                    </article>
                    <article class="rounded-ezer border border-ezer-green-border bg-white p-6 flex flex-col gap-4 shadow-sm">
                        <span class="w-10 h-10 rounded-full bg-ezer-green text-white inline-flex items-center justify-center font-bold">2</span>
                        <h3 class="text-lg lg:text-xl font-semibold text-ezer-dark">Écouter l'histoire</h3>
                        <p class="text-ezer-muted text-sm">« De quoi vous entretenez-vous en marchant ? » — <strong>Luc 24:17</strong></p>
                        <p class="text-ezer-dark italic text-sm">Entretien gratuit. Vous racontez, nous écoutons. Rien n'est décidé à votre place.</p>
                    </article>
                    <article class="rounded-ezer border border-ezer-green-border bg-white p-6 flex flex-col gap-4 shadow-sm">
                        <span class="w-10 h-10 rounded-full bg-ezer-green text-white inline-flex items-center justify-center font-bold">3</span>
                        <h3 class="text-lg lg:text-xl font-semibold text-ezer-dark">Éclairer</h3>
                        <p class="text-ezer-muted text-sm">« Il leur expliqua dans toutes les Écritures. » — <strong>Luc 24:27</strong></p>
                        <p class="text-ezer-dark italic text-sm">Séances d'accompagnement. La Parole et la prière y ont leur place, si vous le souhaitez.</p>
                    </article>
                    <article class="rounded-ezer border border-ezer-green-border bg-white p-6 flex flex-col gap-4 shadow-sm">
                        <span class="w-10 h-10 rounded-full bg-ezer-green text-white inline-flex items-center justify-center font-bold">4</span>
                        <h3 class="text-lg lg:text-xl font-semibold text-ezer-dark">Repartir</h3>
                        <p class="text-ezer-muted text-sm">« Se levant à l'heure même, ils retournèrent. » — <strong>Luc 24:33</strong></p>
                        <p class="text-ezer-dark italic text-sm">Vous repartez avec un chemin plus clair. La porte d'EZER HOME reste ouverte.</p>
                    </article>
                </div>
            </section>

            <!-- NOS STATUTS -->
            <section aria-labelledby="ap-statuts" class="border border-ezer-green-border rounded-ezer p-5 lg:p-8 flex flex-col gap-5">
                <h2 id="ap-statuts" class="font-cooper font-normal text-2xl lg:text-3xl">Nos statuts</h2>
                <dl class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="flex flex-col gap-0.5"><dt class="text-sm text-ezer-muted">Forme juridique</dt><dd class="font-semibold">Association à but non lucratif</dd></div>
                    <div class="flex flex-col gap-0.5"><dt class="text-sm text-ezer-muted">Récépissé de déclaration</dt><dd class="font-semibold">N° [NUMÉRO] du [date]</dd></div>
                    <div class="flex flex-col gap-0.5"><dt class="text-sm text-ezer-muted">Date de création</dt><dd class="font-semibold">[date]</dd></div>
                    <div class="flex flex-col gap-0.5"><dt class="text-sm text-ezer-muted">Siège social</dt><dd class="font-semibold">[Quartier, rue], Lomé, Togo</dd></div>
                    <div class="flex flex-col gap-0.5"><dt class="text-sm text-ezer-muted">Objet</dt><dd class="font-semibold">Accompagnement, assistance et formation</dd></div>
                    <div class="flex flex-col gap-0.5"><dt class="text-sm text-ezer-muted">Président(e)</dt><dd class="font-semibold">[NOM Prénoms]</dd></div>
                </dl>
                <div class="border-t border-ezer-green-borderLight pt-5 flex flex-wrap gap-3">
                    <a href="#" class="inline-flex items-center gap-2 min-h-[52px] px-6 rounded-ezer bg-ezer-green text-white font-semibold hover:bg-ezer-green-dark transition-colors">
                        <svg aria-hidden="true" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H6a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8z"></path><path d="M14 3v5h5"></path></svg>
                        Lire les statuts (PDF, [poids])
                    </a>
                    <a href="#" class="inline-flex items-center gap-2 min-h-[52px] px-6 rounded-ezer border-2 border-ezer-green text-ezer-green font-semibold hover:bg-ezer-green-light transition-colors">
                        <svg aria-hidden="true" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H6a1 1 0 0 0-1 1v16a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V8z"></path><path d="M14 3v5h5"></path></svg>
                        Récépissé de déclaration (PDF)
                    </a>
                </div>
            </section>

            <!-- LE BUREAU -->
            <section aria-labelledby="ap-bureau" class="flex flex-col gap-5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h2 id="ap-bureau" class="font-cooper font-normal text-2xl lg:text-3xl">Le bureau</h2>
                    <a href="{{ url('/equipe') }}" class="font-semibold text-ezer-green hover:underline">Voir toute l'équipe</a>
                </div>
                <ul class="grid grid-cols-2 xl:grid-cols-4 gap-4 list-none">
                    <li class="flex flex-col gap-3 bg-ezer-green-light rounded-ezer p-5">
                        <span aria-hidden="true" class="w-14 h-14 rounded-ezer bg-ezer-green-light text-ezer-green inline-flex items-center justify-center">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>
                        </span>
                        <span class="font-semibold">[NOM Prénoms]</span>
                        <span class="text-[15px] text-ezer-muted">Président(e)</span>
                    </li>
                    <li class="flex flex-col gap-3 bg-ezer-green-light rounded-ezer p-5">
                        <span aria-hidden="true" class="w-14 h-14 rounded-ezer bg-ezer-green-light text-ezer-green inline-flex items-center justify-center">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>
                        </span>
                        <span class="font-semibold">[NOM Prénoms]</span>
                        <span class="text-[15px] text-ezer-muted">Vice-président(e)</span>
                    </li>
                    <li class="flex flex-col gap-3 bg-ezer-green-light rounded-ezer p-5">
                        <span aria-hidden="true" class="w-14 h-14 rounded-ezer bg-ezer-green-light text-ezer-green inline-flex items-center justify-center">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>
                        </span>
                        <span class="font-semibold">[NOM Prénoms]</span>
                        <span class="text-[15px] text-ezer-muted">Secrétaire général(e)</span>
                    </li>
                    <li class="flex flex-col gap-3 bg-ezer-green-light rounded-ezer p-5">
                        <span aria-hidden="true" class="w-14 h-14 rounded-ezer bg-ezer-green-light text-ezer-green inline-flex items-center justify-center">
                            <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>
                        </span>
                        <span class="font-semibold">[NOM Prénoms]</span>
                        <span class="text-[15px] text-ezer-muted">Trésorier(ère)</span>
                    </li>
                </ul>
            </section>

            <!-- CHARTE -->
            <section aria-labelledby="ap-charte" class="bg-ezer-green-light rounded-ezer p-5 lg:p-8 flex flex-col gap-4">
                <h2 id="ap-charte" class="font-cooper font-normal text-2xl lg:text-3xl">Notre charte d'écoute</h2>
                <p>Toute personne accompagnée la signe, et toute l'équipe s'engage à la respecter. Elle est publique : chacun peut la lire.</p>
                <a href="{{ route('charte') }}" class="self-start inline-flex items-center justify-center min-h-[52px] px-6 rounded-ezer border-2 border-ezer-green bg-white text-ezer-green font-semibold hover:bg-ezer-green-pale transition-colors">Lire la charte complète</a>
                <ul class="flex flex-col gap-3 list-none">
                    <li class="bg-white rounded-ezer p-4 flex items-start gap-2.5">
                        <svg aria-hidden="true" class="shrink-0 mt-0.5 w-[18px] h-[18px] text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>
                        <span>Ce qui est partagé reste confidentiel, sauf danger grave pour la personne ou pour autrui.</span>
                    </li>
                    <li class="bg-white rounded-ezer p-4 flex items-start gap-2.5">
                        <svg aria-hidden="true" class="shrink-0 mt-0.5 w-[18px] h-[18px] text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>
                        <span>L'écoute se fait sans jugement, et sans décider à la place de la personne.</span>
                    </li>
                    <li class="bg-white rounded-ezer p-4 flex items-start gap-2.5">
                        <svg aria-hidden="true" class="shrink-0 mt-0.5 w-[18px] h-[18px] text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>
                        <span>Les échanges se font dans le respect mutuel, aux horaires convenus.</span>
                    </li>
                    <li class="bg-white rounded-ezer p-4 flex items-start gap-2.5">
                        <svg aria-hidden="true" class="shrink-0 mt-0.5 w-[18px] h-[18px] text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"></path></svg>
                        <span>L'écoute ne remplace ni un suivi médical, ni un service d'urgence.</span>
                    </li>
                </ul>
            </section>

            <!-- POUR ALLER PLUS LOIN -->
            <section aria-labelledby="ap-plus" class="flex flex-col gap-5">
                <h2 id="ap-plus" class="font-cooper font-normal text-2xl lg:text-3xl">Pour aller plus loin</h2>
                <ul class="grid grid-cols-1 md:grid-cols-3 gap-4 list-none">
                    <li>
                        <a href="{{ url('/partenaires') }}" class="flex flex-col gap-1 h-full p-6 border border-ezer-green-border rounded-ezer bg-white hover:border-ezer-green transition-colors">
                            <span class="font-semibold text-lg">Nos partenaires</span>
                            <span class="text-[15px] text-ezer-muted">Ceux qui nous soutiennent.</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/a-quoi-servent-les-dons') }}" class="flex flex-col gap-1 h-full p-6 border border-ezer-green-border rounded-ezer bg-white hover:border-ezer-green transition-colors">
                            <span class="font-semibold text-lg">À quoi servent les dons</span>
                            <span class="text-[15px] text-ezer-muted">Comment nous utilisons chaque franc reçu.</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/contact') }}" class="flex flex-col gap-1 h-full p-6 border border-ezer-green-border rounded-ezer bg-white hover:border-ezer-green transition-colors">
                            <span class="font-semibold text-lg">Nous contacter</span>
                            <span class="text-[15px] text-ezer-muted">Une question, un projet de partenariat ?</span>
                        </a>
                    </li>
                </ul>
            </section>
        </div>
</x-layouts.public>
