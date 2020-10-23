<?php

namespace App\Http\Controllers\Api\V1\Settings\DefaultRole;

use App\Domain\Settings\DefaultRole\DefaultRoleValidator;
use App\Http\ResponseError;
use App\Http\ResponseSchema\DefaultRoleSchema;
use App\Repository\Repository;
use Illuminate\Http\Request;
use Lib\Adapter\AdapterHelper;

class DefaultRoleController
{
    private $roleSchema;
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->roleSchema = new DefaultRoleSchema();
        $this->repository = $repository;
    }

    public function load(Request $request)
    {
        $project = $this->repository->project()->getByUuid(
            $request->input('project_uuid', '')
        );
        if (!$project) {
            return ResponseError::resourceNotFound();
        }

        $defaultRoles = $this->repository->defaultRole()
            ->get($project->getId());

        $adapter = AdapterHelper::listOf($this->roleSchema);
        return response([
            'items' => $adapter->adapt($defaultRoles)
        ]);
    }

    public function save(Request $request)
    {
        $validator = DefaultRoleValidator::forSave($this->repository)->forAll($request->all());
        if ($validator->fails()) {
            return ResponseError::invalidRequest($validator->errors());
        }
        $project = $this->repository->project()
            ->getByUuid($request->input('project_uuid'));

        $roles = $this->repository->role()
            ->findListForProjectByNames($project->getId(), $request->input('roles', []));
        $roleIds = [];
        foreach ($roles as $role) {
            $roleIds[] = $role->getId();
        }

        $this->repository->defaultRole()
            ->set($project->getId(), $roleIds);

        $defaultRoles = $this->repository->defaultRole()
            ->get($project->getId());

        $adapter = AdapterHelper::listOf($this->roleSchema);
        return response([
            'items' => $adapter->adapt($defaultRoles)
        ]);
    }
}