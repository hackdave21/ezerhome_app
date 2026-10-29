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

            @forelse ($partenaires as $partenaire)
                @if ($loop->first)
                    <ul class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 list-none">
                @endif
                    <li class="flex flex-col gap-3 p-5 border border-ezer-green-border rounded-ezer bg-white">
                        @php $contenu = $partenaire->url; @endphp
                        <span class="h-20 lg:h-24 rounded-ezer bg-white border border-ezer-green-border inline-flex items-center justify-center p-3">
                            @if ($partenaire->logo && $partenaire->logo->isPubliclyServed())
                                <img src="{{ $partenaire->logo->url() }}" alt="Logo {{ $partenaire->name }}" loading="lazy" class="max-h-full max-w-full object-contain">
                            @else
                                <span class="font-semibold text-ezer-green">{{ $partenaire->name }}</span>
                            @endif
                        </span>
                        @if ($contenu)
                            <a href="{{ $contenu }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-[15px] text-ezer-green hover:underline">{{ $partenaire->name }}</a>
                        @else
                            <span class="font-semibold text-[15px]">{{ $partenaire->name }}</span>
                        @endif
                    </li>
                @if ($loop->last)
                    </ul>
                @endif
            @empty
                <p class="text-ezer-muted">La liste de nos partenaires sera bientôt en ligne.</p>
            @endforelse

            <section class="bg-ezer-green-light rounded-ezer p-6 lg:p-10 flex flex-col gap-3">
                <h2 class="font-cooper font-normal text-2xl lg:text-[30px]">Devenir partenaire</h2>
                <p>Entreprise, fondation, institution : parlons de ce que nous pourrions faire ensemble.</p>
                <a href="{{ url('/contact/institutionnel') }}" class="self-start inline-flex items-center justify-center min-h-[52px] px-6 rounded-ezer bg-ezer-green text-white font-semibold hover:bg-ezer-green-dark transition-colors">Nous écrire</a>
            </section>
        </div>
</x-layouts.public>
