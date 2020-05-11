<?php

namespace Tests\Feature\Api\V1\Project;

use App\Domain\Project\ProjectEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class ProjectViewTest extends ControllerActionTestCase
{
    public function testResponseOk()
    {
        $this->projectRepository->withEntity(
            new ProjectEntity(1, 'aabb', 'test_project')
        );
        $response = $this->get('api/v1/projects/aabb');

        $response->assertStatus(200);
    }

    public function testResponseNotFound()
    {
        $response = $this->get('api/v1/projects/aacc');

        $response->assertStatus(404);
    }
}
