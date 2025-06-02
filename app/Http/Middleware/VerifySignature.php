<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\SignatureHelper;

class SignatureMiddleware
{
  public function handle(Request $request, Closure $next)
  {
    $signature = $request->header('X-Signature');
    $token = $request->header('X-Signature-Token');

    if (! $signature || ! $token) {
      return response()->json(['message' => 'Signature or token missing'], 400);
    }

    $cachedPayload = Cache::get("signed:$token");

    if (! $cachedPayload) {
      return response()->json(['message' => 'Signature token invalid or expired'], 403);
    }

    if (! (new SignatureHelper)->verifySignature($cachedPayload, $signature)) {
      return response()->json(['message' => 'Signature mismatch'], 403);
    }

    if ($request->all() !== $cachedPayload) {
      return response()->json(['message' => 'Payload has been tampered'], 403);
    }

    return $next($request);
  }
}
