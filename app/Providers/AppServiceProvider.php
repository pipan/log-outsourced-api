<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Lib\Generator\HexadecimalGenerator;
use Lib\Generator\UidGenerator;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(HexadecimalGenerator::class, HexadecimalGenerator::class);
    }

    public function boot()
    {
        //
    }
}
