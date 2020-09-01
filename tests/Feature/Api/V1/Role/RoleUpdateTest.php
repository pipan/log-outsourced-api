<?php

namespace Tests\Feature\Api\V1\Role;

use App\Domain\Role\RoleEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class RoleUpdateTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $roles = [
            new RoleEntity(1, 'aabb', 'Product', 'Access', ['product.view'])
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

    public function getAllInvalidRequestData()
    {
        return RoleRequests::getAllInvalid();
    }

    public function testResponseOk()
    {
        $response = $this->put('api/v1/roles/aabb', [
            'domain' => 'Products',
            'name' => 'View',
            'permissions' => ['products.view']
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'uuid' => 'aabb',
            'domain' => 'Products',
            'name' => 'View',
            'permissions' => ['products.view']
        ]);
    }

    public function testResponseNotFound()
    {
        $response = $this->put('api/v1/roles/0011', [
            'domain' => 'Products',
            'name' => 'View',
            'permissions' => ['products.view']
        ]);

        $response->assertStatus(404);
        $response->assertJson([]);
    }

    /**
     * @dataProvider getAllInvalidRequestData
     */
    public function testResponseValidationError($requestData)
    {
        $response = $this->put('api/v1/roles/aabb', $requestData);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }
}
