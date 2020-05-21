<?php

namespace Tests\Feature\Api\V1\Listener;

use App\Domain\Listener\ListenerEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class ListenerUpdateTest extends ControllerActionTestCase
{
    public function testResponseOk()
    {
        $this->listenerRepository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn(
                new ListenerEntity(1, '0011', 1, 'name', [], 1, ""),
                ['0011']
            );
        $response = $this->put('api/v1/listeners/0011', [
            'name' => 'test',
        ]);

        $updated = $this->listenerRepository->getMocker()
            ->getSimulation('update')
            ->getExecutions();

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'test'
        ]);
        $this->assertCount(1, $updated);
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
        $this->listenerRepository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn(
                new ListenerEntity(1, '0011', 1, 'name', [], 1, ""),
                ['0011']
            );
        $response = $this->put('api/v1/listeners/0011', [
            'name' => ''
        ]);

        $response->assertStatus(422);
    }

    public function testResponseValidationErrorLongName()
    {
        $this->listenerRepository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn(
                new ListenerEntity(1, '0011', 1, 'name', [], 1, ""),
                ['0011']
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
