<?php

namespace App\Repository\Database\Settings\DefaultRole;

use App\Domain\Settings\DefaultRole\DefaultRoleEntity;
use Lib\Adapter\NullAdapter;

class ReadAdapter extends NullAdapter
{
    protected function adaptNotNull($item)
    {
        return new DefaultRoleEntity((array) $item);
    }
}