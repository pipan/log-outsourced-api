<?php

namespace App\Http\Controllers\Api\V1\Permission;

use App\Domain\Permission\PermissionEntity;
use App\Domain\Permission\PermissionValidator;
use App\Http\ResponseError;
use App\Repository\Repository;
use Illuminate\Http\Request;
use Lib\Adapter\AdapterHelper;
use Lib\Entity\EntityWhitelistAdapter;

class PermissionController
{
    private $repository;
    private $schema;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
        $this->schema = new EntityWhitelistAdapter(['name']);
    }

    public function index(Request $request)
    {
        $project = $this->repository->project()
            ->getByUuid($request->input('project_uuid'));

        $permissions = $this->repository->permission()
            ->getAllForProject($project->getId());

        $adapter = AdapterHelper::listOf($this->schema);
        return response([
            'items' => $adapter->adapt($permissions)
        ]);
    }

    public function create(Request $request)
    {
        $validator = PermissionValidator::forCreation($this->repository)
            ->forRequest($request);
        if ($validator->fails()) {
            return ResponseError::invalidRequest($validator->errors());
        }

        $project = $this->repository->project()
            ->getByUuid($request->input('project_uuid'));

        $name = $request->input('name', '');
        $permission = $this->repository->permission()
            ->getByNameForProject($name, $project->getId());
        if ($permission) {
            return ResponseError::invalidRequest(['name' => 'name exists']);
        }

        $permission = new PermissionEntity([
            'name' => $name,
            'project_id' => $project->getId()
        ]);

        $permission = $this->repository->permission()
            ->insert($permission);

        return response($this->schema->adapt($permission), 201);
    }
}