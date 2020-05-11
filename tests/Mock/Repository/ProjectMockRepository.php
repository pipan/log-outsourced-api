<?php

namespace Tests\Mock\Repository;

use App\Domain\Project\ProjectEntity;
use App\Domain\Project\ProjectRepository;

class ProjectMockRepository implements ProjectRepository
{
    private $all = [];
    private $entity = null;
    private $inserted = null;
    private $updated = null;
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

    public function getInserted()
    {
        return $this->inserted;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function getAll()
    {
        return $this->all;
    }

    public function getByUuid($uuid)
    {
        return $this->entity;
    }

    public function insert(ProjectEntity $project)
    {
        $this->inserted = $project;
    }

    public function update($id, ProjectEntity $project)
    {
        $this->updated = $project;
    }
    
    public function delete(ProjectEntity $project)
    {
        $this->deleted = $project;
        return $project;
    }

    public function exists($value)
    {
        return $this->getByUuid($value) != null;
    }
}