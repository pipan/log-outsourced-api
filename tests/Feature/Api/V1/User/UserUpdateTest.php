<?php

namespace Tests\Feature\Api\V1\User;

use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class UserUpdateTest extends ControllerActionTestCase
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
            'roles' => ['user']
        ], AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJsonStructure(['uuid', 'username', 'roles']);
        $response->assertJsonFragment([
            'username' => 'admin',
            'roles' => ['user']
        ]);
    }

    public function testResponseNotFound()
    {
        $response = $this->put('/api/v1/users/0123', [
            'roles' => [1]
        ], AuthHeaders::authorize());

        $response->assertStatus(404);
        $response->assertJsonStructure(['message']);
    }

    /**
     * @dataProvider getAllInvalidRequestData
     */
    public function testResponseValidationError($requestData)
    {
        $response = $this->put('/api/v1/users/aabb', $requestData, AuthHeaders::authorize());

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors', 'message']);
    }

    public function testResponseUnauthorized()
    {
        $response = $this->put('api/v1/users/aabb', [
            'roles' => ['user']
        ]);

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}