<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Sentry\Laravel\Integration;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/custom_routes.php'));
            Route::middleware('web')
                ->group(base_path('routes/user.php'));
            Route::middleware('web')
                ->group(base_path('routes/payment.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->use([
            \App\Http\Middleware\TrustProxies::class,
            \Illuminate\Http\Middleware\HandleCors::class,
            \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
            \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
            \App\Http\Middleware\TrimStrings::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        ]);

        $middleware->web([
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->api([
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // ✅ Middleware aliases fix
        $middleware->alias([
            'activity'             => \App\Http\Middleware\UserActivity::class,
            'admin'                => \App\Http\Middleware\AdminMiddleware::class,
            'user'                 => \App\Http\Middleware\UserMiddleware::class,
            'prevent-back-history' => \App\Http\Middleware\PreventBackHistory::class,
            'fileUploadDisabled'   => \App\Http\Middleware\DisableFileUpload::class,
            'clean-url'            => \App\Http\Middleware\CleanUrlMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        Integration::handles($exceptions);
    })
    ->create();
