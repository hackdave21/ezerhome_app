<?php

namespace App\Http\Requests;

use App\Models\ListeningRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListeningRequestStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', ListeningRequest::class) ?? false;
    }

    public function rules(): array
    {
        return [
            // Texte libre : pas de questionnaire clinique.
            'description' => ['required', 'string', 'min:10', 'max:5000'],
            'urgency' => ['nullable', Rule::in(array_keys(ListeningRequest::URGENCIES))],
            'preferred_channel' => ['nullable', Rule::in(array_keys(ListeningRequest::CHANNELS))],
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => 'Expliquez en quelques mots ce qui vous amène.',
            'description.min' => 'Donnez-nous un peu plus de détails (au moins 10 caractères).',
        ];
    }
}
