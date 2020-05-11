<?php

namespace Tests\Feature\Api\V1\Listener;

use App\Domain\Listener\ListenerEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class ListenerDeleteTest extends ControllerActionTestCase
{
    public function testResponseOk()
    {
        $this->listenerRepository->withEntity(
            new ListenerEntity(1, '0011', 1, 'test', [], 1, "")
        );
        $response = $this->delete('api/v1/listeners/0011');

        $response->assertStatus(204);
        $this->assertEquals('test', $this->listenerRepository->getDeleted()->getName());
    }

    public function testHandlerNotFound()
    {
        $response = $this->delete('api/v1/listeners/0011', [
            'name' => 'test_handler',
        ]);

        $response->assertStatus(404);
    }
}
