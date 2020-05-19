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

    public function getForProject($projectId)
    {
        return $this->mocker->getSimulation('getForProject')
            ->execute([$projectId]);
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