<?php

namespace App\Domain\Role;

use App\Domain\Project\ProjectDynamicValidator;
use App\Validator\DynamicValidator;
use App\Repository\Repository;

class RoleDynamicValidator
{
    public static function create(Repository $repository): DynamicValidator
    {
        return ProjectDynamicValidator::createAware($repository->project(), [
            'domain' => ['bail', 'required', 'max:255'],
            'name' => ['bail', 'required', 'max:255'],
            'permissions' => ['bail', 'required', 'array']
        ]);
    }
}