<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;

class RouteNotFoundTest extends TestCase
{
    public function testResponseJson()
    {
        $response = $this->get('api/v1/xxxx');

        $response->assertJsonStructure(['message']);
    }
}