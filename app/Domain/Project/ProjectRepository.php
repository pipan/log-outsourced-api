<?php

namespace App\Domain\Project;

use App\Domain\ExistsValidable;

interface ProjectRepository extends ExistsValidable
{
    public function getAll();
    public function getByUuid($uuid): ?ProjectEntity;

    public function insert(ProjectEntity $project): ProjectEntity;
    public function update($id, ProjectEntity $project): ProjectEntity;

    public function delete(ProjectEntity $uuid);
}