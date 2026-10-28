<x-layouts.public title="Nos partenaires — Ezer Home" active="a-propos">

        <section class="bg-ezer-green-light">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-12 flex flex-col gap-3">
                <nav aria-label="Fil d'Ariane" class="text-sm">
                    <ol class="flex flex-wrap items-center gap-2 text-ezer-muted">
                        <li><a href="{{ url('/') }}" class="text-ezer-green hover:underline">Accueil</a></li>
                        <li aria-hidden="true">/</li>
                        <li aria-current="page">Partenaires</li>
                    </ol>
                </nav>
                <h1 class="font-cooper font-normal text-3xl lg:text-5xl">Nos partenaires</h1>
                <p class="text-base max-w-[640px]">Ils soutiennent l'action d'Ezer Home par un don, un savoir-faire ou un projet mené ensemble. Merci à eux.</p>
            </div>
        </section>

        <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-12 flex flex-col gap-12">

            <ul class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 list-none">
                <li class="flex flex-col gap-3 p-5 border border-ezer-green-border rounded-ezer bg-white">
                    <span class="h-20 lg:h-24 rounded-ezer bg-white border border-ezer-green-border inline-flex items-center justify-center p-3">
                        <img src="{{ asset('assets/images/partenaires/logo_MCPRA.png') }}" alt="Logo MCPRA" loading="lazy" class="max-h-full max-w-full object-contain">
                    </span>
                    <span class="font-semibold text-[15px]">MCPRA</span>
                </li>
                <li class="flex flex-col gap-3 p-5 border border-ezer-green-border rounded-ezer bg-white">
                    <span class="h-20 lg:h-24 rounded-ezer bg-white border border-ezer-green-border inline-flex items-center justify-center p-3">
                        <img src="{{ asset('assets/images/partenaires/sss_consulting.png') }}" alt="Logo SSS Consulting and Training" loading="lazy" class="max-h-full max-w-full object-contain">
                    </span>
                    <span class="font-semibold text-[15px]">SSS Consulting and Training</span>
                </li>
                <li class="flex flex-col gap-3 p-5 border border-ezer-green-border rounded-ezer bg-white">
                    <span class="h-20 lg:h-24 rounded-ezer bg-white border border-ezer-green-border inline-flex items-center justify-center p-3">
                        <img src="{{ asset('assets/images/partenaires/ds_consulting.png') }}" alt="Logo DS Consulting" loading="lazy" class="max-h-full max-w-full object-contain">
                    </span>
                    <span class="font-semibold text-[15px]">DS Consulting</span>
                </li>
            </ul>

            <section class="bg-ezer-green-light rounded-ezer p-6 lg:p-10 flex flex-col gap-3">
                <h2 class="font-cooper font-normal text-2xl lg:text-[30px]">Devenir partenaire</h2>
                <p>Entreprise, fondation, institution : parlons de ce que nous pourrions faire ensemble.</p>
                <a href="{{ url('/contact/institutionnel') }}" class="self-start inline-flex items-center justify-center min-h-[52px] px-6 rounded-ezer bg-ezer-green text-white font-semibold hover:bg-ezer-green-dark transition-colors">Nous écrire</a>
            </section>
        </div>
</x-layouts.public>
