<?php

namespace App\Handler;

interface LogHandler
{
    public function handle($log, $config);
}