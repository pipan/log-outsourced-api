<?php

namespace App\Domain\Project;

interface ProjectRepository
{
    public function getAll();
    public function getByUuid($uuid);
    public function getByHexUuid($hexUuid);

    public function create(ProjectEntity $project);

    public function deleteByHexUuid($hexUuid);
    public function deleteByUuid($uuid);
}