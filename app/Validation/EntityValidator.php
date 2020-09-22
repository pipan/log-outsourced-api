<?php

namespace App\Validator;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class EntityValidator implements DynamicValidator
{
    private $rules;

    public function __construct($rules)
    {
        $this->rules = $rules;
    }

    public function forAll($data): Validator
    {
        return FacadesValidator::make($data, $this->rules);
    }

    public function forOnly($data, $keys): Validator
    {
        $subarray = [];
        foreach ($keys as $key) {
            if (!isset($this->rules[$key])) {
                continue;
            }
            $subarray[$key] = $this->rules[$key];
        }
        return FacadesValidator::make($data, $subarray);
    }
}