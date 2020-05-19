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
            ->insert(new HandlerEntity('file', 'File', '', [
                'schema' => [
                    [
                        'type' => 'checkbox',
                        'name' => 'Daily'
                    ]
                ]
            ]));

        $repository->handler()
            ->insert(new HandlerEntity('database', 'Database', '', [
                'schema' => [
                    [
                        'type' => 'string',
                        'name' => 'Host',
                        'default' => 'localhost'
                    ], [
                        'type' => 'number',
                        'name' => 'Port',
                        'default' => 3306
                    ], [
                        'type' => 'string',
                        'name' => 'Database'
                    ], [
                        'type' => 'string',
                        'name' => 'Table',
                        'default' => 'logs'
                    ], [
                        'type' => 'string',
                        'name' => 'User'
                    ], [
                        'type' => 'password',
                        'name' => 'Password'
                    ]
                ]
            ]));

        $repository->handler()
            ->insert(new HandlerEntity('sentry', 'Sentry', '', [
                'schema' => [
                    [
                        'type' => 'string',
                        'name' => 'URL'
                    ]
                ]
            ]));
    }
}
