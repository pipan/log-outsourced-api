<?php

namespace App\Http\Controllers\Api\V1\Administrator;

use App\Domain\Administrator\AdministratorDynamicValidator;
use App\Domain\Administrator\AdministratorEntity;
use App\Http\ResponseSchema\ValidationErrorResponseSchema;
use App\Repository\Repository;
use Illuminate\Http\Request;

class RegisterController
{
    private $errorSchema;

    public function __construct()
    {
        $this->errorSchema = new ValidationErrorResponseSchema();
    }

    public function __invoke(Request $request, Repository $repository)
    {
        $validator = AdministratorDynamicValidator::forRegistration($repository)
            ->forAll($request->all());
        if ($validator->fails()) {
            return response($this->errorSchema->adapt($validator->errors()), 422);
        }

        $administrator = $repository->administrator()->getByInviteToken(
            $request->input('invite_token')
        );
        if ($administrator->getPasswordHash() !== "") {
            return response([], 422);
        }

        $repository->administrator()->update(
            $administrator->getId(),
            AdministratorEntity::createWithPassword(
                $administrator->getId(),
                $administrator->getUsername(),
                $request->input('password')
            )
        );

        return response([], 200);
    }
}