<?php

namespace App\Domain\User;

use App\Domain\Project\ProjectValidator;
use App\Domain\UuidValidator;
use App\Validator\DynamicValidator;
use App\Repository\Repository;
use App\Validator\EntityValidator;

class UserValidator
{
    public static function create(Repository $repository): DynamicValidator
    {
        return new EntityValidator([
            'project_uuid' => ProjectValidator::getProjectUuidRule($repository->project()),
            'username' => ['bail', 'required', 'max:255'],
            'roles' => ['nullable', 'array']
        ]);
    }

    public static function forSchema(): DynamicValidator
    {
        return new EntityValidator([
            'uuid' => UuidValidator::getRules(),
            'project_id' => ProjectValidator::getProjectIdRule(),
            'username' => ['bail', 'required', 'max:255'],
            'roles' => ['array']
        ]);
    }
}