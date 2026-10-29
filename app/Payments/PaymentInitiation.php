<?php

namespace App\Payments;

/**
 * Résultat de l'initiation d'un paiement : ce qu'il faut présenter au donateur. En phase 1
 * (manuel), ce sont les instructions de versement ; en phase 2, ce pourra être une URL de
 * redirection vers l'opérateur.
 */
class PaymentInitiation
{
    /** @param array<int, string> $instructions */
    public function __construct(
        public string $reference,
        public ?string $merchantNumber = null,
        public array $instructions = [],
        public ?string $redirectUrl = null,
    ) {}
}
