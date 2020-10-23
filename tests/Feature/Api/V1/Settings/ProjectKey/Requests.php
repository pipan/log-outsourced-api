<?php

namespace Tests\Feature\Api\V1\Settings\ProjectKey;

class Requests
{
    public static function forCreation()
    {
        $nameLong = "";
        for ($i = 0; $i < 256; $i++) {
            $nameLong .= "a";
        }
        return [
            'project uuid missing' => [
                [
                    'name' => 'test'
                ]
            ],
            'project uuid empty' => [
                [
                    'project_uuid' => '',
                    'name' => 'test'
                ]
            ],
            'project uuid not existing' => [
                [
                    'project_uuid' => 'zzzz',
                    'name' => 'test'
                ]
            ],
            'name missing' => [
                [
                    'project_uuid' => 'aabb'
                ]
            ],
            'name empty' => [
                [
                    'project_uuid' => 'aabb',
                    'name' => ''
                ]
            ],
            'name too long' => [
                [
                    'project_uuid' => 'aabb',
                    'name' => $nameLong
                ]
            ]
        ];
    }

    public static function getInvalidForUpdates()
    {
        $nameLong = "";
        for ($i = 0; $i < 256; $i++) {
            $nameLong .= "a";
        }

        return [
            'name empty' => [
                [
                    'name' => ''
                ]
            ],
            'name too long' => [
                [
                    'name' => $nameLong
                ]
            ]
        ];
    }
}