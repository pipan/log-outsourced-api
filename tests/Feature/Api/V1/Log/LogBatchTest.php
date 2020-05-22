<?php

namespace Tests\Feature\Api\V1\Log;

use App\Domain\Listener\ListenerEntity;
use App\Domain\Project\ProjectEntity;
use App\Handler\LogHandlerContainer;
use Tests\Feature\Api\V1\ControllerActionTestCase;
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

        $response = $this->post('/logs/12345678/batch', [
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

        $response = $this->post('/logs/12345678/batch', [
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

    public function testResponse404MissingProject()
    {
        $response = $this->post('/logs/12345678/batch', [
            [
                'level' => 'error',
                'message' => 'Log this message'
            ]
        ]);

        $response->assertStatus(404);
        $response->assertJson([]);
    }

    public function testResponse422SendSingle()
    {
        $response = $this->post('/logs/12345678/batch', [
            'level' => 'error',
            'message' => 'Log this message'
        ]);

        $response->assertStatus(422);
        $response->assertJson([]);
    }

    public function testResponse422MissingLevelValue()
    {
        $response = $this->post('/logs/12345678/batch', [
            [
                'message' => 'Log this message'
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJson([]);
    }

    public function testResponse422EmptyLogLevel()
    {
        $response = $this->post('/logs/12345678/batch', [
            'level' => '',
            'message' => 'Log this message'
        ]);

        $response->assertStatus(422);
        $response->assertJson([]);
    }

    public function testResponse422LogLevelNotStandardName()
    {
        $response = $this->post('/logs/12345678/batch', [
            [
                'level' => 'custom',
                'message' => 'Log this message'
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJson([]);
    }

    public function testResponse422MissingMessage()
    {
        $response = $this->post('/logs/12345678/batch', [
            [
                'level' => 'error'
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJson([]);
    }

    public function testResponse422MessageEmpty()
    {
        $response = $this->post('/logs/12345678/batch', [
            [
                'level' => 'error',
                'message' => ''
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJson([]);
    }
}