<?php

namespace App\Repository\Database\Project;

use Lib\Adapter\Adapter;

class ProjectDatabaseWriteAdapter implements Adapter
{
    public function adapt($project)
    {
        return [
            'uuid' => $project->getUuid(),
            'name' => $project->getName()
        ];
    }
}