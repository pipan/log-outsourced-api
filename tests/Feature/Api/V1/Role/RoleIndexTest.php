<?php

namespace Tests\Feature\Api\V1\Role;

use App\Domain\Role\RoleEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class RoleIndexTest extends ControllerActionTestCase
{
    // public function setUp(): void
    // {
    //     parent::setUp();

    //     $administrators = [
    //         $this->createUser('1', 'root', 'root')
    //     ];
    //     foreach ($administrators as $administrator) {
    //         $this->administratorRepository->getMocker()
    //             ->getSimulation('getByUsername')
    //             ->whenInputReturn($administrator, [$administrator->getUsername()]);
    //         $this->administratorRepository->getMocker()
    //             ->getSimulation('exists')
    //             ->whenInputReturn(true, [$administrator->getUsername()]);
    //     }
    // }

    public function testResponseOkEmpty()
    {
        $response = $this->get('api/v1/roles');

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function testResponseOkNotEmpty()
    {
        $this->roleRepository->getMocker()
            ->getSimulation('getAll')
            ->whenInputReturn([
                new RoleEntity(1, 'aabb', 'Product', 'Access', ['product.view'])
            ]);

        $response = $this->get('api/v1/roles');

        $response->assertStatus(200);
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
