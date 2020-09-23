<?php

namespace App\Domain\Role;

use App\Domain\Project\ProjectAwareRepository;

interface RoleRepository extends ProjectAwareRepository
{
    public function getByUuid($uuid): ?RoleEntity;

    public function insert(RoleEntity $entity): RoleEntity;
    public function update($id, RoleEntity $entity): RoleEntity;

    public function delete(RoleEntity $entity);
}