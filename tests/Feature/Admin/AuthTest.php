<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Tests\Traits\AuthTrait;

class AuthTest extends TestCase
{
  use AuthTrait;

  protected const ENDPOINT_AUTH_LOGIN = '/api/v1/auth/login';
  protected const ENDPOINT_AUTH_LOGOUT = '/api/v1/auth/logout';

  /**
   * Test admin login
   */
  public function test_admin_can_login(): void
  {
    $response = $this->postJsonWithAdminAuth(self::ENDPOINT_AUTH_LOGIN, [
      'email' => env('TESTING_ADMIN_EMAIL', ''),
      'password' => env('TESTING_ADMIN_PASSWORD', ''),
    ]);

    $response->assertStatus(200)
      ->assertJsonStructure([
        'status_code',
        'data' => [
          'access_token',
          'token_type',
          'user' => [
            'id',
            'name',
            'email',
            'photo_url',
            'phone_number',
            'updated_security',
            'm_user_roles_id',
            'role' => [
              'id',
              'name',
              'description'
            ],
            'access'
          ],
          'role'
        ],
        'message',
        'settings'
      ]);
  }

  /**
   * Test invalid login
   */
  public function test_invalid_login_fails(): void
  {
    $response = $this->postJsonWithAdminAuth(self::ENDPOINT_AUTH_LOGIN, [
      'email' => 'invalid@gmail.com',
      'password' => 'wrongpassword',
    ]);

    $response->assertStatus(422);
  }

  /**
   * Test logout
   */
  public function test_admin_can_logout(): void
  {
    $response = $this->postJsonWithAdminAuth(self::ENDPOINT_AUTH_LOGOUT, [], $this->getAdminAuthHeaders());

    $response->assertStatus(200);
  }

  /**
   * Test login returns correct user data
   */
  public function test_login_returns_correct_user_data(): void
  {
    $response = $this->postJsonWithAdminAuth(self::ENDPOINT_AUTH_LOGIN, [
      'email' => env('TESTING_ADMIN_EMAIL', ''),
      'password' => env('TESTING_ADMIN_PASSWORD', ''),
    ]);

    $response->assertStatus(200)
      ->assertJsonFragment([
        'email' => env('TESTING_ADMIN_EMAIL', ''),
        'token_type' => 'bearer',
        'role' => 'Admin'
      ]);
  }
}
