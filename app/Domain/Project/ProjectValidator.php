<?php

namespace App\Domain\Project;

use App\Domain\ExistsRule;
use App\Validator\DynamicValidator;
use App\Validator\EntityValidator;

class ProjectValidator
{
    public static function createAware(ProjectRepository $projectRepository, $rules): DynamicValidator
    {
        $projectExists = new ExistsRule(
            new UuidExistsValidator($projectRepository)
        );
        
        return new EntityValidator($rules + [
            'project_uuid' => ['bail', 'required', $projectExists]
        ]);
    }
}