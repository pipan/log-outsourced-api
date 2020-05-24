<?php

namespace App\Handler;

use App\Repository\Repository;
use Ext\Handler\Database\DatabaseHandlerPlugin;
use Ext\Handler\File\FileHandlerPlugin;
use Ext\Handler\Sentry\SentryHandlerPlugin;
use Illuminate\Support\ServiceProvider;

class HandlerProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(LogHandlerContainer::class, LogHandlerContainer::class);
    }

    public function boot()
    {
        $installedPlugins = [
            new FileHandlerPlugin(),
            new DatabaseHandlerPlugin(),
            new SentryHandlerPlugin()
        ];
        foreach ($installedPlugins as $plugin) {
            $this->connectHandler($plugin);
        }
    }

    private function connectHandler(HandlerPlugin $plugin)
    {
        $entity = $plugin->getDefinition();
        $this->app->make(Repository::class)->handler()
            ->insert($entity);

        $this->app->make(LogHandlerContainer::class)
            ->add($entity->getSlug(), $plugin->getLogHandler());
    }
}