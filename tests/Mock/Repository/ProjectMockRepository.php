<?php

namespace Tests\Mock\Repository;

use App\Domain\Project\ProjectEntity;
use App\Domain\Project\ProjectRepository;
use Lib\Pagination\PaginationEntity;
use Tests\Mock\Mocker;

class ProjectMockRepository implements ProjectRepository
{
    private $mocker;

    public function __construct()
    {
        $this->mocker = new Mocker();
    }

    public function getMocker(): Mocker
    {
        return $this->mocker;
    }

    public function getAll(PaginationEntity $pagination)
    {
        return $this->mocker->getSimulation('getAll')
            ->execute([$pagination]);
    }

    public function count($search)
    {
        return $this->getMocker()->getSimulation('count')
            ->execute([$search]);
    }

    public function getByUuid($uuid): ?ProjectEntity
    {
        return $this->mocker->getSimulation('getByUuid')
            ->execute([$uuid]);
    }

    public function insert(ProjectEntity $project): ProjectEntity
    {
        $this->mocker->getSimulation('insert')
            ->execute([$project]);
        return $project;
    }

    public function update($id, ProjectEntity $project): ProjectEntity
    {
        $this->mocker->getSimulation('update')
            ->execute([$id, $project]);
        return $project;
    }
    
    public function delete(ProjectEntity $project)
    {
        return $this->mocker->getSimulation('delete')
            ->execute([$project]);
    }
}