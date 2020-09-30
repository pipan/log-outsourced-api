<?php

namespace App\Domain\Permission;

use App\Domain\ExistsRule;
use App\Domain\Project\ProjectValidator;
use App\Domain\User\UsernameExistsValidation;
use App\Validator\DynamicValidator;
use App\Repository\Repository;
use App\Validator\EntityValidator;

class PermissionValidator
{
    public static function forCreation(Repository $repository): DynamicValidator
    {
        return new EntityValidator([
            'project_uuid' => ProjectValidator::getProjectUuidRule($repository->project()),
            'name' => ['bail', 'required', 'max:255']
        ]);
    }

    public static function forSchema(): DynamicValidator
    {
        return new EntityValidator([
            'project_id' => ProjectValidator::getProjectIdRule(),
            'name' => ['bail', 'filled', 'max:255'],
        ]);
    }

    public static function forValidation(Repository $repository, $projectId): DynamicValidator
    {
        $userExists = new ExistsRule(
            new UsernameExistsValidation($repository->user(), $projectId)
        );
        return new EntityValidator([
            'permissions' => ['required', 'array', 'min:1'],
            'user' => ['bail', 'required', 'max:255', $userExists]
        ]);
    }
}