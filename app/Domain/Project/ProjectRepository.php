<?php

namespace App\Domain\Project;

use Lib\Pagination\PaginationEntity;

interface ProjectRepository
{
    public function getAll(PaginationEntity $pagination);
    public function count($search);
    public function getByUuid($uuid): ?ProjectEntity;

    public function insert(ProjectEntity $entity): ProjectEntity;
    public function update($id, ProjectEntity $entity): ProjectEntity;

    public function delete(ProjectEntity $uuid);
}