<?php

namespace Tests\Feature\Api\V1\Project;

use App\Domain\Project\ProjectEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class ProjectUpdateTest extends ControllerActionTestCase
{
    public function testResponseOk()
    {
        $this->projectRepository->withEntity(
            new ProjectEntity(1, 'aabb', 'test_project')
        );
        $response = $this->put('api/v1/projects/aabb', [
            'name' => 'new_project'
        ]);

        $response->assertStatus(200);
        $this->assertEquals(1, $this->projectRepository->getUpdated()->getId());
        $response->assertJsonFragment([
            'name' => 'new_project'
        ]);
    }

    public function testResponseValidationErrorEmptyName()
    {
        $this->projectRepository->withEntity(
            new ProjectEntity(1, 'aabb', 'test_project')
        );
        $response = $this->put('api/v1/projects/aabb', [
            'name' => ''
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorLongName()
    {
        $this->projectRepository->withEntity(
            new ProjectEntity(1, 'aabb', 'test_project')
        );
        $name = "";
        for ($i = 0; $i < 256; $i++) {
            $name .= "a";
        }
        
        $response = $this->put('api/v1/projects/aabb', [
            'name' => $name
        ]);

        $response->assertStatus(422);
    }
       

    public function testResponseNotFound()
    {
        $response = $this->put('api/v1/projects/aabb');

        $response->assertStatus(404);
    }
}
