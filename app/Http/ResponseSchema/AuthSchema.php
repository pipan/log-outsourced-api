<?php

namespace App\Http\ResponseSchema;

use Firebase\JWT\JWT;
use Lib\Adapter\Adapter;

class AuthSchema implements Adapter
{
    public function adapt($item)
    {
        $payload = [
            'iss' => config('app.url'),
            'iat' => time(),
            'sub' => $item['sub']
        ];
        $accessTimeToLive = $item['access']['ttl'];
        $refreshTimeToLive = $item['refresh']['ttl'];

        $access = JWT::encode($payload + ['exp' => time() + $accessTimeToLive], config('app.key'));
        $refresh = JWT::encode($payload + ['exp' => $refreshTimeToLive + time()], config('app.key'));

        return [
            'access' => $access,
            'refresh' => $refresh
        ];
    }
}