<?php

namespace App\Support;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/** Décide si un compte a le droit de se connecter, avec un message clair sinon. */
class LoginGate
{
    /** Débuts des messages explicites affichés tels quels sur la page de connexion. */
    private const CUSTOM_PREFIXES = ['Votre compte', 'Ce téléphone', 'Les codes par SMS', 'Indiquez votre'];

    public static function isCustomMessage(string $message): bool
    {
        return $message !== '' && Str::startsWith($message, self::CUSTOM_PREFIXES);
    }

    /** Retourne l'utilisateur s'il peut se connecter, sinon lève une ValidationException explicite. */
    public static function allow(User $user, string $field = 'identifier'): User
    {
        return match ($user->status) {
            UserStatus::Active => $user,
            UserStatus::PendingVerification => throw ValidationException::withMessages([
                $field => "Votre compte n'est pas encore vérifié. Utilisez « Recevoir un code de connexion » pour le valider.",
            ]),
            UserStatus::Inactive => throw (ValidationException::withMessages([$field => 'Votre compte est désactivé.'])
                ->redirectTo(self::deactivatedUrl($user))),
            UserStatus::Suspended => throw ValidationException::withMessages([
                $field => 'Votre compte est suspendu. Contactez-nous pour en savoir plus.',
            ]),
        };
    }

    /** Connexion par code ou lien : un compte en attente de vérification est accepté (le code vaut vérification). */
    public static function allowPending(User $user, string $field = 'identifier'): User
    {
        return $user->status === UserStatus::PendingVerification ? $user : self::allow($user, $field);
    }

    private static function deactivatedUrl(User $user): string
    {
        session()->flash('deactivated_user_id', $user->getKey());

        return route('account.deactivated');
    }
}
