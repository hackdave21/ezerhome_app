<?php

namespace App\Services\Otp;

use Illuminate\Support\Facades\Log;

/**
 * Passerelle SMS de substitution : n'envoie rien. En environnement local uniquement,
 * le code est écrit dans le journal pour pouvoir tester. À remplacer par la vraie passerelle.
 */
class LogSmsOtpSender implements OtpSender
{
    public function send(string $to, string $code, string $purpose): void
    {
        if (app()->environment('local', 'testing')) {
            Log::info("[OTP SMS] {$purpose} vers {$to} : {$code}");
        }
    }
}
