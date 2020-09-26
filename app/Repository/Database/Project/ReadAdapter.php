<?php

namespace App\Repository\Database\Project;

use App\Domain\Project\ProjectEntity;
use Lib\Adapter\NullAdapter;

class ReadAdapter extends NullAdapter
{
    protected function adaptNotNull($item)
    {
        return new ProjectEntity((array) $item);
    }
}