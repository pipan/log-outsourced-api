<?php

namespace App\Repository\File\Project;

use Lib\Adapter\Adapter;

class ProjectFileWriteAdapter implements Adapter
{
    public function adapt($item)
    {
        return [
            'id' => $item->getId(),
            'uuid' => $item->getUuid(),
            'name' => $item->getName()
        ];
    }
}