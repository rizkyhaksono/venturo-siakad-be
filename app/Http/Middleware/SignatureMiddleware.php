<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SignatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    // public function handle(Request $request, Closure $next)
    // {
    //     if (! empty($request->header('signature'))) {
    //         $privateKey = file_get_contents(storage_path() . '/auth/private.pem');
    //         if (openssl_private_decrypt(base64_decode($request->header('signature')), $decrypted, $privateKey)) {
    //             if (json_encode($request->all()) != $decrypted) {
    //                 return response()->failed(['Data tidak valid']);
    //             }
    //         } else {
    //             return response()->failed(['Gagal decrypt data']);
    //         }
    //     }

    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next)
    {
        if (in_array($request->method(), ['POST', 'PUT', 'DELETE'])) {
            $signature = $request->header('signature');

            if (!$signature) {
                return response()->json(['error' => 'Signature not provided'], 403);
            }

            if ($signature === env('SIGNATURE_BYPASS')) {
                return $next($request);
            }

            $data = $request->method() === 'DELETE'
                ? json_encode(['id' => last(explode('/', $request->path()))])
                : $request->getContent();

            $publicKey = file_get_contents(base_path('public.pem'));

            $verified = openssl_verify(
                $data,
                base64_decode($signature),
                $publicKey,
                OPENSSL_ALGO_SHA256
            );

            if ($verified !== 1) {
                return response()->json(['error' => 'Signature mismatch'], 403);
            }
        }

        return $next($request);
    }
}
