<?php

namespace Tests\Feature\Api\V1\User;

class UserRequests
{
    public static function getAllInvalid()
    {
        $usernameLong = "";
        for ($i = 0; $i < 256; $i++) {
            $usernameLong .= "a";
        }

        return [
            'username missing' => [
                [
                    'project_uuid' => 'aabb'
                ]
            ],
            'username empty' => [
                [
                    'project_uuid' => 'aabb',
                    'username' => ''
                ]
            ],
            'username too long' => [
                [
                    'project_uuid' => 'aabb',
                    'username' => $usernameLong
                ]
            ],
            'username exists' => [
                [
                    'project_uuid' => 'aabb',
                    'username' => 'admin'
                ]
            ],
            'project uuid missing' => [
                [
                    'username' => 'valid@example.com'
                ]
            ],
            'project uuid empty' => [
                [
                    'project_uuid' => '',
                    'username' => 'valid@example.com'
                ]
            ],
            'project uuid not exists' => [
                [
                    'project_uuid' => 'ffff',
                    'username' => 'valid@example.com'
                ]
            ]
        ] + UserRequests::getRolesInvalid();
    }

    public static function getRolesInvalid()
    {
        return [
            'roles is integer' => [
                [
                    'project_uuid' => 'aabb',
                    'username' => 'valid@example.com',
                    'roles' => 1
                ]
            ],
            'roles is string' => [
                [
                    'project_uuid' => 'aabb',
                    'username' => 'valid@example.com',
                    'roles' => '1'
                ]
            ]
        ];
    }
}