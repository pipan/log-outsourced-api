<?php

namespace App\Repository\Database\Listener;

use App\Domain\Listener\ListenerEntity;
use Lib\Adapter\NullAdapter;

class ReadAdapter extends NullAdapter
{
    protected function adaptNotNull($item)
    {
        return (new ListenerEntity((array) $item))
            ->withHandlerValues(
                json_decode(decrypt($item->handler_values), true)
            );
    }
}