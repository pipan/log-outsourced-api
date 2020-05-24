<?php

namespace App\Repository\File\Listener;

use Lib\Adapter\Adapter;

class ListenerFileWriteAdapter implements Adapter
{
    public function adapt($item)
    {
        return [
            'id' => $item->getId(),
            'uuid' => $item->getUuid(),
            'project_id' => $item->getProjectId(),
            'name' => $item->getName(),
            'rules' => $item->getRules(),
            'handler' => [
                'slug' => $item->gethandlerSlug(),
                'values' => $item->getHandlerValues()
            ]
        ];
    }
}