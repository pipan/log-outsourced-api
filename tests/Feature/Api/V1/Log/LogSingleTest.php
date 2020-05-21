<?php

namespace Tests\Feature\Api\V1\Log;

use App\Domain\Listener\ListenerEntity;
use App\Domain\Project\ProjectEntity;
use App\Handler\LogHandlerContainer;
use Tests\Feature\Api\V1\ControllerActionTestCase;
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
    }

    public function testResponseOkMissingContext()
    {
        $this->projectRepository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn(
                new ProjectEntity(1, '12345678', 'test'),
                ['12345678']
            );
        $this->listenerRepository->getMocker()
            ->getSimulation('getForProject')
            ->whenInputReturn(
                [new ListenerEntity(1, 'aabb', 1, 'error_mock', ['error'], 'mock', [])],
                [1]
            );

        $response = $this->post('/log/12345678', [
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
        $this->projectRepository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn(
                new ProjectEntity(1, '12345678', 'test'),
                ['12345678']
            );
        $this->listenerRepository->getMocker()
            ->getSimulation('getForProject')
            ->whenInputReturn(
                [new ListenerEntity(1, 'aabb', 1, 'error_mock', ['error'], 'mock', [])],
                [1]
            );

        $response = $this->post('/log/12345678', [
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

    public function testResponse404MissingProject()
    {
        $response = $this->post('/log/12345678', [
            'level' => 'error',
            'message' => 'Log this message'
        ]);

        $response->assertStatus(404);
        $response->assertJson([]);
    }

    public function testResponse422MissingLevelValue()
    {
        $response = $this->post('/log/12345678', [
            'message' => 'Log this message'
        ]);

        $response->assertStatus(422);
        $response->assertJson([]);
    }

    public function testResponse422EmptyLogLevel()
    {
        $response = $this->post('/log/12345678', [
            'level' => '',
            'message' => 'Log this message'
        ]);

        $response->assertStatus(422);
        $response->assertJson([]);
    }

    public function testResponse422LogLevelNotStandardname()
    {
        $response = $this->post('/log/12345678', [
            'level' => 'custom',
            'message' => 'Log this message'
        ]);

        $response->assertStatus(422);
        $response->assertJson([]);
    }

    public function testResponse422MissingMessage()
    {
        $response = $this->post('/log/12345678', [
            'level' => 'error'
        ]);

        $response->assertStatus(422);
        $response->assertJson([]);
    }

    public function testResponse422MessageEmpty()
    {
        $response = $this->post('/log/12345678', [
            'level' => 'error',
            'message' => ''
        ]);

        $response->assertStatus(422);
        $response->assertJson([]);
    }
}