<?php

namespace App\Http\Controllers\Api\V1\Administrator;

use App\Repository\Repository;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RenewController
{
    public function __invoke(Request $request, Repository $repository)
    {
        $administrator = $repository->administrator()->getByUsername($request->input('username'));
        if (!$administrator) {
            return response([], 401);
        }

        if (!Hash::check($request->input('password'), $administrator->getPasswordHash())) {
            return response([], 401);
        }

        $payload = [
            'iss' => config('app.url'),
            'iat' => time(),
            'sub' => $administrator->getId()
        ];
        $accessTimeToLive = config('auth.jwt.access.time_to_live');
        $refreshTimeToLive = config('auth.jwt.refresh.time_to_live');

        $access = JWT::encode($payload + ['exp' => time() + $accessTimeToLive], config('app.key'));
        $refresh = JWT::encode($payload + ['exp' => $refreshTimeToLive + time()], config('app.key'));

        return response([], 200)
            ->withCookie('access', $access, $accessTimeToLive / 60, null, null, true, true)
            ->withCookie('refresh', $refresh, $refreshTimeToLive / 60, null, null, true, true);
    }
}