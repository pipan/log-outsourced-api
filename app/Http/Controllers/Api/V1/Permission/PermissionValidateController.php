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

        $requestedPermissions = $request->input('permissions', []);
        $roles = $repository->role()->getForUser($user->getId());
        $permissions = [];
        foreach ($roles as $role) {
            foreach ($role->getPermissions() as $rolePermission) {
                if (!in_array($rolePermission, $requestedPermissions)) {
                    continue;
                }
                $permissions[] = $rolePermission;
            }            
        }
        $permissions = array_unique($permissions);

        return response([
            'permissions' => $permissions
        ]);
    }
}