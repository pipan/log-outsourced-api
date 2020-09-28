<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Domain\User\UserValidator;
use App\Domain\User\UserEntity;
use App\Http\ResponseError;
use App\Http\ResponseSchema\UserSchemaAdapter;
use App\Http\ResponseSchema\ValidationErrorResponseSchema;
use App\Repository\Pagination;
use App\Repository\Repository;
use Illuminate\Http\Request;
use Lib\Adapter\AdapterHelper;
use Lib\Generator\HexadecimalGenerator;

class UserController
{
    private $userSchema;
    private $errorSchema;
    private $repository;
    private $userValidator;

    public function __construct(Repository $repository)
    {
        $this->errorSchema = new ValidationErrorResponseSchema();
        $this->userSchema = new UserSchemaAdapter();
        $this->userValidator = UserValidator::create($repository);
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $project = $this->repository->project()->getByUuid(
            $request->input('project_uuid', '')
        );
        $roles = $this->repository->user()
            ->getForProject(
                $project->getId(), Pagination::fromRequest($request)
            );
        $adapter = AdapterHelper::listOf($this->userSchema);
        return response($adapter->adapt($roles));
    }

    public function create(Request $request, HexadecimalGenerator $hexadecimalGenerator)
    {
        $validation = $this->userValidator->forAll($request->all());
        if ($validation->fails()) {
            return ResponseError::invalidRequest($validation->errors());
        }

        $project = $this->repository->project()->getByUuid(
            $request->input('project_uuid')
        );
        $user = $this->repository->user()->getByUsernameForProject(
            $request->input('username'),
            $project->getId()
        );
        if ($user) {
            return ResponseError::invalidRequest([
                'username' => "Username already exiists"
            ]);
        }

        $user = new UserEntity([
            'uuid' => $hexadecimalGenerator->next(),
            'project_id' => $project->getId(),
            'username' => $request->input('username'),
            'roles' => $request->input('roles', [])
        ]);
        $user = $this->repository->user()->insert($user);
        return response($this->userSchema->adapt($user), 201);
    }

    public function update($uuid, Request $request)
    {
        $user = $this->repository->user()->getByUuid($uuid);
        if (!$user) {
            return ResponseError::resourceNotFound();
        }
        $validation = $this->userValidator->forOnly($request->all(), ['roles']);
        if ($validation->fails()) {
            return ResponseError::invalidRequest($validation->errors());
        }

        $user = $user->withRoles($request->input('roles', []));
            
        $user = $this->repository->user()->update($user->getId(), $user);
        return response($this->userSchema->adapt($user), 200);
    }
}