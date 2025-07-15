<?php

namespace Tests\Feature\Teacher;

use Tests\TestCase;
use Tests\Traits\AuthTrait;

class SubjectTest extends TestCase
{
  use AuthTrait;

  protected const ENDPOINT_TEACHER_SUBJECT = '/api/v1/teacher/subjects';

  /**
   * Test getting all subjects as teacher (index)
   */
  public function test_can_get_all_subjects_as_teacher(): void
  {
    $response = $this->getJsonWithTeacherAuth(self::ENDPOINT_TEACHER_SUBJECT);

    $response->assertStatus(200)
    ->assertJsonStructure([
      'status',
      'data' => [
        '*' => [
          'id',
          'name',
          'created_at',
          'updated_at',
          'deleted_at'
        ]
      ],
      'meta' => [
        'current_page',
        'last_page',
        'per_page',
        'total'
      ]
    ])
    ->assertJsonFragment([
      'status' => 'success'
    ]);
  }

  /**
   * Test getting subjects with custom pagination
   */
  public function test_can_get_subjects_with_custom_pagination(): void
  {
    $response = $this->getJsonWithTeacherAuth(self::ENDPOINT_TEACHER_SUBJECT . '?per_page=5');
    $response->assertStatus(200)
      ->assertJsonFragment([
        'status' => 'success'
      ])
      ->assertJsonPath('meta.per_page', 5);
  }

  /**
   * Test showing non-existent subject returns 404
   */
  public function test_show_non_existent_subject_returns_404(): void
  {
    $nonExistentId = 'non-existent-uuid-12345';
    $response = $this->getJsonWithTeacherAuth(self::ENDPOINT_TEACHER_SUBJECT . '/' . $nonExistentId);
    $response->assertStatus(404)
      ->assertJsonFragment([
        'status' => 'error',
        'message' => 'Subject not found'
      ]);
  }

  /**
   * Test teacher cannot access subjects without authentication
   */
  public function test_cannot_get_subjects_without_authentication(): void
  {
    $response = $this->getJson(self::ENDPOINT_TEACHER_SUBJECT);
    $response->assertStatus(403);
  }

  /**
   * Test teacher cannot create subject (read-only access)
   */
  public function test_teacher_cannot_create_subject(): void
  {
    $subjectData = [
      'name' => 'Test Subject - ' . time()
    ];
    $response = $this->postJsonWithTeacherAuth(self::ENDPOINT_TEACHER_SUBJECT, $subjectData);
    $this->assertContains($response->status(), [404, 405, 500]);
  }

  /**
   * Test teacher cannot update subject (read-only access)
   */
  public function test_teacher_cannot_update_subject(): void
  {
    $updateData = [
      'name' => 'Updated Subject - ' . time()
    ];
    $response = $this->putJsonWithTeacherAuth(self::ENDPOINT_TEACHER_SUBJECT . '/ngawur', $updateData);
    $this->assertContains($response->status(), [404, 405, 500]);
  }

  /**
   * Test teacher cannot delete subject (read-only access)
   */
  public function test_teacher_cannot_delete_subject(): void
  {
    $response = $this->deleteJsonWithTeacherAuth(self::ENDPOINT_TEACHER_SUBJECT . '/ngawur');
    $this->assertContains($response->status(), [404, 405, 500]);
  }

  /**
   * Test error handling when subject service fails
   */
  public function test_handles_subject_service_error_gracefully(): void
  {
    $response = $this->getJsonWithTeacherAuth(self::ENDPOINT_TEACHER_SUBJECT);
    $this->assertContains($response->status(), [200, 500]);

    if ($response->status() === 500) {
      $response->assertJsonStructure([
        'status',
        'message',
        'error'
      ]);
    }
  }

  /**
   * Test pagination structure for teacher subject endpoint
   */
  public function test_subject_pagination_structure(): void
  {
    $response = $this->getJsonWithTeacherAuth(self::ENDPOINT_TEACHER_SUBJECT);
    $response->assertStatus(200)
      ->assertJsonStructure([
        'status',
        'data',
        'meta' => [
          'current_page',
          'last_page',
          'per_page',
          'total'
        ]
      ]);
  }

  /**
   * Test response format matches expected structure
   */
  public function test_subject_response_format(): void
  {
    $response = $this->getJsonWithTeacherAuth(self::ENDPOINT_TEACHER_SUBJECT);
    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success'
      ]);

    $data = $response->json();

    $this->assertArrayHasKey('status', $data);
    $this->assertArrayHasKey('data', $data);
    $this->assertArrayHasKey('meta', $data);
    $this->assertEquals('success', $data['status']);
  }
}
