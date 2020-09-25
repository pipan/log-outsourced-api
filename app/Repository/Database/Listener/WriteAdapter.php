<?php

namespace App\Repository\Database\Listener;

use Lib\Adapter\NullAdapter;

class WriteAdapter extends NullAdapter
{
    protected function adaptNotNull($item)
    {
        return [
            'uuid' => $item->getUuid(),
            'project_id' => $item->getProjectId(),
            'name' => $item->getName(),
            'handler_slug' => $item->getHandlerSlug(),
            'handler_values' => json_encode($item->getHandlerValues())
        ];
    }
}