<?php

namespace Tests\Mock\Repository;

use App\Domain\Role\RoleEntity;
use App\Domain\Role\RoleRepository;
use Lib\Pagination\PaginationEntity;
use Tests\Mock\Mocker;

class RoleMockRepository implements RoleRepository
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

    public function getByUuid($uuid): ?RoleEntity
    {
        return $this->mocker->getSimulation('getByUuid')
            ->execute([$uuid]);
    }

    public function get($id): ?RoleEntity
    {
        return $this->mocker->getSimulation('get')
            ->execute([$id]);
    }

    public function insert(RoleEntity $entity): RoleEntity
    {
        $this->mocker->getSimulation('insert')
            ->execute([$entity]);
        return $entity;
    }

    public function update($id, RoleEntity $entity): RoleEntity
    {
        $this->mocker->getSimulation('update')
            ->execute([$id, $entity]);
        return $entity;
    }
    
    public function delete(RoleEntity $entity)
    {
        return $this->mocker->getSimulation('delete')
            ->execute([$entity]);
    }
}