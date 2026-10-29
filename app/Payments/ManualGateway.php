<?php

namespace App\Payments;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Http\Request;
use RuntimeException;

/**
 * Passerelle manuelle (active en phase 1). Elle ne dialogue avec aucun opérateur : elle place le
 * paiement « en attente » et fournit les instructions de versement. Le passage à « payé » se fait
 * uniquement par la confirmation Admin tracée (règle d'or).
 */
class ManualGateway implements PaymentGateway
{
    public function initiate(Payment $payment): PaymentInitiation
    {
        if ($payment->status === PaymentStatus::Initie) {
            $payment->update(['status' => PaymentStatus::EnAttente]);
        }

        $method = $payment->donation?->method;
        $merchant = $method ? Setting::get(PaymentMethod::from($method)->merchantSettingKey()) : null;
        $operateur = $method ? PaymentMethod::from($method)->label() : 'l\'opérateur indiqué';

        return new PaymentInitiation(
            reference: $payment->external_ref ?? $payment->uuid,
            merchantNumber: $merchant,
            instructions: array_values(array_filter([
                $merchant ? "Effectuez le versement vers le numéro marchand {$operateur} : {$merchant}." : "Contactez l'association pour obtenir le numéro marchand {$operateur}.",
                'Indiquez la référence ci-dessous lors du versement.',
                'Votre don sera confirmé par le bureau dès réception.',
            ])),
        );
    }

    /** Pas de vérification automatique : le statut courant fait foi (confirmé par l'Admin). */
    public function verify(Payment $payment): PaymentStatus
    {
        return $payment->status;
    }

    public function handleWebhook(Request $request): void
    {
        throw new RuntimeException('La passerelle manuelle ne reçoit pas de webhook.');
    }
}
