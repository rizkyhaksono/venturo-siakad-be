<?php

namespace Tests\Feature\Student;

use Tests\TestCase;
use Tests\Traits\AuthTrait;

class RombelTest extends TestCase
{
  use AuthTrait;

  protected const ENDPOINT_STUDENT_ROMBEL = "/api/v1/student/rombels";

  /**
   * Test getting all rombels (index)
   */
  public function test_can_get_all_rombels(): void
  {
    $response = $this->getJsonWithStudentAuth(self::ENDPOINT_STUDENT_ROMBEL);

    $response->assertStatus(200)
      ->assertJson([
        'status' => 'success',
        'message' => 'Rombel list retrieved successfully'
      ])
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
            'student' => [
              'id',
              'user_id',
              'name',
              'student_number',
              'status',
              'created_by',
              'updated_by',
              'deleted_by',
              'created_at',
              'updated_at',
              'deleted_at'
            ],
            'subject_schedules'
          ]
        ]
      ]);
  }

  /**
   * Test that endpoint requires authentication
   */
  public function test_rombel_endpoint_requires_authentication(): void
  {
    $response = $this->getJson(self::ENDPOINT_STUDENT_ROMBEL);

    $response->assertStatus(403);
  }

  /**
   * Test getting rombels with invalid token
   */
  public function test_rombel_endpoint_rejects_invalid_token(): void
  {
    $response = $this->getJson(self::ENDPOINT_STUDENT_ROMBEL, [
      'Authorization' => 'Bearer invalid-token'
    ]);

    $response->assertStatus(403);
  }
}
