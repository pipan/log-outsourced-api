<?php

namespace Tests\Feature\Api\V1\Permission;

use App\Domain\Permission\PermissionEntity;
use Tests\Mock\Repository\PermissionMockRepository;

class PermissionTestSeeder
{
    public static function seed(PermissionMockRepository $repository)
    {
        $permission = new PermissionEntity([
            'id' => 1,
            'project_id' => 1,
            'name' => 'user.view'
        ]);

        $repository->getMocker()
            ->getSimulation('getByNameForProject')
            ->whenInputReturn($permission, ['user.view', 1]);
    }
}