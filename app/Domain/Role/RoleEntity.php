<?php

namespace App\Domain\Role;

use App\Domain\IdEntity;
use App\Repository\Database\DatabaseEntity;
use Exception;

class RoleEntity extends IdEntity
{
    public function __construct($data)
    {
        parent::__construct([
            'id' => $data['id'] ?? 0,
            'uuid' => $data['uuid'] ??'',
            'project_id' => $data['project_id'] ?? 0,
            'name' => $data['name'] ?? '',
            'permissions' => $data['permissions'] ?? []
        ]);

        $validator = RoleValidator::forSchema()->forEntity($this);
        if ($validator->fails()) {
            throw new Exception('Role entity is invalid: ' . $this->getUuid());
        }
    }

    protected function create($data)
    {
        return new RoleEntity($data);
    }

    public function getUuid()
    {
        return $this->data['uuid'];
    }

    public function getProjectId()
    {
        return $this->data['project_id'];
    }

    public function getName()
    {
        return $this->data['name'];
    }

    public function getPermissions()
    {
        return $this->data['permissions'];
    }

    public function withName($value)
    {
        return $this->with('name', $value);
    }

    public function withPermissions($value)
    {
        return $this->with('permissions', $value);
    }

    public function withId($value)
    {
        return $this->with('id', $value);
    }
}