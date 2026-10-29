<x-layouts.public title="Instructions de versement — Ezer Home" active="">

        <section class="bg-ezer-green-light">
            <div class="max-w-[1400px] mx-auto px-6 lg:px-12 py-8 lg:py-12 flex flex-col gap-3">
                <h1 class="font-cooper font-normal text-3xl lg:text-5xl">Merci pour votre don</h1>
                <p class="text-[17px] max-w-[680px]">Il reste une étape : effectuer le versement. Votre don sera confirmé par le bureau dès réception.</p>
            </div>
        </section>

        <div class="max-w-[680px] mx-auto px-6 lg:px-12 py-8 lg:py-14 flex flex-col gap-6">

            <div class="flex flex-col gap-3 p-5 lg:p-6 rounded-ezer border border-ezer-green-border">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <span class="text-ezer-muted">Montant</span>
                    <span class="text-2xl font-semibold">{{ number_format($donation->amount, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-2 border-t border-ezer-green-borderLight pt-3">
                    <span class="text-ezer-muted">Moyen de paiement</span>
                    <span class="font-semibold">{{ $donation->methodLabel() }}</span>
                </div>
                @if ($initiation->merchantNumber)
                    <div class="flex flex-wrap items-center justify-between gap-2 border-t border-ezer-green-borderLight pt-3">
                        <span class="text-ezer-muted">Numéro marchand</span>
                        <span class="font-semibold">{{ $initiation->merchantNumber }}</span>
                    </div>
                @endif
                <div class="flex flex-wrap items-center justify-between gap-2 border-t border-ezer-green-borderLight pt-3">
                    <span class="text-ezer-muted">Référence à indiquer</span>
                    <span class="font-semibold text-ezer-green">{{ $initiation->reference }}</span>
                </div>
            </div>

            <ol class="flex flex-col gap-2 list-decimal pl-5">
                @foreach ($initiation->instructions as $etape)
                    <li class="text-[15px]">{{ $etape }}</li>
                @endforeach
            </ol>

            @if ($donation->recurring)
                <p class="p-3.5 px-4 rounded-ezer bg-ezer-green-light text-[15px]">Vous avez choisi un don régulier : vous recevrez un rappel chaque mois. Aucun prélèvement automatique n'est effectué.</p>
            @endif

            <a href="{{ url('/') }}" class="self-start inline-flex items-center justify-center min-h-[52px] px-6 rounded-ezer bg-ezer-green text-white font-semibold hover:bg-ezer-green-dark transition-colors">Revenir à l'accueil</a>
        </div>
</x-layouts.public>
