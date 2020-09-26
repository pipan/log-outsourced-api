<?php

namespace Tests\Feature\Api\V1\Role;

use App\Domain\Role\RoleEntity;
use Tests\Mock\Repository\RoleMockRepository;

class RoleTestSeeder
{
    public static function seed(RoleMockRepository $repository)
    {
        $roles = [
            new RoleEntity([
                'id' => 1,
                'uuid' => 'aabb',
                'project_id' => 1,
                'name' => 'Product.Access',
                'permissions' => ['product.view']
            ])
        ];
        
        foreach ($roles as $role) {
            $repository->getMocker()
                ->getSimulation('getByUuid')
                ->whenInputReturn($role, [$role->getUuid()]);
        }
    }
}