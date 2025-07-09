<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Scramble::configure()
            ->withDocumentTransformers(function (OpenApi $openApi) {
                $openApi->secure(
                    SecurityScheme::http('bearer')
                );
            });

        Gate::define('viewScalar', function (?User $user) {
            return $user && $user->hasPermission('view-scalar');
        });

        Response::macro('success', function ($data = [], $message = '', $settings = []) {
            return Response::make([
                'status_code' => 200,
                'data' => $data,
                'message' => $message,
                'settings' => $settings,
            ], 200);
        });

        Response::macro('successWithSignature', function ($data = [], $message = '', $settings = []) {
            $publicKey = file_get_contents(storage_path() . '/auth/public.pem');
            $signature = (openssl_public_encrypt(json_encode($data), $encrypted, $publicKey)) ? base64_encode($encrypted) : null;

            return Response::make([
                'status_code' => 200,
                'data' => $data,
                'message' => $message,
                'settings' => $settings,
            ], 200, ['signature' => $signature]);
        });

        Response::macro('failed', function ($error = [], $httpCode = 422, $settings = []) {
            return Response::make([
                'status_code' => $httpCode,
                'errors' => $error,
                'settings' => $settings,
            ], $httpCode);
        });

        Response::macro('failedWithSignature', function ($error = null, $httpCode = 422, $settings = []) {
            if ($error instanceof \Illuminate\Support\MessageBag) {
                $errMobile = $error->first();
            } elseif (is_array($error)) {
                $errMobile = isset($error[0]) ? $error[0] : '';
            } elseif (is_string($error)) {
                $errMobile = $error;
            }

            $content = [
                'status_code' => $httpCode,
                'errors' => $error,
                'errors_mobile' => isset($errMobile) ? $errMobile : '',
                'settings' => $settings,
            ];

            $publicKey = file_get_contents(storage_path() . '/auth/public.pem');
            $signature = (openssl_public_encrypt(json_encode($content), $encrypted, $publicKey)) ? base64_encode($encrypted) : null;

            return Response::make($content, $httpCode, ['signature' => $signature]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
