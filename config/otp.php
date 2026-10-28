<?php

return [
    // Durée de validité d'un code, en minutes.
    'ttl' => 10,

    // Essais autorisés avant invalidation du code.
    'max_attempts' => 3,

    // Délai minimal entre deux envois, en secondes.
    'resend_after' => 60,

    // Durée de validité du lien magique, en minutes.
    'magic_link_ttl' => 15,

    // Passerelle SMS/WhatsApp disponible ? Tant que non, la vérification se fait par e-mail
    // (cf. étape 1.3.3). Brancher une implémentation de App\Services\Otp\OtpSender pour 'sms'.
    'sms_enabled' => (bool) env('OTP_SMS_ENABLED', false),
];
