<?php

use App\Http\Middleware\JwtMiddleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\SignatureMiddleware;
use App\Http\Middleware\VerifySignature;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth.api' => JwtMiddleware::class,
            'role' => RoleMiddleware::class,
            'signature' => SignatureMiddleware::class,
            'signature.verify' => SignatureMiddleware::class,
            'verify.signature' => VerifySignature::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
