<?php

namespace Tests\Feature\Api\V1\Role;

use App\Domain\Role\RoleEntity;
use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;
use Tests\Feature\Api\V1\Project\ProjectTestSeeder;

class RoleIndexTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ProjectTestSeeder::seed($this->projectRepository);
    }

    public function testResponseOkEmpty()
    {
        $response = $this->get('api/v1/roles?project_uuid=aabb', AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    public function testResponseOkNotEmpty()
    {
        $role = new RoleEntity([
            'id' => 1,
            'uuid' => 'aabb',
            'project_id' => 1,
            'name' => 'Product.Access',
            'permissions' => ['product.view']
        ]);
        $this->roleRepository->getMocker()
            ->getSimulation('getForProject')
            ->whenInputReturn([$role], [1, ['limit' => 25, 'page' => 1, 'search' => '']]);

        $response = $this->get('api/v1/roles?project_uuid=aabb', AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment([
            [
                'uuid' => 'aabb',
                'name' => 'Product.Access',
                'permissions' => ['product.view']
            ]
        ]);
    }

    public function testResponseUnauthorized()
    {
        $response = $this->get('api/v1/roles?project_uuid=aabb');

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}
