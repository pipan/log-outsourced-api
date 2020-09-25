<?php

namespace App\Repository\Eloquent\Role;

use App\Domain\Role\RoleEntity;
use App\Domain\Role\RoleRepository;
use Lib\Adapter\AdapterHelper;

class RoleEloquentRepository implements RoleRepository
{
    private $readAdapter;
    private $writeAdapter;

    public function __construct()
    {
        $this->readAdapter = new ReadAdapter();
        $this->writeAdapter = new WriteAdapter();
    }

    public function getForProject($projectId, $config = [])
    {
        $result = Role::where('project_id', '=', $projectId)
            ->get();

        $adapter = AdapterHelper::listOf($this->readAdapter);
        return $adapter->adapt($result);
    }

    public function getByUuid($uuid): ?RoleEntity
    {
        $result = Role::where('uuid', '=', $uuid)
            ->first();
        
        return $this->readAdapter->adapt($result);
    }

    public function update($id, RoleEntity $entity): RoleEntity
    {
        $role = Role::find($id);
        $role->name = $entity->getName();
        $role->save();

        // TODO: replace all permissions

        return $this->readAdapter->adapt($role);
    }

    public function insert(RoleEntity $entity): RoleEntity
    {
        $role = $this->writeAdapter->adapt($entity);
        $role->save();

        return $this->readAdapter->adapt($role);
    }

    public function delete(RoleEntity $entity)
    {
        Role::destroy($entity->getId());
    }
}