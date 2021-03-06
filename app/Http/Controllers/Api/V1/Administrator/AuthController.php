<?php

namespace App\Http\Controllers\Api\V1\Administrator;

use App\Domain\Administrator\AdministratorEntity;
use App\Http\ResponseError;
use App\Http\ResponseSchema\AuthSchema;
use App\Repository\Repository;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
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

        return response($tokens, 200)
            ->withCookie('refresh', $tokens['refresh'], $data['refresh']['ttl'] / 60, route('auth.refresh'));
    }

    public function access(Request $request, Repository $repository)
    {
        if ($request->input('username') == env('ROOT_USERNAME', '')) {
            $administrator = new AdministratorEntity([
                'id' => 'root',
                'uuid' => 'root',
                'username' => env('ROOT_USERNAME', ''),
                'password_hash' => base64_decode(env('ROOT_PASSWORD', ''))
            ]);
        } else {
            $administrator = $repository->administrator()->getByUsername($request->input('username'));
        }
        
        if (!$administrator) {
            return ResponseError::unauthorized();
        }

        if (!Hash::check($request->input('password'), $administrator->getPasswordHash())) {
            return ResponseError::unauthorized();
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
            return ResponseError::unauthorized();
        } catch(SignatureInvalidException $ex) {
            return ResponseError::error($ex);
        }

        return $this->getResponse(
            $refreshToken->sub
        );
    }
}