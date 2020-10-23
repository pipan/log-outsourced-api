<?php

namespace Tests\Feature\Api\V1\Settings\ProjectKey;

use App\Domain\Settings\ProjectKey\ProjectKeyEntity;
use Tests\Mock\Repository\ProjectKeyMockRepository;

class ProjectKeyTestSeeder
{
    public static function seed(ProjectKeyMockRepository $repository)
    {
        $keys = [
            new ProjectKeyEntity([
                'id' => 1,
                'key' => 'aabb',
                'project_id' => 1,
                'name' => 'Production'
            ])
        ];
        
        foreach ($keys as $key) {
            $repository->getMocker()
                ->getSimulation('getByKey')
                ->whenInputReturn($key, [$key->getKey()]);
        }
    }
}