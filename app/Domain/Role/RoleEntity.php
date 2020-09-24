<?php

namespace App\Domain\Role;

use App\Domain\Project\ProjectAwareEntity;

class RoleEntity extends ProjectAwareEntity
{
    protected $id;
    protected $uuid;
    protected $name;
    protected $permissions;

    public function __construct($id, $uuid, $projectId, $name, $permissions)
    {
        parent::__construct($projectId);
        $this->id = $id;
        $this->uuid = $uuid;
        $this->name = $name;
        $this->permissions = $permissions;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPermissions()
    {
        return $this->permissions;
    }
}