<?php

namespace Tests\Mock\Repository;

use App\Domain\Permission\PermissionEntity;
use App\Domain\Permission\PermissionRepository;
use Tests\Mock\Mocker;

class PermissionMockRepository implements PermissionRepository
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

    public function getAllForProject($projectId)
    {
        return $this->mocker->getSimulation('getAllForProject')
            ->execute([$projectId]);
    }

    public function getByNameForProject($name, $projectId)
    {
        return $this->mocker->getSimulation('getByNameForProject')
            ->execute([$name, $projectId]);
    }

    public function insert(PermissionEntity $entity): PermissionEntity
    {
        $this->mocker->getSimulation('insert')
            ->execute([$entity]);
        return $entity;
    }

    public function delete(PermissionEntity $entity)
    {
        return $this->getMocker()->getSimulation('delete')
            ->execute([$entity]);
    }
}