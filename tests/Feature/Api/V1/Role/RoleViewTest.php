<?php

namespace Tests\Feature\Api\V1\Role;

use App\Domain\Role\RoleEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class RoleViewTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $roles = [
            new RoleEntity(1, 'aabb', 1, 'Product', 'Access', ['product.view'])
        ];
        foreach ($roles as $role) {
            $this->roleRepository->getMocker()
                ->getSimulation('getByUuid')
                ->whenInputReturn($role, [$role->getUuid()]);
            $this->roleRepository->getMocker()
                ->getSimulation('exists')
                ->whenInputReturn(true, [$role->getUuid()]);
        }
    }

    public function testResponseOk()
    {
        $response = $this->get('api/v1/roles/aabb');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'uuid' => 'aabb',
            'domain' => 'Product',
            'name' => 'Access',
            'permissions' => ['product.view']
        ]);
    }

    public function testResponseNotFound()
    {
        $response = $this->get('api/v1/roles/0011');

        $response->assertStatus(404);
        $response->assertJson([]);
    }
}
