<?php

namespace App\Handler;

use App\Repository\Repository;
use Ext\Handler\File\FileHandlerPlugin;
use Illuminate\Support\ServiceProvider;

class HandlerProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(LogHandlerContainer::class, LogHandlerContainer::class);
    }

    public function boot()
    {
        // $installedPlugins = [
        //     new FileHandlerPlugin()
        // ];
        $installedPlugins = [];
        foreach ($installedPlugins as $plugin) {
            $plugin->connectHandler($plugin);
        }
    }

    private function connectHandler(HandlerPlugin $plugin)
    {
        $entity = $plugin->getDefinition();
        $this->app->make(Repository::class)->handlers()
            ->insert($entity);

        $this->app->make(LogHandlerContainer::class)
            ->add($entity->getSlug(), $plugin->getLogHandler());
    }
}