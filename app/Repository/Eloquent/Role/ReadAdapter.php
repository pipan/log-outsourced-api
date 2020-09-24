<?php

namespace App\Repository\Eloquent\Role;

use App\Domain\Role\RoleEntity;
use Lib\Adapter\Adapter;

class ReadAdapter implements Adapter
{
    public function adapt($item)
    {
        if ($item === null) {
            return null;
        }
        return new RoleEntity(
            $item->id,
            $item->uuid,
            $item->projectId,
            $item->name,
            []
        );
    }
}