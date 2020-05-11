<?php

namespace App\Http\ResponseSchema;

use Lib\Adapter\Adapter;

class ProjectResponseSchemaAdapter implements Adapter
{
    public function adapt($item)
    {
        return [
            'uuid' => $item->getUuid(),
            'name' => $item->getName()
        ];
    }
}