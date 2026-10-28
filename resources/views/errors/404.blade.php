<x-layouts.public title="Page introuvable — Ezer Home" active="">

        <div class="flex flex-col gap-3">
            <p class="text-xs font-semibold uppercase tracking-widest text-ezer-green">Erreur 404</p>
            <h1 class="font-cooper font-normal text-3xl lg:text-5xl">Cette page est introuvable</h1>
            <p class="text-[17px]">Elle a peut-être été déplacée, ou l'adresse contient une erreur. Voici quelques pistes pour retrouver votre chemin.</p>
        </div>

        <a href="{{ url('/') }}" class="self-start inline-flex items-center justify-center min-h-[52px] px-7 rounded-ezer bg-ezer-green text-white font-semibold hover:bg-ezer-green-dark transition-colors">Retour à l'accueil</a>

        <nav aria-label="Pages utiles">
            <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3 list-none">
                <li>
                    <a href="{{ url('/ecoute') }}" class="w-full flex items-center justify-between gap-3 min-h-[56px] p-3 px-4 border border-ezer-green-border rounded-ezer font-semibold hover:border-ezer-green transition-colors">
                        Être écouté(e)
                        <svg aria-hidden="true" class="shrink-0 w-5 h-5 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"></path></svg>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/assistance') }}" class="w-full flex items-center justify-between gap-3 min-h-[56px] p-3 px-4 border border-ezer-green-border rounded-ezer font-semibold hover:border-ezer-green transition-colors">
                        Demander une aide matérielle
                        <svg aria-hidden="true" class="shrink-0 w-5 h-5 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"></path></svg>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/formation') }}" class="w-full flex items-center justify-between gap-3 min-h-[56px] p-3 px-4 border border-ezer-green-border rounded-ezer font-semibold hover:border-ezer-green transition-colors">
                        Voir les formations
                        <svg aria-hidden="true" class="shrink-0 w-5 h-5 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"></path></svg>
                    </a>
                </li>
                <li>
                    <a href="{{ url('/contact') }}" class="w-full flex items-center justify-between gap-3 min-h-[56px] p-3 px-4 border border-ezer-green-border rounded-ezer font-semibold hover:border-ezer-green transition-colors">
                        Nous contacter
                        <svg aria-hidden="true" class="shrink-0 w-5 h-5 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"></path></svg>
                    </a>
                </li>
            </ul>
        </nav>

        <p class="text-[15px] text-ezer-muted">Vous cherchiez autre chose ? <a href="https://wa.me/22800000000" class="font-semibold text-ezer-green hover:underline">Écrivez-nous sur WhatsApp</a>, nous vous orienterons.</p>
</x-layouts.public>
