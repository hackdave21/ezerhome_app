<x-layouts.guest title="Vérification en deux étapes — Ezer Home">@php $secours = request()->boolean('secours'); @endphp


                <span aria-hidden="true" class="w-16 h-16 rounded-full bg-ezer-green-light text-ezer-green inline-flex items-center justify-center">
                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 3v5c0 4.5-3 8.3-7 10-4-1.7-7-5.5-7-10V6z"></path><path d="M9 12l2 2 4-4"></path></svg>
                </span>

                <div class="flex flex-col gap-2">
                    <h1 class="font-cooper font-normal text-3xl">Vérification en deux étapes</h1>
                    <p>Ouvrez votre application d'authentification et saisissez le code à 6 chiffres affiché pour Ezer Home.</p>
                </div>

                <form method="POST" action="{{ route('two-factor.login.store') }}" novalidate aria-label="Code de double authentification" class="flex flex-col gap-4">
                    @csrf
                    <div class="flex flex-col gap-2">
                        <label for="tfa" class="text-[15px] font-semibold">{{ $secours ? 'Code de secours' : 'Code à 6 chiffres' }}</label>
                        @if ($secours)
                        <input id="tfa" name="recovery_code" type="text" autocomplete="one-time-code" autofocus placeholder="––––-––––" class="w-full h-[68px] px-4 text-center text-2xl font-semibold tracking-widest border rounded-ezer bg-white focus:border-ezer-green focus:outline-none {{ $errors->any() ? 'border-2 border-ezer-red' : 'border-ezer-borderMuted' }}">
                        <span class="text-sm text-ezer-muted">Chaque code de secours ne sert qu'une fois.</span>
                        @else
                        <input id="tfa" name="code" type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="6" autofocus placeholder="––––––" class="w-full h-[68px] px-4 text-center text-3xl font-semibold tracking-[0.5em] border rounded-ezer bg-white focus:border-ezer-green focus:outline-none {{ $errors->any() ? 'border-2 border-ezer-red' : 'border-ezer-borderMuted' }}">
                        <span class="text-sm text-ezer-muted">Le code change toutes les 30 secondes.</span>
                        @endif
                        @if ($errors->any())<p role="alert" class="text-sm font-semibold text-ezer-red">Ce code ne correspond pas. Vérifiez-le et réessayez.</p>@endif
                    </div>
                    <label class="flex items-start gap-3 text-[15px] cursor-pointer">
                        <input type="checkbox" name="trust_device" value="1" class="w-6 h-6 shrink-0 accent-ezer-green">
                        Ne plus me le demander sur cet appareil pendant 30 jours
                    </label>
                    <button type="submit" class="w-full min-h-[56px] rounded-ezer border-0 bg-ezer-green text-white text-[17px] font-semibold hover:bg-ezer-green-dark transition-colors">Valider</button>
                </form>

                <div class="flex flex-col pt-4 border-t border-ezer-green-borderLight">
                    <a href="{{ route('two-factor.login', $secours ? [] : ['secours' => 1]) }}" class="flex items-center min-h-[44px] font-semibold text-ezer-green hover:underline">{{ $secours ? 'Utiliser le code de mon application' : 'Utiliser un code de secours' }}</a>
                    <a href="{{ url('/contact') }}" class="flex items-center min-h-[44px] font-semibold text-ezer-green hover:underline">Je n'ai plus accès à mon application</a>
                </div>
</x-layouts.guest>
