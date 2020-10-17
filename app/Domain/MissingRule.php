<?php

namespace App\Domain;

use Illuminate\Contracts\Validation\Rule;

class MissingRule implements Rule
{
    protected $existsRule;

    public function __construct(ExistsValidable $existsValidable)
    {
        $this->existsRule = new ExistsRule($existsValidable);
    }

    public function passes($attribute, $value)
    {
        return !$this->existsRule->passes($attribute, $value);
    }

    public function message()
    {
        return 'The :attribute must be unique.';
    }
}