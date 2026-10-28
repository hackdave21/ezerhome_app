<?php

namespace App\Http\Requests;

use App\Rules\Turnstile;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public const OBJETS = ['Question générale', 'Formation', 'Coaching', 'Dons', 'Bénévolat', 'Autre'];

    public const TYPES_ORGANISATION = [
        'Entreprise', 'Fondation', 'ONG ou association', 'Institution publique', 'Coopération internationale', 'Autre',
    ];

    public const SOUTIENS = ['Don financier', 'Don en nature', 'Mécénat de compétences', 'Projet mené ensemble'];

    public function rules(): array
    {
        $commun = [
            'profil' => ['required', 'in:particulier,organisation'],
            'nom' => ['required', 'string', 'max:120'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            'consentement' => ['accepted'],
            // Honeypot : doit rester vide.
            'website' => ['prohibited'],
            'cf-turnstile-response' => [new Turnstile],
        ];

        if ($this->input('profil') === 'organisation') {
            return $commun + [
                'organisation' => ['required', 'string', 'max:150'],
                'type_organisation' => ['required', 'in:'.implode(',', self::TYPES_ORGANISATION)],
                'fonction' => ['required', 'string', 'max:120'],
                'email' => ['required', 'email:rfc', 'max:190'],
                'telephone' => ['nullable', 'string', 'max:30'],
                'soutien' => ['nullable', 'array'],
                'soutien.*' => ['in:'.implode(',', self::SOUTIENS)],
                'dossier' => ['nullable', 'boolean'],
            ];
        }

        return $commun + [
            'telephone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email:rfc', 'max:190'],
            'objet' => ['required', 'in:'.implode(',', self::OBJETS)],
        ];
    }

    public function attributes(): array
    {
        return [
            'nom' => $this->input('profil') === 'organisation' ? 'nom et prénoms du contact' : 'nom et prénoms',
            'email' => 'e-mail',
            'telephone' => 'téléphone',
            'organisation' => "nom de l'organisation",
            'type_organisation' => "type d'organisation",
            'message' => $this->input('profil') === 'organisation' ? 'projet ou question' : 'message',
            'consentement' => 'consentement',
        ];
    }

    public function messages(): array
    {
        return ['consentement.accepted' => 'Merci de cocher la case pour que nous puissions vous répondre.'];
    }
}
