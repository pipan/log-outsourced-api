<?php

namespace App\Repository\File\Project;

use App\Domain\Project\ProjectEntity;
use App\Domain\Project\ProjectRepository;
use App\Repository\File\IndexGenerator;
use App\Repository\File\JsonFile;
use Lib\Adapter\AdapterHelper;

class ProjectFileRepository implements ProjectRepository
{
    private $readAdapter;
    private $writeAdapter;
    private $jsonFile;
    private $indexGenerator;

    public function __construct()
    {
        $this->readAdapter = new ProjectFileReadAdapter();
        $this->writeAdapter = new ProjectFileWriteAdapter();
        $this->jsonFile = new JsonFile('projects.json');
        $this->indexGenerator = new IndexGenerator('projects');
    }

    public function getAll()
    {
        $adapter = AdapterHelper::listOf($this->readAdapter);
        return $adapter->adapt(
            $this->jsonFile->read()
        );
    }

    public function getByUuid($uuid): ?ProjectEntity
    {
        foreach ($this->getAll() as $project) {
            if ($project->getUuid() == $uuid) {
                return $project;
            }
        }
        return null;
    }

    public function insert(ProjectEntity $entity): ProjectEntity
    {
        $projects = $this->getAll();
        $projects[] = new ProjectEntity(
            $this->indexGenerator->next(),
            $entity->getUuid(),
            $entity->getName()
        );

        $this->save($projects);
        return $entity;
    }

    public function update($id, ProjectEntity $entity): ProjectEntity
    {
        $projects = $this->getAll();
        foreach ($projects as $key => $project) {
            if ($project->getId() == $id) {
                $projects[$key] = $entity;
            }
        }

        $this->save($projects);
        return $entity;
    }

    public function delete(ProjectEntity $entity)
    {
        $projects = $this->getAll();
        foreach ($projects as $key => $project) {
            if ($project->getId() == $entity->getId()) {
                unset($projects[$key]);
            }
        }

        $this->save($projects);
    }

    private function save($all)
    {
        $adapter = AdapterHelper::listOf($this->writeAdapter);
        $this->jsonFile->write(
            $adapter->adapt($all)
        );
    }
}