<?php

namespace Tests\Feature\Api\V1\Log;

use App\Handler\LogHandlerContainer;
use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;
use Tests\Feature\Api\V1\Listener\ListenerTestSeeder;
use Tests\Feature\Api\V1\Project\ProjectTestSeeder;
use Tests\Feature\Api\V1\Settings\ProjectKey\ProjectKeyTestSeeder;
use Tests\Mock\Handler\LogHandlerMock;

class LogBatchApiTest extends ControllerActionTestCase
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

    public function testResponseOk()
    {
        $response = $this->post('/api/v1/logs/batch?project_uuid=aabb', [
            'logs' => [[
                'level' => 'error',
                'message' => 'Log this message'
            ]]
        ], AuthHeaders::authorize());

        $handled = $this->logHandler->getMocker()
            ->getSimulation('handle')
            ->getExecutions();

        $response->assertStatus(200);
        $response->assertJson([]);
        $this->assertCount(1, $handled);
    }

    public function testResponseUnauthorized()
    {
        $response = $this->post('/api/v1/logs/batch?project_uuid=aabb', [
            'logs' => [[
                'level' => 'error',
                'message' => 'Log this message'
            ]]
        ]);

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}