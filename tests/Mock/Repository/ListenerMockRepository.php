<?php

namespace Tests\Mock\Repository;

use App\Domain\Listener\ListenerEntity;
use App\Domain\Listener\ListenerRepository;
use Tests\Mock\Mocker;

class ListenerMockRepository implements ListenerRepository
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

    public function get($id): ?ListenerEntity
    {
        return $this->mocker->getSimulation('get')
            ->execute([$id]);
    }

    public function getForProject($projectId, $config = [])
    {
        return $this->mocker->getSimulation('getForProject')
            ->execute([$projectId, $config]);
    }

    public function getByUuid($uuid): ?ListenerEntity
    {
        return $this->mocker->getSimulation('getByUuid')
            ->execute([$uuid]);
    }

    public function insert(ListenerEntity $entity): ListenerEntity
    {
        $this->mocker->getSimulation('insert')
            ->execute([$entity]);
        return $entity;
    }

    public function update($id, ListenerEntity $entity): ListenerEntity
    {
        $this->mocker->getSimulation('update')
            ->execute([$id, $entity]);
        return $entity;
    }
    
    public function delete(ListenerEntity $entity)
    {
        return $this->mocker->getSimulation('delete')
            ->execute([$entity]);
    }
}