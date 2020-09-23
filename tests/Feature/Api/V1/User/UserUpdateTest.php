<?php

namespace Tests\Feature\Api\V1\User;

use Tests\Feature\Api\V1\ControllerActionTestCase;

class UserUpdateController extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        UserTestSeeder::seed($this->userRepository);
    }

    public function getAllInvalidRequestData()
    {
        return UserRequests::getRolesInvalid();
    }

    public function testResponseOk()
    {
        $response = $this->put('/api/v1/users/aabb', [
            'roles' => [1]
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['uuid', 'username', 'roles']);
        $response->assertJsonFragment([
            'username' => 'test@example.com',
            'roles' => [1]
        ]);
    }

    public function testResponseNotFound()
    {
        $response = $this->put('/api/v1/users/0123', [
            'roles' => [1]
        ]);

        $response->assertStatus(404);
    }

    /**
     * @dataProvider getAllInvalidRequestData
     */
    public function testResponseValidationError($requestData)
    {
        $response = $this->put('/api/v1/users/aabb', $requestData);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }
}