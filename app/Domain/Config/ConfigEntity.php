<?php

namespace App\Domain\Config;

class ConfigEntity
{
    protected $data;

    public function __construct($jsonData)
    {
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