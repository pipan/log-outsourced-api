<?php

namespace App\Domain\Administrator;

use App\Domain\ExistsRule;
use App\Domain\MissingRule;
use App\Domain\UuidValidator;
use App\Validator\DynamicValidator;
use App\Repository\Repository;
use App\Validator\EntityValidator;
use Illuminate\Validation\Rule;

class AdministratorValidator
{
    public static function forInvitation(Repository $repository): DynamicValidator
    {
        $missingRule = new MissingRule(
            new UsernameExistsValidation($repository->administrator())
        );
        return new EntityValidator([
            'username' => ['bail', 'required', 'max:255', $missingRule]
        ]);
    }

    public static function forRegistration(Repository $repository): DynamicValidator
    {
        $existsRule = new ExistsRule(
            new InviteTokenExistsValidation($repository->administrator())
        );
        return new EntityValidator([
            'password' => ['bail', 'required'],
            'invite_token' => ['bail', 'required', $existsRule]
        ]);
    }

    public static function forSchema($entityId)
    {
        return new EntityValidator([
            'uuid' => UuidValidator::getRules(),
            'username' => ['bail', 'required', 'max:255']
        ]);
    }
}