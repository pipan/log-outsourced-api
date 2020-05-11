<?php

namespace App\Repository\Database\Handler;

use App\Domain\Handler\HandlerEntity;
use Lib\Adapter\Adapter;

class HandlerDatabaseReadAdapter implements Adapter
{
    public function adapt($result)
    {
        return new HandlerEntity(
            $result->id,
            $result->slug,
            $result->name,
            ""
        );
    }
}