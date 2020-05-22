<?php

namespace App\Providers;

use App\Domain\Listener\ListenerPatternMatcher;
use Illuminate\Support\ServiceProvider;
use Lib\Generator\HexadecimalGenerator;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(HexadecimalGenerator::class, HexadecimalGenerator::class);

        $this->app->singleton(ListenerPatternMatcher::class, ListenerPatternMatcher::class);
    }

    public function boot()
    {
        
    }
}
