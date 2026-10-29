<?php

namespace App\Http\Requests;

use App\Enums\AssistanceCategory;
use App\Rules\Turnstile;
use App\Support\Phone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssistanceRequestStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // formulaire public
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:150'],
            'telephone' => ['required', 'string', 'max:30', function ($attribute, $value, $fail) {
                if (Phone::normalize($value) === null) {
                    $fail('Indiquez un numéro de téléphone togolais valide (8 chiffres).');
                }
            }],
            'email' => ['nullable', 'email:rfc', 'max:190'],
            'category' => ['required', Rule::enum(AssistanceCategory::class)],
            'description' => ['required', 'string', 'min:10', 'max:5000'],
            'consentement' => ['accepted'],
            // Honeypot : doit rester vide.
            'website' => ['prohibited'],
            'cf-turnstile-response' => [new Turnstile],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Indiquez vos nom et prénoms.',
            'telephone.required' => 'Un numéro de téléphone ou WhatsApp est obligatoire.',
            'category.required' => 'Choisissez le type d\'aide.',
            'description.required' => 'Décrivez votre situation en quelques mots.',
            'consentement.accepted' => 'Cochez la case de consentement pour envoyer votre demande.',
            'website.prohibited' => 'Votre demande n\'a pas pu être envoyée.',
        ];
    }

    public function normalizedPhone(): string
    {
        return (string) Phone::normalize($this->input('telephone'));
    }
}
