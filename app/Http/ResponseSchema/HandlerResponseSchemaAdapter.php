<?php

namespace App\Http\ResponseSchema;

use Lib\Adapter\Adapter;

class HandlerResponseSchemaAdapter implements Adapter
{
    public function adapt($item)
    {
        return [
            'slug' => $item->getSlug(),
            'name' => $item->getName(),
            'configSchema' => $item->getConfigSchema(),
            'meta' => $item->getMeta(),
        ];
    }
}