<?php

namespace App\Http\ResponseSchema;

use Lib\Adapter\Adapter;

class ConfigResponseSchemaAdapter implements Adapter
{
    public function adapt($config)
    {
        return $config->getJson();
    }
}