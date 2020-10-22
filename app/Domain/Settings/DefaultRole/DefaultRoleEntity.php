<?php

namespace App\Domain\Settings\DefaultRole;

use App\Domain\IdEntity;

class DefaultRoleEntity extends IdEntity
{
    public function __construct($data)
    {
        parent::__construct([
            'id' => $data['id'] ?? 0,
            'project_id' => $data['project_id'] ?? 0,
            'role_id' => $data['role_id'] ?? 0,
            'role' => $data['role'] ?? null
        ]);
    }

    protected function create($data)
    {
        return new DefaultRoleEntity($data);
    }

    public function getId()
    {
        return $this->data['id'];
    }

    public function getProjectId()
    {
        return $this->data['project_id'];
    }

    public function getRoleId()
    {
        return $this->data['role_id'];
    }

    public function getRole()
    {
        return $this->data['role'];
    }

    public function withId($value)
    {
        return $this->with('id', $value);
    }

    public function withRole($value)
    {
        return $this->with('role', $value);
    }
}