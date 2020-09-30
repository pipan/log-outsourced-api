<?php

namespace App\Domain\Permission;

use App\Domain\Project\ProjectValidator;
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
}