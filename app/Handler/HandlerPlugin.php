<?php

namespace App\Handler;

use App\Domain\Handler\HandlerEntity;

interface HandlerPlugin
{
    public function getDefinition(): HandlerEntity;
    public function getLogHandler(): LogHandler;
}