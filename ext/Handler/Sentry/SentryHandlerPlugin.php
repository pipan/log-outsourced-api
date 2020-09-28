<?php

namespace Ext\Handler\Sentry;

use App\Domain\Handler\HandlerEntity;
use App\Handler\HandlerPlugin;
use App\Handler\LogHandler;

class SentryHandlerPlugin implements HandlerPlugin
{
    public function getDefinition(): HandlerEntity
    {
        return new HandlerEntity([
            'slug' => 'sentry',
            'name' => 'Sentry',
            'meta' => [
                'schema' => [
                    'sentry_dsn' => [
                        'type' => 'string',
                        'name' => 'DSN'
                    ],
                    'sentry_environment' => [
                        'type' => 'string',
                        'name' => 'Environment'
                    ]
                ],
                'meta' => [
                    'icon' => 'wifi'
                ]
            ]
        ]);
    }

    public function getLogHandler(): LogHandler
    {
        return new SentryLogHandler();
    }
}