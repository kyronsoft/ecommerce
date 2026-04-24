<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.auth' => \App\Http\Middleware\EnsureAdminAuthenticated::class,
            'admin.guest' => \App\Http\Middleware\RedirectIfAdminAuthenticated::class,
            'store.auth' => \App\Http\Middleware\EnsureStoreAuthenticated::class,
            'store.guest' => \App\Http\Middleware\RedirectIfStoreAuthenticated::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'payments/epayco/response',
            'payments/epayco/confirmation',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Default exception handling.
    })->create();
