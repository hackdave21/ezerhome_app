<x-layouts.guest title="Créer votre compte — Ezer Home">

                <div class="flex flex-col gap-2">
                    <h1 class="font-cooper font-normal text-3xl">Créer votre compte</h1>
                    <p class="text-ezer-muted">Il vous permet de suivre vos demandes, vos formations et votre coaching, en toute confidentialité.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" novalidate aria-label="Création de compte" class="flex flex-col gap-5">
                    @csrf

                    <div class="flex flex-col gap-1.5">
                        <label for="in-nom" class="text-[15px] font-semibold">Votre nom</label>
                        <span id="in-nom-aide" class="text-sm text-ezer-muted">Un prénom ou un pseudonyme suffit.</span>
                        @error('name')<p role="alert" class="text-sm font-semibold text-ezer-red">{{ $message }}</p>@enderror
                        <input id="in-nom" name="name" value="{{ old('name') }}" required type="text" autocomplete="name" aria-describedby="in-nom-aide" class="w-full h-[52px] px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none">
                    </div>

                    <fieldset class="flex flex-col gap-3.5">
                        <legend class="text-[15px] font-semibold mb-1">Comment vous joindre ?</legend>
                        <span id="in-contact-aide" class="text-sm text-ezer-muted">Indiquez au moins l'un des deux. Nous y enverrons un code de vérification.</span>
                        @error('phone')<p role="alert" class="text-sm font-semibold text-ezer-red">{{ $message }}</p>@enderror
                        @error('email')<p role="alert" class="text-sm font-semibold text-ezer-red">{{ $message }}</p>@enderror
                        <div class="flex flex-col gap-1.5">
                            <label for="in-tel" class="text-[15px] font-semibold">Téléphone</label>
                            <div class="flex items-stretch border border-ezer-borderMuted rounded-ezer overflow-hidden bg-white">
                                <span aria-hidden="true" class="inline-flex items-center px-3.5 bg-ezer-green-light font-semibold border-r border-ezer-borderMuted">+228</span>
                                <input id="in-tel" name="phone" value="{{ old('phone') }}" type="tel" inputmode="numeric" autocomplete="tel-national" class="flex-1 min-w-0 h-[52px] px-4 border-0 bg-white focus:outline-none">
                            </div>
                        </div>
                        <div class="flex flex-col gap-1.5">
                            <label for="in-mail" class="text-[15px] font-semibold">E-mail</label>
                            <input id="in-mail" name="email" value="{{ old('email') }}" type="email" autocomplete="email" class="w-full h-[52px] px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none">
                        </div>
                    </fieldset>

                    <fieldset class="flex flex-col gap-2.5">
                        <legend class="text-[15px] font-semibold mb-2">Comment voulez-vous vous connecter ?</legend>
                        <label class="flex items-start gap-3 p-4 border border-ezer-borderMuted rounded-ezer bg-white cursor-pointer has-[:checked]:border-2 has-[:checked]:border-ezer-green has-[:checked]:bg-ezer-green-light">
                            <input type="radio" name="method" value="mdp" @checked(old('method', 'mdp') === 'mdp') class="w-5 h-5 mt-0.5 shrink-0 accent-ezer-green">
                            <span class="flex flex-col gap-0.5">
                                <span class="font-semibold">Avec un mot de passe</span>
                                <span class="text-sm text-ezer-muted">Vous le choisissez maintenant.</span>
                            </span>
                        </label>
                        <label class="flex items-start gap-3 p-4 border border-ezer-borderMuted rounded-ezer bg-white cursor-pointer has-[:checked]:border-2 has-[:checked]:border-ezer-green has-[:checked]:bg-ezer-green-light">
                            <input type="radio" name="method" value="sans" @checked(old('method') === 'sans') class="w-5 h-5 mt-0.5 shrink-0 accent-ezer-green">
                            <span class="flex flex-col gap-0.5">
                                <span class="font-semibold">Sans mot de passe</span>
                                <span class="text-sm text-ezer-muted">À chaque connexion, un code par SMS ou un lien par e-mail.</span>
                            </span>
                        </label>
                    </fieldset>

                    <div id="in-mdp-bloc" @if (old('method') === 'sans') hidden @endif class="flex flex-col gap-1.5">
                        <label for="in-mdp" class="text-[15px] font-semibold">Choisissez un mot de passe</label>
                        <span id="in-mdp-aide" class="text-sm text-ezer-muted">8 caractères minimum, dont au moins un chiffre.</span>
                        @error('password')<p role="alert" class="text-sm font-semibold text-ezer-red">{{ $message }}</p>@enderror
                        <div class="flex items-center border border-ezer-borderMuted rounded-ezer bg-white pr-1">
                            <input id="in-mdp" name="password" type="password" autocomplete="new-password" aria-describedby="in-mdp-aide" class="flex-1 min-w-0 h-[52px] px-4 border-0 rounded-ezer bg-white focus:outline-none">
                            <button type="button" id="in-toggle" aria-pressed="false" class="shrink-0 min-h-[44px] px-3 border-0 bg-transparent text-ezer-green font-semibold text-sm">Afficher</button>
                        </div>
                    </div>

                    <label class="flex items-start gap-3 text-[15px] cursor-pointer">
                        <input type="checkbox" name="terms" value="1" @checked(old('terms')) class="w-6 h-6 shrink-0 accent-ezer-green">
                        J'accepte les conditions d'utilisation et la politique de confidentialité.
                    </label>
                    @error('terms')<p role="alert" class="text-sm font-semibold text-ezer-red">{{ $message }}</p>@enderror
                    <p class="-mt-3 ml-9 text-sm">
                        <a href="{{ url('/cgu') }}" class="text-ezer-green hover:underline">Lire les CGU</a> ·
                        <a href="{{ url('/confidentialite') }}" class="text-ezer-green hover:underline">Lire la politique de confidentialité</a>
                    </p>

                    <button type="submit" class="w-full min-h-[56px] rounded-ezer border-0 bg-ezer-green text-white text-[17px] font-semibold hover:bg-ezer-green-dark transition-colors">Créer mon compte</button>

                    <p class="flex items-start gap-2 text-sm text-ezer-muted">
                        <svg aria-hidden="true" class="shrink-0 mt-0.5 w-4 h-4 text-ezer-green" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2"></rect><path d="M8 11V8a4 4 0 0 1 8 0v3"></path></svg>
                        Vos informations restent confidentielles. Nous ne les partageons jamais.
                    </p>
                </form>

                <p class="pt-5 border-t border-ezer-green-borderLight text-center">Déjà un compte ? <a href="{{ route('login') }}" class="font-semibold text-ezer-green hover:underline">Se connecter</a></p>

@push('scripts')
<script>
(function () {
    var mdp = document.getElementById('in-mdp');
    var toggle = document.getElementById('in-toggle');
    var bloc = document.getElementById('in-mdp-bloc');

    toggle.addEventListener('click', function () {
        var visible = mdp.type === 'text';
        mdp.type = visible ? 'password' : 'text';
        toggle.textContent = visible ? 'Afficher' : 'Masquer';
        toggle.setAttribute('aria-pressed', visible ? 'false' : 'true');
    });

    document.querySelectorAll('[name="method"]').forEach(function (r) {
        r.addEventListener('change', function () { bloc.hidden = r.value !== 'mdp'; });
    });
})();
</script>
@endpush
</x-layouts.guest>
