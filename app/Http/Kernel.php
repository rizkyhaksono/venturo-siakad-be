<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
  /**
   * The application's route middleware.
   *
   * These middleware may be assigned to groups or used individually.
   *
   * @var array<string, class-string|string>
   */
  protected $routeMiddleware = [
    'auth:sanctum' => \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'registration.status' => \App\Http\Middleware\CheckRegistrationStatus::class,
    'role' => \App\Http\Middleware\RoleMiddleware::class,
    'verify.signature' => \App\Http\Middleware\VerifySignature::class,
    'signature' => \App\Http\Middleware\SignatureMiddleware::class,
  ];

  protected $middlewareGroups = [
    'api' => [
      \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
      'throttle:api',
      \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
  ];
}
