<?php

namespace Tests\Feature\Api\V1\Project;

use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class ProjectDeleteTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ProjectTestSeeder::seed($this->projectRepository);
    }

    public function testResponseOk()
    {
        $response = $this->delete(
            'api/v1/projects/aabb',
            [],
            AuthHeaders::authorize()
        );

        $executions = $this->projectRepository->getMocker()
            ->getSimulation('delete')
            ->getExecutions();

        $response->assertStatus(200);
        $response->assertExactJson([]);
        $this->assertCount(1, $executions);
    }

    public function testResponseNotFound()
    {
        $response = $this->delete(
            'api/v1/projects/xxxx',
            [],
            AuthHeaders::authorize()
        );

        $response->assertStatus(404);
        $response->assertJsonStructure(['message']);
    }

    public function testResponseUnauthorized()
    {
        $response = $this->delete('api/v1/projects/aabb');

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}
