<?php

namespace App\Repository\Database\User;

use App\Domain\User\UserEntity;
use Lib\Adapter\NullAdapter;

class ReadAdapter extends NullAdapter
{
    protected function adaptNotNull($item)
    {
        return new UserEntity((array) $item);
    }
}