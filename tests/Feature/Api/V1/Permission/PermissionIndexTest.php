<?php

namespace Tests\Feature\Api\V1\Permission;

use App\Domain\Permission\PermissionEntity;
use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;
use Tests\Feature\Api\V1\Project\ProjectTestSeeder;

class PermissionIndexTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ProjectTestSeeder::seed($this->projectRepository);
    }

    public function testResponseOkEmptyItems()
    {    
        $this->permissionRepository->getMocker()
            ->getSimulation('getAllForProject')
            ->whenInputReturn([], [1]);
        $response = $this->get('api/v1/permissions?project_uuid=aabb', AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJson([
            'items' => []
        ]);
    }

    public function testResponseOk()
    {
        $permission = new PermissionEntity([
            'id' => 1,
            'project_id' => 1,
            'name' => 'user.view'
        ]);
        $this->permissionRepository->getMocker()
            ->getSimulation('getAllForProject')
            ->whenInputReturn([$permission], [1]);
        $response = $this->get('api/v1/permissions?project_uuid=aabb', AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJson([
            'items' => [[
                'name' => "user.view"
            ]]
        ]);
    }

    public function testResponseUnauthorized()
    {
        $this->permissionRepository->getMocker()
            ->getSimulation('getAllForProject')
            ->whenInputReturn([], [1]);
        $response = $this->get('api/v1/permissions?project_uuid=aabb');

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}
