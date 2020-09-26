<?php

namespace App\Providers;

use App\Domain\Listener\ListenerPatternMatcher;
use App\Http\Auth\AuthorizedAdministrator;
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

        $this->app->singleton(AuthorizedAdministrator::class, AuthorizedAdministrator::class);
    }

    public function boot()
    {
        
    }
}
