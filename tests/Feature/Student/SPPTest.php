<?php

namespace Tests\Feature\Student;

use Tests\TestCase;
use Tests\Traits\AuthTrait;

class SPPTest extends TestCase
{
  use AuthTrait;

  protected const ENDPOINT_STUDENT_SPP = '/api/v1/student/spp';

  /**
   * Test getting all SPP as student (index)
   */
  public function test_can_get_all_spp_as_student(): void
  {
    $response = $this->getJsonWithStudentAuth(self::ENDPOINT_STUDENT_SPP);

    $response->assertStatus(200)
      ->assertJsonStructure([
        'current_page',
        'data' => [
          '*' => [
            'id',
            'name',
            'jenis_biaya',
            'study_year_id',
            'total',
            'created_at',
            'updated_at',
            'deleted_at',
            'created_by',
            'updated_by',
            'deleted_by',
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
            ]
          ]
        ],
        'first_page_url',
        'from',
        'last_page',
        'last_page_url',
        'links' => [
          '*' => [
            'url',
            'label',
            'active'
          ]
        ],
        'next_page_url',
        'path',
        'per_page',
        'prev_page_url',
        'to',
        'total'
      ]);
  }

  /**
   * Test student cannot access SPP without authentication
   */
  public function test_cannot_get_spp_without_authentication(): void
  {
    $response = $this->getJson(self::ENDPOINT_STUDENT_SPP);

    $response->assertStatus(403);
  }

  /**
   * Test student cannot create SPP (should not have this endpoint)
   */
  public function test_student_cannot_create_spp(): void
  {
    $sppData = [
      'name' => 'Test SPP - ' . time(),
      'jenis_biaya' => 'Reguler',
      'study_year_id' => '8d3d91b9-b58d-4687-929b-4338d54f9ea8',
      'total' => 2500000
    ];

    $response = $this->postJsonWithStudentAuth(self::ENDPOINT_STUDENT_SPP, $sppData);

    $this->assertContains($response->status(), [404, 405, 500]);
  }

  /**
   * Test student cannot update SPP (should not have this endpoint)
   */
  public function test_student_cannot_update_spp(): void
  {
    $updateData = [
      'name' => 'Updated SPP - ' . time(),
      'jenis_biaya' => 'Mandiri',
      'study_year_id' => '8d3d91b9-b58d-4687-929b-4338d54f9ea8',
      'total' => 3500000
    ];

    $response = $this->putJsonWithStudentAuth(self::ENDPOINT_STUDENT_SPP . '/some-id', $updateData);

    $this->assertContains($response->status(), [404, 405, 500]);
  }

  /**
   * Test student cannot delete SPP (should not have this endpoint)
   */
  public function test_student_cannot_delete_spp(): void
  {
    $response = $this->deleteJsonWithStudentAuth(self::ENDPOINT_STUDENT_SPP . '/some-id');

    $this->assertContains($response->status(), [404, 405, 500]);
  }

  /**
   * Test that SPP data includes study year relationship
   */
  public function test_spp_includes_study_year_relationship(): void
  {
    $response = $this->getJsonWithStudentAuth(self::ENDPOINT_STUDENT_SPP);
    $response->assertStatus(200);
    $data = $response->json();

    if (!empty($data['data'])) {
      $this->assertArrayHasKey('study_year', $data['data'][0]);
      $this->assertIsArray($data['data'][0]['study_year']);
    }
  }

  /**
   * Test pagination structure for student SPP endpoint
   */
  public function test_spp_pagination_structure(): void
  {
    $response = $this->getJsonWithStudentAuth(self::ENDPOINT_STUDENT_SPP);

    $response->assertStatus(200)
      ->assertJsonStructure([
        'current_page',
        'per_page',
        'total',
        'last_page',
        'from',
        'to',
        'path',
        'first_page_url',
        'last_page_url',
        'next_page_url',
        'prev_page_url'
      ]);
  }

  /**
   * Test that student can access specific page of SPP data
   */
  public function test_can_access_paginated_spp_data(): void
  {
    $response = $this->getJsonWithStudentAuth(self::ENDPOINT_STUDENT_SPP . '?page=1');

    $response->assertStatus(200)
      ->assertJsonFragment([
        'current_page' => 1
      ]);
  }

  /**
   * Test error handling when SPP service fails
   */
  public function test_handles_spp_service_error_gracefully(): void
  {
    $response = $this->getJsonWithStudentAuth(self::ENDPOINT_STUDENT_SPP);

    $this->assertContains($response->status(), [200, 500]);

    if ($response->status() === 500) {
      $response->assertJsonStructure([
        'status',
        'message',
        'error'
      ]);
    }
  }
}
