<?php

namespace App\Domain\Permission;

interface PermissionRepository
{
    public function getAllForProject($projectId);

    public function getByNameForProject($name, $projectId);

    public function insert(PermissionEntity $entity): ?PermissionEntity;
}