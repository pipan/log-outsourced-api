<?php

namespace App\Repository\File\Listener;

use App\Domain\Listener\ListenerEntity;
use Lib\Adapter\Adapter;

class ListenerFileReadAdapter implements Adapter
{
    public function adapt($item)
    {
        return new ListenerEntity(
            $item['id'],
            $item['uuid'],
            $item['project_id'],
            $item['name'],
            $item['rules'],
            $item['handler']['slug'],
            $item['handler']['values']
        );
    }
}