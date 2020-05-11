<?php

namespace App\Handler;

use Ext\Handler\File\FileHandlerPlugin;
use Illuminate\Support\ServiceProvider;

class HandlerProvider extends ServiceProvider
{
    protected $installedPlugins = [];

    public function __construct()
    {
        $this->installedPlugins[] = new FileHandlerPlugin();
    }

    public function register()
    {

    }

    public function boot()
    {
        foreach ($this->installedPlugins as $plugin) {
            $plugin->connect();
        }
    }
}