<?php

namespace Tests\Mock\Repository;

use App\Domain\Administrator\AdministratorEntity;
use App\Domain\Administrator\AdministratorRepository;
use Tests\Mock\Mocker;

class AdministratorMockRepository implements AdministratorRepository
{
    protected $mocker;

    public function __construct()
    {
        $this->mocker = new Mocker();
    }

    public function getMocker(): Mocker
    {
        return $this->mocker;
    }

    public function get($id): ?AdministratorEntity
    {
        return $this->getMocker()->getSimulation('get')
            ->execute([$id]);
    }

    public function getByUsername($username): ?AdministratorEntity
    {
        return $this->mocker->getSimulation('getByUsername')
            ->execute([$username]);
    }

    public function getByInviteToken($token): ?AdministratorEntity
    {
        return $this->mocker->getSimulation('getByInviteToken')
            ->execute([$token]);
    }

    public function insert(AdministratorEntity $entity): AdministratorEntity
    {
        $this->mocker->getSimulation('insert')
            ->execute([$entity]);
        return $entity;
    }

    public function update($id, AdministratorEntity $entity): AdministratorEntity
    {
        $this->mocker->getSimulation('update')
            ->execute([$id, $entity]);
        return $entity;
    }

    public function delete(AdministratorEntity $entity)
    {
        return $this->mocker->getSimulation('delete')
            ->execute([$entity]);
    }
}