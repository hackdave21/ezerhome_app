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
            'liens' => array_filter([
                ['label' => 'À propos', 'url' => url('/a-propos')],
                ['label' => 'Notre équipe', 'url' => url('/equipe')],
                ['label' => 'Partenaires', 'url' => url('/partenaires')],
                !$sobre ? ['label' => 'Faire un don', 'url' => url('/dons')] : null,
                !$sobre ? ['label' => 'À quoi servent les dons', 'url' => url('/a-quoi-servent-les-dons')] : null,
                !$sobre ? ['label' => 'Devenir thérapeute ou coach', 'url' => url('/candidature')] : null,
            ])
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

<footer class="bg-ezer-green-pale border-t border-ezer-green-borderLight text-ezer-dark mt-auto">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-12 lg:py-16">
        <div class="flex flex-col lg:flex-row gap-10 lg:gap-16 justify-between">
            <!-- Colonne 1 : Présentation & Coordonnées -->
            <div class="max-w-[420px] flex flex-col gap-4">
                <a href="{{ url('/') }}" aria-label="Ezer Home" class="block">
                    <img src="{{ asset('assets/images/Logo_Ezer-01.png') }}" alt="Logo Ezer Home" class="h-16 w-auto object-contain">
                </a>
                <p class="text-sm text-ezer-muted leading-relaxed">
                    Association chrétienne dédiée à l'accueil, l'écoute et l'accompagnement de toute personne en quête de sens, de repos ou de repères, sur place à Lomé ou en ligne.
                </p>
                <div class="text-sm text-ezer-dark flex flex-col gap-1.5 pt-2">
                    <p class="font-semibold text-ezer-dark">Siège social :</p>
                    <p class="text-ezer-muted">{{ setting('contact.adresse') }}, Togo</p>
                    <p class="text-ezer-muted">Tél : <a href="tel:{{ preg_replace('/[^+0-9]/', '', setting('contact.telephone')) }}" class="text-ezer-green hover:underline font-medium">{{ setting('contact.telephone') }}</a></p>
                    <p class="text-ezer-muted">Email : <a href="mailto:{{ setting('contact.email') }}" class="text-ezer-green hover:underline font-medium">{{ setting('contact.email') }}</a></p>
                </div>
            </div>

            <!-- Colonnes de Liens -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-8 flex-1 max-w-[700px]">
                @foreach($groupes as $groupe)
                    <div class="flex flex-col gap-3">
                        <p class="text-xs font-semibold uppercase tracking-wider text-ezer-muted">{{ $groupe['titre'] }}</p>
                        <ul class="flex flex-col gap-2">
                            @foreach($groupe['liens'] as $lien)
                                <li>
                                    <a href="{{ $lien['url'] }}" class="text-[14px] text-ezer-dark hover:text-ezer-green transition-colors">
                                        {{ $lien['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-12 pt-6 border-t border-ezer-green-borderLight flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-ezer-muted">
            <p>&copy; {{ date('Y') }} EZER HOME. Tous droits réservés.</p>
            <p class="italic">« Venez à moi, vous tous qui êtes fatigués et chargés, et je vous donnerai du repos. » (Matthieu 11:28)</p>
        </div>
    </div>
</footer>

