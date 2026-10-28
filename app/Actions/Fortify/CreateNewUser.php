<?php

namespace App\Actions\Fortify;

use App\Enums\UserStatus;
use App\Models\User;
use App\Support\Phone;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    /**
     * Formulaire minimal : nom, téléphone et/ou e-mail, mot de passe ou connexion sans mot de passe.
     *
     * @param  array<string, mixed>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        $input['phone'] = Phone::normalize($input['phone'] ?? null);
        $input['email'] = filled($input['email'] ?? null) ? mb_strtolower(trim($input['email'])) : null;
        $smsEnabled = (bool) config('otp.sms_enabled');

        $validated = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'required_without:email', Rule::unique(User::class, 'phone')],
            'email' => [
                $smsEnabled ? 'nullable' : 'required', 'required_without:phone', 'email:rfc', 'max:255',
                Rule::unique(User::class, 'email'),
            ],
            'method' => ['required', 'in:mdp,sans'],
            'password' => ['exclude_if:method,sans', 'required', 'string', Password::default()],
            'terms' => ['accepted'],
        ], [
            'phone.required_without' => 'Indiquez au moins un téléphone ou une adresse e-mail.',
            'phone.unique' => 'Un compte existe déjà avec ce numéro. Essayez de vous connecter.',
            'email.required' => "L'envoi de codes par SMS n'est pas encore disponible : indiquez votre adresse e-mail.",
            'email.required_without' => 'Indiquez au moins un téléphone ou une adresse e-mail.',
            'email.unique' => 'Un compte existe déjà avec cette adresse. Essayez de vous connecter.',
            'terms.accepted' => "Merci d'accepter les conditions d'utilisation et la politique de confidentialité.",
            'password.required' => 'Choisissez un mot de passe, ou optez pour la connexion sans mot de passe.',
        ], [
            'name' => 'nom',
            'phone' => 'téléphone',
            'password' => 'mot de passe',
        ])->validate();

        return User::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'password' => $validated['method'] === 'mdp' ? $validated['password'] : null,
            'password_changed_at' => $validated['method'] === 'mdp' ? now() : null,
            'status' => UserStatus::PendingVerification,
            'locale' => 'fr',
        ]);
    }
}
