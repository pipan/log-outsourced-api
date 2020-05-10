<?php

namespace Tests\Repository;

use App\Domain\Project\ProjectEntity;
use App\Domain\Project\ProjectRepository;

class ProjectRepositoryMock implements ProjectRepository
{
    private $all;

    public function __construct($all)
    {
        $this->all = $all;
    }

    public function createProject($id, $hexUuid, $name)
    {
        $this->create(new ProjectEntity($id, hex2bin($hexUuid), $name));
    }

    public function setAll($all)
    {
        $this->all = $all;
    }

    public function getAll()
    {
        return $this->all;
    }

    public function getByUuid($uuid)
    {
        foreach ($this->all as $item) {
            if ($item->getUuid() == $uuid) {
                return  $item;
            }
        }
        return null;
    }

    public function getByHexUuid($hexUuid)
    {
        return $this->getByUuid(hex2bin($hexUuid));
    }

    public function create(ProjectEntity $project)
    {
        $this->all[] = $project;
    }
    
    public function deleteByUuid($uuid)
    {
        $project = $this->getByUuid($uuid);
        $this->lastDeleted = $project;
        if ($project == null) {
            return;
        }
        $key = array_search($this->lastDeleted, $this->all);
        unset($this->all[$key]);
    }

    public function deleteByHexUuid($hexUuid)
    {
        return $this->deleteByUuid(hex2bin($hexUuid));
    }

    public function exists($value)
    {
        return $this->getByHexUuid($value) != null;
    }
}