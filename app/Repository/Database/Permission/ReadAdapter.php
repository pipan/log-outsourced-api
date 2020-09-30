<?php

namespace App\Repository\Database\Permission;

use App\Domain\Permission\PermissionEntity;
use Lib\Adapter\NullAdapter;

class ReadAdapter extends NullAdapter
{
    protected function adaptNotNull($item)
    {
        return new PermissionEntity((array) $item);
    }
}