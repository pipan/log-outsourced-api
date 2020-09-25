<?php

namespace App\Http\Controllers\Api\V1\Administrator;

use App\Http\ResponseSchema\AuthSchema;
use App\Repository\Repository;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController
{
    private $authSchema;

    public function __construct()
    {
        $this->authSchema = new AuthSchema();
    }

    private function getResponse($id)
    {
        $data = [
            'sub' => $id,
            'access' => [
                'ttl' => config('auth.jwt.access.time_to_live')
            ],
            'refresh' => [
                'ttl' => config('auth.jwt.refresh.time_to_live')
            ]
        ];
        $tokens = $this->authSchema->adapt($data);

        return response([], 200)
            ->withCookie('access', $tokens['access'], $data['access']['ttl'] / 60)
            ->withCookie('refresh', $tokens['refresh'], $data['refresh']['ttl'] / 60);
    }

    public function access(Request $request, Repository $repository)
    {
        $administrator = $repository->administrator()->getByUsername($request->input('username'));
        if (!$administrator) {
            return response([], 401);
        }

        if (!Hash::check($request->input('password'), $administrator->getPasswordHash())) {
            return response([], 401);
        }
        
        return $this->getResponse(
            $administrator->getId()
        );
    }

    public function refresh(Request $request)
    {
        try {
            $refreshToken = JWT::decode($request->input('refresh_token'), config('app.key'), ['HS256']);
        } catch (ExpiredException $ex) {
            return response([], 401);
        } catch (SignatureInvalidException $ex) {
            return response([], 500);
        }

        return $this->getResponse(
            $refreshToken->sub
        );
    }
}