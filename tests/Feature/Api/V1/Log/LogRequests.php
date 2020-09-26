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
                [
                    'level' => 'error',
                    'message' => 'Log this message'
                ]
            ],
            'level missing' => [
                [[
                    'message' => 'Log this message'
                ]]
            ],
            'level empty' => [
                [[
                    'level' => '',
                    'message' => 'Log this message'
                ]]
            ],
            'level not standard name' => [
                [[
                    'level' => 'non-standard',
                    'message' => 'Log this message'
                ]]
            ],
            'message missing' => [
                [[
                    'level' => 'error'
                ]]
            ],
            'message empty' => [
                [[
                    'level' => 'error',
                    'message' => ''
                ]]
            ],
        ];
    }
}