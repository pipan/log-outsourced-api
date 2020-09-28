<?php

namespace Tests\Feature\Api\V1\Handler;

use App\Domain\Handler\HandlerEntity;
use Tests\Feature\Api\V1\Administrator\AuthHeaders;
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
        $response = $this->get('/api/v1/handlers/file', AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJson([
            'slug' => 'file',
            'name' => 'File',
            'meta' => []
        ]);
    }

    public function testResponseNotFound()
    {
        $response = $this->get('/api/v1/handlers/xxxx', AuthHeaders::authorize());

        $response->assertStatus(404);
        $response->assertJsonStructure(['message']);
    }

    public function testResponseUnauthorized()
    {
        $response = $this->get('api/v1/handlers/file');

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}