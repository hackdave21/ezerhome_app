<?php

namespace App\Payments;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use Illuminate\Http\Request;

/**
 * Contrat commun à toutes les passerelles de paiement. Posé dès la phase 1 pour ne pas refondre
 * en phase 2 : seule ManualGateway est active maintenant ; MixxGateway et MoovGateway viendront
 * s'y brancher (étape 2.7) sans changer le reste de l'application.
 */
interface PaymentGateway
{
    public function initiate(Payment $payment): PaymentInitiation;

    public function verify(Payment $payment): PaymentStatus;

    public function handleWebhook(Request $request): void;
}
