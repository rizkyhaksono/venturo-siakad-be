<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Tests\Traits\AuthTrait;

class RombelTest extends TestCase
{
  use AuthTrait;

  protected const ENDPOINT_ADMIN_ROMBEL = '/api/v1/admin/rombels';

  /**
   * Test getting all rombels (index)
   */
  public function test_can_get_all_rombels(): void
  {
    $response = $this->getJsonWithAdminAuth(self::ENDPOINT_ADMIN_ROMBEL);

    $response->assertStatus(200)
      ->assertJsonStructure([
        'status',
        'message',
        'data' => [
          '*' => [
            'id',
            'name',
            'class' => [
              'id',
              'name'
            ],
            'study_year' => [
              'id',
              'semester',
              'year'
            ],
            'teacher' => [
              'id',
              'name',
              'employee_number'
            ],
            'subject_schedules',
            'created_at',
            'updated_at',
            'students' => [
              '*' => [
                'id',
                'name',
                'student_number',
                'status'
              ]
            ]
          ]
        ]
      ]);
  }

  /**
   * Test creating a new rombel
   */
  public function test_can_create_rombel(): void
  {
    $rombelData = [
      'nama' => 'Test Rombel - ' . time(),
      'kelas' => '9f41b1d8-22b4-4360-b79b-f9eef94b2a5f',
      'tahunAjaran' => '8d3d91b9-b58d-4687-929b-4338d54f9ea8',
      'waliKelas' => '9f41b29c-eef4-411f-8bc8-2fe3cd737c6b'
    ];

    $response = $this->postJsonWithAdminAuth(self::ENDPOINT_ADMIN_ROMBEL, $rombelData);

    $response->assertStatus(201)
      ->assertJsonFragment([
        'name' => $rombelData['nama']
      ]);
  }

  /**
   * Test getting a specific rombel
   */
  public function test_can_show_rombel(): void
  {
    $rombelData = [
      'nama' => 'Test Show Rombel - ' . time(),
      'kelas' => '9f41b1d8-22b4-4360-b79b-f9eef94b2a5f',
      'tahunAjaran' => '8d3d91b9-b58d-4687-929b-4338d54f9ea8',
      'waliKelas' => '9f41b29c-eef4-411f-8bc8-2fe3cd737c6b'
    ];

    $createResponse = $this->postJsonWithAdminAuth(self::ENDPOINT_ADMIN_ROMBEL, $rombelData);
    $createResponse->assertStatus(201);

    $createdRombel = $createResponse->json('data');
    $response = $this->getJsonWithAdminAuth("/api/v1/admin/rombels/{$createdRombel['id']}");

    $response->assertStatus(200)
      ->assertJsonFragment([
        'id' => $createdRombel['id'],
        'name' => $rombelData['nama']
      ]);
  }

  /**
   * Test updating a rombel
   */
  public function test_can_update_rombel(): void
  {
    // First create a rombel
    $rombelData = [
      'nama' => 'Original Rombel - ' . time(),
      'kelas' => '9f41b1d8-22b4-4360-b79b-f9eef94b2a5f',
      'tahunAjaran' => '8d3d91b9-b58d-4687-929b-4338d54f9ea8',
      'waliKelas' => '9f41b29c-eef4-411f-8bc8-2fe3cd737c6b'
    ];

    $createResponse = $this->postJsonWithAdminAuth(self::ENDPOINT_ADMIN_ROMBEL, $rombelData);
    $createResponse->assertStatus(201);

    $createdRombel = $createResponse->json('data');
    $updateData = [
      'nama' => 'Updated Rombel - ' . time(),
      'kelas' => '9f41b1d8-22b4-4360-b79b-f9eef94b2a5f',
      'tahunAjaran' => '8d3d91b9-b58d-4687-929b-4338d54f9ea8',
      'waliKelas' => '9f41b29c-eef4-411f-8bc8-2fe3cd737c6b'
    ];

    $response = $this->putJsonWithAdminAuth("/api/v1/admin/rombels/{$createdRombel['id']}", $updateData);
    $response->assertStatus(200);
    $showResponse = $this->getJsonWithAdminAuth("/api/v1/admin/rombels/{$createdRombel['id']}");
    $showResponse->assertJsonFragment([
      'name' => $updateData['nama']
    ]);
  }

  /**
   * Test deleting a rombel
   */
  public function test_can_delete_rombel(): void
  {
    $rombelData = [
      'nama' => 'Rombel to Delete - ' . time(),
      'kelas' => '9f41b1d8-22b4-4360-b79b-f9eef94b2a5f',
      'tahunAjaran' => '8d3d91b9-b58d-4687-929b-4338d54f9ea8',
      'waliKelas' => '9f41b29c-eef4-411f-8bc8-2fe3cd737c6b'
    ];

    $createResponse = $this->postJsonWithAdminAuth(self::ENDPOINT_ADMIN_ROMBEL, $rombelData);
    $createResponse->assertStatus(201);

    $createdRombel = $createResponse->json('data');
    $response = $this->deleteJsonWithAdminAuth("/api/v1/admin/rombels/{$createdRombel['id']}");

    $response->assertStatus(200);
    $showResponse = $this->getJsonWithAdminAuth("/api/v1/admin/rombels/{$createdRombel['id']}");
    $showResponse->assertStatus(404);
  }

  /**
   * Test validation when creating rombel with invalid data
   */
  public function test_cannot_create_rombel_with_invalid_data(): void
  {
    $response = $this->postJsonWithAdminAuth(self::ENDPOINT_ADMIN_ROMBEL, []);

    $response->assertStatus(422)
      ->assertJsonStructure([
        'message',
        'errors' => [
          'kelas',
          'nama',
          'tahunAjaran',
          'waliKelas'
        ]
      ]);
  }
}
