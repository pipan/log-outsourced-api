<?php

namespace Ext\Handler\File;

use App\Domain\Handler\HandlerEntity;
use App\Handler\Plugin;
use App\Handler\SimplePlugin;
use Illuminate\Contracts\Foundation\Application;

class FileHandlerPlugin implements Plugin
{
    private $simplePlugin;

    public function __construct()
    {
        $this->simplePlugin = new SimplePlugin(
            new HandlerEntity(
                'file',
                'File',
                [],
                []
            )
        );
    }

    public function connect(Application $app)
    {
        $this->simplePlugin->connect($app);
    }

    public function disconnect(Application $app)
    {
        $this->simplePlugin->disconnect($app);
    }
}