<?php

namespace Tests\Feature\Api\V1\Project;

use Tests\Feature\Api\V1\ControllerActionTestCase;

class ProjectCreateTest extends ControllerActionTestCase
{
    public function testResponseOk()
    {
        $response = $this->post('api/v1/projects', [
            'name' => 'test_project'
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'name' => 'test_project'
        ]);
        $this->assertEquals('test_project', $this->projectRepository->getInserted()->getName());
    }

    public function testResponseValidationErrorMissingName()
    {
        $response = $this->post('api/v1/projects');

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorEmptyName()
    {
        $response = $this->post('api/v1/projects', [
            'name' => ''
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorLongName()
    {
        $name = "";
        for ($i = 0; $i < 256; $i++) {
            $name .= "a";
        }
        $response = $this->post('api/v1/projects', [
            'name' => $name
        ]);

        $response->assertStatus(422);
    }
}
