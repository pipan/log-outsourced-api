<?php

namespace Tests\Feature\Api\V1\Project;

use App\Domain\Project\ProjectEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class ProjectGenerateUuidTest extends ControllerActionTestCase
{
    public function testResponseOk()
    {
        $this->projectRepository->withEntity(
            new ProjectEntity(1, 'aabb', 'test_project')
        );
        $response = $this->put('api/v1/projects/aabb/generate');

        $response->assertStatus(200);
        $this->assertEquals(1, $this->projectRepository->getUpdated()->getId());
        $this->assertNotEquals('aabb', $response->json('uuid'));
    }

    public function testResponseNotFound()
    {
        $response = $this->put('api/v1/projects/aabb/generate');

        $response->assertStatus(404);
    }
}
