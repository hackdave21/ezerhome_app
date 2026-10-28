<x-layouts.guest title="Mot de passe oublié — Ezer Home">

                <span aria-hidden="true" class="w-16 h-16 rounded-full bg-ezer-green-light text-ezer-green inline-flex items-center justify-center">
                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="15" r="4"></circle><path d="M10.8 12.2 20 3M17 6l3 3M14 9l2 2"></path></svg>
                </span>

                <div class="flex flex-col gap-2">
                    <h1 class="font-cooper font-normal text-3xl">Mot de passe oublié ?</h1>
                    <p>Pas d'inquiétude, cela arrive. Indiquez votre téléphone ou votre e-mail : nous vous enverrons un code pour en choisir un nouveau.</p>
                </div>

                <form method="POST" action="{{ route('password.send') }}" novalidate aria-label="Mot de passe oublié" class="flex flex-col gap-5">
                    @csrf
                    <div class="flex flex-col gap-1.5">
                        <label for="ou-id" class="text-[15px] font-semibold">Téléphone ou e-mail</label>
                        <input id="ou-id" name="identifier" value="{{ old('identifier') }}" type="text" autocomplete="username" class="w-full h-[52px] px-4 border border-ezer-borderMuted rounded-ezer bg-white focus:border-ezer-green focus:outline-none">
                    </div>
                    @error('identifier')<p role="alert" class="text-sm font-semibold text-ezer-red">{{ $message }}</p>@enderror
                    <button type="submit" class="w-full min-h-[56px] rounded-ezer border-0 bg-ezer-green text-white text-[17px] font-semibold hover:bg-ezer-green-dark transition-colors">Recevoir un code</button>
                </form>

                <p class="text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center min-h-[44px] font-semibold text-ezer-green hover:underline">Retour à la connexion</a>
                </p>
</x-layouts.guest>
