<?php

namespace App\Domain;

use Illuminate\Contracts\Validation\Rule;

class ExistsRule implements Rule
{
    protected $existsValdable;

    public function __construct(ExistsValidable $existsValidable)
    {
        $this->existsValdable = $existsValidable;
    }

    public function passes($attribute, $value)
    {
        return $this->existsValdable->exists($value);
    }

    public function message()
    {
        return 'The :attribute must exist.';
    }
}