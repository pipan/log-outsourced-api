<?php

namespace App\Http\Controllers\Api\V1\Permission;

use App\Domain\Permission\PermissionValidator;
use App\Http\ResponseError;
use App\Repository\Repository;
use Illuminate\Http\Request;

class PermissionValidateController
{
    public function __invoke($uuid, Request $request, Repository $repository)
    {
        $project = $repository->project()->getByUuid($uuid);
        if (!$project) {
            return ResponseError::resourceNotFound();
        }

        $validation = PermissionValidator::forValidation($repository, $project->getId())->forRequest($request);
        if ($validation->fails()) {
            return ResponseError::invalidRequest($validation->errors());
        }

        $user = $repository->user()->getByUsernameForProject(
            $request->input('user'),
            $project->getId()
        );

        $roles = $repository->role()->getForUser($user->getId());
        $permissions = [];
        foreach ($roles as $role) {
            $permissions = array_merge($permissions, $role->getPermissions());
            $permissions = array_unique($permissions);
        }
        $permissions = array_intersect($permissions, $request->input('permissions'));

        return response([
            'permissions' => $permissions
        ]);
    }
}