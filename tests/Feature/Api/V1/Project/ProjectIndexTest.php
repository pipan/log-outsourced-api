<?php

namespace Tests\Feature\Api\V1\Project;

use App\Domain\Project\ProjectEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class ProjectIndexTest extends ControllerActionTestCase
{
    public function testResponseOk()
    {
        $response = $this->get('api/v1/projects');

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function testResponseNotEmpty()
    {

        $this->projectRepository->withAll([
            new ProjectEntity(1, 'aabc', 'test')
        ]);
        
        $response = $this->get('api/v1/projects');

        $response->assertStatus(200);
        $response->assertJson([
            [
                'uuid' => 'aabc',
                'name' => 'test'
            ]
        ]);
    }
}
