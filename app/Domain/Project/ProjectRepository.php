<?php

namespace App\Domain\Project;

use App\Domain\ExistsValidable;

interface ProjectRepository extends ExistsValidable
{
    public function getAll();
    public function getByUuid($uuid);

    public function insert(ProjectEntity $project);
    public function update($id, ProjectEntity $project);

    public function delete(ProjectEntity $uuid);
}