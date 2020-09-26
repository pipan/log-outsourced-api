<?php

namespace Tests\Feature\Api\V1\Project;

use App\Domain\Project\ProjectEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class ProjectIndexTest extends ControllerActionTestCase
{
    public function testResponseOk()
    {
        $this->projectRepository->getMocker()
            ->getSimulation('getAll')
            ->whenInputReturn([]);
        $response = $this->get('api/v1/projects');

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function testResponseNotEmpty()
    {
        $project = new ProjectEntity([
            'id' => 1,
            'uuid' => 'aabb',
            'name' => 'test'
        ]);
        $this->projectRepository->getMocker()
            ->getSimulation('getAll')
            ->whenInputReturn([$project]);
        
        $response = $this->get('api/v1/projects');

        $response->assertStatus(200);
        $response->assertJson([
            [
                'uuid' => 'aabb',
                'name' => 'test'
            ]
        ]);
    }
}
