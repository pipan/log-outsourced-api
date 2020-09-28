<?php

namespace App\Domain\Project;

use App\Domain\ExistsRule;
use App\Domain\UuidValidator;
use App\Validator\DynamicValidator;
use App\Validator\EntityValidator;

class ProjectValidator
{
    public static function createAware(ProjectRepository $projectRepository, $rules): DynamicValidator
    {        
        return new EntityValidator($rules + [
            'project_uuid' => self::getProjectUuidRule($projectRepository)
        ]);
    }

    public static function getProjectUuidRule(ProjectRepository $projectRepository)
    {
        $projectExists = new ExistsRule(
            new UuidExistsValidator($projectRepository)
        );
        return ['bail', 'required', $projectExists];
    }

    public static function getProjectIdRule()
    {
        return ['bail', 'required', 'integer', 'min:1'];
    }

    public static function forSchema(): DynamicValidator
    {
        return new EntityValidator([
            'uuid' => UuidValidator::getRules(),
            'name' => ['bail', 'required', 'max:255']
        ]);
    }

    public static function forInsert(): DynamicValidator
    {
        return new EntityValidator([
            'name' => ['bail', 'required', 'max:255']
        ]);
    }

    public static function forUpdate(): DynamicValidator
    {
        return self::forInsert();
    }
}