<?php

namespace App\Repository\File\Role;

use App\Domain\Role\RoleEntity;
use Lib\Adapter\Adapter;

class RoleFileReadAdapter implements Adapter
{
    public function adapt($item)
    {
        if ($item === null) {
            return null;
        }
        return new RoleEntity(
            $item['id'],
            $item['uuid'],
            $item['domain'],
            $item['name'],
            $item['permissions'] ?? []
        );
    }
}