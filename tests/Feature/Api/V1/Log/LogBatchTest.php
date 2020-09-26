<?php

namespace Tests\Feature\Api\V1\Log;

use App\Domain\Listener\ListenerEntity;
use App\Handler\LogHandlerContainer;
use Tests\Feature\Api\V1\ControllerActionTestCase;
use Tests\Feature\Api\V1\Project\ProjectTestSeeder;
use Tests\Mock\Handler\LogHandlerMock;

class LogBatchTest extends ControllerActionTestCase
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
        return LogRequests::getInvalidForBatch();
    }

    public function testResponseOkMissingContext()
    {
        $this->listenerRepository->getMocker()
            ->getSimulation('getForProject')
            ->whenInputReturn(
                [new ListenerEntity(1, 'aabb', 1, 'error_mock', ['error'], 'mock', [])],
                [1, []]
            );

        $response = $this->post('/logs/aabb/batch', [
            [
                'level' => 'error',
                'message' => 'Log this message'
            ]
        ]);

        $handled = $this->logHandler->getMocker()
            ->getSimulation('handle')
            ->getExecutions();

        $response->assertStatus(200);
        $response->assertJson([]);
        $this->assertCount(1, $handled);
    }

    public function testResponseOkWithContext()
    {
        $this->listenerRepository->getMocker()
            ->getSimulation('getForProject')
            ->whenInputReturn(
                [new ListenerEntity(1, 'aabb', 1, 'error_mock', ['error'], 'mock', [])],
                [1, []]
            );

        $response = $this->post('/logs/aabb/batch', [
            [
                'level' => 'error',
                'message' => 'Log this message',
                'context' => [
                    'stackTrace' => 'Error'
                ]
            ]
        ]);

        $handled = $this->logHandler->getMocker()
            ->getSimulation('handle')
            ->getExecutions();

        $response->assertStatus(200);
        $response->assertJson([]);
        $this->assertCount(1, $handled);
    }

    public function testResponseNotFound()
    {
        $response = $this->post('/logs/xxxx/batch', [
            [
                'level' => 'error',
                'message' => 'Log this message'
            ]
        ]);

        $response->assertStatus(404);
        $response->assertJson([]);
    }

    /**
     * @dataProvider getInvalidRequests
     */
    public function testResponseValidationError($requestData)
    {
        $response = $this->post('/logs/aabb/batch', $requestData);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }
}