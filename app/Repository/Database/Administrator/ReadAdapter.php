<?php

namespace App\Repository\Database\Administrator;

use App\Domain\Administrator\AdministratorEntity;
use Lib\Adapter\NullAdapter;

class ReadAdapter extends NullAdapter
{
    protected function adaptNotNull($item)
    {
        return new AdministratorEntity((array) $item);
    }
}