<?php

namespace Tests\Mock\Repository;

use App\Domain\User\UserEntity;
use App\Domain\User\UserRepository;
use Lib\Pagination\PaginationEntity;
use Tests\Mock\Mocker;

class UserMockRepository implements UserRepository
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

    public function getByUuid($uuid): ?UserEntity
    {
        return $this->mocker->getSimulation('getByUuid')
            ->execute([$uuid]);
    }

    public function getByUsernameForProject($username, $projectId): ?UserEntity
    {
        return $this->mocker->getSimulation('getByUsernameForProject')
            ->execute([$username, $projectId]);
    }

    public function insert(UserEntity $entity): UserEntity
    {
        $this->mocker->getSimulation('insert')
            ->execute([$entity]);
        return $entity;
    }

    public function update($id, UserEntity $entity): UserEntity
    {
        $this->mocker->getSimulation('update')
            ->execute([$id, $entity]);
        return $entity;
    }

    public function delete(UserEntity $entity)
    {
        return $this->mocker->getSimulation('delete')
            ->execute([$entity]);
    }
}