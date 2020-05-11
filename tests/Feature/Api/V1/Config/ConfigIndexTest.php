<?php

namespace Tests\Feature\Api\V1\Config;

use Tests\TestCase;

class ConfigIndexTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testResponseOk()
    {
        $response = $this->get('api/v1/config');

        $response->assertStatus(200);
    }

    // public function testResponseNotEmpty()
    // {

    //     $this->projectRepoMock->withAll([
    //         new ProjectEntity(1, hex2bin('aabc'), 'test')
    //     ]);
        
    //     $response = $this->get('api/v1/projects');

    //     $response->assertStatus(200);
    //     $response->assertJson([
    //         [
    //             'uuid' => 'aabc',
    //             'name' => 'test'
    //         ]
    //     ]);
    // }
}
