<?php

namespace App\Domain\Role;

use App\Domain\ExistsValidable;

interface RoleRepository extends ExistsValidable
{
    public function getAll();
    public function getByUuid($uuid): ?RoleEntity;

    public function insert(RoleEntity $entity): RoleEntity;
    public function update($id, RoleEntity $entity): RoleEntity;

    public function delete(RoleEntity $entity);
}