<?php

namespace App\Providers;

use App\Domain\Handler\HandlerEntity;
use App\Domain\Listener\ListenerPatternMatcher;
use App\Repository\Repository;
use Illuminate\Support\ServiceProvider;
use Lib\Generator\HexadecimalGenerator;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(HexadecimalGenerator::class, HexadecimalGenerator::class);

        $this->app->singleton(ListenerPatternMatcher::class, ListenerPatternMatcher::class);
    }

    public function boot(Repository $repository)
    {
        $repository->handler()
            ->insert(new HandlerEntity('file', 'File', '', [
                'schema' => [
                    'file_daily' => [
                        'type' => 'checkbox',
                        'name' => 'Daily'
                    ]
                ]
            ]));

        $repository->handler()
            ->insert(new HandlerEntity('database', 'Database', '', [
                'schema' => [
                    'db_host' => [
                        'type' => 'string',
                        'name' => 'Host',
                        'default' => 'localhost'
                    ],
                    'db_port' => [
                        'type' => 'number',
                        'name' => 'Port',
                        'default' => 3306
                    ],
                    'db_database' => [
                        'type' => 'string',
                        'name' => 'Database'
                    ],
                    'db_table' => [
                        'type' => 'string',
                        'name' => 'Table',
                        'default' => 'logs'
                    ],
                    'db_user' => [
                        'type' => 'string',
                        'name' => 'User'
                    ],
                    'db_password' => [
                        'type' => 'password',
                        'name' => 'Password'
                    ]
                ]
            ]));

        $repository->handler()
            ->insert(new HandlerEntity('sentry', 'Sentry', '', [
                'schema' => [
                    'sentry_url' => [
                        'type' => 'string',
                        'name' => 'URL'
                    ]
                ]
            ]));
    }
}
