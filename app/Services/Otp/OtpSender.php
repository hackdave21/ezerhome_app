<?php

namespace App\Services\Otp;

/** Envoi d'un code à usage unique. Une implémentation par canal (e-mail, SMS/WhatsApp). */
interface OtpSender
{
    public function send(string $to, string $code, string $purpose): void;
}
