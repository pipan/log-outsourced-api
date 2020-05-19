<?php

namespace Tests\Feature\Api\V1\Project;

use App\Domain\Project\ProjectEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class ProjectUpdateTest extends ControllerActionTestCase
{
    public function testResponseOk()
    {
        $this->projectRepository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn(
                new ProjectEntity(1, 'aabb', 'project'),
                ['aabb']
            );
        $response = $this->put('api/v1/projects/aabb', [
            'name' => 'new_project'
        ]);

        $updated = $this->projectRepository->getMocker()
            ->getSimulation('update')
            ->getExecutions();

        $response->assertStatus(200);
        $this->assertCount(1, $updated);
        $response->assertJsonFragment([
            'name' => 'new_project'
        ]);
    }

    public function testResponseValidationErrorEmptyName()
    {
        $this->projectRepository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn(
                new ProjectEntity(1, 'aabb', 'project'),
                ['aabb']
            );
        $response = $this->put('api/v1/projects/aabb', [
            'name' => ''
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorLongName()
    {
        $this->projectRepository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn(
                new ProjectEntity(1, 'aabb', 'project'),
                ['aabb']
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
