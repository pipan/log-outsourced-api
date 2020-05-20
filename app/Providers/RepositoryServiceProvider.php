<?php

namespace App\Providers;

use App\Repository\Database\Project\ProjectDatabaseRepository;
use App\Repository\Eloquent\Listener\ListenerEloquentRepository;
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
                new ProjectDatabaseRepository(),
                new ListenerEloquentRepository(),
                new HandlerMemoryRepository()
            );
        });
    }

    public function boot()
    {
        
    }
}
