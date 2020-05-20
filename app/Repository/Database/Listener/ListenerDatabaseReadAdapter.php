<?php

namespace App\Repository\Database\Listener;

use App\Domain\Listener\ListenerEntity;
use Lib\Adapter\Adapter;

class ListenerDatabaseReadAdapter implements Adapter
{
    public function adapt($result)
    {
        return new ListenerEntity(
            $result->id,
            $result->uuid,
            $result->project_id,
            $result->name,
            [],
            $result->handler_slug,
            json_decode($result->handler_values)
        );
    }
}