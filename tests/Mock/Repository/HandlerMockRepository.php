<?php

namespace Tests\Mock\Repository;

use App\Domain\Handler\HandlerEntity;
use App\Domain\Handler\HandlerRepository;
use Tests\Mock\Mocker;

class HandlerMockRepository implements HandlerRepository
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

    public function getAll()
    {
        return $this->mocker->getSimulation('getAll')
            ->execute();
    }

    public function getBySlug($slug): ?HandlerEntity
    {
        return $this->mocker->getSimulation('getBySlug')
            ->execute([$slug]);
    }

    public function insert(HandlerEntity $entity): HandlerEntity
    {
        $this->mocker->getSimulation('insert')
            ->execute([$entity]);
        return $entity;
    }
    
    public function delete(HandlerEntity $entity)
    {
        $this->mocker->getSimulation('delete')
            ->execute([$entity]);
        return $entity;
    }

    public function exists($value)
    {
        return $this->mocker->getSimulation('exists')
            ->execute([$value]);
    }
}