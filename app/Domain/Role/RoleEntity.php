<?php

namespace App\Domain\Role;

use Exception;
use Lib\Entity\Entity;

class RoleEntity extends Entity
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

    public function getName()
    {
        return $this->data['name'];
    }

    public function getPermissions()
    {
        return $this->data['permissions'];
    }

    protected function with($key, $value)
    {
        $data = $this->toArray();
        $data[$key] = $value;
        return new RoleEntity($data);
    }

    public function withName($value)
    {
        return $this->with('name', $value);
    }

    public function withPermissions($value)
    {
        return $this->with('permissions', $value);
    }
}