<?php

namespace App\Domain\Role;

class RoleEntity
{
    protected $id;
    protected $uuid;
    protected $domain;
    protected $name;
    protected $permissions;

    public function __construct($id, $uuid, $domain, $name, $permissions)
    {
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