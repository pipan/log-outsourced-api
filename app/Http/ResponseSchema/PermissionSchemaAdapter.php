<?php

namespace App\Http\ResponseSchema;

use Lib\Adapter\NullAdapter;

class PermissionSchemaAdapter extends NullAdapter
{
    protected function adaptNotNull($item)
    {
        return $item->getName();
    }
}