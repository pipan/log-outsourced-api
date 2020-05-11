<?php

namespace Tests\Feature\Api\V1\Handler;

use App\Domain\Handler\HandlerEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class HandlerViewText extends ControllerActionTestCase
{
    public function testResponseOk()
    {
        $this->handlerRepository->withEntity(
            new HandlerEntity('test', 'test', [], [])
        );
        $response = $this->get('/api/v1/handlers/test');

        $response->assertStatus(200);
        $response->assertJson([
            'slug' => 'test',
            'name' => 'test',
            'configSchema' => [],
            'meta' => []
        ]);
    }

    public function testResponseNotFound()
    {
        $response = $this->get('/api/v1/handlers/test');

        $response->assertStatus(404);
    }
}