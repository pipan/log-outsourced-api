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
        $response = $this->get('api/v1/users?project_uuid=aabb', AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    /**
     * @dataProvider getPaginatedRequests
     */
    public function testResponseOkPaginated($url, $paginationConfig)
    {
        $pagination = new PaginationEntity($paginationConfig);
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

        $response = $this->get($url, AuthHeaders::authorize());

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment([
            [
                'uuid' => 'aabb',
                'username' => 'user@example.com',
                'roles' => ['user']
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
