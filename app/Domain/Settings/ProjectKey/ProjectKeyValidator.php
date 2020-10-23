<?php

namespace App\Domain\Settings\ProjectKey;

use App\Domain\Project\ProjectValidator;
use App\Validator\DynamicValidator;
use App\Repository\Repository;
use App\Validator\EntityValidator;

class ProjectKeyValidator
{
    public static function forCreate(Repository $repository): DynamicValidator
    {
        return new EntityValidator([
            'project_uuid' => ProjectValidator::getProjectUuidRule($repository->project()),
            'name' => ['bail', 'required', 'max:255']
        ]);
    }

    public static function forUpdate(): DynamicValidator
    {
        return new EntityValidator([
            'name' => ['bail', 'filled', 'max:255']
        ]);
    }
}