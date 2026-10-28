<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Thérapeute, formateur/coach, comité et admin : 2FA obligatoire avant tout accès à leur espace. */
class EnsureTwoFactorEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->mustUseTwoFactor() && ! $user->hasConfirmedTwoFactor()) {
            return redirect()->route('two-factor.setup')
                ->with('status', 'La double authentification est obligatoire pour votre rôle.');
        }

        return $next($request);
    }
}
