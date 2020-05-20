<?php

namespace Tests\Feature\Api\V1\Project;

use App\Domain\Project\ProjectEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class ProjectViewTest extends ControllerActionTestCase
{
    public function testResponseOk()
    {
        $this->projectRepository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn(
                new ProjectEntity(1, 'aabb', 'test_project'),
                ['aabb']
            );
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
        $response = $this->get('api/v1/projects/aacc');

        $response->assertStatus(404);
        $response->assertJson([]);
    }
}
