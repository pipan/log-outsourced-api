<?php

namespace App\Http\ResponseSchema;

use Lib\Adapter\NullAdapter;

class DefaultRoleSchema extends NullAdapter
{
    protected function adaptNotNull($item)
    {
        return $item->getRole()->getName();
    }
}