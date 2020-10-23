<?php

namespace App\Repository\Database\Settings\ProjectKey;

use App\Domain\Settings\ProjectKey\ProjectKeyEntity;
use Lib\Adapter\NullAdapter;

class ReadAdapter extends NullAdapter
{
    protected function adaptNotNull($item)
    {
        return new ProjectKeyEntity((array) $item);
    }
}