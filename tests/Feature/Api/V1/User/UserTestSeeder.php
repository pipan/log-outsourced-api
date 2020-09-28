<?php

namespace Tests\Feature\Api\V1\User;

use App\Domain\User\UserEntity;
use Tests\Mock\Repository\UserMockRepository;

class UserTestSeeder
{
    public static function seed(UserMockRepository $repository)
    {
        $user = new UserEntity([
            'id' => 1,
            'uuid' => 'aabb',
            'project_id' => 1,
            'username' => 'test@example.com'
        ]);

        $repository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn($user, ['aabb']);
        $repository->getMocker()
            ->getSimulation('getByUsernameForProject')
            ->whenInputReturn($user, ['test@example.com', 1]);
    }
}