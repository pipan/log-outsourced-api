<?php

namespace App\Repository\Eloquent\Listener;

use App\Domain\Listener\ListenerEntity;
use Lib\Adapter\Adapter;

class ListenerEloquentReadAdapter implements Adapter
{
    public function adapt($result)
    {
        if ($result == null) {
            return null;
        }

        return new ListenerEntity(
            $result->id,
            $result->uuid,
            $result->project_id,
            $result->name,
            $result->rules->map(function ($rule) { 
                return $rule->pattern; 
            })->toArray(),
            $result->handler_slug,
            json_decode($result->handler_values)
        );
    }
}