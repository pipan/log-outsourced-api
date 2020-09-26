<?php

namespace App\Http\Controllers\Api\V1\Role;

use App\Domain\Role\RoleDynamicValidator;
use App\Domain\Role\RoleEntity;
use App\Domain\Role\RoleValidator;
use App\Http\ResponseSchema\RoleSchemaAdapter;
use App\Http\ResponseSchema\ValidationErrorResponseSchema;
use App\Repository\Pagination;
use App\Repository\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Lib\Adapter\AdapterHelper;
use Lib\Generator\HexadecimalGenerator;

class RoleController
{
    private $roleSchema;
    private $errorSchema;
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->roleSchema = new RoleSchemaAdapter();
        $this->errorSchema = new ValidationErrorResponseSchema();
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
        $roles = $this->repository->role()
            ->getForProject(
                $project->getId(), Pagination::fromRequest($request)
            );
        $adapter = AdapterHelper::listOf($this->roleSchema);
        return response($adapter->adapt($roles));
    }

    public function create(Request $request, HexadecimalGenerator $generator)
    {
        $validator = RoleValidator::forCreation($this->repository)->forAll($request->all());
        if ($validator->fails()) {
            return response($this->errorSchema->adapt($validator->errors()), 422);
        }
        $project = $this->repository->project()
            ->getByUuid($request->input('project_uuid'));

        $role = new RoleEntity([
            'uuid' => $generator->next(),
            'project_id' => $project->getId(),
            'name' => $request->input('name'),
            'permissions' => $request->input('permissions')
        ]);
        $role = $this->repository->role()->insert($role);
        return response($this->roleSchema->adapt($role), 201)
            ->withHeaders([
                'Location' => route('roles.view', ['uuid' => $role->getUuid()])
            ]);
    }

    public function view($uuid)
    {
        $role = $this->repository->role()->getByUuid($uuid);

        if ($role === null) {
            return response([], 404);
        }
;
        return response($this->roleSchema->adapt($role));
    }

    public function delete($uuid)
    {
        $role = $this->repository->role()->getByUuid($uuid);

        if ($role === null) {
            return response([], 404);
        }

        $this->repository->role()->delete($role);
        return response([], 200);
    }

    public function update($uuid, Request $request)
    {
        $role = $this->repository->role()->getByUuid($uuid);
        if ($role === null) {
            return response([], 404);
        }

        $validator = RoleValidator::forUpdates()->forAll($request->all());
        if ($validator->fails()) {
            return response($this->errorSchema->adapt($validator->errors()), 422);
        }

        $role = $role->withName($request->input('name', $role->getName()))
            ->withPermissions($request->input('permissions', $role->getPermissions()));

        $role = $this->repository->role()->update($role->getId(), $role);
        return response($this->roleSchema->adapt($role), 200);
    }
}