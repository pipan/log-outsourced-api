<?php

namespace Tests\Feature\Api\V1\Settings\ProjectKey;

use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class ProjectKeyDeleteTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ProjectKeyTestSeeder::seed($this->projectKeyRepository);
    }

    public function testResponseOk()
    {
        $response = $this->delete('api/v1/settings/projectkeys/aabb', [], AuthHeaders::authorize());

        $deletes = $this->projectKeyRepository->getMocker()
            ->getSimulation('delete')
            ->getExecutions();

        $response->assertStatus(200);
        $response->assertJson([]);

        $this->assertCount(1, $deletes);
    }

    public function testResponseNotFound()
    {
        $response = $this->delete('api/v1/settings/projectkeys/xxxx', [], AuthHeaders::authorize());

        $response->assertStatus(404);
        $response->assertJsonStructure(['message']);
    }

    public function testResponseUnauthorized()
    {
        $response = $this->delete('api/v1/settings/projectkeys/aabb');

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}
