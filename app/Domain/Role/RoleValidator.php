<?php

namespace App\Domain\Role;

use App\Domain\Project\ProjectValidator;
use App\Domain\UuidValidator;
use App\Validator\DynamicValidator;
use App\Repository\Repository;
use App\Validator\EntityValidator;

class RoleValidator
{
    public static function forCreation(Repository $repository): DynamicValidator
    {
        return ProjectValidator::createAware($repository->project(), [
            'name' => ['bail', 'required', 'max:255'],
            'permissions' => ['bail', 'required', 'array', 'min:1']
        ]);
    }

    public static function forUpdates(): DynamicValidator
    {
        return new EntityValidator([
            'name' => ['bail', 'filled', 'max:255'],
            'permissions' => ['bail', 'array', 'min:1']
        ]);
    }

    public static function forSchema(): DynamicValidator
    {
        return new EntityValidator([
            'uuid' => UuidValidator::getRules(),
            'project_id' => ProjectValidator::getProjectIdRule(),
            'name' => ['bail', 'filled', 'max:255'],
            'permissions' => ['bail', 'array']
        ]);
    }
}