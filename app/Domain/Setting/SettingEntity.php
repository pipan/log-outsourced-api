<?php

namespace App\Domain\Setting;

use App\Domain\UuidEntity;

class SettingEntity extends UuidEntity
{
    protected $data;

    public function __construct($id, $uuid, $jsonData)
    {
        parent::__construct($id, $uuid);
        $this->data = $jsonData;
    }

    public function getData()
    {
        return json_encode($this->data);
    }

    public function getJson()
    {
        return $this->data;
    }
}