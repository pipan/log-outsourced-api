<?php

namespace App\Repository\Database\Project;

use App\Domain\Project\ProjectEntity;
use Lib\Adapter\Adapter;

class ProjectDatabaseResultAdapter implements Adapter
{
    public function adapt($result)
    {
        return new ProjectEntity(
            $result->id,
            $result->uuid,
            $result->name
        );
    }
}