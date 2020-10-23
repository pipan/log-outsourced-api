<?php

namespace App\Domain\Settings\ProjectKey;

use App\Domain\IdEntity;
use Exception;

class ProjectKeyEntity extends IdEntity
{
    public function __construct($data)
    {
        parent::__construct([
            'id' => $data['id'] ?? 0,
            'key' => $data['key'] ?? '',
            'project_id' => $data['project_id'] ?? 0,
            'name' => $data['name'] ?? ''
        ]);

        // $validator = RoleValidator::forSchema()->forEntity($this);
        // if ($validator->fails()) {
        //     throw new Exception('Role entity is invalid: ' . $this->getUuid());
        // }
    }

    protected function create($data)
    {
        return new ProjectKeyEntity($data);
    }

    public function getKey()
    {
        return $this->data['key'];
    }

    public function getProjectId()
    {
        return $this->data['project_id'];
    }

    public function getName()
    {
        return $this->data['name'];
    }

    public function withName($value)
    {
        return $this->with('name', $value);
    }

    public function withId($value)
    {
        return $this->with('id', $value);
    }
}