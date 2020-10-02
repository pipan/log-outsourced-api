<?php

namespace Tests\Feature\Api\V1\User;

use App\Domain\User\UserEntity;
use Lib\Pagination\PaginationEntity;
use Tests\Feature\Api\V1\Administrator\AuthHeaders;
use Tests\Feature\Api\V1\ControllerActionTestCase;
use Tests\Feature\Api\V1\PaginationRequests;
use Tests\Feature\Api\V1\Project\ProjectTestSeeder;

class UserIndexTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ProjectTestSeeder::seed($this->projectRepository);
    }

    public function getPaginatedRequests()
    {
        return PaginationRequests::getPaginated('api/v1/users', [
            'project_uuid' => 'aabb'
        ]);
    }

    public function testResponseOkEmpty()
    {
        $pagination = (new PaginationEntity([]))
            ->searchBy('username')
            ->orderBy('username');
        $this->userRepository->getMocker()
            ->getSimulation('getForProject')
            ->whenInputReturn([], [1, $pagination]);
        $this->userRepository->getMocker()
            ->getSimulation('countForProject')
            ->whenInputReturn(0, [1, '']);

        $response = $this->get('api/v1/users?project_uuid=aabb', AuthHeaders::authorize());

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
            ->searchBy('username')
            ->orderBy('username');
        $user = new UserEntity([
            'id' => 1,
            'uuid' => 'aabb',
            'project_id' => 1,
            'username' => 'user@example.com',
            'roles' => ['user']
        ]);
        $this->userRepository->getMocker()
            ->getSimulation('getForProject')
            ->whenInputReturn([$user], [1, $pagination]);
        $this->userRepository->getMocker()
            ->getSimulation('countForProject')
            ->whenInputReturn(1, [1, '']);

        $response = $this->get('api/v1/users?project_uuid=aabb', AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJson([
            'items' => [[
                'uuid' => 'aabb',
                'username' => 'user@example.com',
                'roles' => ['user']
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
        $this->userRepository->getMocker()
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
        $response = $this->get('api/v1/users?project_uuid=aabb');

        $response->assertStatus(401);
        $response->assertJsonStructure(['message']);
    }
}
