<?php

namespace App\Http\Controllers\Api\V1\Administrator;

use App\Domain\Administrator\AdministratorEntity;
use App\Http\ResponseSchema\ValidationErrorResponseSchema;
use App\Repository\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Lib\Generator\HexadecimalGenerator;

class InviteController
{
    private $errorSchema;

    public function __construct()
    {
        $this->errorSchema = new ValidationErrorResponseSchema();
    }

    public function __invoke(Request $request, Repository $repository, HexadecimalGenerator $generator)
    {
        $validator = Validator::make($request->all(), [
            'username' => ['bail', 'required', 'max:255']
        ]);

        if ($validator->fails()) {
            return response($this->errorSchema->adapt($validator->errors()), 422);
        }

        $administrator = $repository->administrator()->getByUsername(
            $request->input('username')
        );
        if ($administrator) {
            return response([], 422);
        }

        $inviteAdministrator = AdministratorEntity::createInvite(
            0,
            $request->input('username'),
            $generator->next()
        );
        $inviteAdministrator = $repository->administrator()->insert($inviteAdministrator);

        return response([
            'id' => $inviteAdministrator->getId(),
            'username' => $inviteAdministrator->getUsername(),
            'invite_token' => $inviteAdministrator->getInviteToken()
        ], 200);
    }
}