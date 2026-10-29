<?php

namespace App\Livewire;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Donation;
use App\Models\Payment;
use App\Services\Donations\DonationWorkflow;
use Livewire\Attributes\Computed;
use Livewire\Component;

/** Dons et paiements côté Admin : filtres, confirmation manuelle tracée, remboursement noté. */
class AdminDonationList extends Component
{
    public string $status = 'toutes';

    public string $method = 'toutes';

    /** Paiement en cours de confirmation et référence saisie. */
    public ?int $confirmingId = null;

    public string $reference = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('donations.view'), 403);
    }

    #[Computed]
    public function donations()
    {
        return Donation::with('payment')
            ->when($this->method !== 'toutes', fn ($q) => $q->where('method', $this->method))
            ->when($this->status !== 'toutes', fn ($q) => $q->whereHas('payment', fn ($p) => $p->where('status', $this->status)))
            ->latest('id')
            ->get();
    }

    #[Computed]
    public function totals(): array
    {
        $paid = Payment::where('status', PaymentStatus::Paye->value);

        return ['confirmed_count' => (clone $paid)->count(), 'confirmed_amount' => (int) (clone $paid)->sum('amount')];
    }

    public function startConfirm(int $paymentId): void
    {
        abort_unless(auth()->user()->can('payments.confirm-manual'), 403);
        $this->confirmingId = $paymentId;
        $this->reference = '';
        $this->resetErrorBag();
    }

    public function cancelConfirm(): void
    {
        $this->confirmingId = null;
        $this->reference = '';
    }

    public function confirm(DonationWorkflow $workflow): void
    {
        abort_unless(auth()->user()->can('payments.confirm-manual'), 403);

        $this->validate(['reference' => ['required', 'string', 'max:120']], [
            'reference.required' => 'La référence de transaction est obligatoire pour confirmer.',
        ]);

        $payment = Payment::findOrFail($this->confirmingId);

        if (! $payment->isPaid()) {
            $workflow->confirmManual($payment, $this->reference, auth()->user());
        }

        $this->cancelConfirm();
        unset($this->donations, $this->totals);
    }

    public function refund(int $paymentId, DonationWorkflow $workflow): void
    {
        abort_unless(auth()->user()->can('payments.confirm-manual'), 403);

        $payment = Payment::findOrFail($paymentId);
        $workflow->refund($payment, auth()->user());

        unset($this->donations, $this->totals);
    }

    #[Computed]
    public function methods(): array
    {
        return PaymentMethod::cases();
    }

    #[Computed]
    public function statuses(): array
    {
        return PaymentStatus::cases();
    }

    public function render()
    {
        return view('livewire.admin-donation-list');
    }
}
