<?php

namespace App\Repository\Database\Listener;

use Lib\Adapter\Adapter;

class ListenerDatabaseWriteAdapter implements Adapter
{
    public function adapt($entity)
    {
        return [
            'uuid' => $entity->getUuid(),
            'project_id' => $entity->getProjectId(),
            'name' => $entity->getName(),
            'handler_id' => $entity->getHandlerId(),
            'handler_settings' => $entity->getHandlerSettings()
        ];
    }
}