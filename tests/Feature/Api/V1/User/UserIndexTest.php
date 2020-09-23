<?php

namespace Tests\Feature\Api\V1\User;

use App\Domain\User\UserEntity;
use Tests\Feature\Api\V1\ControllerActionTestCase;
use Tests\Feature\Api\V1\Project\ProjectTestSeeder;

class UserIndexTest extends ControllerActionTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ProjectTestSeeder::seed($this->projectRepository);
    }

    public function testResponseOkEmpty()
    {
        $response = $this->get('api/v1/users?project_uuid=aabb');

        $response->assertStatus(200);
        $response->assertJsonCount(0);
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
        $response->assertJsonCount(1);
        $response->assertJsonFragment([
            [
                'uuid' => 'aabb',
                'username' => 'user@example.com',
                'roles' => ['user']
            ]
        ]);
    }
}
