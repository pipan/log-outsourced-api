<?php

namespace App\Http\ResponseSchema;

use Lib\Adapter\Adapter;

class RoleSchemaAdapter implements Adapter
{
    public function adapt($item)
    {
        return [
            'uuid' => $item->getUuid(),
            'domain' => $item->getDomain(),
            'name' => $item->getName(),
            'permissions' => $item->getPermissions()
        ];
    }
}