<?php

namespace App\Services\Otp;

use App\Models\OtpCode;
use App\Models\User;
use App\Support\Phone;
use Illuminate\Support\Facades\Hash;

class OtpService
{
    public const VERIFY = 'verify';

    public const LOGIN = 'login';

    public const RESET = 'reset';

    public const DELETE = 'delete';

    public function __construct(
        private readonly MailOtpSender $mail,
        private readonly OtpSender $sms,
    ) {}

    /** Les codes par SMS ne sont possibles que si une passerelle est branchée. */
    public function smsEnabled(): bool
    {
        return (bool) config('otp.sms_enabled');
    }

    public function channelFor(string $identifier): string
    {
        return Phone::isIdentifierEmail($identifier) ? 'email' : 'sms';
    }

    /** Identifiant canonique : e-mail en minuscules ou +228XXXXXXXX. */
    public function canonical(string $identifier): ?string
    {
        $identifier = trim($identifier);

        return Phone::isIdentifierEmail($identifier)
            ? (filter_var($identifier, FILTER_VALIDATE_EMAIL) ? mb_strtolower($identifier) : null)
            : Phone::normalize($identifier);
    }

    /** Un code vient-il d'être envoyé à cet identifiant ? */
    public function sentRecently(string $identifier, string $purpose): bool
    {
        return OtpCode::where('identifier', $identifier)->where('purpose', $purpose)
            ->where('created_at', '>', now()->subSeconds(config('otp.resend_after')))
            ->exists();
    }

    /** Génère et envoie un code à 6 chiffres ; invalide les précédents. */
    public function issue(string $identifier, string $purpose, ?User $user = null): void
    {
        OtpCode::where('identifier', $identifier)->where('purpose', $purpose)->whereNull('consumed_at')
            ->update(['consumed_at' => now()]);

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $channel = $this->channelFor($identifier);

        OtpCode::create([
            'identifier' => $identifier,
            'channel' => $channel,
            'purpose' => $purpose,
            'user_id' => $user?->getKey(),
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(config('otp.ttl')),
        ]);

        ($channel === 'email' ? $this->mail : $this->sms)->send($identifier, $code, $purpose);
    }

    /**
     * Vérifie un code. Retourne le code consommé, ou null s'il est faux, expiré ou épuisé.
     * $remaining reçoit le nombre d'essais restants après un échec.
     */
    public function verify(string $identifier, string $purpose, string $code, ?int &$remaining = null): ?OtpCode
    {
        $otp = OtpCode::where('identifier', $identifier)->where('purpose', $purpose)
            ->whereNull('consumed_at')->where('expires_at', '>', now())
            ->latest('id')->first();

        $remaining = 0;

        if ($otp === null || $otp->attempts >= config('otp.max_attempts')) {
            return null;
        }

        if (! Hash::check(trim($code), $otp->code_hash)) {
            $otp->increment('attempts');
            $remaining = max(0, config('otp.max_attempts') - $otp->attempts);

            return null;
        }

        $otp->update(['consumed_at' => now()]);

        return $otp;
    }
}
