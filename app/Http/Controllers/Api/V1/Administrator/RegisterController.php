<?php

namespace App\Http\Controllers\Api\V1\Administrator;

use App\Domain\Administrator\AdministratorEntity;
use App\Domain\ExistsRule;
use App\Http\ResponseSchema\ValidationErrorResponseSchema;
use App\Repository\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController
{
    private $errorSchema;

    public function __construct()
    {
        $this->errorSchema = new ValidationErrorResponseSchema();
    }

    public function __invoke(Request $request, Repository $repository)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['bail', 'required', 'max:255', new ExistsRule($repository->administrator())],
            'password' => ['bail', 'required'],
            'invite_token' => ['bail', 'required'],
        ]);

        if ($validator->fails()) {
            return response($this->errorSchema->adapt($validator->errors()), 422);
        }

        $administrator = $repository->administrator()->getByUsername(
            $request->input('username')
        );
        if ($administrator->getPasswordHash() !== "") {
            return response([], 422);
        }
        if ($administrator->getInviteToken() !== $request->input('invite_token')) {
            return response([], 422);
        }

        $repository->administrator()->update(
            $administrator->getUsername(),
            AdministratorEntity::createWithPassword(
                $administrator->getId(),
                $administrator->getUsername(),
                $request->input('password')
            )
        );
        // $inviteAdministrator = AdministratorEntity::createInvite(
        //     0,
        //     $request->input('username'),
        //     $generator->next()
        // );
        // $inviteAdministrator = $repository->administrator()->insert($inviteAdministrator);

        return response([], 200);
    }
}