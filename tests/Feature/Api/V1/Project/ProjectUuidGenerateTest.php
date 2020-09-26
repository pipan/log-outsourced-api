<?php

namespace Tests\Feature\Api\V1\Project;

use Tests\Feature\Api\V1\ControllerActionTestCase;

class ProjectUuidGenerateTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ProjectTestSeeder::seed($this->projectRepository);
    }

    public function testResponseOk()
    {
        $response = $this->put('api/v1/projects/aabb/generate');

        $response->assertStatus(200);

        $updated = $this->projectRepository->getMocker()
            ->getSimulation('update')
            ->getExecutions();

        $this->assertCount(1, $updated);
        $this->assertNotEquals('aabb', $response->json('uuid'));
    }

    public function testResponseNotFound()
    {
        $response = $this->put('api/v1/projects/xxxx/generate');

        $response->assertStatus(404);
        $response->assertJson([]);
    }
}
