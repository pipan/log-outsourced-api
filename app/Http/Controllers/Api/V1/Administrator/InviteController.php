<?php

namespace App\Http\Controllers\Api\V1\Administrator;

use App\Domain\Administrator\AdministratorDynamicValidator;
use App\Domain\Administrator\AdministratorEntity;
use App\Http\ResponseSchema\AdministratorSchema;
use App\Http\ResponseSchema\ValidationErrorResponseSchema;
use App\Repository\Repository;
use Illuminate\Http\Request;
use Lib\Generator\HexadecimalGenerator;

class InviteController
{
    private $errorSchema;
    private $administratorSchema;
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
        $this->errorSchema = new ValidationErrorResponseSchema();
        $this->administratorSchema = new AdministratorSchema();
    }

    public function view($token)
    {
        $administrator = $this->repository->administrator()
            ->getByInviteToken($token);
        if (!$administrator) {
            return response([], 404);
        }

        return response($this->administratorSchema->adapt($administrator), 200);
    }

    public function create(Request $request, HexadecimalGenerator $generator)
    {
        $validator = AdministratorDynamicValidator::forInvitation($this->repository)
            ->forAll($request->all());
        if ($validator->fails()) {
            return response($this->errorSchema->adapt($validator->errors()), 422);
        }

        $inviteAdministrator = AdministratorEntity::createInvite(
            0,
            $request->input('username'),
            $generator->next()
        );
        $inviteAdministrator = $this->repository->administrator()->insert($inviteAdministrator);

        return response($this->administratorSchema->adapt($inviteAdministrator), 200);
    }
}