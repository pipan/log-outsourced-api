<?php

namespace Tests\Feature\Api\V1\Handler;

use App\Domain\Handler\HandlerEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class HandlerViewText extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        HandlerTestSeeder::seed($this->handlerRepository);
    }

    public function testResponseOk()
    {
        $response = $this->get('/api/v1/handlers/file');

        $response->assertStatus(200);
        $response->assertJson([
            'slug' => 'file',
            'name' => 'file',
            'meta' => []
        ]);
    }

    public function testResponseNotFound()
    {
        $response = $this->get('/api/v1/handlers/xxxx');

        $response->assertStatus(404);
    }
}