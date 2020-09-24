<?php

namespace App\Repository\File\Role;

use Lib\Adapter\Adapter;

class RoleFileWriteAdapter implements Adapter
{
    public function adapt($item)
    {
        if ($item === null) {
            return null;
        }
        return [
            'id' => $item->getId(),
            'uuid' => $item->getUuid(),
            'name' => $item->getName(),
            'permissions' => $item->getPermissions()
        ];
    }
}