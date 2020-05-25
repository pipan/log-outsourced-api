<?php

namespace Tests\Feature\Api\V1\Handler;

use App\Domain\Handler\HandlerEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class HandlerIndexText extends ControllerActionTestCase
{
    public function testResponseOkEmpty()
    {
        $this->handlerRepository->getMocker()
            ->getSimulation('getAll')
            ->whenInputReturn([]);
        $response = $this->get('/api/v1/handlers');

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function testResponseOkResult()
    {
        $this->handlerRepository->getMocker()
            ->getSimulation('getAll')
            ->whenInputReturn([
                new HandlerEntity('test', 'test', [], [])
            ]);
        $response = $this->get('/api/v1/handlers');

        $response->assertStatus(200);
        $response->assertJson([
            [
                'slug' => 'test',
                'name' => 'test',
                'meta' => []
            ]
        ]);
    }
}