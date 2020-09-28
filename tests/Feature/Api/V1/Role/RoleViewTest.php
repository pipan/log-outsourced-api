<?php

namespace Tests\Feature\Api\V1\Role;

use App\Domain\Role\RoleEntity;
use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class RoleViewTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        RoleTestSeeder::seed($this->roleRepository);
    }

    public function testResponseOk()
    {
        $response = $this->get('api/v1/roles/aabb', AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'uuid' => 'aabb',
            'name' => 'Product.Access',
            'permissions' => ['product.view']
        ]);
    }

    public function testResponseNotFound()
    {
        $response = $this->get('api/v1/roles/0011', AuthHeaders::authorize());

        $response->assertStatus(404);
        $response->assertJsonStructure(['message']);
    }

    public function testResponseUnauthorized()
    {
        $response = $this->get('api/v1/roles/aabb');

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}
