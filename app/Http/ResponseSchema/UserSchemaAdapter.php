<?php

namespace App\Http\ResponseSchema;

use Lib\Adapter\Adapter;

class UserSchemaAdapter implements Adapter
{
    public function adapt($item)
    {
        return [
            'uuid' => $item->getUuid(),
            'username' => $item->getUsername(),
            'roles' => $item->getRoles()
        ];
    }
}