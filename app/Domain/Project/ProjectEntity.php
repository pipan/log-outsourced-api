<?php

namespace App\Domain\Project;

use Exception;
use Lib\Entity\Entity;

class ProjectEntity extends Entity
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

    public function getId()
    {
        return $this->data['id'];
    }

    public function getUuid()
    {
        return $this->data['uuid'];
    }

    public function getName()
    {
        return $this->data['name'];
    }

    protected function with($key, $value)
    {
        $data = $this->toArray();
        $data[$key] = $value;
        return new ProjectEntity($data);
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