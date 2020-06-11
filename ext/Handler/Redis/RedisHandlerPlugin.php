<?php

namespace Ext\Handler\Redis;

use App\Domain\Handler\HandlerEntity;
use App\Handler\HandlerPlugin;
use App\Handler\LogHandler;

class RedisHandlerPlugin implements HandlerPlugin
{
    public function getDefinition(): HandlerEntity
    {
        return new HandlerEntity(
            'redis',
            'Redis',
            [
                'schema' => [
                    'redis_url' => [
                        'type' => 'string',
                        'name' => 'Url'
                    ],
                    'redis_host' => [
                        'type' => 'string',
                        'name' => 'Host',
                        'default' => 'localhost'
                    ],
                    'redis_port' => [
                        'type' => 'number',
                        'name' => 'Port',
                        'default' => 6379
                    ],
                    'redis_database' => [
                        'type' => 'string',
                        'name' => 'Database'
                    ],
                    'redis_password' => [
                        'type' => 'password',
                        'name' => 'Password'
                    ]
                ],
                'meta' => [
                    'icon' => 'dns'
                ]
            ]
        );
    }

    public function getLogHandler(): LogHandler
    {
        return new RedisLogHandler();
    }
}