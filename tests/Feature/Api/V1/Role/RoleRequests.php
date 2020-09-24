<?php

namespace Tests\Feature\Api\V1\Role;

class RoleRequests
{
    public static function getAllInvalid()
    {
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
            ]
        ] + RoleRequests::getUpdateInvalid();
    }

    public static function getUpdateInvalid()
    {
        $nameLong = "";
        for ($i = 0; $i < 256; $i++) {
            $nameLong .= "a";
        }

        return [
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
}