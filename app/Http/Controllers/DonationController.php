<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Http\Requests\DonationStoreRequest;
use App\Models\Payment;
use App\Models\Setting;
use App\Payments\PaymentManager;
use App\Services\Donations\DonationWorkflow;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

/** Volet Dons public : formulaire (≤ 6 champs) et instructions de versement (flux manuel V1). */
class DonationController extends Controller
{
    public function create(): View
    {
        return view('site.dons', [
            'methods' => PaymentMethod::cases(),
            'suggested' => $this->suggestedAmounts(),
        ]);
    }

    public function store(DonationStoreRequest $request, DonationWorkflow $workflow): RedirectResponse
    {
        $resultat = $workflow->create([
            'amount' => (int) $request->validated('amount'),
            'method' => $request->validated('method'),
            'is_anonymous' => (bool) $request->validated('is_anonymous'),
            'recurring' => (bool) $request->validated('recurring'),
            'donor_name' => $request->validated('donor_name'),
            'donor_contact' => $request->validated('donor_contact'),
        ]);

        return redirect()->route('dons.instructions', $resultat['payment']);
    }

    public function instructions(Payment $payment, PaymentManager $payments): View
    {
        abort_if($payment->donation === null, 404);

        return view('site.dons-instructions', [
            'payment' => $payment,
            'donation' => $payment->donation,
            'initiation' => $payments->gateway($payment->gateway)->initiate($payment),
        ]);
    }

    /** @return array<int, int> */
    private function suggestedAmounts(): array
    {
        return collect(explode(',', (string) Setting::get('donations.suggested')))
            ->map(fn ($v) => (int) trim($v))
            ->filter()
            ->values()
            ->all();
    }
}
