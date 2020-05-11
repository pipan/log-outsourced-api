<?php

namespace Tests\Feature\Api\V1\Listener;

use App\Domain\Project\ProjectEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class ListenerCreateTest extends ControllerActionTestCase
{
    public function testResponseOk()
    {
        $this->projectRepository->withEntity(
            new ProjectEntity(1, 'aabb', 'project')
        );
        $response = $this->post('api/v1/listeners', [
            'name' => 'test_handler',
            'project_uuid' => 'aabb'
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'name' => 'test_handler',
            'project_id' => 1
        ]);
        $this->assertEquals('test_handler', $this->listenerRepository->getInserted()->getName());
    }

    public function testResponseValidationErrorMissingName()
    {
        $this->projectRepository->withEntity(
            new ProjectEntity(1, 'aabb', 'project')
        );
        $response = $this->post('api/v1/listeners', [
            'project_uuid' => 'aabb'
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorEmptyName()
    {
        $this->projectRepository->withEntity(
            new ProjectEntity(1, 'aabb', 'project')
        );
        $response = $this->post('api/v1/listeners', [
            'name' => '',
            'project_uuid' => 'aabb'
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorLongName()
    {
        $this->projectRepository->withEntity(
            new ProjectEntity(1, 'aabb', 'project')
        );
        $name = "";
        for ($i = 0; $i < 256; $i++) {
            $name .= "a";
        }
        $response = $this->post('api/v1/listeners', [
            'name' => $name,
            'project_uuid' => 'aabb'
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorProjectUuidMissing()
    {
        $this->projectRepository->withEntity(
            new ProjectEntity(1, 'aabb', 'project')
        );
        $response = $this->post('api/v1/listeners', [
            'name' => 'test_handler'
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorProjectNotFound()
    {
        $response = $this->post('api/v1/listeners', [
            'name' => 'test_handler',
            'project_uuid' => 'aacc'
        ]);

        $response->assertStatus(422);
    }
}
