<?php

namespace App\Services\Otp;

use App\Mail\OtpCodeMail;
use Illuminate\Support\Facades\Mail;

class MailOtpSender implements OtpSender
{
    public function send(string $to, string $code, string $purpose): void
    {
        Mail::to($to)->send(new OtpCodeMail($code, $purpose));
    }
}
