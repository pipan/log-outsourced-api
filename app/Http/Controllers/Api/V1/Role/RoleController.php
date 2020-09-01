<?php

namespace App\Http\Controllers\Api\V1\Role;

use App\Domain\Role\RoleEntity;
use App\Http\ResponseSchema\RoleSchemaAdapter;
use App\Http\ResponseSchema\ValidationErrorResponseSchema;
use App\Repository\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Lib\Adapter\AdapterHelper;
use Lib\Generator\HexadecimalGenerator;

class RoleController
{
    private $roleSchema;
    private $errorSchema;

    public function __construct()
    {
        $this->roleSchema = new RoleSchemaAdapter();
        $this->errorSchema = new ValidationErrorResponseSchema();
    }

    private function getValidator(Request $request)
    {
        return Validator::make($request->all(), [
            'domain' => ['bail', 'required', 'max:255'],
            'name' => ['bail', 'required', 'max:255'],
            'permissions' => ['bail', 'required', 'array']
        ]);
    }

    public function index(Repository $repository)
    {
        $roles = $repository->role()->getAll();
        $adapter = AdapterHelper::listOf($this->roleSchema);
        return response($adapter->adapt($roles));
    }

    public function create(Request $request, Repository $repository, HexadecimalGenerator $generator)
    {
        $validator = $this->getValidator($request);
        if ($validator->fails()) {
            return response($this->errorSchema->adapt($validator->errors()), 422);
        }

        $role = new RoleEntity(
            0,
            $generator->next(),
            $request->input('domain'),
            $request->input('name'),
            $request->input('permissions')
        );
        $role = $repository->role()->insert($role);
        return response($this->roleSchema->adapt($role), 201)
            ->withHeaders([
                'Location' => route('roles.view', ['uuid' => $role->getUuid()])
            ]);
    }

    public function view($uuid, Repository $repository)
    {
        $role = $repository->role()->getByUuid($uuid);

        if ($role === null) {
            return response([], 404);
        }
;
        return response($this->roleSchema->adapt($role));
    }

    public function delete($uuid, Repository $repository)
    {
        $role = $repository->role()->getByUuid($uuid);

        if ($role === null) {
            return response([], 404);
        }

        $repository->role()->delete($role);
        return response([], 200);
    }

    public function update($uuid, Repository $repository, Request $request)
    {
        $role = $repository->role()->getByUuid($uuid);
        if ($role === null) {
            return response([], 404);
        }

        $validator = $this->getValidator($request);
        if ($validator->fails()) {
            return response($this->errorSchema->adapt($validator->errors()), 422);
        }

        $role = new RoleEntity(
            $role->getId(),
            $role->getUuid(),
            $request->input('domain'),
            $request->input('name'),
            $request->input('permissions')
        );
        $role = $repository->role()->update($role->getId(), $role);
        return response($this->roleSchema->adapt($role), 200);
    }
}