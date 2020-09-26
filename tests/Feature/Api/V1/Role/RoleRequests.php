<?php

namespace Tests\Feature\Api\V1\Role;

class RoleRequests
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
                    'domain' => 'test',
                    'name' => 'test',
                    'permissions' => ['test']
                ]
            ],
            'project uuid empty' => [
                [
                    'project_uuid' => '',
                    'domain' => 'test',
                    'name' => 'test',
                    'permissions' => ['test']
                ]
            ],
            'project uuid not existing' => [
                [
                    'project_uuid' => 'zzzz',
                    'domain' => 'test',
                    'name' => 'test',
                    'permissions' => ['test']
                ]
            ],
            'name missing' => [
                [
                    'project_uuid' => 'aabb',
                    'domain' => 'test',
                    'permissions' => ['test']
                ]
            ],
            'name empty' => [
                [
                    'project_uuid' => 'aabb',
                    'domain' => 'test',
                    'name' => '',
                    'permissions' => ['test']
                ]
            ],
            'name too long' => [
                [
                    'project_uuid' => 'aabb',
                    'domain' => 'test',
                    'name' => $nameLong,
                    'permissions' => ['test']
                ]
            ],
            'permissions missing' => [
                [
                    'project_uuid' => 'aabb',
                    'domain' => 'test',
                    'name' => 'test'
                ]
            ],
            'permissions empty' => [
                [
                    'project_uuid' => 'aabb',
                    'domain' => 'test',
                    'name' => 'test',
                    'permissions' => []
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
                    'name' => $nameLong,
                ]
            ],
            'permissions empty' => [
                [
                    'permissions' => []
                ]
            ]
        ];
    }

    public static function getValidForUpdates()
    {
        return [
            'only name' => [
                [
                    'name' => 'test'
                ]
            ],
            'only permissions' => [
                [
                    'permissions' => ['test']
                ]
            ],
            'name and permissions' => [
                [
                    'name' => 'test',
                    'permissions' => ['test']
                ]
            ]
        ];
    }
}