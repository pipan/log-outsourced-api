<?php

namespace Ext\Handler\Database;

use App\Domain\Handler\HandlerEntity;
use App\Handler\HandlerPlugin;
use App\Handler\LogHandler;

class DatabaseHandlerPlugin implements HandlerPlugin
{
    public function getDefinition(): HandlerEntity
    {
        return new HandlerEntity(
            'database',
            'Database',
            '',
            [
                'schema' => [
                    'db_driver' => [
                        'type' => 'select',
                        'name' => 'Driver',
                        'default' => 'mysql',
                        'options' => ['mysql', 'mariadb', 'postgre']
                    ],
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
            ]
        );
    }

    public function getLogHandler(): LogHandler
    {
        return new DatabaseLogHandler();
    }
}