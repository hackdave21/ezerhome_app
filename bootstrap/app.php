<?php

use App\Http\Middleware\EnsureAccountIsActive;
use App\Http\Middleware\EnsureListeningCharterSigned;
use App\Http\Middleware\EnsureTwoFactorEnabled;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\Middleware\AuthenticateSession;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectUsersTo('/espace');

        // En-têtes de sécurité sur toutes les réponses web.
        $middleware->web(append: [SecurityHeaders::class]);

        $middleware->alias([
            'account.active' => EnsureAccountIsActive::class,
            'two-factor.required' => EnsureTwoFactorEnabled::class,
            'charter.listening' => EnsureListeningCharterSigned::class,
            'auth.session' => AuthenticateSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
