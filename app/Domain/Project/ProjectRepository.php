<?php

namespace App\Domain\Project;

interface ProjectRepository
{
    public function getAll();
    public function getByUuid($uuid): ?ProjectEntity;

    public function insert(ProjectEntity $entity): ProjectEntity;
    public function update($id, ProjectEntity $entity): ProjectEntity;

    public function delete(ProjectEntity $uuid);
}