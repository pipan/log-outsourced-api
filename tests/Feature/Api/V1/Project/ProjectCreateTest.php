<?php

namespace Tests\Feature\Api\V1\Project;

use Tests\Feature\Api\V1\ControllerActionTestCase;

class ProjectCreateTest extends ControllerActionTestCase
{
    public function getInvalidRequests()
    {
        return ProjectRequests::getInvalidForCreation();
    }

    public function testResponseOk()
    {
        $response = $this->post('api/v1/projects', [
            'name' => 'test_project'
        ]);

        $inserted = $this->projectRepository->getMocker()
            ->getSimulation('insert')
            ->getExecutions();

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'name' => 'test_project'
        ]);
        $this->assertCount(1, $inserted);
    }

    /**
     * @dataProvider getInvalidRequests
     */
    public function testResponseValidationError($requestData)
    {
        $response = $this->post('api/v1/projects', $requestData);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'errors'
        ]);
    }
}
