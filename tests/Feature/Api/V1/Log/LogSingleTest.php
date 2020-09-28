<?php

namespace Tests\Feature\Api\V1\Log;

use App\Domain\Listener\ListenerEntity;
use App\Handler\LogHandlerContainer;
use Tests\Feature\Api\V1\ControllerActionTestCase;
use Tests\Feature\Api\V1\Project\ProjectTestSeeder;
use Tests\Mock\Handler\LogHandlerMock;

class LogSingleTest extends ControllerActionTestCase
{
    private $logHandler;

    public function setUp(): void
    {
        parent::setUp();

        $this->logHandler = new LogHandlerMock();
        $handlerContainer = $this->app->make(LogHandlerContainer::class);
        $handlerContainer->add('mock', $this->logHandler);

        ProjectTestSeeder::seed($this->projectRepository);
    }

    public function getInvalidRequests()
    {
        return LogRequests::getInvalidForSingle();
    }

    public function testResponseOkMissingContext()
    {
        $listener = new ListenerEntity([
            'id' => 1,
            'uuid' => 'aabb',
            'project_id' => 1,
            'name' => 'error mock',
            'rules' => ['error'],
            'handler_slug' => 'mock',
            'handler_values' => []
        ]);
        $this->listenerRepository->getMocker()
            ->getSimulation('getForProject')
            ->whenInputReturn([$listener], [1, []]);

        $response = $this->post('/logs/aabb', [
            'level' => 'error',
            'message' => 'Log this message'
        ]);

        $handled = $this->logHandler->getMocker()
            ->getSimulation('handle')
            ->getExecutions();

        $response->assertStatus(200);
        $this->assertCount(1, $handled);
    }

    public function testResponseOkWithContext()
    {
        $listener = new ListenerEntity([
            'id' => 1,
            'uuid' => 'aabb',
            'project_id' => 1,
            'name' => 'error mock',
            'rules' => ['error'],
            'handler_slug' => 'mock',
            'handler_values' => []
        ]);
        $this->listenerRepository->getMocker()
            ->getSimulation('getForProject')
            ->whenInputReturn([$listener], [1, []]);

        $response = $this->post('/logs/aabb', [
            'level' => 'error',
            'message' => 'Log this message',
            'context' => [
                'stackTrace' => 'Error'
            ]
        ]);

        $handled = $this->logHandler->getMocker()
            ->getSimulation('handle')
            ->getExecutions();

        $response->assertStatus(200);
        $this->assertCount(1, $handled);
    }

    public function testResponseNotFound()
    {
        $response = $this->post('/logs/xxxx', [
            'level' => 'error',
            'message' => 'Log this message'
        ]);

        $response->assertStatus(404);
        $response->assertJson([]);
    }

    /**
     * @dataProvider getInvalidRequests
     */
    public function testResponseValidationError($requestData)
    {
        $response = $this->post('/logs/aabb', $requestData);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }
}