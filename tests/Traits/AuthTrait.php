<?php

namespace Tests\Traits;

use Illuminate\Testing\TestResponse;

trait AuthTrait
{
  protected static $adminToken;
  protected const ACCEPT_JSON = 'application/json';
  protected const BEARER_PREFIX = 'Bearer ';

  /**
   * Get admin token (cached)
   */
  protected function getAdminToken(): string
  {
    if (!static::$adminToken) {
      $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'admin@gmail.com',
        'password' => 'admin123',
      ]);

      $response->assertStatus(200);
      $data = $response->json();
      static::$adminToken = $data['data']['access_token'] ?? '';
    }

    return static::$adminToken;
  }

  /**
   * Get authentication headers
   */
  protected function getAuthHeaders(): array
  {
    return [
      'Accept' => self::ACCEPT_JSON,
      'Authorization' => self::BEARER_PREFIX . $this->getAdminToken(),
    ];
  }

  /**
   * Make authenticated GET request
   */
  protected function getJsonWithAuth(string $uri, array $data = []): TestResponse
  {
    return $this->getJson($uri, array_merge($this->getAuthHeaders(), $data));
  }

  /**
   * Make authenticated POST request
   */
  protected function postJsonWithAuth(string $uri, array $data = [], array $headers = []): TestResponse
  {
    return $this->postJson($uri, $data, array_merge($this->getAuthHeaders(), $headers));
  }

  /**
   * Make authenticated PUT request
   */
  protected function putJsonWithAuth(string $uri, array $data = [], array $headers = []): TestResponse
  {
    return $this->putJson($uri, $data, array_merge($this->getAuthHeaders(), $headers));
  }

  /**
   * Make authenticated DELETE request
   */
  protected function deleteJsonWithAuth(string $uri, array $data = [], array $headers = []): TestResponse
  {
    return $this->deleteJson($uri, $data, array_merge($this->getAuthHeaders(), $headers));
  }
}
