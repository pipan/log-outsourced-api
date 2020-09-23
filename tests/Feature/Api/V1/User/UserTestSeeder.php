<?php

namespace Tests\Feature\Api\V1\User;

use App\Domain\User\UserEntity;
use Tests\Mock\Repository\UserMockRepository;

class UserTestSeeder
{
    public static function seed(UserMockRepository $repository)
    {
        $repository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn(
                new UserEntity(1, 'aabb', 'test@example.com', 1, []),
                ['aabb']
            );
        $repository->getMocker()
            ->getSimulation('getByUsernameForProject')
            ->whenInputReturn(
                new UserEntity(1, 'aabb', 'test@example.com', 1, []),
                ['test@example.com', 1]
            );
    }
}