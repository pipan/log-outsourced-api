<?php

namespace App\Repository\Eloquent\Role;

use Lib\Adapter\Adapter;

class WriteAdapter implements Adapter
{
    public function adapt($item)
    {
        if ($item === null) {
            return null;
        }

        $role = new Role();
        if ($item->getId() > 0) {
            $role->id = $item->getId();
        }
        $role->uuid = $item->getUuid();
        $role->projectId = $item->getProjectId();
        $role->name = $item->getName();

        return $role;
    }
}