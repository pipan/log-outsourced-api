<?php

namespace Tests\Feature\Api\V1\Settings\DefaultRole;

class Requests
{
    public static function getInvalidForSave()
    {
        return [
            'project uuid missing' => [
                []
            ],
            'project uuid empty' => [
                [
                    'project_uuid' => ''
                ]
            ],
            'project uuid not existing' => [
                [
                    'project_uuid' => 'zzzz'
                ]
            ],
            'roles empty string' => [
                [
                    'project_uuid' => 'zzzz',
                    'roles' => ''
                ]
            ],
            'roles string' => [
                [
                    'project_uuid' => 'zzzz',
                    'roles' => 'project'
                ]
            ],
            'roles number' => [
                [
                    'project_uuid' => 'zzzz',
                    'roles' => 1
                ]
            ]
        ];
    }
}