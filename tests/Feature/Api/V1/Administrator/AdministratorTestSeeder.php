<?php

namespace Tests\Feature\Api\V1\Administrator;

use App\Domain\Administrator\AdministratorEntity;
use Tests\Mock\Repository\AdministratorMockRepository;

class AdministratorTestSeeder
{
    public static function seed(AdministratorMockRepository $repository)
    {
        $admins = [
            AdministratorEntity::createWithPassword(1, 'root', 'root'),
            AdministratorEntity::createInvite(2, 'user', '01234'),
            new AdministratorEntity(3, 'broken', 'pass', 'abcd')
        ];

        foreach ($admins as $admin) {
            $repository->getMocker()
                ->getSimulation('get')
                ->whenInputReturn($admin, [$admin->getId()]);
            $repository->getMocker()
                ->getSimulation('getByUsername')
                ->whenInputReturn($admin, [$admin->getUsername()]);
            $repository->getMocker()
                ->getSimulation('getByInviteToken')
                ->whenInputReturn($admin, [$admin->getInviteToken()]);
        }
    }
}