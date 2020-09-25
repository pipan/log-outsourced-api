<?php

namespace App\Repository\Database\Project;

use Lib\Adapter\NullAdapter;

class ProjectDatabaseWriteAdapter extends NullAdapter
{
    protected function adaptNotNull($item)
    {
        return [
            'uuid' => $item->getUuid(),
            'name' => $item->getName()
        ];
    }
}