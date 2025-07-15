<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Tests\Traits\AuthTrait;

class SPPTest extends TestCase
{
  use AuthTrait;

  protected const ENDPOINT_ADMIN_SPP = '/api/v1/admin/spp';

  /**
   * Test getting all SPP (index)
   */
  public function test_can_get_all_spp(): void
  {
    $response = $this->getJsonWithAdminAuth(self::ENDPOINT_ADMIN_SPP);

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
   * Test creating a new SPP
   */
  public function test_can_create_spp(): void
  {
    $sppData = [
      'name' => 'Test SPP - ' . time(),
      'jenis_biaya' => 'Reguler',
      'study_year_id' => '8d3d91b9-b58d-4687-929b-4338d54f9ea8',
      'total' => 2500000
    ];

    $response = $this->postJsonWithAdminAuth(self::ENDPOINT_ADMIN_SPP, $sppData);

    $response->assertStatus(201)
      ->assertJsonFragment([
        'name' => $sppData['name'],
        'jenis_biaya' => $sppData['jenis_biaya'],
        'total' => $sppData['total']
      ]);
  }

  /**
   * Test getting a specific SPP
   */
  public function test_can_show_spp(): void
  {
    $sppData = [
      'name' => 'Test Show SPP - ' . time(),
      'jenis_biaya' => 'Mandiri',
      'study_year_id' => '8d3d91b9-b58d-4687-929b-4338d54f9ea8',
      'total' => 3000000
    ];

    $createResponse = $this->postJsonWithAdminAuth(self::ENDPOINT_ADMIN_SPP, $sppData);
    $createResponse->assertStatus(201);

    $createdSPP = $createResponse->json();
    $response = $this->getJsonWithAdminAuth("/api/v1/admin/spp/{$createdSPP['id']}");

    $response->assertStatus(200)
      ->assertJsonFragment([
        'id' => $createdSPP['id'],
        'name' => $sppData['name'],
        'jenis_biaya' => $sppData['jenis_biaya'],
        'study_year_id' => $sppData['study_year_id'],
        'total' => $sppData['total']
      ]);
  }

  /**
   * Test updating a SPP
   */
  public function test_can_update_spp(): void
  {
    $sppData = [
      'name' => 'Original SPP - ' . time(),
      'jenis_biaya' => 'Reguler',
      'study_year_id' => '8d3d91b9-b58d-4687-929b-4338d54f9ea8',
      'total' => 2000000
    ];

    $createResponse = $this->postJsonWithAdminAuth(self::ENDPOINT_ADMIN_SPP, $sppData);
    $createResponse->assertStatus(201);

    $createdSPP = $createResponse->json();
    $updateData = [
      'name' => 'Updated SPP - ' . time(),
      'jenis_biaya' => 'Mandiri',
      'study_year_id' => '8d3d91b9-b58d-4687-929b-4338d54f9ea8',
      'total' => 3500000
    ];

    $response = $this->putJsonWithAdminAuth("/api/v1/admin/spp/{$createdSPP['id']}", $updateData);
    $response->assertStatus(200);

    $showResponse = $this->getJsonWithAdminAuth("/api/v1/admin/spp/{$createdSPP['id']}");
    $showResponse->assertJsonFragment([
      'name' => $updateData['name'],
      'jenis_biaya' => $updateData['jenis_biaya'],
      'total' => $updateData['total']
    ]);
  }

  /**
   * Test deleting a SPP
   */
  public function test_can_delete_spp(): void
  {
    $sppData = [
      'name' => 'SPP to Delete - ' . time(),
      'jenis_biaya' => 'Reguler',
      'study_year_id' => '8d3d91b9-b58d-4687-929b-4338d54f9ea8',
      'total' => 2200000
    ];

    $createResponse = $this->postJsonWithAdminAuth(self::ENDPOINT_ADMIN_SPP, $sppData);
    $createResponse->assertStatus(201);

    $createdSPP = $createResponse->json();
    $response = $this->deleteJsonWithAdminAuth("/api/v1/admin/spp/{$createdSPP['id']}");

    $response->assertStatus(200)
      ->assertJsonFragment([
        'status' => true,
        'message' => 'SPP deleted successfully'
      ]);

    $showResponse = $this->getJsonWithAdminAuth("/api/v1/admin/spp/{$createdSPP['id']}");
    $showResponse->assertStatus(404);
  }

  /**
   * Test validation when creating SPP with invalid data
   */
  public function test_cannot_create_spp_with_invalid_data(): void
  {
    $response = $this->postJsonWithAdminAuth(self::ENDPOINT_ADMIN_SPP, []);

    $response->assertStatus(422)
      ->assertJsonStructure([
        'message',
        'errors' => [
          'name',
          'jenis_biaya',
          'study_year_id',
          'total'
        ]
      ]);
  }

  /**
   * Test validation for jenis_biaya field
   */
  public function test_cannot_create_spp_with_invalid_jenis_biaya(): void
  {
    $sppData = [
      'name' => 'Test SPP Invalid Jenis - ' . time(),
      'jenis_biaya' => 'InvalidType',
      'study_year_id' => '8d3d91b9-b58d-4687-929b-4338d54f9ea8',
      'total' => 2500000
    ];

    $response = $this->postJsonWithAdminAuth(self::ENDPOINT_ADMIN_SPP, $sppData);

    $response->assertStatus(422)
      ->assertJsonStructure([
        'message',
        'errors' => [
          'jenis_biaya'
        ]
      ]);
  }

  /**
   * Test validation for total field
   */
  public function test_cannot_create_spp_with_invalid_total(): void
  {
    $sppData = [
      'name' => 'Test SPP Invalid Total - ' . time(),
      'jenis_biaya' => 'Reguler',
      'study_year_id' => '8d3d91b9-b58d-4687-929b-4338d54f9ea8',
      'total' => 'not-a-number'
    ];

    $response = $this->postJsonWithAdminAuth(self::ENDPOINT_ADMIN_SPP, $sppData);

    $response->assertStatus(422)
      ->assertJsonStructure([
        'message',
        'errors' => [
          'total'
        ]
      ]);
  }

  /**
   * Test validation for study_year_id field
   */
  public function test_cannot_create_spp_with_invalid_study_year(): void
  {
    $sppData = [
      'name' => 'Test SPP Invalid Study Year - ' . time(),
      'jenis_biaya' => 'Reguler',
      'study_year_id' => 'non-existent-uuid',
      'total' => 2500000
    ];

    $response = $this->postJsonWithAdminAuth(self::ENDPOINT_ADMIN_SPP, $sppData);

    $response->assertStatus(422)
      ->assertJsonStructure([
        'message',
        'errors' => [
          'study_year_id'
        ]
      ]);
  }
}
