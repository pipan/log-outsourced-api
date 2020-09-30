<?php

namespace Tests\Feature\Api\V1\Permission;

use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;
use Tests\Feature\Api\V1\Project\ProjectTestSeeder;

class PermissionCreateTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ProjectTestSeeder::seed($this->projectRepository);
        PermissionTestSeeder::seed($this->permissionRepository);
    }

    public function getInvalidRequests()
    {
        return PermissionRequests::getInvalidForCreation();
    }

    public function testResponseOk()
    {    
        $response = $this->post('api/v1/permissions', [
            'project_uuid' => 'aabb',
            'name' => 'user.delete'
        ], AuthHeaders::authorize());

        $execution = $this->permissionRepository->getMocker()
            ->getSimulation('insert')
            ->getExecutions();

        $response->assertStatus(201);
        $response->assertJson([
            'name' => 'user.delete'
        ]);
        $this->assertCount(1, $execution);
    }

    /**
     * @dataProvider getInvalidRequests
     */
    public function testResponseValidationError($requestData)
    {
        $response = $this->post('api/v1/permissions', $requestData, AuthHeaders::authorize());

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors', 'message']);
    }

    public function testResponseUnauthorized()
    {
        $response = $this->post('api/v1/permissions', [
            'project_uuid' => 'aabb',
            'name' => 'user.delete'
        ]);

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}
