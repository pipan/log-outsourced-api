<?php

namespace Tests\Feature\Api\V1\Project;

use Tests\Feature\Api\V1\Administrator\AuthHeaders;
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

        $response = $this->get('api/v1/projects/aabb', AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'uuid',
            'name'
        ]);
    }

    public function testResponseNotFound()
    {
        $response = $this->get('api/v1/projects/xxxx', AuthHeaders::authorize());

        $response->assertStatus(404);
        $response->assertJsonStructure(['message']);
    }

    public function testResponseUnauthorized()
    {
        $response = $this->get('api/v1/projects/aabb');

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}
