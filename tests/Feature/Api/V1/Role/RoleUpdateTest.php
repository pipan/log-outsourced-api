<?php

namespace Tests\Feature\Api\V1\Role;

use App\Domain\Role\RoleEntity;
use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class RoleUpdateTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        RoleTestSeeder::seed($this->roleRepository);
    }

    public function getInvalidRequests()
    {
        return RoleRequests::getInvalidForUpdates();
    }

    public function getValidRequests()
    {
        return RoleRequests::getValidForUpdates();
    }

    /**
     * @dataProvider getValidRequests
     */
    public function testResponseOk($requestData)
    {
        $response = $this->put('api/v1/roles/aabb', $requestData, AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJsonFragment($requestData);
    }

    public function testResponseNotFound()
    {
        $response = $this->put('api/v1/roles/0011', [
            'name' => 'View',
            'permissions' => ['products.view']
        ], AuthHeaders::authorize());

        $response->assertStatus(404);
        $response->assertJsonStructure(['message']);
    }

    /**
     * @dataProvider getInvalidRequests
     */
    public function testResponseValidationError($requestData)
    {
        $response = $this->put('api/v1/roles/aabb', $requestData, AuthHeaders::authorize());

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors', 'message']);
    }

    public function testResponseUnauthorized()
    {
        $response = $this->put('api/v1/roles/aabb', [
            'name' => 'test',
            'permissions' => ['test']
        ]);

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}
