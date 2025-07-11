<?php

namespace Tests\Feature\Teacher;

use Tests\TestCase;
use Tests\Traits\AuthTrait;

class AuthTest extends TestCase
{
  use AuthTrait;

  protected const ENDPOINT_AUTH_LOGIN = '/api/v1/auth/login';
  protected const ENDPOINT_AUTH_LOGOUT = '/api/v1/auth/logout';

  /**
   * Test teacher login
   */
  public function test_teacher_can_login(): void
  {
    $response = $this->postJsonWithTeacherAuth(self::ENDPOINT_AUTH_LOGIN, [
      'email' => env('TESTING_TEACHER_EMAIL', ''),
      'password' => env('TESTING_TEACHER_PASSWORD', ''),
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
    $response = $this->postJsonWithTeacherAuth(self::ENDPOINT_AUTH_LOGIN, [
      'email' => 'gataucapek@gmail.com',
      'password' => 'males',
    ]);

    $response->assertStatus(422);
  }

  /**
   * Test teacher logout
   */
  public function test_teacher_can_logout(): void
  {
    $response = $this->postJsonWithTeacherAuth(
      self::ENDPOINT_AUTH_LOGOUT,
      [],
      $this->getTeacherAuthHeaders()
    );

    $response->assertStatus(200);
  }

  /**
   * Test login returns corrent user data
   */
  public function test_login_returns_correct_user_data(): void
  {
    $response = $this->postJsonWithTeacherAuth(self::ENDPOINT_AUTH_LOGIN, [
      'email' => env('TESTING_TEACHER_EMAIL', ''),
      'password' => env('TESTING_TEACHER_PASSWORD', ''),
    ]);

    $response->assertStatus(200)
      ->assertJsonFragment([
        'email' => env('TESTING_TEACHER_EMAIL', ''),
        'token_type' => 'bearer',
        'role' => 'Teacher'
      ]);
  }
}
