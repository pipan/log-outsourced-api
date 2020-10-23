<?php

namespace App\Domain\Settings\DefaultRole;

use App\Domain\Project\ProjectValidator;
use App\Validator\DynamicValidator;
use App\Repository\Repository;
use App\Validator\EntityValidator;

class DefaultRoleValidator
{
    public static function forSave(Repository $repository): DynamicValidator
    {
        return new EntityValidator([
            'project_uuid' => ProjectValidator::getProjectUuidRule($repository->project()),
            'roles' => ['nullable', 'array']
        ]);
    }
}