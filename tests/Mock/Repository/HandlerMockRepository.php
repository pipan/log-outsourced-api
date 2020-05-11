<?php

namespace Tests\Mock\Repository;

use App\Domain\Handler\HandlerEntity;
use App\Domain\Handler\HandlerRepository;

class HandlerMockRepository implements HandlerRepository
{
    protected $all = [];
    protected $entity = null;
    protected $inserted = null;
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

    public function getInserted()
    {
        return $this->inserted;
    }

    public function getDeleted()
    {
        return $this->deleted;
    }

    public function getAll()
    {
        return $this->all;
    }

    public function getBySlug($slug)
    {
        return $this->entity;
    }

    public function insert(HandlerEntity $entity)
    {
        $this->inserted = $entity;
        return $entity;
    }
    
    public function delete(HandlerEntity $entity)
    {
        $this->deleted = $entity;
        return $entity;
    }
}