<?php

namespace App\Http\Responses;

use App\Support\TrustedDevice;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;

class TwoFactorLoginResponse implements TwoFactorLoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();
        $user?->forceFill(['last_activity_at' => now()])->save();

        $response = $request->wantsJson()
            ? response()->noContent()
            : redirect()->intended(config('fortify.home'));

        if ($user && $request->boolean('trust_device')) {
            $response->withCookie(TrustedDevice::cookieFor($user));
        }

        return $response;
    }
}
