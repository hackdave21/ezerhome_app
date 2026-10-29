<?php

namespace App\Http\Requests;

use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DonationStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // formulaire public
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_anonymous' => $this->boolean('is_anonymous'),
            'recurring' => $this->input('frequence') === 'regulier',
        ]);
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'integer', 'min:100', 'max:100000000'],
            'method' => ['required', Rule::enum(PaymentMethod::class)],
            'donor_name' => ['nullable', 'string', 'max:150'],
            'donor_contact' => ['nullable', 'string', 'max:150'],
            'is_anonymous' => ['boolean'],
            'recurring' => ['boolean'],
            'frequence' => ['required', 'in:ponctuel,regulier'],
            // Honeypot.
            'website' => ['prohibited'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Indiquez un montant.',
            'amount.min' => 'Le montant minimum est de 100 FCFA.',
            'method.required' => 'Choisissez un moyen de paiement.',
        ];
    }
}
