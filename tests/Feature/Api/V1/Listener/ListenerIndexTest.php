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
        $this->listenerRepository->getMocker()
            ->getSimulation('countForProject')
            ->whenInputReturn(0, [1, '']);

        $response = $this->get('api/v1/listeners?project_uuid=aabb', AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJson([
            'items' => [],
            'meta' => [
                'pagination' => [
                    'page' => 1,
                    'limit' => 25,
                    'max' => 0,
                    'total' => 0
                ]
            ]
        ]);
    }

    public function testResponseOk()
    {
        $pagination = (new PaginationEntity([]))
            ->searchBy('name')
            ->orderBy('name');
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
        $this->listenerRepository->getMocker()
            ->getSimulation('countForProject')
            ->whenInputReturn(1, [1, '']);

        $response = $this->get('api/v1/listeners?project_uuid=aabb', AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJson([
            'items' => [[
                'uuid' => 'aabb',
                'name' => 'critical',
                'project_id' => 1,
                'rules' => ['critical'],
                'handler_slug' => 'file',
                'handler_values' => ['one' => '1']
            ]],
            'meta' => [
                'pagination' => [
                    'page' => 1,
                    'limit' => 25,
                    'max' => 1,
                    'total' => 1
                ]
            ]
        ]);
    }

    /**
     * @dataProvider getPaginatedRequests
     */
    public function testResponseOkPaginated($url, $pagination)
    {
        $this->listenerRepository->getMocker()
            ->getSimulation('countForProject')
            ->whenInputReturn(20, [1, '']);

        $response = $this->get($url, AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJson([
            'meta' => [
                'pagination' => $pagination
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
