<?php

namespace App\Providers;

use App\Domain\Handler\HandlerEntity;
use App\Repository\Repository;
use Illuminate\Support\ServiceProvider;
use Lib\Generator\HexadecimalGenerator;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(HexadecimalGenerator::class, HexadecimalGenerator::class);
    }

    public function boot(Repository $repository)
    {
        $repository->handler()
            ->insert(new HandlerEntity('test', 'test', '', []));
    }
}
