<?php

namespace Tests\Feature\Api\V1\Handler;

use App\Domain\Handler\HandlerEntity;
use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class HandlerIndexTest extends ControllerActionTestCase
{
    public function testResponseOkEmpty()
    {
        $this->handlerRepository->getMocker()
            ->getSimulation('getAll')
            ->whenInputReturn([]);
        $response = $this->get('/api/v1/handlers', AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function testResponseOkResult()
    {
        $handler = new HandlerEntity([
            'slug' => 'file',
            'name' => 'File'
        ]);
        $this->handlerRepository->getMocker()
            ->getSimulation('getAll')
            ->whenInputReturn([$handler]);
        $response = $this->get('/api/v1/handlers', AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJson([
            'items' => [
                [
                    'slug' => 'file',
                    'name' => 'File',
                    'meta' => []
                ]
            ]
        ]);
    }

    public function testResponseUnauthorized()
    {
        $this->handlerRepository->getMocker()
            ->getSimulation('getAll')
            ->whenInputReturn([]);
        $response = $this->get('api/v1/handlers');

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}