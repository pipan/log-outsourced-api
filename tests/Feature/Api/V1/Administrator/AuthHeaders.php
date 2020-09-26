<?php

namespace Tests\Feature\Api\V1\Administrator;

use Firebase\JWT\JWT;

class AuthHeaders
{
    public static function authorize()
    {
        $payload = [
            'sub' => 1,
            'exp' => time() + 3600
        ];
        $token = JWT::encode($payload, config('app.key'));
        return [
            'Authorization' => 'Bearer ' . $token
        ];
    }
}