<?php

namespace Tests\Feature\Api\V1\Listener;

use App\Domain\Listener\ListenerEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;


class ListenerUpdateTest extends ControllerActionTestCase
{
    public function testResponseOk()
    {
        $this->listenerRepository->withEntity(
            new ListenerEntity(1, '0011', 1, 'name', [], 1, "")
        );
        $response = $this->put('api/v1/listeners/0011', [
            'name' => 'test',
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'test'
        ]);
        $this->assertEquals('test', $this->listenerRepository->getUpdated()->getName());
    }

    public function testHandlerNotFound()
    {
        $response = $this->put('api/v1/listeners/0011', [
            'name' => 'test_handler',
        ]);

        $response->assertStatus(404);
    }

    public function testResponseValidationErrorEmptyName()
    {
        $this->listenerRepository->withEntity(
            new ListenerEntity(1, '0011', 1, 'name', [], 1, "")
        );
        $response = $this->put('api/v1/listeners/0011', [
            'name' => ''
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorLongName()
    {
        $this->listenerRepository->withEntity(
            new ListenerEntity(1, '0011', 1, 'name', [], 1, "")
        );
        $name = "";
        for ($i = 0; $i < 256; $i++) {
            $name .= "a";
        }
        $response = $this->put('api/v1/listeners/0011', [
            'name' => $name
        ]);

        $response->assertStatus(422);
    }
}
