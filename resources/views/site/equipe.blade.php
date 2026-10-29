<x-layouts.public title="Notre équipe — Ezer Home" active="a-propos">

        <section class="bg-ezer-green-light">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-12 flex flex-col gap-3">
                <nav aria-label="Fil d'Ariane" class="text-sm">
                    <ol class="flex flex-wrap items-center gap-2 text-ezer-muted">
                        <li><a href="{{ url('/') }}" class="text-ezer-green hover:underline">Accueil</a></li>
                        <li aria-hidden="true">/</li>
                        <li><a href="{{ url('/a-propos') }}" class="text-ezer-green hover:underline">À propos</a></li>
                        <li aria-hidden="true">/</li>
                        <li aria-current="page">Notre équipe</li>
                    </ol>
                </nav>
                <h1 class="font-cooper font-normal text-3xl lg:text-5xl">Notre équipe</h1>
                <p class="text-base max-w-[640px]">Les personnes qui font vivre Ezer Home, organisées par instance.</p>
            </div>
        </section>

        <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-14 flex flex-col gap-14 lg:gap-16">

            @php
                $instances = collect(\App\Models\OrgMember::COMMITTEES)
                    ->mapWithKeys(fn ($label, $cle) => [$cle => ($groupes[$cle] ?? collect())])
                    ->filter(fn ($membres) => $membres->isNotEmpty());
            @endphp

            @if ($instances->count() > 1)
                <nav aria-label="Instances" class="flex flex-wrap gap-2">
                    @foreach ($instances as $cle => $membres)
                        <a href="#{{ $cle }}" class="min-h-[44px] inline-flex items-center px-4 rounded-ezer border border-ezer-green-border bg-white text-ezer-green font-semibold text-[15px] hover:bg-ezer-green-light transition-colors">{{ \App\Models\OrgMember::COMMITTEES[$cle] }}</a>
                    @endforeach
                </nav>
            @endif

            @forelse ($instances as $cle => $membres)
                <section id="{{ $cle }}" class="flex flex-col gap-5 scroll-mt-36">
                    <h2 class="font-cooper font-normal text-2xl lg:text-3xl">{{ \App\Models\OrgMember::COMMITTEES[$cle] }}</h2>
                    <ul class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 list-none">
                        @foreach ($membres as $membre)
                            <li class="flex flex-col gap-3 p-5 border border-ezer-green-border rounded-ezer bg-white">
                                <span role="img" aria-label="Photo de {{ $membre->fullName() }}" class="w-full h-28 rounded-ezer bg-ezer-green-light border-2 border-ezer-green text-ezer-muted inline-flex items-center justify-center overflow-hidden">
                                    @if ($membre->photo && $membre->photo->isPubliclyServed())
                                        <img src="{{ $membre->photo->url() }}" alt="Photo de {{ $membre->fullName() }}" loading="lazy" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>
                                    @endif
                                </span>
                                <span class="font-semibold uppercase tracking-wide text-[15px]">{{ $membre->fullName() }}</span>
                                <span class="text-ezer-green font-semibold text-sm">{{ $membre->position }}</span>
                                @if ($membre->bio)
                                    <p class="text-[15px] text-ezer-muted">{{ $membre->bio }}</p>
                                @endif
                                @if ($membre->public_contact)
                                    <span class="text-sm text-ezer-muted">{{ $membre->public_contact }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </section>
            @empty
                <p class="text-ezer-muted">L'organigramme sera bientôt en ligne.</p>
            @endforelse


            <!-- CTA -->
            <section class="bg-ezer-green-light rounded-ezer p-5 lg:p-8 flex flex-col gap-3">
                <h2 class="text-xl font-semibold">Vous souhaitez vous engager à nos côtés ?</h2>
                <p>Écrivez-nous en présentant votre parcours et vos disponibilités.</p>
                <a href="{{ url('/contact') }}" class="self-start inline-flex items-center justify-center min-h-[52px] px-6 rounded-ezer bg-ezer-green text-white font-semibold hover:bg-ezer-green-dark transition-colors">Nous contacter</a>
            </section>
        </div>
</x-layouts.public>
