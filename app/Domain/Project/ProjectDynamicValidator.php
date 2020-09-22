<?php

namespace App\Domain\Project;

use App\Domain\ExistsRule;
use App\Validator\DynamicValidator;
use App\Validator\EntityValidator;

class ProjectDynamicValidator
{
    public static function createAware(ProjectRepository $projectRepository, $rules): DynamicValidator
    {
        return new EntityValidator($rules + [
            'project_uuid' => ['bail', 'required', new ExistsRule($projectRepository)]
        ]);
    }
}