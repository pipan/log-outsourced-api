<?php

namespace App\Repository\File\Project;

use App\Domain\Project\ProjectEntity;
use Lib\Adapter\Adapter;

class ProjectFileReadAdapter implements Adapter
{
    public function adapt($item)
    {
        return new ProjectEntity(
            $item['id'],
            $item['uuid'],
            $item['name']
        );
    }
}