<?php

namespace App\Domain\Project;

use App\Domain\ExistsValidable;

interface ProjectRepository extends ExistsValidable
{
    public function getAll();
    public function getByUuid($uuid);
    public function getByHexUuid($hexUuid);

    public function save(ProjectEntity $project);

    public function delete(ProjectEntity $hexUuid);
}