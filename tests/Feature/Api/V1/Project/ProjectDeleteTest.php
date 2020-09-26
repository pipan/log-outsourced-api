<?php

namespace Tests\Feature\Api\V1\Project;

use Tests\Feature\Api\V1\ControllerActionTestCase;

class ProjectDeleteTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ProjectTestSeeder::seed($this->projectRepository);
    }

    public function testResponseOk()
    {
        $response = $this->delete('api/v1/projects/aabb');

        $response->assertStatus(200);
        $response->assertExactJson([]);
    }

    public function testResponseNotFound()
    {
        $response = $this->delete('api/v1/projects/xxxx');

        $response->assertStatus(404);
        $response->assertExactJson([]);
    }
}
