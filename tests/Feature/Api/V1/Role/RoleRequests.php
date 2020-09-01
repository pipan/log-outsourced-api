<?php

namespace Tests\Feature\Api\V1\Role;

class RoleRequests
{
    public static function getAllInvalid()
    {
        $domainLong = "";
        $nameLong = "";
        for ($i = 0; $i < 256; $i++) {
            $domainLong .= "a";
            $nameLong .= "a";
        }

        return [
            'domain missing' => [
                [
                    'name' => 'test',
                    'permissions' => ['test']
                ]
            ],
            'domain empty' => [
                [
                    'domain' => '',
                    'name' => 'test',
                    'permissions' => ['test']
                ]
            ],
            'domain too long' => [
                [
                    'domain' => $domainLong,
                    'name' => 'test',
                    'permissions' => ['test']
                ]
            ],
            'name missing' => [
                [
                    'domain' => 'test',
                    'permissions' => ['test']
                ]
            ],
            'name empty' => [
                [
                    'domain' => 'test',
                    'name' => '',
                    'permissions' => ['test']
                ]
            ],
            'name too long' => [
                [
                    'domain' => 'test',
                    'name' => $nameLong,
                    'permissions' => ['test']
                ]
            ],
            'permissions missing' => [
                [
                    'domain' => 'test',
                    'name' => 'test'
                ]
            ],
            'permissions empty' => [
                [
                    'domain' => 'test',
                    'name' => 'test',
                    'permissions' => []
                ]
            ]
        ];
    }
}