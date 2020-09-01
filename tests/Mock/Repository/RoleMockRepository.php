<?php

namespace Tests\Mock\Repository;

use App\Domain\Role\RoleEntity;
use App\Domain\Role\RoleRepository;
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

    public function getAll()
    {
        return $this->mocker->getSimulation('getAll')
            ->execute();
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

    public function exists($value)
    {
        return $this->mocker->getSimulation('exists')
            ->execute([$value]);
    }
}