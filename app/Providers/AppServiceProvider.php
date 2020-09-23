<?php

namespace App\Providers;

use App\Domain\Listener\ListenerPatternMatcher;
use App\DynamicValidator\DynamicValidator;
use App\Http\Controllers\Api\V1\User\UserController;
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
