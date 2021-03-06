<?php

namespace Tests\Feature\Api\V1\Log;

use App\Handler\LogHandlerContainer;
use Tests\Feature\Api\V1\ControllerActionTestCase;
use Tests\Feature\Api\V1\Listener\ListenerTestSeeder;
use Tests\Feature\Api\V1\Project\ProjectTestSeeder;
use Tests\Feature\Api\V1\Settings\ProjectKey\ProjectKeyTestSeeder;
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
        ListenerTestSeeder::seedForProject($this->listenerRepository);
        ProjectKeyTestSeeder::seed($this->projectKeyRepository);
    }

    public function getInvalidRequests()
    {
        return LogRequests::getInvalidForBatch();
    }

    public function testResponseOkMissingContext()
    {
        $response = $this->post('/logs/1234/batch', [
            'logs' => [[
                'level' => 'error',
                'message' => 'Log this message'
            ]]
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
        $response = $this->post('/logs/1234/batch', [
            'logs' => [[
                'level' => 'error',
                'message' => 'Log this message',
                'context' => [
                    'stackTrace' => 'Error'
                ]
            ]]
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
            'logs' => [[
                'level' => 'error',
                'message' => 'Log this message'
            ]]
        ]);

        $response->assertStatus(404);
        $response->assertJson([]);
    }

    /**
     * @dataProvider getInvalidRequests
     */
    public function testResponseValidationError($requestData)
    {
        $response = $this->post('/logs/1234/batch', $requestData);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }
}