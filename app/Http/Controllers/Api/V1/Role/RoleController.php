<?php

namespace App\Http\Controllers\Api\V1\Role;

use App\Domain\Role\RoleEntity;
use App\Domain\Role\RoleValidator;
use App\Http\Controllers\Api\V1\ListMetaEntity;
use App\Http\ResponseError;
use App\Http\ResponseSchema\RoleSchemaAdapter;
use App\Repository\Pagination;
use App\Repository\Repository;
use Illuminate\Http\Request;
use Lib\Adapter\AdapterHelper;
use Lib\Generator\HexadecimalGenerator;

class RoleController
{
    private $roleSchema;
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->roleSchema = new RoleSchemaAdapter();
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $project = $this->repository->project()->getByUuid(
            $request->input('project_uuid', '')
        );
        if (!$project) {
            return ResponseError::resourceNotFound();
        }

        $pagination = Pagination::fromRequest($request)
            ->searchBy('name')
            ->orderBy('name');
        $roles = $this->repository->role()
            ->getForProject($project->getId(), $pagination);
        $count = $this->repository->role()
            ->countForProject($project->getId(), $pagination->getSearchValue());

        $listMeta = ListMetaEntity::fromPagination($pagination)
            ->withTotalItems($count);
        $adapter = AdapterHelper::listOf($this->roleSchema);
        return response([
            'items' => $adapter->adapt($roles),
            'meta' => $listMeta->toArray()
        ]);
    }

    public function create(Request $request, HexadecimalGenerator $generator)
    {
        $validator = RoleValidator::forCreation($this->repository)->forAll($request->all());
        if ($validator->fails()) {
            return ResponseError::invalidRequest($validator->errors());
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

        if (!$role) {
            return ResponseError::resourceNotFound();
        }
;
        return response($this->roleSchema->adapt($role));
    }

    public function delete($uuid)
    {
        $role = $this->repository->role()->getByUuid($uuid);

        if (!$role) {
            return ResponseError::resourceNotFound();
        }

        $this->repository->role()->delete($role);
        return response([]);
    }

    public function update($uuid, Request $request)
    {
        $role = $this->repository->role()->getByUuid($uuid);
        if (!$role) {
            return ResponseError::resourceNotFound();
        }

        $validator = RoleValidator::forUpdates()->forAll($request->all());
        if ($validator->fails()) {
            return ResponseError::invalidRequest($validator->errors());
        }

        $role = $role->withName($request->input('name', $role->getName()))
            ->withPermissions($request->input('permissions', $role->getPermissions()));

        $role = $this->repository->role()->update($role->getId(), $role);
        return response($this->roleSchema->adapt($role), 200);
    }
}