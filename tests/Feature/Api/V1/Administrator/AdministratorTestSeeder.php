<?php

namespace Tests\Feature\Api\V1\Administrator;

use App\Domain\Administrator\AdministratorEntity;
use Tests\Mock\Repository\AdministratorMockRepository;

class AdministratorTestSeeder
{
    public static function seed(AdministratorMockRepository $repository)
    {
        $admins = [
            new AdministratorEntity([
                'id' => 1,
                'uuid' => 'aabb',
                'username' => 'admin',
                'password' => 'admin',
            ]),
            new AdministratorEntity([
                'id' => 2,
                'uuid' => 'aabbcc',
                'username' => 'user',
                'invite_token' => '01234'
            ]),
            new AdministratorEntity([
                'id' => 3,
                'uuid' => 'aabbccdd',
                'username' => 'broken',
                'password' => 'pass',
                'invite_token' => 'abcd'
            ])
        ];

        foreach ($admins as $admin) {
            $repository->getMocker()
                ->getSimulation('get')
                ->whenInputReturn($admin, [$admin->getId()]);
            $repository->getMocker()
                ->getSimulation('getByUuid')
                ->whenInputReturn($admin, [$admin->getUuid()]);
            $repository->getMocker()
                ->getSimulation('getByUsername')
                ->whenInputReturn($admin, [$admin->getUsername()]);
            $repository->getMocker()
                ->getSimulation('getByInviteToken')
                ->whenInputReturn($admin, [$admin->getInviteToken()]);
        }
    }
}