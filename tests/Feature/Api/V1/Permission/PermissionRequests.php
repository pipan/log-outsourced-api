<?php

namespace Tests\Feature\Api\V1\Permission;

class PermissionRequests
{
    public static function getInvalidForCreation()
    {
        $nameLong = "";
        for ($i = 0; $i < 256; $i++) {
            $nameLong .= "a";
        }
        return [
            'project uuid missing' => [
                [
                    'name' => 'user.create'
                ]
            ],
            'project uuid empty' => [
                [
                    'project_uuid' => '',
                    'name' => 'user.create'
                ]
            ],
            'project uuid not existing' => [
                [
                    'project_uuid' => 'xxxx',
                    'name' => 'user.create'
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
            ],
            'name exists' => [
                [
                    'project_uuid' => 'aabb',
                    'name' => 'user.view'
                ]
            ],
        ];
    }

    public static function getInvalidForValidation()
    {
        return [
            'user missing' => [
                'permissions/1234?permissions[]=user.view'
            ],
            'user empty' => [
                'permissions/1234?user=&permissions[]=user.view'
            ],
            'permissions missing' => [
                'permissions/1234?user=admin'
            ],
            'permissions not array' => [
                'permissions/1234?user=admin&permissions=user.view'
            ]
        ];
    }

    public static function getNotFoundForValidation()
    {
        return [
            'project uuid missing' => [
                'permissions?user=admin&permissions[]=user.view'
            ],
            'project not existing' => [
                'permissions/xxxx?user=admin&permissions[]=user.view'
            ]
        ];
    }
}