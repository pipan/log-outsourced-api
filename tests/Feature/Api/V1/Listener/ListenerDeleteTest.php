<?php

namespace Tests\Feature\Api\V1\Listener;

use Tests\Feature\Api\V1\ControllerActionTestCase;

class ListenerDeleteTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ListenerTestSeeder::seed($this->listenerRepository);
    }

    public function testResponseOk()
    {
        $response = $this->delete('api/v1/listeners/aabb');

        $deleted = $this->listenerRepository->getMocker()
            ->getSimulation('delete')
            ->getExecutions();

        $response->assertStatus(200);
        $response->assertJson([]);
        $this->assertCount(1, $deleted);
    }

    public function testHandlerNotFound()
    {
        $response = $this->delete('api/v1/listeners/0011');

        $response->assertStatus(404);
        $response->assertJson([]);
    }
}
