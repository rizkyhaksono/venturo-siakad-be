<?php

namespace Tests\Feature\Student;

use Tests\TestCase;
use Tests\Traits\AuthTrait;

class AuthTest extends TestCase
{
  use AuthTrait;

  protected const ENDPOINT_AUTH_LOGIN = '/api/v1/auth/login';
  protected const ENDPOINT_AUTH_LOGOUT = '/api/v1/auth/logout';

  /**
   * Test student login
   */
  public function test_student_can_login(): void
  {
    $response = $this->postJsonWithStudentAuth(self::ENDPOINT_AUTH_LOGIN, [
      'email' => env('TESTING_STUDENT_EMAIL', ''),
      'password' => env('TESTING_STUDENT_PASSWORD', ''),
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
    $response = $this->postJsonWithStudentAuth(self::ENDPOINT_AUTH_LOGIN, [
      'email' => 'invalid@gmail.com',
      'password' => 'wrongpassword',
    ]);

    $response->assertStatus(422);
  }

  /**
   * Test student logout
   */
  public function test_student_can_logout(): void
  {
    $response = $this->postJsonWithStudentAuth(self::ENDPOINT_AUTH_LOGOUT, [], $this->getStudentAuthHeaders());

    $response->assertStatus(200);
  }

  /**
   * Test login returns corrent user data
   */
  public function test_login_returns_correct_user_data(): void
  {
    $response = $this->postJsonWithStudentAuth(self::ENDPOINT_AUTH_LOGIN, [
      'email' => env('TESTING_STUDENT_EMAIL', ''),
      'password' => env('TESTING_STUDENT_PASSWORD', ''),
    ]);

    $response->assertStatus(200)
      ->assertJsonFragment([
        'email' => env('TESTING_STUDENT_EMAIL', ''),
        'token_type' => 'bearer',
        'role' => 'Student'
      ]);
  }
}
