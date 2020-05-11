<?php

namespace Tests\Mock\Repository;

use App\Domain\Listener\ListenerEntity;
use App\Domain\Listener\ListenerRepository;

class ListenerMockRepository implements ListenerRepository
{
    protected $all = [];
    protected $entity = null;
    protected $forProject = [];
    protected $byUuidParam = null;
    protected $inserted = null;
    protected $updated = null;
    protected $deleted = null;

    public function withAll($all)
    {
        $this->all = $all;
        return $this;
    }

    public function withEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    public function withForProject($forProject)
    {
        $this->forProject = $forProject;
        return $this;
    }

    public function getByUuidParam()
    {
        return $this->byUuidParam;
    }

    public function getInserted()
    {
        return $this->inserted;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function getDeleted()
    {
        return $this->deleted;
    }

    public function getAll()
    {
        return $this->all;
    }

    public function getForProject($projectId)
    {
        return $this->forProject;
    }

    public function getByUuid($uuid)
    {
        return $this->entity;
    }

    public function insert(ListenerEntity $entity)
    {
        $this->inserted = $entity;
        return $entity;
    }

    public function update($id, ListenerEntity $entity)
    {
        $this->updated = $entity;
        return $entity;
    }
    
    public function delete(ListenerEntity $entity)
    {
        $this->deleted = $entity;
        return $entity;
    }
}