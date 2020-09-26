<?php

namespace App\Http\Auth;

use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class JwtAutorization implements Authorization
{
    private $id = 0;

    public function __construct(Request $request)
    {
        if (!$request->hasHeader('Authorization')) {
            return;
        }

        try {
            $token = substr($request->header('Authorization', 'Bearer '), 7);
            $tokenData = JWT::decode($token, ['HS256']);
        } catch (ExpiredException $ex) {
            return;
        } catch (Exception $ex) {
            return;
        }

        $this->id = $tokenData->sub;
    }

    public function getId()
    {
        return $this->id;
    }
}