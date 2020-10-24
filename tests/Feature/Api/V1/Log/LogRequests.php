<?php

namespace Tests\Feature\Api\V1\Log;

class LogRequests
{
    public static function getInvalidForSingle()
    {
        return [
            'level missing' => [
                [
                    'message' => 'Log this message'
                ]
            ],
            'level empty' => [
                [
                    'level' => '',
                    'message' => 'Log this message'
                ]
            ],
            'level not standard name' => [
                [
                    'level' => 'non-standard',
                    'message' => 'Log this message'
                ]
            ],
            'message missing' => [
                [
                    'level' => 'error'
                ]
            ],
            'message empty' => [
                [
                    'level' => 'error',
                    'message' => ''
                ]
            ],
        ];
    }

    public static function getInvalidForBatch()
    {
        return [
            'send single' => [
                'logs' => [
                    'level' => 'error',
                    'message' => 'Log this message'
                ]
            ],
            'send without logs index' => [
                [
                    'level' => 'error',
                    'message' => 'Log this message'
                ]
            ],
            'level missing' => [
                'logs' => [[
                    'message' => 'Log this message'
                ]]
            ],
            'level empty' => [
                'logs' => [[
                    'level' => '',
                    'message' => 'Log this message'
                ]]
            ],
            'level not standard name' => [
                'logs' => [[
                    'level' => 'non-standard',
                    'message' => 'Log this message'
                ]]
            ],
            'message missing' => [
                'logs' => [[
                    'level' => 'error'
                ]]
            ],
            'message empty' => [
                'logs' => [[
                    'level' => 'error',
                    'message' => ''
                ]]
            ],
        ];
    }
}