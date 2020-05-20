<?php

namespace App\Repository\Eloquent\Listener;

use Lib\Adapter\Adapter;

class ListenerEloquentWriteAdapter implements Adapter
{
    public function adapt($entity)
    {
        if ($entity == null) {
            return null;
        }

        $listener = new Listener();
        if ($entity->getId() > 0) {
            $listener->id = $entity->getId();
        }

        $listener->uuid = $entity->getUuid();
        $listener->name = $entity->getName();
        $listener->project_id = $entity->getProjectId();
        $listener->handler_slug = $entity->getHandlerSlug();
        $listener->handler_values = json_encode($entity->getHandlerValues());

        return $listener;
    }
}