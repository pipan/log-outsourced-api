<?php

namespace App\Domain\Permission;

use App\Domain\IdEntity;
use Lib\Entity\InvalidEntityException;

class PermissionEntity extends IdEntity
{
    public function __construct($data)
    {
        parent::__construct([
            'id' => $data['id'] ?? 0,
            'project_id' => $data['project_id'] ?? 0,
            'name' => $data['name'] ?? ''
        ]);

        $validator = PermissionValidator::forSchema()->forEntity($this);
        if ($validator->fails()) {
            throw new InvalidEntityException('Permission entity is invalid: ' . $this->getId());
        }
    }

    protected function create($data)
    {
        return new PermissionEntity($data);
    }

    public function getName()
    {
        return $this->data['name'];
    }
}