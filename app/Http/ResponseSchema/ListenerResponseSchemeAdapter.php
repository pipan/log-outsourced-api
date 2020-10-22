<?php

namespace App\Http\ResponseSchema;

use Lib\Adapter\Adapter;

class ListenerResponseSchemaAdapter implements Adapter
{
    public function adapt($item)
    {
        return [
            'uuid' => $item->getUuid(),
            'project_id' => $item->getProjectId(),
            'name' => $item->getName(),
            'rules' => $item->getRules(),
            'handler_slug' => $item->getHandlerSlug(),
            'handler_values' => $item->getHandlerValues()
        ];
    }
}