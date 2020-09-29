<?php

namespace App\Domain\User;

use App\Domain\IdEntity;
use Exception;

class UserEntity extends IdEntity
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

        $validator = UserValidator::forSchema()->forEntity($this);
        if ($validator->fails()) {
            throw new Exception('User entity is invalid: ' . $this->getUuid());
        }
    }

    protected function create($data)
    {
        return new UserEntity($data);
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

    public function withRoles($value)
    {
        return $this->with('roles', $value);
    }
}