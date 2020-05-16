<?php

namespace Tests\Feature\Api\V1\Project;

use App\Domain\Project\ProjectEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class ProjectDeleteTest extends ControllerActionTestCase
{
    public function testResponseOk()
    {
        $this->projectRepository->withEntity(
            new ProjectEntity(1, 'aabb', 'test_project')
        );
        $response = $this->delete('api/v1/projects/aabb');

        $response->assertStatus(200);
        $response->assertExactJson([]);
    }

    public function testResponseNotFound()
    {
        $response = $this->delete('api/v1/projects/aabb');

        $response->assertStatus(404);
        $response->assertExactJson([]);
    }
}
