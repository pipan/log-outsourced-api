<?php

namespace Tests\Feature\Api\V1\Administrator;

class RegisterRequests
{
    public static function getAllInvalid()
    {
        return [
            'password missing' => [
                [
                    'invite_token' => '010101'
                ]
            ],
            'password empty' => [
                [
                    'invite_token' => '010101',
                    'password' => ''
                ]
            ],
            'user has password' => [
                [
                    'invite_token' => 'aabb',
                    'password' => 'pass'
                ]
            ],
            'invite token missing' => [
                [
                    'password' => 'pass'
                ]
            ],
            'invite token empty' => [
                [
                    'invite_token' => '',
                    'password' => 'pass'
                ]
            ],
            'invite does not exists' => [
                [
                    'invite_token' => 'abcdef',
                    'password' => 'pass'
                ]
            ]
        ];
    }
}