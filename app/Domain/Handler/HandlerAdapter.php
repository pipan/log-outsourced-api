<?php

namespace App\Domain\Handler;

use Lib\Adapter\Adapter;

class HandlerAdapter implements Adapter
{
    public function adapt($item)
    {
        return [
            'uuid' => $item->getUuid(),
            'project_id' => $item->getProjectId(),
            'name' => $item->getName()
        ];
    }
}