<?php

namespace App\Domain\Role;

use App\Domain\Project\ProjectAwareEntity;

class RoleEntity extends ProjectAwareEntity
{
    protected $id;
    protected $uuid;
    protected $domain;
    protected $name;
    protected $permissions;

    public function __construct($id, $uuid, $projectId, $domain, $name, $permissions)
    {
        parent::__construct($projectId);
        $this->id = $id;
        $this->uuid = $uuid;
        $this->domain = $domain;
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

    public function getDomain()
    {
        return $this->domain;
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