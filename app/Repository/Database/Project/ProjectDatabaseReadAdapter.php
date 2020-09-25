<?php

namespace App\Repository\Database\Project;

use App\Domain\Project\ProjectEntity;
use Lib\Adapter\NullAdapter;

class ProjectDatabaseReadAdapter extends NullAdapter
{
    protected function adaptNotNull($item)
    {
        return new ProjectEntity(
            $item->id,
            $item->uuid,
            $item->name
        );
    }
}