<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Tests\Traits\AuthTrait;

class ClassTest extends TestCase
{
    use AuthTrait;

    protected const ENDPOINT_ADMIN_CLASS = '/api/v1/admin/classes';

    /**
     * Test getting all classes (index)
     */
    public function test_can_get_all_classes(): void
    {
        $response = $this->getJsonWithAuth(self::ENDPOINT_ADMIN_CLASS);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'study_year_id',
                        'created_by',
                        'updated_by',
                        'deleted_by',
                        'created_at',
                        'updated_at',
                        'deleted_at',
                        'study_year' => [
                            'year',
                            'semester',
                            'created_by',
                            'updated_by',
                            'deleted_by',
                            'created_at',
                            'updated_at',
                            'deleted_at'
                        ]
                    ]
                ],
                'links' => [
                    'first',
                    'last',
                    'prev',
                    'next'
                ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'links' => [
                        '*' => [
                            'url',
                            'label',
                            'active'
                        ]
                    ],
                    'path',
                    'per_page',
                    'to',
                    'total'
                ]
            ]);
    }

    /**
     * Test creating a new class
     */
    public function test_can_create_class(): void
    {
        $classData = [
            'name' => 'Test Class - ' . time(),
            'description' => 'Test Description',
            'study_year_id' => '8d3d91b9-b58d-4687-929b-4338d54f9ea8'
        ];

        $response = $this->postJsonWithAuth(self::ENDPOINT_ADMIN_CLASS, $classData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => $classData['name']
            ]);
    }

    /**
     * Test getting a specific class
     */
    public function test_can_show_class(): void
    {
        // First create a class
        $classData = [
            'name' => 'Test Show Class - ' . time(),
            'description' => 'Test Description',
            'study_year_id' => '8d3d91b9-b58d-4687-929b-4338d54f9ea8'
        ];

        $createResponse = $this->postJsonWithAuth(self::ENDPOINT_ADMIN_CLASS, $classData);
        $createResponse->assertStatus(201);

        $createdClass = $createResponse->json();

        $response = $this->getJsonWithAuth("/api/v1/admin/classes/{$createdClass['id']}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $createdClass['id'],
                'name' => $classData['name'],
                'study_year_id' => $classData['study_year_id'],
            ]);
    }

    /**
     * Test updating a class
     */
    public function test_can_update_class(): void
    {
        // First create a class
        $classData = [
            'name' => 'Original Class - ' . time(),
            'description' => 'Original Description',
            'study_year_id' => '8d3d91b9-b58d-4687-929b-4338d54f9ea8'
        ];

        $createResponse = $this->postJsonWithAuth(self::ENDPOINT_ADMIN_CLASS, $classData);
        $createResponse->assertStatus(201);

        $createdClass = $createResponse->json();

        // Then update it
        $updateData = [
            'name' => 'Updated Class - ' . time(),
            'description' => 'Updated Description',
            'study_year_id' => '8d3d91b9-b58d-4687-929b-4338d54f9ea8'
        ];

        $response = $this->putJsonWithAuth("/api/v1/admin/classes/{$createdClass['id']}", $updateData);

        $response->assertStatus(200);

        // Verify the update
        $showResponse = $this->getJsonWithAuth("/api/v1/admin/classes/{$createdClass['id']}");
        $showResponse->assertJsonFragment([
            'name' => $updateData['name']
        ]);
    }

    /**
     * Test deleting a class
     */
    public function test_can_delete_class(): void
    {
        // First create a class
        $classData = [
            'name' => 'Class to Delete - ' . time(),
            'description' => 'Description to delete',
            'study_year_id' => '8d3d91b9-b58d-4687-929b-4338d54f9ea8'
        ];

        $createResponse = $this->postJsonWithAuth(self::ENDPOINT_ADMIN_CLASS, $classData);
        $createResponse->assertStatus(201);

        $createdClass = $createResponse->json();

        // Then delete it
        $response = $this->deleteJsonWithAuth("/api/v1/admin/classes/{$createdClass['id']}");

        $response->assertStatus(204);

        // Verify it's deleted (should return 404)
        $showResponse = $this->getJsonWithAuth("/api/v1/admin/classes/{$createdClass['id']}");
        $showResponse->assertStatus(404);
    }

    /**
     * Test validation when creating class with invalid data
     */
    public function test_cannot_create_class_with_invalid_data(): void
    {
        $response = $this->postJsonWithAuth(self::ENDPOINT_ADMIN_CLASS, []);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'status',
                'message',
                'errors' => [
                    'name',
                    'study_year_id'
                ]
            ]);
    }

    /**
     * Test search functionality if available
     */
    public function test_can_search_classes(): void
    {
        // Create a class with specific name
        $uniqueName = 'Unique Test Class - ' . time();
        $this->postJsonWithAuth(self::ENDPOINT_ADMIN_CLASS, [
            'name' => $uniqueName,
            'description' => 'Test Description',
            'study_year_id' => '8d3d91b9-b58d-4687-929b-4338d54f9ea8'
        ]);

        // Search for the class
        $response = $this->getJsonWithAuth('/api/v1/admin/classes?search=' . urlencode($uniqueName));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => $uniqueName
            ]);
    }
}
