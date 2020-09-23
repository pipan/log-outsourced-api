<?php

namespace App\Domain\User;

use App\Domain\Project\ProjectDynamicValidator;
use App\Validator\DynamicValidator;
use App\Repository\Repository;

class UserDynamicValidator
{
    public static function create(Repository $repository): DynamicValidator
    {
        return ProjectDynamicValidator::createAware($repository->project(), [
            'username' => ['bail', 'required', 'max:255'],
            'roles' => ['nullable', 'array']
        ]);
    }
}