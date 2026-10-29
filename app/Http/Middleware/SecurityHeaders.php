<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * En-têtes de sécurité appliqués à toutes les réponses web (étape 1.11). La CSP autorise les
 * scripts/styles de l'application (Livewire, Alpine, Vite) et les polices Google ; elle n'est pas
 * posée si la réponse en définit déjà une (ex. route des médias, qui s'auto-isole). HSTS n'est
 * envoyé que sur une requête HTTPS.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $headers = $response->headers;

        $headers->set('X-Content-Type-Options', 'nosniff', false);
        $headers->set('X-Frame-Options', 'DENY', false);
        $headers->set('Referrer-Policy', 'strict-origin-when-cross-origin', false);
        $headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), interest-cohort=()', false);

        if (! $headers->has('Content-Security-Policy')) {
            $headers->set('Content-Security-Policy', $this->policy());
        }

        // HSTS : uniquement en HTTPS (jamais en local http ni pendant les tests).
        if ($request->isSecure()) {
            $headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains', false);
        }

        return $response;
    }

    private function policy(): string
    {
        $turnstile = 'https://challenges.cloudflare.com';

        return implode('; ', [
            "default-src 'self'",
            // Livewire injecte son script avec la page ; Alpine évalue des expressions (unsafe-eval).
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' {$turnstile}",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            'font-src \'self\' https://fonts.gstatic.com data:',
            "img-src 'self' data:",
            "connect-src 'self'",
            "frame-src {$turnstile}",
            "frame-ancestors 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ]);
    }
}
