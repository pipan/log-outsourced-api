<?php

namespace Tests\Feature\Api\V1\User;

use App\Domain\Project\ProjectEntity;
use App\Domain\User\UserEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;

class UserIndexTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->projectRepository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn(
                new ProjectEntity(1, 'aabb', 'project'),
                ['aabb']
            );
        $this->projectRepository->getMocker()
            ->getSimulation('exists')
            ->whenInputReturn(true, ['aabb']);
    }

    public function testResponseOkEmpty()
    {
        $response = $this->get('api/v1/users?project_uuid=aabb');

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    public function testResponseOkNotEmpty()
    {
        $this->userRepository->getMocker()
            ->getSimulation('getForProject')
            ->whenInputReturn([
                new UserEntity(1, 'aabb', 'user@example.com', 1, ['user']),
            ], [1, ['limit' => 25, 'page' => 1, 'search' => '']]);

        $response = $this->get('api/v1/users?project_uuid=aabb');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            [
                'uuid' => 'aabb',
                'username' => 'user@example.com',
                'roles' => ['user']
            ]
        ]);
    }

    public function testResponseNotFoundIfProjectNotExists()
    {
        $response = $this->get('api/v1/users?project_uuid=9988');
        $response->assertStatus(404);
    }

    public function testResponseInvalidIfMissingProjectUuid()
    {
        $response = $this->get('api/v1/users');
        $response->assertStatus(422);
    }

    public function testResponseInvalidIfEmptyProjectUuid()
    {
        $response = $this->get('api/v1/users?project_uuid');
        $response->assertStatus(422);
    }
}
