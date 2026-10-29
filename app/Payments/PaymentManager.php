<?php

namespace App\Payments;

use InvalidArgumentException;

/**
 * Résout la passerelle à utiliser à partir de son identifiant (« gateway »). En phase 1, seule
 * « manual » est enregistrée ; les passerelles Mixx et Moov s'ajouteront ici en phase 2.
 */
class PaymentManager
{
    /** @var array<string, class-string<PaymentGateway>> */
    private array $gateways = [
        'manual' => ManualGateway::class,
    ];

    public function gateway(string $name): PaymentGateway
    {
        if (! isset($this->gateways[$name])) {
            throw new InvalidArgumentException("Passerelle de paiement « {$name} » indisponible en phase 1.");
        }

        return app($this->gateways[$name]);
    }
}
