<?php

namespace Tests\Feature\Api\V1\Handler;

use App\Domain\Handler\HandlerEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class HandlerViewText extends ControllerActionTestCase
{
    public function testResponseOk()
    {
        $this->handlerRepository->getMocker()
            ->getSimulation('getBySlug')
            ->whenInputReturn(
                new HandlerEntity('test', 'test', [], []),
                ['test']
            );
        $response = $this->get('/api/v1/handlers/test');

        $response->assertStatus(200);
        $response->assertJson([
            'slug' => 'test',
            'name' => 'test',
            'meta' => []
        ]);
    }

    public function testResponseNotFound()
    {
        $response = $this->get('/api/v1/handlers/test');

        $response->assertStatus(404);
    }
}