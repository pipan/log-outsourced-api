<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lib\Generator\UidGenerator;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(UidGenerator::class, UidGenerator::class);
    }

    public function boot()
    {
        //
    }
}
