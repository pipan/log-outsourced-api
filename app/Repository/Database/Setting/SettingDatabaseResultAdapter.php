<?php

namespace App\Repository\Database\Setting;

use App\Domain\Setting\SettingEntity;
use Lib\Adapter\Adapter;

class SettingDatabaseResultAdapter implements Adapter
{
    public function adapt($item)
    {
        return new SettingEntity(
            $item->id,
            $item->uuid,
            json_decode($item->data)
        );
    }
}