<x-layouts.guest title="Connexion — Ezer Home">@php
    $msg = (string) $errors->first('identifier');
    $erreur = $errors->has('identifier') || $errors->has('password');
    $custom = \App\Support\LoginGate::isCustomMessage($msg);
@endphp


                <!-- SÉLECTEUR D'ÉTATS -->

                <div class="flex flex-col gap-2">
                    <h1 class="font-cooper font-normal text-3xl">Se connecter</h1>
                    <p class="text-ezer-muted">Heureux de vous revoir.</p>
                </div>

                <!-- ALERTE -->
                @if ($erreur)
<div id="cx-alerte" role="alert" class="flex items-start gap-2.5 border-2 border-ezer-dark rounded-ezer p-3.5 px-4 text-[15px]">
                    <svg aria-hidden="true" class="shrink-0 mt-0.5 w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v6M12 16.5v.5"></path></svg>
                    <span>@if ($custom)<strong class="font-semibold">{{ $msg }}</strong>@else<strong class="font-semibold">Identifiant ou mot de passe incorrect.</strong> Vérifiez votre saisie, ou recevez un code de connexion.@endif</span>
                </div>
@endif

                <form id="cx-form" method="POST" action="{{ route('login.store') }}" novalidate aria-label="Connexion" class="flex flex-col gap-5">
                    @csrf
                    <div class="flex flex-col gap-1.5">
                        <label for="cx-id" class="text-[15px] font-semibold">Téléphone ou e-mail</label>
                        <input id="cx-id" name="identifier" value="{{ old('identifier') }}" type="text" autocomplete="username" @if ($erreur) aria-invalid="true" @endif class="w-full h-[52px] px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none {{ $erreur ? 'border-2 border-ezer-red' : '' }}">
                    </div>

                    <div class="flex flex-col gap-1.5">
                        <div class="flex flex-wrap items-baseline justify-between gap-1">
                            <label for="cx-mdp" class="text-[15px] font-semibold">Mot de passe</label>
                            <a href="{{ route('password.request') }}" class="text-sm font-semibold py-1 text-ezer-green hover:underline">Mot de passe oublié ?</a>
                        </div>
                        <div id="cx-mdp-bloc" class="flex items-center border border-ezer-borderMuted rounded-ezer bg-white pr-1 {{ $erreur ? 'border-2 border-ezer-red' : '' }}">
                            <input id="cx-mdp" name="password" type="password" autocomplete="current-password" class="flex-1 min-w-0 h-[52px] px-4 border-0 rounded-ezer bg-white focus:outline-none">
                            <button type="button" id="cx-toggle" aria-pressed="false" class="shrink-0 min-h-[44px] px-3 border-0 bg-transparent text-ezer-green font-semibold text-sm">Afficher</button>
                        </div>
                    </div>

                    <button type="submit" class="w-full min-h-[56px] rounded-ezer border-0 bg-ezer-green text-white text-[17px] font-semibold hover:bg-ezer-green-dark transition-colors">Se connecter</button>
                </form>

                <div aria-hidden="true" class="flex items-center gap-3 text-sm text-ezer-muted">
                    <span class="flex-1 h-px bg-ezer-green-border"></span>ou<span class="flex-1 h-px bg-ezer-green-border"></span>
                </div>

                <div class="flex flex-col gap-2">
                    <button type="submit" form="cx-form" formaction="{{ route('login.code') }}" formnovalidate class="flex items-center justify-center gap-2 min-h-[56px] rounded-ezer border-2 border-ezer-green bg-white text-ezer-green text-[17px] font-semibold hover:bg-ezer-green-light transition-colors cursor-pointer">Recevoir un code de connexion</button>
                    <p class="-mt-2 text-center text-sm text-ezer-muted">Sans mot de passe : par SMS ou par e-mail.</p>
                </div>

                <p class="pt-5 border-t border-ezer-green-borderLight text-center">Pas encore de compte ? <a href="{{ route('register') }}" class="font-semibold text-ezer-green hover:underline">Créer un compte</a></p>

@push('scripts')
<script>
(function () {
    var champ = document.getElementById('cx-mdp');
    var btn = document.getElementById('cx-toggle');
    btn.addEventListener('click', function () {
        var visible = champ.type === 'text';
        champ.type = visible ? 'password' : 'text';
        btn.textContent = visible ? 'Afficher' : 'Masquer';
        btn.setAttribute('aria-pressed', visible ? 'false' : 'true');
    });
})();
</script>
@endpush
</x-layouts.guest>
