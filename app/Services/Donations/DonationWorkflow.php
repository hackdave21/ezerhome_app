<?php

namespace App\Services\Donations;

use App\Enums\PaymentStatus;
use App\Models\Donation;
use App\Models\Payment;
use App\Models\User;
use App\Payments\PaymentInitiation;
use App\Payments\PaymentManager;
use Illuminate\Support\Facades\DB;

/**
 * Logique métier des dons : création (don + paiement en attente via la passerelle), confirmation
 * manuelle tracée et remboursement. Le passage à « payé » est verrouillé par le modèle Payment.
 */
class DonationWorkflow
{
    public function __construct(private PaymentManager $payments) {}

    /**
     * @param  array{amount: int, method: string, is_anonymous: bool, recurring: bool, donor_name: ?string, donor_contact: ?string}  $data
     * @return array{donation: Donation, payment: Payment, initiation: PaymentInitiation}
     */
    public function create(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $donation = Donation::create([
                'amount' => $data['amount'],
                'currency' => 'XOF',
                'method' => $data['method'],
                'is_anonymous' => $data['is_anonymous'],
                'recurring' => $data['recurring'],
                'donor_name' => $data['is_anonymous'] ? null : ($data['donor_name'] ?: null),
                'donor_contact' => $data['is_anonymous'] ? null : ($data['donor_contact'] ?: null),
            ]);

            $payment = Payment::create([
                'donation_id' => $donation->id,
                'amount' => $donation->amount,
                'currency' => 'XOF',
                'status' => PaymentStatus::Initie,
                'gateway' => 'manual',
            ]);

            // La référence communiquée au donateur dérive de l'UUID du paiement (non sensible).
            $payment->update(['external_ref' => 'DON-'.mb_strtoupper(substr($payment->uuid, 0, 8))]);
            $donation->update(['payment_id' => $payment->id]);

            $initiation = $this->payments->gateway($payment->gateway)->initiate($payment->fresh());

            return ['donation' => $donation->fresh(), 'payment' => $payment->fresh(), 'initiation' => $initiation];
        });
    }

    /** Confirmation manuelle par l'Admin : référence de transaction obligatoire, action tracée. */
    public function confirmManual(Payment $payment, string $reference, User $admin): void
    {
        $payment->update([
            'status' => PaymentStatus::Paye,
            'external_ref' => $reference,
            'confirmed_by' => $admin->id,
            'paid_at' => now(),
        ]);
    }

    public function refund(Payment $payment, User $admin, ?string $reference = null): void
    {
        $payment->update([
            'status' => PaymentStatus::Rembourse,
            'external_ref' => $reference ?: $payment->external_ref,
        ]);

        activity()->performedOn($payment)->causedBy($admin)->event('refunded')->log('Remboursement noté');
    }
}
