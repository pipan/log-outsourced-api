<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Domain\User\UserDynamicValidator;
use App\Domain\User\UserEntity;
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
        $this->userValidator = UserDynamicValidator::create($repository);
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $project = $this->repository->project()->getByUuid(
            $request->input('project_uuid', '')
        );
        if (!$project) {
            return response([], 404);
        }
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
            return response(
                $this->errorSchema->adapt($validation->errors()),
                422
            );
        }

        $project = $this->repository->project()->getByUuid(
            $request->input('project_uuid')
        );
        $user = $this->repository->user()->getByUsernameForProject(
            $request->input('username'),
            $project->getId()
        );
        if ($user) {
            return response(['errors' => []], 422);
        }

        $user = new UserEntity(
            0,
            $hexadecimalGenerator->next(),
            $request->input('username'),
            $project->getId(),
            $request->input('roles', [])
        );
        $user = $this->repository->user()->insert($user);
        return response($this->userSchema->adapt($user), 201);
    }

    public function update($uuid, Request $request)
    {
        $user = $this->repository->user()->getByUuid($uuid);
        if (!$user) {
            return response([], 404);
        }
        $validation = $this->userValidator->forOnly($request->all(), ['roles']);
        if ($validation->fails()) {
            return response(
                $this->errorSchema->adapt($validation->errors()),
                422
            );
        }

        $user = new UserEntity(
            $user->getId(),
            $user->getUuid(),
            $user->getUsername(),
            $user->getProjectId(),
            $request->input('roles', [])
        );
        $user = $this->repository->user()->update($user->getId(), $user);
        return response($this->userSchema->adapt($user), 200);
    }
}