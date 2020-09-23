<?php

namespace Tests\Feature\Api\V1\Role;

use App\Domain\Role\RoleEntity;
use Tests\Mock\Repository\RoleMockRepository;

class RoleTestSeeder
{
    public static function seed(RoleMockRepository $repository)
    {
        $roles = [
            new RoleEntity(1, 'aabb', 1, 'Product', 'Access', ['product.view'])
        ];
        foreach ($roles as $role) {
            $repository->getMocker()
                ->getSimulation('getByUuid')
                ->whenInputReturn($role, [$role->getUuid()]);
            $repository->getMocker()
                ->getSimulation('exists')
                ->whenInputReturn(true, [$role->getUuid()]);
        }
    }
}