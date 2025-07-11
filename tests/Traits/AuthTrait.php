<?php

namespace Tests\Traits;

use Illuminate\Testing\TestResponse;

trait AuthTrait
{
  protected static $adminToken;
  protected static $studentToken;
  protected static $teacherToken;

  protected const ACCEPT_JSON = 'application/json';
  protected const BEARER_PREFIX = 'Bearer ';
  protected const ENDPOINT_AUTH = "/api/v1/auth/login";

  // Role constants
  protected const ROLE_ADMIN = 'admin';
  protected const ROLE_STUDENT = 'student';
  protected const ROLE_TEACHER = 'teacher';

  /**
   * Get token for specific role (cached)
   */
  protected function getTokenForRole(string $role): string
  {
    $tokenProperty = $role . 'Token';

    if (!static::${$tokenProperty}) {
      $emailKey = 'TESTING_' . strtoupper($role) . '_EMAIL';
      $passwordKey = 'TESTING_' . strtoupper($role) . '_PASSWORD';

      $response = $this->postJson(self::ENDPOINT_AUTH, [
        'email' => env($emailKey, ""),
        'password' => env($passwordKey, ""),
      ]);

      $response->assertStatus(200);
      $data = $response->json();
      static::${$tokenProperty} = $data['data']['access_token'] ?? '';
    }

    return static::${$tokenProperty};
  }

  /**
   * Get admin token (cached)
   */
  protected function getAdminToken(): string
  {
    return $this->getTokenForRole(self::ROLE_ADMIN);
  }

  /**
   * Get student token (cached)
   */
  protected function getStudentToken(): string
  {
    return $this->getTokenForRole(self::ROLE_STUDENT);
  }

  /**
   * Get teacher token (cached)
   */
  protected function getTeacherToken(): string
  {
    return $this->getTokenForRole(self::ROLE_TEACHER);
  }

  /**
   * Get authentication headers for specific role
   */
  protected function getAuthHeadersForRole(string $role): array
  {
    return [
      'Accept' => self::ACCEPT_JSON,
      'Authorization' => self::BEARER_PREFIX . $this->getTokenForRole($role),
    ];
  }

  /**
   * Get authentication headers for admin
   */
  protected function getAdminAuthHeaders(): array
  {
    return $this->getAuthHeadersForRole(self::ROLE_ADMIN);
  }

  /**
   * Get authentication headers for student
   */
  protected function getStudentAuthHeaders(): array
  {
    return $this->getAuthHeadersForRole(self::ROLE_STUDENT);
  }

  /**
   * Get authentication headers for teacher
   */
  protected function getTeacherAuthHeaders(): array
  {
    return $this->getAuthHeadersForRole(self::ROLE_TEACHER);
  }

  /**
   * Make authenticated HTTP request with specific role
   */
  protected function makeAuthenticatedRequest(string $method, string $uri, string $role, array $data = [], array $headers = []): TestResponse
  {
    $authHeaders = $this->getAuthHeadersForRole($role);
    $allHeaders = array_merge($authHeaders, $headers);

    return match (strtoupper($method)) {
      'GET' => $this->getJson($uri, array_merge($allHeaders, $data)),
      'POST' => $this->postJson($uri, $data, $allHeaders),
      'PUT' => $this->putJson($uri, $data, $allHeaders),
      'DELETE' => $this->deleteJson($uri, $data, $allHeaders),
      default => throw new \InvalidArgumentException("Unsupported HTTP method: $method")
    };
  }

  // Admin requests
  protected function getJsonWithAdminAuth(string $uri, array $data = []): TestResponse
  {
    return $this->makeAuthenticatedRequest('GET', $uri, self::ROLE_ADMIN, $data);
  }

  protected function postJsonWithAdminAuth(string $uri, array $data = [], array $headers = []): TestResponse
  {
    return $this->makeAuthenticatedRequest('POST', $uri, self::ROLE_ADMIN, $data, $headers);
  }

  protected function putJsonWithAdminAuth(string $uri, array $data = [], array $headers = []): TestResponse
  {
    return $this->makeAuthenticatedRequest('PUT', $uri, self::ROLE_ADMIN, $data, $headers);
  }

  protected function deleteJsonWithAdminAuth(string $uri, array $data = [], array $headers = []): TestResponse
  {
    return $this->makeAuthenticatedRequest('DELETE', $uri, self::ROLE_ADMIN, $data, $headers);
  }

  // Student requests
  protected function getJsonWithStudentAuth(string $uri, array $data = []): TestResponse
  {
    return $this->makeAuthenticatedRequest('GET', $uri, self::ROLE_STUDENT, $data);
  }

  protected function postJsonWithStudentAuth(string $uri, array $data = [], array $headers = []): TestResponse
  {
    return $this->makeAuthenticatedRequest('POST', $uri, self::ROLE_STUDENT, $data, $headers);
  }

  protected function putJsonWithStudentAuth(string $uri, array $data = [], array $headers = []): TestResponse
  {
    return $this->makeAuthenticatedRequest('PUT', $uri, self::ROLE_STUDENT, $data, $headers);
  }

  protected function deleteJsonWithStudentAuth(string $uri, array $data = [], array $headers = []): TestResponse
  {
    return $this->makeAuthenticatedRequest('DELETE', $uri, self::ROLE_STUDENT, $data, $headers);
  }

  // Teacher requests
  protected function getJsonWithTeacherAuth(string $uri, array $data = []): TestResponse
  {
    return $this->makeAuthenticatedRequest('GET', $uri, self::ROLE_TEACHER, $data);
  }

  protected function postJsonWithTeacherAuth(string $uri, array $data = [], array $headers = []): TestResponse
  {
    return $this->makeAuthenticatedRequest('POST', $uri, self::ROLE_TEACHER, $data, $headers);
  }

  protected function putJsonWithTeacherAuth(string $uri, array $data = [], array $headers = []): TestResponse
  {
    return $this->makeAuthenticatedRequest('PUT', $uri, self::ROLE_TEACHER, $data, $headers);
  }

  protected function deleteJsonWithTeacherAuth(string $uri, array $data = [], array $headers = []): TestResponse
  {
    return $this->makeAuthenticatedRequest('DELETE', $uri, self::ROLE_TEACHER, $data, $headers);
  }
}
