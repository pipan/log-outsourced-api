<?php

namespace Tests\Feature\Api\V1\Project;

use App\Domain\Project\ProjectEntity;
use Lib\Pagination\PaginationEntity;
use Tests\Feature\Api\V1\Administrator\AdministratorTestSeeder;
use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;
use Tests\Feature\Api\V1\PaginationRequests;

class ProjectIndexTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        AdministratorTestSeeder::seed($this->administratorRepository);
    }

    public function getPaginatedRequests()
    {
        return PaginationRequests::getPaginated('api/v1/projects');
    }

    protected function seedCount($total, $search = '')
    {
        $this->projectRepository->getMocker()
            ->getSimulation('count')
            ->whenInputReturn($total, [$search]);
    }

    protected function assertPagination($response, $pagination)
    {
        $response->assertJson([
            'meta' => [
                'pagination' => $pagination
            ]
        ]);
    }

    protected function assertItems($response, $items)
    {
        $response->assertJson([
            'items' => $items
        ]);
    }

    public function testResponseOkEmptyItems()
    {
        $pagination = (new PaginationEntity([]))
            ->searchBy('name')
            ->orderBy('name');
    
        $this->projectRepository->getMocker()
            ->getSimulation('getAll')
            ->whenInputReturn([], [$pagination]);
        $this->seedCount(0, $pagination);
        $response = $this->get('api/v1/projects', AuthHeaders::authorize());

        $response->assertStatus(200);
        $this->assertPagination($response, [
            'page' => 1,
            'limit' => 25,
            'max' => 0
        ]);
        $this->assertItems($response, []);
    }

    public function testResponseOk()
    {
        $pagination = (new PaginationEntity([]))
            ->searchBy('name')
            ->orderBy('name');

        $project = new ProjectEntity([
            'id' => 1,
            'uuid' => 'aabb',
            'name' => 'test'
        ]);
        $this->projectRepository->getMocker()
            ->getSimulation('getAll')
            ->whenInputReturn([$project], [$pagination]);
        $this->seedCount(1);
        $response = $this->get('api/v1/projects', AuthHeaders::authorize());

        $response->assertStatus(200);
        $this->assertPagination($response, [
            'page' => 1,
            'limit' => 25,
            'max' => 1
        ]);
        $this->assertItems($response, [[
            'uuid' => 'aabb',
            'name' => 'test'
        ]]);
    }

    /**
     * @dataProvider getPaginatedRequests
     */
    public function testResponseOkPaginated($url, $paginationResponse)
    {
        $this->seedCount(20);
        $response = $this->get($url, AuthHeaders::authorize());

        $response->assertStatus(200);
        $this->assertPagination($response, $paginationResponse);
    }

    public function testResponseUnauthorized()
    {
        $this->projectRepository->getMocker()
            ->getSimulation('getAll')
            ->whenInputReturn([]);
        $response = $this->get('api/v1/projects');

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}
