<?php

namespace App\Repository\Database\Handler;

use App\Domain\Handler\HandlerEntity;
use Lib\Adapter\Adapter;

class HandlerDatabaseResultAdapter implements Adapter
{
    public function adapt($result)
    {
        return new HandlerEntity(
            $result->id,
            $result->uuid,
            $result->project_id,
            $result->name
        );
    }
}