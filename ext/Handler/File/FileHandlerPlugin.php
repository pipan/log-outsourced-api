<?php

namespace Ext\Handler\File;

use App\Domain\Handler\HandlerEntity;
use App\Handler\HandlerPlugin;
use App\Handler\LogHandler;

class FileHandlerPlugin implements HandlerPlugin
{
    public function getDefinition(): HandlerEntity
    {
        return new HandlerEntity(
            'file',
            'File',
            [],
            []
        );
    }

    public function getLogHandler(): LogHandler
    {
        return new FileLogHandler();
    }
}