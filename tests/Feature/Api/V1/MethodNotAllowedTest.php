<?php

namespace Tests\Feature\Api\V1;

use Tests\TestCase;

class MethodNotAllowedTest extends TestCase
{
    public function testResponseJson()
    {
        $response = $this->get('api/v1/access');

        $response->assertJsonStructure(['message']);
    }
}