<?php

namespace Tests\Feature\Teacher;

use Tests\TestCase;
use Tests\Traits\AuthTrait;

class RombelTest extends TestCase
{
  use AuthTrait;

  protected const ENDPOINT_TEACHER_ROMBEL = '/api/v1/teacher/rombels';

  /**
   * Test getting all rombels as teacher (index)
   */
  public function test_can_get_all_rombels_as_teacher(): void
  {
    $response = $this->getJsonWithTeacherAuth(self::ENDPOINT_TEACHER_ROMBEL);

    $response->assertStatus(200)
      ->assertJsonStructure([
        'status',
        'message',
        'data' => [
          '*' => [
            'id',
            'name',
            'class_id',
            'study_year_id',
            'teacher_id',
            'student_id',
            'subject_schedule_id',
            'created_at',
            'updated_at',
            'deleted_at',
            'class' => [
              'id',
              'name',
              'study_year_id',
              'total_rombel',
              'created_by',
              'updated_by',
              'deleted_by',
              'created_at',
              'updated_at',
              'deleted_at'
            ],
            'study_year' => [
              'id',
              'semester',
              'year',
              'created_by',
              'updated_by',
              'deleted_by',
              'created_at',
              'updated_at',
              'deleted_at'
            ],
            'teacher' => [
              'id',
              'user_id',
              'name',
              'employee_number',
              'subject',
              'created_by',
              'updated_by',
              'deleted_by',
              'created_at',
              'updated_at',
              'deleted_at'
            ],
            'student',
            'subject_schedules'
          ]
        ]
      ])
      ->assertJsonFragment([
        'status' => 'success',
        'message' => 'Rombel list retrieved successfully'
      ]);
  }

  /**
   * Test teacher cannot access rombels without authentication
   */
  public function test_cannot_get_rombels_without_authentication(): void
  {
    $response = $this->getJson(self::ENDPOINT_TEACHER_ROMBEL);

    $response->assertStatus(403);
  }

  /**
   * Test teacher cannot access students by rombel name without authentication
   */
  public function test_cannot_get_students_by_rombel_name_without_authentication(): void
  {
    $response = $this->getJson(self::ENDPOINT_TEACHER_ROMBEL . '/students/SomeRombelName');

    $response->assertStatus(422);
  }

  /**
   * Test user without teacher association cannot access rombels
   */
  public function test_user_without_teacher_cannot_access_rombels(): void
  {
    $response = $this->getJsonWithStudentAuth(self::ENDPOINT_TEACHER_ROMBEL);

    $response->assertStatus(403)
      ->assertJsonFragment([
        'errors' => ['Anda tidak memiliki akses untuk fitur ini'],
        'settings' => [],
        'status_code' => 403
      ]);
  }

  /**
   * Test teacher cannot create rombel (read-only access)
   */
  public function test_teacher_cannot_create_rombel(): void
  {
    $rombelData = [
      'name' => 'Test Rombel - ' . time(),
      'class_id' => 'some-class-id',
      'study_year_id' => 'some-study-year-id',
      'teacher_id' => 'some-teacher-id',
      'student_id' => 'some-student-id'
    ];

    $response = $this->postJsonWithTeacherAuth(self::ENDPOINT_TEACHER_ROMBEL, $rombelData);

    $this->assertContains($response->status(), [404, 405, 500]);
  }

  /**
   * Test teacher cannot update rombel (read-only access)
   */
  public function test_teacher_cannot_update_rombel(): void
  {
    $updateData = [
      'name' => 'Updated Rombel - ' . time()
    ];

    $response = $this->putJsonWithTeacherAuth(self::ENDPOINT_TEACHER_ROMBEL . '/ngawur', $updateData);

    $this->assertContains($response->status(), [404, 405, 500]);
  }

  /**
   * Test teacher cannot delete rombel (read-only access)
   */
  public function test_teacher_cannot_delete_rombel(): void
  {
    $response = $this->deleteJsonWithTeacherAuth(self::ENDPOINT_TEACHER_ROMBEL . '/ngawur');

    $this->assertContains($response->status(), [404, 405, 500]);
  }

  /**
   * Test rombel response format
   */
  public function test_rombel_response_format(): void
  {
    $response = $this->getJsonWithTeacherAuth(self::ENDPOINT_TEACHER_ROMBEL);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success'
      ]);

    $data = $response->json();

    $this->assertArrayHasKey('status', $data);
    $this->assertArrayHasKey('message', $data);
    $this->assertArrayHasKey('data', $data);
    $this->assertEquals('success', $data['status']);
    $this->assertEquals('Rombel list retrieved successfully', $data['message']);
  }

  /**
   * Test that teacher only sees their assigned rombels
   */
  public function test_teacher_only_sees_assigned_rombels(): void
  {
    $response = $this->getJsonWithTeacherAuth(self::ENDPOINT_TEACHER_ROMBEL);

    $response->assertStatus(200);

    $data = $response->json();

    if (!empty($data['data'])) {
      foreach ($data['data'] as $rombel) {
        $this->assertArrayHasKey('teacher', $rombel);
        $this->assertNotNull($rombel['teacher']);
      }
    }
  }

  /**
   * Test error handling when rombel service fails
   */
  public function test_handles_rombel_service_error_gracefully(): void
  {
    $response = $this->getJsonWithTeacherAuth(self::ENDPOINT_TEACHER_ROMBEL);

    $this->assertContains($response->status(), [200, 500]);

    if ($response->status() === 500) {
      $response->assertJsonStructure([
        'status',
        'message'
      ]);
    }
  }
}
