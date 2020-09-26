<?php

namespace Tests\Feature\Api\V1\Project;

use Tests\Feature\Api\V1\ControllerActionTestCase;

class ProjectViewTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ProjectTestSeeder::seed($this->projectRepository);
    }

    public function testResponseOk()
    {
        $this->listenerRepository->getMocker()
            ->getSimulation('getForProject')
            ->whenInputReturn([], [1]);

        $response = $this->get('api/v1/projects/aabb');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'project' => [
                'uuid',
                'name'
            ],
            'listeners'
        ]);
    }

    public function testResponseNotFound()
    {
        $response = $this->get('api/v1/projects/xxxx');

        $response->assertStatus(404);
        $response->assertJson([]);
    }
}
