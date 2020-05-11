<?php

namespace App\Providers;

use App\Repository\Database\DatabaseRepository;
use App\Repository\Database\Listener\ListenerDatabaseRepository;
use App\Repository\Database\Project\ProjectDatabaseRepository;
use App\Repository\Memory\Handler\HandlerMemoryRepository;
use App\Repository\ProxyRepository;
use App\Repository\Repository;
use App\Repository\SimpleRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ProxyRepository::class, function ($app) {
            return new ProxyRepository();
        });
        $this->app->singleton(Repository::class, function ($app) {
            return $app->make(ProxyRepository::class);
        });
    }

    public function boot(
        ProxyRepository $proxyRepository
    )
    {
        $proxyRepository->setProxy(
            new SimpleRepository(
                new ProjectDatabaseRepository(),
                new ListenerDatabaseRepository(),
                new HandlerMemoryRepository()
            )
        );
    }
}
