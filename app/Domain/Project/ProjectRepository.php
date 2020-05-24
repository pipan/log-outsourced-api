<?php

namespace App\Domain\Project;

use App\Domain\ExistsValidable;

interface ProjectRepository extends ExistsValidable
{
    public function getAll();
    public function getByUuid($uuid): ?ProjectEntity;

    public function insert(ProjectEntity $entity): ProjectEntity;
    public function update($id, ProjectEntity $entity): ProjectEntity;

    public function delete(ProjectEntity $uuid);
}