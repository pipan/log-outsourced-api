<?php

namespace Tests\Feature\Api\V1\Project;

use App\Domain\Project\ProjectEntity;
use Tests\Mock\Repository\ProjectMockRepository;

class ProjectTestSeeder
{
    public static function seed(ProjectMockRepository $projectRepository)
    {
        $projectRepository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn(
                new ProjectEntity(1, 'aabb', 'test project'),
                ['aabb']
            );
        $projectRepository->getMocker()
            ->getSimulation('exists')
            ->whenInputReturn(true, ['aabb']);
    }
}