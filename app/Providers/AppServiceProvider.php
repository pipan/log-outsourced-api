<?php

namespace App\Providers;

use App\Domain\Listener\ListenerPatternMatcher;
use Illuminate\Support\ServiceProvider;
use Lib\Generator\HexadecimalGenerator;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(HexadecimalGenerator::class, function () {
            return new HexadecimalGenerator(8);
        });

        $this->app->singleton(ListenerPatternMatcher::class, ListenerPatternMatcher::class);
    }

    public function boot()
    {
        
    }
}
