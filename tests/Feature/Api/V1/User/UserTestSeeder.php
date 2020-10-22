<?php

namespace Tests\Feature\Api\V1\User;

use App\Domain\User\UserEntity;
use Tests\Mock\Repository\UserMockRepository;

class UserTestSeeder
{
    public static function seed(UserMockRepository $repository)
    {
        $users = [
            new UserEntity([
                'id' => 1,
                'uuid' => 'aabb',
                'project_id' => 1,
                'username' => 'admin'
            ]),
            new UserEntity([
                'id' => 2,
                'uuid' => 'ccdd',
                'project_id' => 2,
                'username' => 'exists'
            ]),
        ];

        foreach ($users as $user) {
            $repository->getMocker()
                ->getSimulation('getByUuid')
                ->whenInputReturn($user, [$user->getUuid()]);
            $repository->getMocker()
                ->getSimulation('getByUsernameForProject')
                ->whenInputReturn($user, [$user->getUsername(), $user->getProjectId()]);
        }
    }
}