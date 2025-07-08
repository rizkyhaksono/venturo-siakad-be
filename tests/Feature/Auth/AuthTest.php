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
    $response = $this->postJson(self::ENDPOINT_AUTH_LOGIN, [
      'email' => env('TESTING_EMAIL', ''),
      'password' => env('TESTING_PASSWORD', ''),
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
    $response = $this->postJson(self::ENDPOINT_AUTH_LOGIN, [
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
    $response = $this->postJson(self::ENDPOINT_AUTH_LOGOUT, [], $this->getAuthHeaders());

    $response->assertStatus(200);
  }

  /**
   * Test login response contains required fields
   */
  public function test_login_response_contains_required_fields(): void
  {
    $response = $this->postJson(self::ENDPOINT_AUTH_LOGIN, [
      'email' => env('TESTING_EMAIL', ''),
      'password' => env('TESTING_PASSWORD', ''),
    ]);

    $response->assertStatus(200);

    $data = $response->json();

    // Assert main structure
    $this->assertArrayHasKey('status_code', $data);
    $this->assertArrayHasKey('data', $data);
    $this->assertArrayHasKey('message', $data);
    $this->assertArrayHasKey('settings', $data);

    // Assert data structure
    $this->assertArrayHasKey('access_token', $data['data']);
    $this->assertArrayHasKey('token_type', $data['data']);
    $this->assertArrayHasKey('user', $data['data']);
    $this->assertArrayHasKey('role', $data['data']);

    // Assert user structure
    $this->assertArrayHasKey('id', $data['data']['user']);
    $this->assertArrayHasKey('name', $data['data']['user']);
    $this->assertArrayHasKey('email', $data['data']['user']);
    $this->assertArrayHasKey('role', $data['data']['user']);

    // Assert token is not empty
    $this->assertNotEmpty($data['data']['access_token']);
    $this->assertEquals('bearer', $data['data']['token_type']);
    $this->assertEquals(200, $data['status_code']);
  }

  /**
   * Test login returns correct user data
   */
  public function test_login_returns_correct_user_data(): void
  {
    $response = $this->postJson(self::ENDPOINT_AUTH_LOGIN, [
      'email' => env('TESTING_EMAIL', ''),
      'password' => env('TESTING_PASSWORD', ''),
    ]);

    $response->assertStatus(200)
      ->assertJsonFragment([
        'email' => 'admin@gmail.com',
        'token_type' => 'bearer',
        'role' => 'Admin'
      ]);
  }
}
