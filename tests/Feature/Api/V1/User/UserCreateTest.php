<?php

namespace Tests\Feature\Api\V1\User;

use Tests\Feature\Api\V1\ControllerActionTestCase;
use Tests\Feature\Api\V1\Project\ProjectTestSeeder;

class UserCreateController extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ProjectTestSeeder::seed($this->projectRepository);
        UserTestSeeder::seed($this->userRepository);
    }

    public function getAllInvalidRequestData()
    {
        return UserRequests::getAllInvalid();
    }

    public function testResponseOk()
    {
        $response = $this->post('/api/v1/users', [
            'project_uuid' => 'aabb',
            'username' => 'valid@example.com'
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['uuid', 'username', 'roles']);
        $response->assertJsonFragment([
            'username' => 'valid@example.com',
            'roles' => []
        ]);
    }

    public function testResponseOkWithPermissions()
    {
        $response = $this->post('/api/v1/users', [
            'project_uuid' => 'aabb',
            'username' => 'valid@example.com',
            'roles' => [1]
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['uuid', 'username', 'roles']);
        $response->assertJsonFragment([
            'username' => 'valid@example.com',
            'roles' => [1]
        ]);
    }

    /**
     * @dataProvider getAllInvalidRequestData
     */
    public function testResponseValidationError($requestData)
    {
        $response = $this->post('/api/v1/users', $requestData);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }
}