<?php

namespace Tests\Feature\Api\V1\Listener;

use App\Domain\Project\ProjectEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class ListenerCreateTest extends ControllerActionTestCase
{
    public function testResponseOk()
    {
        $this->projectRepository->getMocker()
            ->getSimulation('exists')
            ->whenInputReturn(true, ['aabb']);
            $this->projectRepository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn(
                new ProjectEntity(1, 'aabb', 'project'),
                ['aabb']
            );
        $this->handlerRepository->getMocker()
            ->getSimulation('exists')
            ->whenInputReturn(true, ['file']);
        $response = $this->post('api/v1/listeners', [
            'name' => 'test_handler',
            'project_uuid' => 'aabb',
            'rules' => [],
            'handler_slug' => 'file'
        ]);

        $inserted = $this->listenerRepository->getMocker()
            ->getSimulation('insert')->getExecutions();

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'uuid',
            'name',
            'project_id',
            'rules',
            'handler' => [
                'slug',
                'values'
            ]
        ]);
        $this->assertCount(1, $inserted);
    }

    public function testResponseValidationErrorMissingName()
    {
        $this->projectRepository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn(
                new ProjectEntity(1, 'aabb', 'project'),
                ['aabb']
            );
        $response = $this->post('api/v1/listeners', [
            'project_uuid' => 'aabb',
            'handler_slug' => 'file',
            'rules' => []
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorEmptyName()
    {
        $this->projectRepository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn(
                new ProjectEntity(1, 'aabb', 'project'),
                ['aabb']
            );
        $response = $this->post('api/v1/listeners', [
            'name' => '',
            'project_uuid' => 'aabb',
            'handler_slug' => 'file',
            'rules' => []
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
        $response = $this->post('api/v1/listeners', [
            'name' => $name,
            'project_uuid' => 'aabb',
            'rules' => [],
            'handler_slug' => 'file'
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorProjectUuidMissing()
    {
        $this->projectRepository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn(
                new ProjectEntity(1, 'aabb', 'project'),
                ['aabb']
            );
        $response = $this->post('api/v1/listeners', [
            'name' => 'test_handler',
            'rules' => [],
            'handler_slug' => 'file'
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorProjectNotFound()
    {
        $response = $this->post('api/v1/listeners', [
            'name' => 'test_handler',
            'project_uuid' => 'aacc',
            'rules' => [],
            'handler_slug' => 'file'
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorHandlerSlugMissing()
    {
        $this->projectRepository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn(
                new ProjectEntity(1, 'aabb', 'project'),
                ['aabb']
            );
        $response = $this->post('api/v1/listeners', [
            'name' => 'test_handler',
            'rules' => [],
            'project_uuid' => 'aacc'
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorHandlerNotFound()
    {
        $this->projectRepository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn(
                new ProjectEntity(1, 'aabb', 'project'),
                ['aabb']
            );
        $response = $this->post('api/v1/listeners', [
            'name' => 'test_handler',
            'project_uuid' => 'aacc',
            'rules' => [],
            'handler_slug' => 'nan'
        ]);

        $response->assertStatus(422);
    }
}
