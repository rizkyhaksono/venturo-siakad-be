<?php

namespace App\Helpers;

class SignatureHelper
{
  public function signData(array $data): string
  {
    $privateKey = file_get_contents(storage_path('auth/oauth-private.key'));
    $privateKeyResource = openssl_pkey_get_private($privateKey);
    openssl_sign(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), $signature, $privateKeyResource, OPENSSL_ALGO_SHA256);
    openssl_free_key($privateKeyResource);
    return base64_encode($signature);
  }

  public function verifySignature(array $data, string $signature): bool
  {
    $publicKey = file_get_contents(storage_path('auth/oauth-public.key'));
    $publicKeyResource = openssl_pkey_get_public($publicKey);
    $result = openssl_verify(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), base64_decode($signature), $publicKeyResource, OPENSSL_ALGO_SHA256);
    openssl_free_key($publicKeyResource);
    return $result === 1;
  }
}
