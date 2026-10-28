<?php

namespace App\Actions\Fortify;

use App\Support\TrustedDevice;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable as BaseAction;

/** Comme Fortify, mais saute le défi 2FA sur un appareil de confiance. */
class RedirectIfTwoFactorAuthenticatable extends BaseAction
{
    public function handle($request, $next)
    {
        $user = $this->validateCredentials($request);

        if ($user && $user->hasConfirmedTwoFactor() && ! TrustedDevice::valid($request, $user)) {
            return $this->twoFactorChallengeResponse($request, $user);
        }

        return $next($request);
    }
}
