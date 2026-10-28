<x-layouts.guest title="Vérifiez votre boîte e-mail — Ezer Home">

                <span aria-hidden="true" class="w-16 h-16 rounded-full bg-ezer-green-light text-ezer-green inline-flex items-center justify-center">
                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="m3 7 9 6 9-6"></path></svg>
                </span>

                <div class="flex flex-col gap-2">
                    <h1 class="font-cooper font-normal text-3xl">Vérifiez votre boîte e-mail</h1>
                    <p>Nous avons envoyé un lien de connexion à <strong class="font-semibold">{{ $masque }}</strong>. Ouvrez-le sur cet appareil pour vous connecter.</p>
                </div>

                <ul class="p-4 rounded-ezer bg-ezer-green-light flex flex-col gap-2 text-[15px] list-none">
                    <li>Le lien est valable {{ config('otp.magic_link_ttl') }} minutes et ne fonctionne qu'une fois.</li>
                    <li>Rien reçu ? Regardez dans les courriers indésirables.</li>
                </ul>

                @if (session('renvoi'))<p role="status" class="font-semibold">{{ session('renvoi') }}</p>@endif
                <form method="POST" action="{{ route('login.magic.resend') }}">
                    @csrf
                    <button type="submit" class="w-full min-h-[56px] rounded-ezer border-2 border-ezer-green bg-white text-ezer-green text-[17px] font-semibold hover:bg-ezer-green-light transition-colors cursor-pointer">Renvoyer le lien</button>
                </form>

                <p class="text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center min-h-[44px] font-semibold text-ezer-green hover:underline">Utiliser une autre adresse</a>
                </p>
</x-layouts.guest>
