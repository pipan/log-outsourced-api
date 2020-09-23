<?php

namespace Tests\Feature\Api\V1\Role;

use App\Domain\Role\RoleEntity;
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
        $response = $this->get('api/v1/roles?project_uuid=aabb');

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    public function testResponseOkNotEmpty()
    {
        $this->roleRepository->getMocker()
            ->getSimulation('getForProject')
            ->whenInputReturn([
                new RoleEntity(1, 'aabb', 1, 'Product', 'Access', ['product.view'])
            ], [1, ['limit' => 25, 'page' => 1, 'search' => '']]);

        $response = $this->get('api/v1/roles?project_uuid=aabb');

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment([
            [
                'uuid' => 'aabb',
                'domain' => 'Product',
                'name' => 'Access',
                'permissions' => ['product.view']
            ]
        ]);
    }
}
