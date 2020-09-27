<?php

namespace App\Http\Controllers\Api\V1\Administrator;

use App\Domain\Administrator\AdministratorDynamicValidator;
use App\Http\ResponseError;
use App\Repository\Repository;
use Illuminate\Http\Request;

class RegisterController
{
    public function __invoke(Request $request, Repository $repository)
    {
        $validator = AdministratorDynamicValidator::forRegistration($repository)
            ->forAll($request->all());
        if ($validator->fails()) {
            return ResponseError::invalidRequest($validator->errors());
        }

        $administrator = $repository->administrator()->getByInviteToken(
            $request->input('invite_token')
        );
        if ($administrator->getPasswordHash() !== "") {
            return ResponseError::invalidRequest();
        }

        $administrator = $administrator->withPassword($request->input('password'));

        $repository->administrator()->update($administrator->getId(), $administrator);

        return response([], 200);
    }
}