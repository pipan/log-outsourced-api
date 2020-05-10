<?php

namespace Tests\Repository;

use App\Domain\Project\ProjectEntity;
use App\Domain\Project\ProjectRepository;

class ProjectRepositoryMock implements ProjectRepository
{
    private $all = [];
    private $entity = null;
    private $saved = null;
    private $deleted = null;

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

    public function getDeleted()
    {
        return $this->deleted;
    }

    public function getSaved()
    {
        return $this->saved;
    }

    public function getAll()
    {
        return $this->all;
    }

    public function getByUuid($uuid)
    {
        return $this->entity;
    }

    public function getByHexUuid($hexUuid)
    {
        return $this->getByUuid(hex2bin($hexUuid));
    }

    public function save(ProjectEntity $project)
    {
        $this->saved = $project;
    }
    
    public function delete(ProjectEntity $project)
    {
        $this->deleted = $project;
        return $project;
    }

    public function exists($value)
    {
        return $this->getByHexUuid($value) != null;
    }
}