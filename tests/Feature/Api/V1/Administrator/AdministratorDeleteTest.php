<?php

namespace Tests\Feature\Api\V1\Administrator;

use Tests\Feature\Api\V1\ControllerActionTestCase;

class AdministratorDeleteTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        AdministratorTestSeeder::seed($this->administratorRepository);
    }

    public function testResponseOk()
    {
        $response = $this->delete('api/v1/administrators/aabb', [], AuthHeaders::authorize());

        $executions = $this->administratorRepository->getMocker()
            ->getSimulation('delete')
            ->getExecutions();

        $response->assertStatus(200);
        $response->assertExactJson([]);
        $this->assertCount(1, $executions);
    }

    public function testResponseUnauthorized()
    {
        $response = $this->delete('api/v1/administrators/aabb');

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }

    public function testResponseNotFound()
    {
        $response = $this->delete('api/v1/administrators/xxxx', [], AuthHeaders::authorize());

        $response->assertStatus(404);
        $response->assertJsonStructure(['message']);
    }
}
