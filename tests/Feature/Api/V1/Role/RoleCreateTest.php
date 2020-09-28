<?php

namespace Tests\Feature\Api\V1\Role;

use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;
use Tests\Feature\Api\V1\Project\ProjectTestSeeder;

class RoleCreateTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ProjectTestSeeder::seed($this->projectRepository);
        RoleTestSeeder::seed($this->roleRepository);
    }

    public function getInvalidRequests()
    {
        return RoleRequests::forCreation();
    }

    public function testResponseOk()
    {
        $response = $this->post('api/v1/roles', [
            'project_uuid' => 'aabb',
            'name' => 'Manage',
            'permissions' => ['product.manage']
        ], AuthHeaders::authorize());

        $response->assertStatus(201);
        $response->assertHeader('Location');
        $response->assertJsonStructure([
            'uuid', 'name', 'permissions'
        ]);
        $response->assertJsonFragment([
            'name' => 'Manage',
            'permissions' => ['product.manage']
        ]);
    }

    /**
     * @dataProvider getInvalidRequests
     */
    public function testResponseValidationError($requestData)
    {
        $response = $this->post('api/v1/roles', $requestData, AuthHeaders::authorize());

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors', 'message']);
    }

    public function testResponseUnauthorized()
    {
        $response = $this->get('api/v1/roles');

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}
