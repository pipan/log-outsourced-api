<?php

namespace App\Domain\Listener;

use App\Domain\ExistsRule;
use App\Domain\Handler\SlugExistsValidation;
use App\Domain\Project\ProjectValidator;
use App\Validator\DynamicValidator;
use App\Repository\Repository;
use App\Validator\EntityValidator;

class ListenerValidator
{
    public static function forUpdates(Repository $repository): DynamicValidator
    {
        $handlerExists = new ExistsRule(
            new SlugExistsValidation($repository->handler())
        );
        return new EntityValidator([
            'name' => ['bail', 'nullable', 'filled', 'max:255'],
            'rules' => ['bail', 'array'],
            'handler_slug' => ['bail', 'nullable', $handlerExists]
        ]);
    }

    public static function forCreates(Repository $repository): DynamicValidator
    {
        $handlerExists = new ExistsRule(
            new SlugExistsValidation($repository->handler())
        );
        return ProjectValidator::createAware($repository->project(), [
            'name' => ['bail', 'required', 'max:255'],
            'rules' => ['array'],
            'handler_slug' => ['required', $handlerExists]
        ]);
    }
}