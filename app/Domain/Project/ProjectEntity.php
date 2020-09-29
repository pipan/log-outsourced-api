<?php

namespace App\Domain\Project;

use App\Domain\IdEntity;
use Exception;

class ProjectEntity extends IdEntity
{
    public function __construct($data)
    {
        parent::__construct([
            'id' => $data['id'] ?? 0,
            'uuid' => $data['uuid'] ??'',
            'name' => $data['name'] ?? ''
        ]);

        $validator = ProjectValidator::forSchema()->forEntity($this);
        if ($validator->fails()) {
            throw new Exception('Project entity is incorrect: ' . ($this->getUuid()));
        }
    }

    protected function create($data)
    {
        return new ProjectEntity($data);
    }

    public function getUuid()
    {
        return $this->data['uuid'];
    }

    public function getName()
    {
        return $this->data['name'];
    }

    public function withName($value)
    {
        return $this->with('name', $value);
    }

    public function withUuid($value)
    {
        return $this->with('uuid', $value);
    }
}