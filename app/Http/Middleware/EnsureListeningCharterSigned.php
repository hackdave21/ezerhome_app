<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Verrou technique : aucune demande ni message d'écoute sans la charte (dans sa version en vigueur) signée.
 * À appliquer à toutes les routes de demande d'écoute et, en phase 2, de messagerie.
 */
class EnsureListeningCharterSigned
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasSignedCurrentCharter()) {
            if ($request->expectsJson()) {
                abort(403, "La charte d'écoute doit d'abord être signée.");
            }

            return redirect()->route('espace.charte')->with('charte_requise', $request->fullUrl());
        }

        return $next($request);
    }
}
