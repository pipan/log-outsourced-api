<?php

namespace App\Domain\Role;

use App\Domain\Project\ProjectAwareRepository;

interface RoleRepository extends ProjectAwareRepository
{
    public function getForUser($userId);

    public function getByUuid($uuid): ?RoleEntity;
    public function findList($ids);
    public function findListForProjectByNames($projectId, $names);

    public function insert(RoleEntity $entity): RoleEntity;
    public function update($id, RoleEntity $entity): RoleEntity;

    public function delete(RoleEntity $entity);
}