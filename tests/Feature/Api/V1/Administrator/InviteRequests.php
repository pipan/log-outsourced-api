<?php

namespace Tests\Feature\Api\V1\Administrator;

class InviteRequests
{
    public static function getAllInvalid()
    {
        $usernameLong = "";
        for ($i = 0; $i < 256; $i++) {
            $usernameLong .= "a";
        }

        return [
            'username missing' => [
                []
            ],
            'username empty' => [
                [
                    'username' => ''
                ]
            ],
            'username too long' => [
                [
                    'username' => $usernameLong
                ]
            ],
            'username exists' => [
                [
                    'username' => 'admin'
                ]
            ]
        ];
    }
}