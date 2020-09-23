<?php

namespace App\Domain\User;

use App\Domain\Project\ProjectAwareEntity;

class UserEntity extends ProjectAwareEntity
{
    private $id;
    private $uuid;
    private $username;
    private $roles;

    public function __construct($id, $uuid, $username, $projectId, $roles)
    {
        parent::__construct($projectId);
        $this->id = $id;
        $this->uuid = $uuid;
        $this->username = $username;
        $this->roles = $roles;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getRoles()
    {
        return $this->roles;
    }
}