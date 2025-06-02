<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Helpers\SignatureHelper;

class SignatureController extends Controller
{
  public function sign(Request $request)
  {
    $payload = $request->all();
    $token = Str::uuid();
    Cache::put("signed:$token", $payload, now()->addMinutes(5));

    $signature = (new SignatureHelper)->signData($payload);

    return response()->json([
      'signature' => $signature,
      'token' => $token,
    ]);
  }
}
