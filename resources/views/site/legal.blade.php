<x-layouts.public :title="$page->title.' — Ezer Home'" active="">
    <div id="lg-haut"></div>

    <section class="bg-ezer-green-light">
        <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-12 flex flex-col gap-3.5">
            <nav aria-label="Fil d'Ariane" class="text-sm">
                <ol class="flex flex-wrap items-center gap-2 text-ezer-muted">
                    <li><a href="{{ url('/') }}" class="text-ezer-green hover:underline">Accueil</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a href="{{ route('legal', $slug) }}" class="text-ezer-green hover:underline">{{ $page->title }}</a></li>
                </ol>
            </nav>
            <h1 class="font-cooper font-normal text-3xl lg:text-5xl">{{ $page->title }}</h1>
            <p class="text-sm text-ezer-muted">Dernière mise à jour : {{ $page->updated_at->locale('fr')->translatedFormat('j F Y') }}</p>
            <nav aria-label="Documents" class="flex flex-wrap gap-2 pt-2">
                @foreach ($docs as $s => $label)
                    @php $on = $s === $slug; @endphp
                    <a href="{{ route('legal', $s) }}" @if ($on) aria-current="page" @endif
                       class="inline-flex items-center min-h-[44px] px-4 rounded-ezer font-semibold text-[15px] {{ $on ? 'border-2 border-ezer-green bg-ezer-green text-white' : 'border border-ezer-borderMuted bg-white text-ezer-dark' }}">{{ $label }}</a>
                @endforeach
            </nav>
        </div>
    </section>

    <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-14 flex flex-col lg:flex-row items-start gap-8 lg:gap-16">

        <!-- SOMMAIRE -->
        <nav id="sommaire" aria-label="Sommaire" class="hidden lg:block w-[260px] shrink-0 lg:sticky lg:top-36 border-l-2 border-ezer-green pl-4">
            <p class="text-xs font-semibold uppercase tracking-wider text-ezer-muted mb-2">Sommaire</p>
            <ol class="flex flex-col">
                @foreach ($sommaire as $item)
                    <li><a href="#{{ $item['id'] }}" class="block py-1.5 hover:text-ezer-green hover:underline">{{ $item['titre'] }}</a></li>
                @endforeach
            </ol>
        </nav>

        <details class="lg:hidden w-full border border-ezer-green-border rounded-ezer p-4">
            <summary class="cursor-pointer font-semibold">Sommaire</summary>
            <ol class="mt-3 pl-5 flex flex-col list-decimal">
                @foreach ($sommaire as $item)
                    <li><a href="#{{ $item['id'] }}" class="block py-1.5 hover:text-ezer-green hover:underline">{{ $item['titre'] }}</a></li>
                @endforeach
            </ol>
        </details>

        <!-- ARTICLE -->
        <article class="flex-1 min-w-0 max-w-[720px] flex flex-col gap-10">
            {{-- Contenu HTML saisi par l'Admin (back-office, étape 1.10). --}}
            <div class="flex flex-col gap-10">{!! $page->body !!}</div>
            <div class="border-t border-ezer-green-borderLight pt-6 flex flex-wrap items-center justify-between gap-3">
                <p>Une question ? <a href="{{ url('/contact') }}" class="font-semibold text-ezer-green hover:underline">Contactez-nous</a></p>
                <a href="#lg-haut" class="font-semibold text-ezer-green hover:underline">Retour en haut</a>
            </div>
        </article>
    </div>
</x-layouts.public>
