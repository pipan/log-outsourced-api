<?php

namespace Tests\Feature\Api\V1\Administrator;

class LoginRequests
{
    public static function getAllInvalid()
    {
        return [
            'username does not exists' => [
                [
                    'username' => 'name@example.com',
                    'password' => 'pass'
                ]
            ],
            'password is incorrect' => [
                [
                    'username' => 'root',
                    'password' => 'wrong-pass'
                ]
            ]
        ];
    }
}