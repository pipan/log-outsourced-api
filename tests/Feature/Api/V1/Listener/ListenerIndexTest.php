<?php

namespace Tests\Feature\Api\V1\Listener;

use App\Domain\Listener\ListenerEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;
use Tests\Feature\Api\V1\Project\ProjectTestSeeder;

class ListenerIndexTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ProjectTestSeeder::seed($this->projectRepository);
    }

    public function testResponseOkEmpty()
    {
        $response = $this->get('api/v1/listeners?project_uuid=aabb');

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    public function testResponseOkNotEmpty()
    {
        $this->listenerRepository->getMocker()
            ->getSimulation('getForProject')
            ->whenInputReturn([
                new ListenerEntity(1, 'aabb', 1, 'critical', ['critical'], 'slug', encrypt(['one' => '1']))
            ], [1, ['limit' => 25, 'page' => 1, 'search' => '']]);

        $response = $this->get('api/v1/listeners?project_uuid=aabb');

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment([
            [
                'uuid' => 'aabb',
                'name' => 'critical',
                'project_id' => 1,
                'rules' => ['critical'],
                'handler' => [
                    'slug' => 'slug',
                    'values' => ['one' => '1']
                ]
            ]
        ]);
    }
}
