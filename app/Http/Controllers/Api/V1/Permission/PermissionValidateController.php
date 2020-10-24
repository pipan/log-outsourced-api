<?php

namespace App\Http\Controllers\Api\V1\Permission;

use App\Domain\Permission\PermissionValidator;
use App\Domain\User\UserEntity;
use App\Http\ResponseError;
use App\Repository\Repository;
use Illuminate\Http\Request;
use Lib\Generator\HexadecimalGenerator;

class PermissionValidateController
{
    public function __invoke($key, Request $request, Repository $repository, HexadecimalGenerator $hexadecimalGenerator)
    {
        $projectKey = $repository->projectKey()->getByKey($key);
        if (!$projectKey) {
            return ResponseError::resourceNotFound();
        }
        $project = $repository->project()->get($projectKey->getProjectId());
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
        if (!$user) {
            $defaultRoles = $repository->defaultRole()->get($project->getId());
            $roleNames = [];
            foreach ($defaultRoles as $default) {
                $roleNames[] = $default->getRole()->getName();
            }

            $user = $repository->user()->insert(new UserEntity([
                'uuid' => $hexadecimalGenerator->next(),
                'project_id' => $project->getId(),
                'username' => $request->input('user'),
                'roles' => $roleNames
            ]));
        }

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