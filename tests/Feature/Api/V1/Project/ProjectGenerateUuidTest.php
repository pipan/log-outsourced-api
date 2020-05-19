<?php

namespace Tests\Feature\Api\V1\Project;

use App\Domain\Project\ProjectEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class ProjectGenerateUuidTest extends ControllerActionTestCase
{
    public function testResponseOk()
    {
        $this->projectRepository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn(
                new ProjectEntity(1, 'aabb', 'project'),
                ['aabb']
            );
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
        $response = $this->put('api/v1/projects/aabb/generate');

        $response->assertStatus(404);
    }
}
