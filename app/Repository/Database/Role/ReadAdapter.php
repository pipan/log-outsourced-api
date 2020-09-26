<?php

namespace App\Repository\Database\Role;

use App\Domain\Role\RoleEntity;
use Lib\Adapter\NullAdapter;

class ReadAdapter extends NullAdapter
{
    protected function adaptNotNull($item)
    {
        return new RoleEntity((array) $item);
    }
}