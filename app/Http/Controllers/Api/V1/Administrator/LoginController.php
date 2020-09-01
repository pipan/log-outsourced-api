<?php

namespace App\Http\Controllers\Api\V1\Administrator;

use App\Repository\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController
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

        return response([
            'jwt' => 'testJWT'
        ], 200);
    }
}