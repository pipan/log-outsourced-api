<?php

namespace Tests\Feature\Api\V1\Role;

use App\Domain\Role\RoleEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class RoleUpdateTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        RoleTestSeeder::seed($this->roleRepository);
    }

    public function getAllInvalidRequestData()
    {
        return RoleRequests::getUpdateInvalid();
    }

    public function testResponseOk()
    {
        $response = $this->put('api/v1/roles/aabb', [
            'name' => 'Product.View',
            'permissions' => ['products.view']
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'uuid' => 'aabb',
            'name' => 'Product.View',
            'permissions' => ['products.view']
        ]);
    }

    public function testResponseNotFound()
    {
        $response = $this->put('api/v1/roles/0011', [
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
