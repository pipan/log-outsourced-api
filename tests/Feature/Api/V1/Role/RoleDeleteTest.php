<?php

namespace Tests\Feature\Api\V1\Role;

use App\Domain\Role\RoleEntity;
use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class RoleDeleteTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        RoleTestSeeder::seed($this->roleRepository);
    }

    public function testResponseOk()
    {
        $response = $this->delete('api/v1/roles/aabb', [], AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function testResponseNotFound()
    {
        $response = $this->delete('api/v1/roles/xxxx', [], AuthHeaders::authorize());

        $response->assertStatus(404);
        $response->assertJsonStructure(['message']);
    }

    public function testResponseUnauthorized()
    {
        $response = $this->delete('api/v1/roles/aabb');

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}
