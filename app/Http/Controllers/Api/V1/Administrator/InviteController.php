<?php

namespace App\Http\Controllers\Api\V1\Administrator;

use App\Domain\Administrator\AdministratorDynamicValidator;
use App\Domain\Administrator\AdministratorEntity;
use App\Domain\Administrator\AdministratorSchema;
use App\Http\ResponseError;
use App\Repository\Repository;
use Illuminate\Http\Request;
use Lib\Generator\HexadecimalGenerator;

class InviteController
{
    private $administratorSchema;
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
        $this->administratorSchema = AdministratorSchema::forPublic();
    }

    public function view($token)
    {
        $administrator = $this->repository->administrator()
            ->getByInviteToken($token);
        if (!$administrator) {
            return ResponseError::resourceNotFound();
        }

        return response($this->administratorSchema->adapt($administrator), 200);
    }

    public function create(Request $request, HexadecimalGenerator $generator)
    {
        $validator = AdministratorDynamicValidator::forInvitation($this->repository)
            ->forAll($request->all());
        if ($validator->fails()) {
            return ResponseError::invalidRequest($validator->errors());
        }

        $inviteAdministrator = new AdministratorEntity([
            'uuid' => $generator->next(),
            'username' => $request->input('username'),
            'invite_token' => $generator->next()
        ]);
        $inviteAdministrator = $this->repository->administrator()
            ->insert($inviteAdministrator);

        return response($this->administratorSchema->adapt($inviteAdministrator), 200);
    }
}