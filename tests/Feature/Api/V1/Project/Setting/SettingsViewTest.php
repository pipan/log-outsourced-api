<?php

namespace Tests\Feature\Api\V1\Project\Setting;

use App\Domain\Project\ProjectEntity;
use Tests\TestCase;

class SettingsViewTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testResponseOk()
    {
        $response = $this->get('api/v1/settings/aabb');

        $response->assertStatus(200);
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
