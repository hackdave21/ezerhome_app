@props(['sobre' => false])

@php
    $groupes = [
        [
            'titre' => 'Services',
            'liens' => [
                ['label' => 'Écoute', 'url' => url('/ecoute')],
                ['label' => 'Formation', 'url' => url('/formation')],
                ['label' => 'Coaching', 'url' => url('/coaching')],
                ['label' => 'Assistance', 'url' => url('/assistance')],
            ]
        ],
        [
            'titre' => "L'association",
            'liens' => array_values(array_filter([
                ['label' => 'À propos', 'url' => url('/a-propos')],
                ['label' => 'Notre équipe', 'url' => url('/equipe')],
                ['label' => 'Partenaires', 'url' => url('/partenaires')],
                !$sobre ? ['label' => 'Devenir thérapeute ou coach', 'url' => url('/candidature')] : null,
            ]))
        ],
        [
            'titre' => 'Informations',
            'liens' => [
                ['label' => 'Mentions légales', 'url' => url('/mentions-legales')],
                ['label' => 'Confidentialité', 'url' => url('/confidentialite')],
                ['label' => 'CGU', 'url' => url('/cgu')],
                ['label' => 'Contact', 'url' => url('/contact')],
                ['label' => 'Se connecter', 'url' => url('/login')],
            ]
        ]
    ];
@endphp

<footer class="bg-white border-t-4 border-t-ezer-green mt-12">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-10 lg:py-14 flex flex-col gap-8">
        <div class="flex flex-wrap gap-8">
            <!-- Présentation & Coordonnées -->
            <div class="flex-1 min-w-[260px] max-w-[320px] flex flex-col gap-4">
                <a href="{{ url('/') }}" aria-label="Ezer Home" class="self-start">
                    <img src="{{ asset('assets/images/Logo_Ezer-01.png') }}" alt="Ezer Home" class="h-14 w-auto object-contain">
                </a>
                <address class="not-italic flex flex-col gap-1 text-[15px] text-ezer-dark">
                    <span>{{ setting('contact.adresse') }}</span>
                    <a href="tel:{{ preg_replace('/[^+0-9]/', '', setting('contact.telephone')) }}" class="text-ezer-green hover:underline">{{ setting('contact.telephone') }}</a>
                    <a href="mailto:{{ setting('contact.email') }}" class="text-ezer-green hover:underline">{{ setting('contact.email') }}</a>
                </address>
            </div>

            <!-- Colonnes de liens -->
            @foreach($groupes as $groupe)
                <nav aria-label="{{ $groupe['titre'] }}" class="flex-1 min-w-[140px] flex flex-col">
                    <p class="text-xs font-semibold uppercase tracking-wider text-ezer-muted mb-2">{{ $groupe['titre'] }}</p>
                    @foreach($groupe['liens'] as $lien)
                        <a href="{{ $lien['url'] }}" class="text-[15px] text-ezer-dark hover:text-ezer-green py-1.5">{{ $lien['label'] }}</a>
                    @endforeach
                </nav>
            @endforeach
        </div>

        <div class="border-t border-ezer-green-borderLight pt-6 flex flex-wrap items-center justify-between gap-4 text-[13px] text-ezer-muted">
            <p>&copy; {{ date('Y') }} Ezer Home · Accompagnement · Assistance · Formation</p>
        </div>
    </div>
</footer>
