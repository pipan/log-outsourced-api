<?php

namespace Tests\Repository;

use App\Domain\Handler\HandlerEntity;
use App\Domain\Handler\HandlerRepository;

class HandlerRepositoryMock implements HandlerRepository
{
    protected $all = [];
    protected $entity = null;
    protected $forProject = [];
    protected $byUuidParam = null;
    protected $saved = null;
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

    public function getSaved()
    {
        return $this->saved;
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

    public function getByHexUuid($hexUuid)
    {
        return $this->getByUuid(hex2bin($hexUuid));
    }

    public function save(HandlerEntity $handler)
    {
        $this->saved = $handler;
        return $handler;
    }
    
    public function delete(HandlerEntity $handler)
    {
        $this->deleted = $handler;
        return $handler;
    }
}