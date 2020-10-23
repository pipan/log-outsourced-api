<?php

namespace Tests\Mock\Repository;

use App\Domain\Settings\ProjectKey\ProjectKeyEntity;
use App\Domain\Settings\ProjectKey\ProjectKeyRepository;
use Lib\Pagination\PaginationEntity;
use Tests\Mock\Mocker;

class ProjectKeyMockRepository implements ProjectKeyRepository
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

    public function getForProject($projectId, PaginationEntity $pagination)
    {
        return $this->mocker->getSimulation('getForProject')
            ->execute([$projectId, $pagination]);
    }

    public function countForProject($projectId, $search)
    {
        return $this->getMocker()->getSimulation('countForProject')
            ->execute([$projectId, $search]);
    }

    public function getByKey($key): ?ProjectKeyEntity
    {
        return $this->mocker->getSimulation('getByKey')
            ->execute([$key]);
    }

    public function insert(ProjectKeyEntity $entity): ProjectKeyEntity
    {
        $this->mocker->getSimulation('insert')
            ->execute([$entity]);
        return $entity;
    }

    public function update($id, ProjectKeyEntity $entity): ProjectKeyEntity
    {
        $this->mocker->getSimulation('update')
            ->execute([$id, $entity]);
        return $entity;
    }
    
    public function delete(ProjectKeyEntity $entity)
    {
        return $this->mocker->getSimulation('delete')
            ->execute([$entity]);
    }
}