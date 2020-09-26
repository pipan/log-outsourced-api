<?php

namespace Tests\Feature\Api\V1\Project;

use App\Domain\Project\ProjectEntity;
use Tests\Mock\Repository\ProjectMockRepository;

class ProjectTestSeeder
{
    public static function seed(ProjectMockRepository $projectRepository)
    {
        $projects = [
            new ProjectEntity([
                'id' => 1,
                'uuid' => 'aabb',
                'name' => 'test project'
            ])
        ];

        $projectRepository->getMocker()
            ->getSimulation('getByUuid')
            ->whenInputReturn($projects[0], ['aabb']);

        $projectRepository->getMocker()
            ->getSimulation('getAll')
            ->whenInputReturn($projects, []);
    }
}