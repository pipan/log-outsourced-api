<?php

namespace App\Domain\User;

class UserEntity
{
    private $id;
    private $uuid;
    private $username;
    private $roles;
    private $projectId;

    public function __construct($id, $uuid, $username, $projectId, $roles)
    {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->username = $username;
        $this->projectId = $projectId;
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

    public function getProjectId()
    {
        return $this->projectId;
    }

    public function getRoles()
    {
        return $this->roles;
    }
}