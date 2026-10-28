<x-layouts.guest title="Saisissez le code reçu — Ezer Home">

                <span aria-hidden="true" class="w-16 h-16 rounded-full bg-ezer-green-light text-ezer-green inline-flex items-center justify-center">
                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="2" width="12" height="20" rx="2"></rect><path d="M10 18h4"></path></svg>
                </span>

                <div class="flex flex-col gap-2">
                    <h1 class="font-cooper font-normal text-3xl">Saisissez le code reçu</h1>
                    <p>Nous venons d'envoyer un code à 6 chiffres @if ($channel === 'email')par e-mail à @else par SMS au @endif<strong class="font-semibold">{{ $masque }}</strong>.</p>
                </div>

                <form method="POST" action="{{ route('otp.verify') }}" novalidate aria-label="Vérification du code" class="flex flex-col gap-4">
                    @csrf
                    <div class="flex flex-col gap-2">
                        <label for="otp" class="text-[15px] font-semibold">Code de vérification</label>
                        <input id="otp" name="code" type="text" autofocus @if ($errors->has('code')) aria-invalid="true" @endif inputmode="numeric" autocomplete="one-time-code" maxlength="6" placeholder="––––––" aria-describedby="otp-err otp-aide" class="w-full h-[68px] px-4 text-center text-3xl font-semibold tracking-[0.5em] border rounded-ezer bg-white focus:border-ezer-green focus:outline-none {{ $errors->has('code') ? 'border-2 border-ezer-red' : 'border-ezer-borderMuted' }}">
                        @if ($errors->has('code'))
                        <p id="otp-err" role="alert" class="flex items-start gap-1.5 text-sm font-semibold text-ezer-red">
                            <svg aria-hidden="true" class="shrink-0 mt-0.5 w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v6M12 16.5v.5"></path></svg>
                            @if ($errors->first(\'code\') === \'invalid\')Ce code ne correspond pas. Vérifiez-le et réessayez : il vous reste {{ $restants }} {{ $restants > 1 ? \'essais\' : \'essai\' }}.@else{{ $errors->first(\'code\') }}@endif
                        </p>
                        @endif
                        <span id="otp-aide" class="text-sm text-ezer-muted">Le code est valable {{ config('otp.ttl') }} minutes.</span>
                    </div>
                    <button type="submit" class="w-full min-h-[56px] rounded-ezer border-0 bg-ezer-green text-white text-[17px] font-semibold hover:bg-ezer-green-dark transition-colors">Valider</button>
                </form>

                <div class="flex flex-col gap-1.5 p-4 rounded-ezer bg-ezer-green-light text-[15px]">
                    <span>Vous n'avez rien reçu ?</span>
                    @if (session('renvoi'))<span role="status" class="font-semibold">{{ session('renvoi') }}</span>@endif
                    <span class="text-ezer-muted">Vous pourrez demander un nouveau code dans <strong class="font-semibold text-ezer-dark" id="chrono">0:{{ config('otp.resend_after') }}</strong>.</span>
                    <span class="flex flex-wrap gap-x-4 gap-y-1">
                        <form method="POST" action="{{ route('otp.resend') }}" class="contents">
                            @csrf
                            <button type="submit" id="renvoyer" disabled class="min-h-[44px] p-0 border-0 bg-transparent text-ezer-muted font-semibold cursor-not-allowed">Renvoyer le code</button>
                        </form>
                        <a href="{{ $back }}" class="inline-flex items-center min-h-[44px] font-semibold text-ezer-green hover:underline">{{ $channel === 'email' ? "Modifier l'adresse" : 'Modifier le numéro' }}</a>
                    </span>
                </div>

@push('scripts')
<script>
(function () {
    var restant = {{ (int) config('otp.resend_after') }};
    var chrono = document.getElementById('chrono');
    var btn = document.getElementById('renvoyer');

    function maj() {
        var m = Math.floor(restant / 60), s = restant % 60;
        chrono.textContent = m + ':' + (s < 10 ? '0' : '') + s;
        if (restant <= 0) {
            btn.disabled = false;
            btn.classList.remove('text-ezer-muted', 'cursor-not-allowed');
            btn.classList.add('text-ezer-green', 'hover:underline', 'cursor-pointer');
            clearInterval(timer);
        }
    }
    var timer = setInterval(function () { restant = Math.max(0, restant - 1); maj(); }, 1000);
    maj();
})();
</script>
@endpush
</x-layouts.guest>
