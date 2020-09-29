<?php

namespace Tests\Feature\Api\V1\Listener;

use App\Domain\Listener\ListenerEntity;
use Lib\Pagination\PaginationEntity;
use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;
use Tests\Feature\Api\V1\PaginationRequests;
use Tests\Feature\Api\V1\Project\ProjectTestSeeder;

class ListenerIndexTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ProjectTestSeeder::seed($this->projectRepository);
    }

    public function getPaginatedRequests()
    {
        return PaginationRequests::getPaginated('api/v1/listeners', [
            'project_uuid' => 'aabb'
        ]);
    }

    public function testResponseOkEmpty()
    {
        $response = $this->get('api/v1/listeners?project_uuid=aabb', AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    /**
     * @dataProvider getPaginatedRequests
     */
    public function testResponseOkPaginated($url, $paginationConfig)
    {
        $pagination = new PaginationEntity($paginationConfig);
        $listener = new ListenerEntity([
            'id' => 1,
            'uuid' => 'aabb',
            'project_id' => 1,
            'name' => 'critical',
            'rules' => ['critical'],
            'handler_slug' => 'file',
            'handler_values' => ['one' => '1']
        ]);
        $this->listenerRepository->getMocker()
            ->getSimulation('getForProject')
            ->whenInputReturn([$listener], [1, $pagination]);

        $response = $this->get($url, AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment([
            [
                'uuid' => 'aabb',
                'name' => 'critical',
                'project_id' => 1,
                'rules' => ['critical'],
                'handler' => [
                    'slug' => 'file',
                    'values' => ['one' => '1']
                ]
            ]
        ]);
    }

    public function testResponseUnauthorized()
    {
        $response = $this->get('api/v1/listeners');

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}
