<?php

namespace App\Domain\Project;

use Lib\Adapter\Adapter;

class ProjectAdapter implements Adapter
{
    public function adapt($item)
    {
        return [
            'uuid' => $item->getUuidHex(),
            'name' => $item->getName()
        ];
    }
}