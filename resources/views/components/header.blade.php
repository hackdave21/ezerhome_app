@props(['active' => '', 'showDon' => true])

@php
    $nav = [
        ['id' => 'accueil', 'label' => 'Accueil', 'url' => url('/')],
        ['id' => 'a-propos', 'label' => 'À propos', 'url' => url('/a-propos')],
        ['id' => 'approche', 'label' => 'Notre approche', 'url' => url('/a-propos#notre-approche')],
        ['id' => 'parcours', 'label' => 'Le Parcours', 'url' => url('/a-propos#le-parcours')],
        ['id' => 'ecoute', 'label' => 'Écoute', 'url' => url('/ecoute'), 'badge' => 'Gratuit'],
        ['id' => 'formation', 'label' => 'Formation', 'url' => url('/formation')],
        ['id' => 'coaching', 'label' => 'Coaching', 'url' => url('/coaching')],
        ['id' => 'assistance', 'label' => 'Assistance', 'url' => url('/assistance'), 'badge' => 'Gratuit'],
        ['id' => 'contact', 'label' => 'Contact', 'url' => url('/contact')],
    ];

    $mobileExtra = [
        ['label' => 'Notre équipe', 'url' => url('/equipe')],
        ['label' => 'Club EZER (Telegram et WhatsApp)', 'url' => url('/#club-ezer')],
        ['label' => "Une parole pour aujourd'hui", 'url' => url('/#parole-du-jour')],
        ['label' => "Charte d'accompagnement", 'url' => url('/#charte-accompagnement')],
    ];
@endphp

<!-- Barre supérieure fixe avec coordonnées & Mon Espace -->
<div class="bg-ezer-dark text-white text-xs lg:text-sm">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-2 flex flex-wrap items-center justify-between gap-2 lg:gap-6">
        <div class="flex flex-wrap items-center gap-4 lg:gap-7">
            <span class="inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s-7-6.2-7-11.5a7 7 0 0 1 14 0C19 14.8 12 21 12 21z"></path><circle cx="12" cy="9.5" r="2.5"></circle></svg>
                {{ setting('contact.adresse') }}
            </span>
            <a href="tel:{{ preg_replace('/[^+0-9]/', '', setting('contact.telephone')) }}" class="hover:underline inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8 9.8a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"></path></svg>
                {{ setting('contact.telephone') }}
            </a>
            <a href="mailto:{{ setting('contact.email') }}" class="hover:underline inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="m3 7 9 6 9-6"></path></svg>
                {{ setting('contact.email') }}
            </a>
        </div>
        @auth
            <a href="{{ url('/espace') }}" class="inline-flex items-center gap-2 font-semibold hover:text-ezer-green-light">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>
                Mon espace ({{ Auth::user()->name }})
            </a>
        @else
            <a href="{{ url('/login') }}" class="inline-flex items-center gap-2 font-semibold hover:text-ezer-green-light">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg>
                Mon espace
            </a>
        @endauth
    </div>
</div>

<!-- Navigation principale -->
<header x-data="{ mobileMenuOpen: false }" class="sticky top-0 z-50 bg-white border-b border-ezer-green-borderLight">
    <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-3 flex items-center justify-between gap-4">
        <!-- Logo -->
        <a href="{{ url('/') }}" aria-label="Ezer Home, retour à l'accueil" class="block shrink-0">
            <img src="{{ asset('assets/images/Logo_Ezer-01.png') }}" alt="Logo Ezer Home" class="h-16 lg:h-20 w-auto object-contain">
        </a>

        <!-- Menu Desktop -->
        <nav aria-label="Navigation principale" class="hidden lg:flex items-center gap-1">
            @foreach($nav as $item)
                @php $isActive = ($active === $item['id']); @endphp
                <a href="{{ $item['url'] }}" 
                   @if($isActive) aria-current="page" @endif
                   class="px-3 py-2 font-semibold text-[15px] transition-colors {{ $isActive ? 'text-ezer-green shadow-ezer-nav' : 'text-ezer-dark hover:text-ezer-green' }}">
                    {{ $item['label'] }}
                    @if(isset($item['badge']))
                        <span class="ml-1 text-[11px] font-semibold px-1.5 py-0.5 rounded-full border border-ezer-green bg-ezer-green-light text-ezer-green">
                            {{ $item['badge'] }}
                        </span>
                    @endif
                </a>
            @endforeach
        </nav>

        <!-- Actions Desktop -->
        <div class="hidden lg:flex items-center gap-3">
            @if($showDon)
                <a href="{{ url('/dons') }}" class="inline-flex items-center justify-center min-h-[44px] px-4 rounded-ezer border-2 border-ezer-green text-ezer-green font-semibold text-[15px] hover:bg-ezer-green hover:text-white transition-colors">
                    Faire un don <span class="text-xs ml-1 font-normal opacity-85">(dès 100 F)</span>
                </a>
            @endif
            <a href="https://wa.me/{{ setting('contact.whatsapp') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 min-h-[48px] px-4 rounded-ezer bg-ezer-green text-white font-semibold text-[15px] hover:bg-ezer-green-dark transition-colors">
                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3.5 20.5l1.3-4A8.5 8.5 0 1 1 8 19.6z"></path><path d="M9 9.5c0 3 2.5 5.5 5.5 5.5l1-1.5-2-1-1 .8a4 4 0 0 1-2-2l.8-1-1-2z"></path></svg>
                WhatsApp
            </a>
        </div>

        <!-- Actions Mobile & Toggle -->
        <div class="flex lg:hidden items-center gap-2">
            <a href="https://wa.me/{{ setting('contact.whatsapp') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 min-h-[40px] px-3 rounded-ezer bg-ezer-green text-white font-semibold text-sm">
                <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3.5 20.5l1.3-4A8.5 8.5 0 1 1 8 19.6z"></path><path d="M9 9.5c0 3 2.5 5.5 5.5 5.5l1-1.5-2-1-1 .8a4 4 0 0 1-2-2l.8-1-1-2z"></path></svg>
                WhatsApp
            </a>
            <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" :aria-expanded="mobileMenuOpen"
                class="w-11 h-11 inline-flex items-center justify-center border border-ezer-borderMuted rounded-ezer text-ezer-dark hover:bg-gray-100"
                aria-label="Ouvrir ou fermer le menu">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"></path></svg>
                <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"></path></svg>
            </button>
        </div>
    </div>

    <!-- Menu Mobile Déroulant -->
    <nav x-show="mobileMenuOpen" x-cloak class="lg:hidden border-t border-ezer-green-borderLight bg-white px-6 py-4 flex flex-col gap-4">
        <ul class="flex flex-col">
            @foreach(array_merge($nav, $mobileExtra) as $mItem)
                @php $isActive = (isset($mItem['id']) && $active === $mItem['id']); @endphp
                <li class="border-b border-ezer-green-borderLight last:border-0">
                    <a href="{{ $mItem['url'] }}" 
                       @if($isActive) aria-current="page" @endif
                       class="flex items-center justify-between gap-3 min-h-[50px] font-semibold text-[16px] {{ $isActive ? 'text-ezer-green' : 'text-ezer-dark' }}">
                        <span class="inline-flex items-center gap-2">
                            {{ $mItem['label'] }}
                            @if(isset($mItem['badge']))
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full border border-ezer-green bg-ezer-green-light text-ezer-green">{{ $mItem['badge'] }}</span>
                            @endif
                        </span>
                        <svg class="w-4 h-4 shrink-0 text-ezer-muted" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 6l6 6-6 6"></path></svg>
                    </a>
                </li>
            @endforeach
        </ul>
        <div class="grid grid-cols-2 gap-3 pt-2">
            @auth
                <a href="{{ url('/espace') }}" class="col-span-2 inline-flex items-center justify-center min-h-[48px] rounded-ezer bg-ezer-green text-white font-semibold text-[15px]">Mon espace personnel</a>
            @else
                <a href="{{ url('/login') }}" class="inline-flex items-center justify-center min-h-[48px] rounded-ezer border-2 border-ezer-green text-ezer-green font-semibold text-[15px]">Se connecter</a>
                <a href="{{ url('/register') }}" class="inline-flex items-center justify-center min-h-[48px] rounded-ezer bg-ezer-green text-white font-semibold text-[15px]">Créer un compte</a>
            @endauth
        </div>
        @if($showDon)
            <a href="{{ url('/dons') }}" class="w-full inline-flex items-center justify-center min-h-[48px] rounded-ezer border border-ezer-borderMuted text-ezer-dark font-semibold text-[15px]">Faire un don (dès 100 FCFA)</a>
        @endif
    </nav>
</header>

