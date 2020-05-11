<?php

namespace App\Domain\Config;

interface ConfigRepository
{
    public function load();
    public function save(ConfigEntity $config);
}