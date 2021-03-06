<?php

namespace Tests\Feature\Api\V1\Listener;

use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;
use Tests\Feature\Api\V1\Handler\HandlerTestSeeder;
use Tests\Feature\Api\V1\Project\ProjectTestSeeder;

class ListenerCreateTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ProjectTestSeeder::seed($this->projectRepository);
        HandlerTestSeeder::seed($this->handlerRepository);
    }

    public function provideInvalidRequests()
    {
        return ListenerRequests::getInvalidForCreate();
    }

    public function testResponseOk()
    {
        $response = $this->post('api/v1/listeners', [
            'name' => 'test_handler',
            'project_uuid' => 'aabb',
            'rules' => [],
            'handler_slug' => 'file'
        ], AuthHeaders::authorize());

        $inserted = $this->listenerRepository->getMocker()
            ->getSimulation('insert')->getExecutions();

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'uuid',
            'name',
            'project_id',
            'rules',
            'handler_slug',
            'handler_values'
        ]);
        $this->assertCount(1, $inserted);
    }

    public function testResponseOkRemoveDuplicateRules()
    {
        $response = $this->post('api/v1/listeners', [
            'name' => 'test_handler',
            'project_uuid' => 'aabb',
            'rules' => ['error', 'error'],
            'handler_slug' => 'file'
        ], AuthHeaders::authorize());

        $inserted = $this->listenerRepository->getMocker()
            ->getSimulation('insert')->getExecutions();

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'rules' => ['error']
        ]);
        $this->assertCount(1, $inserted);
    }

    /**
     * @dataProvider provideInvalidRequests
     */
    public function testResponseValidationError($requestData)
    {
        $response = $this->post(
            'api/v1/listeners',
            $requestData,
            AuthHeaders::authorize()
        );

        $response->assertStatus(422);
        $response->assertJsonStructure(['message', 'errors']);
    }

    public function testResponseUnauthorized()
    {
        $response = $this->post('api/v1/listeners', [
            'name' => 'test_handler',
            'project_uuid' => 'aabb',
            'rules' => [],
            'handler_slug' => 'file'
        ]);

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}
