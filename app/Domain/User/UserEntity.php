<?php

namespace App\Domain\User;

use Lib\Entity\Entity;

class UserEntity extends Entity
{
    public function __construct($data)
    {
        parent::__construct([
            'id' => $data['id'] ?? 0,
            'uuid' => $data['uuid'] ?? '',
            'project_id' => $data['project_id'] ?? 0,
            'username' => $data['username'] ?? '',
            'roles' => $data['roles'] ?? []
        ]);

        $validator = '';
    }

    public function getId()
    {
        return $this->data['id'];
    }

    public function getUuid()
    {
        return $this->data['uuid'];
    }

    public function getProjectId()
    {
        return $this->data['project_id'];
    }

    public function getUsername()
    {
        return $this->data['username'];
    }

    public function getRoles()
    {
        return $this->data['roles'];
    }

    private function with($key, $value)
    {
        $data = $this->toArray();
        $data[$key] = $value;
        return new UserEntity($data);
    }

    public function withRoles($value)
    {
        return $this->with('roles', $value);
    }
}