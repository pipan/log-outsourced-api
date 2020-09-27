<?php

namespace Tests\Feature\Api\V1\Administrator;

class LoginRequests
{
    public static function getInvalid()
    {
        return [
            'username does not exists' => [
                [
                    'username' => 'name@example.com',
                    'password' => 'pass'
                ]
            ],
            'password is incorrect for root' => [
                [
                    'username' => 'root',
                    'password' => 'wrong-pass'
                ]
            ],
            'password is incorrect for administrator' => [
                [
                    'username' => 'admin',
                    'password' => 'wrong-pass'
                ]
            ]
        ];
    }
}