<?php

namespace Tests\Feature\Api\V1\Role;

use App\Domain\Role\RoleEntity;
use App\Repository\Pagination;
use Lib\Pagination\PaginationEntity;
use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;
use Tests\Feature\Api\V1\PaginationRequests;
use Tests\Feature\Api\V1\Project\ProjectTestSeeder;

class RoleIndexTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ProjectTestSeeder::seed($this->projectRepository);
    }

    public function getPaginatedRequests()
    {
        return PaginationRequests::getPaginated('api/v1/roles', [
            'project_uuid' => 'aabb'
        ]);
    }

    public function testResponseOkEmpty()
    {
        $this->roleRepository->getMocker()
            ->getSimulation('countForProject')
            ->whenInputReturn(0, [1, '']);
        $response = $this->get('api/v1/roles?project_uuid=aabb', AuthHeaders::authorize());

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
        $role = new RoleEntity([
            'id' => 1,
            'uuid' => 'aabb',
            'project_id' => 1,
            'name' => 'Product.Access',
            'permissions' => ['product.view']
        ]);
        $this->roleRepository->getMocker()
            ->getSimulation('getForProject')
            ->whenInputReturn([$role], [1, $pagination]);
        $this->roleRepository->getMocker()
            ->getSimulation('countForProject')
            ->whenInputReturn(1, [1, '']);

        $response = $this->get('api/v1/roles?project_uuid=aabb', AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJson([
            'items' => [[
                'uuid' => 'aabb',
                'name' => 'Product.Access',
                'permissions' => ['product.view']
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
        $this->roleRepository->getMocker()
            ->getSimulation('countForProject')
            ->whenInputReturn(20, [1, '']);

        $response = $this->get($url, AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJson([
            'items' => [],
            'meta' => [
                'pagination' => $pagination
            ]
        ]);
    }

    public function testResponseUnauthorized()
    {
        $response = $this->get('api/v1/roles?project_uuid=aabb');

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}
