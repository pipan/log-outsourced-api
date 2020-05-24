<?php

namespace App\Providers;

use App\Repository\Database\Project\ProjectDatabaseRepository;
use App\Repository\Eloquent\Listener\ListenerEloquentRepository;
use App\Repository\File\Listener\ListenerFileRepository;
use App\Repository\File\Project\ProjectFileRepository;
use App\Repository\Memory\Handler\HandlerMemoryRepository;
use App\Repository\Repository;
use App\Repository\SimpleRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Repository::class, function ($app) {
            return new SimpleRepository(
                new ProjectFileRepository(),
                new ListenerFileRepository(),
                new HandlerMemoryRepository()
            );
        });
    }

    public function boot()
    {
        
    }
}
