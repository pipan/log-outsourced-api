<?php

namespace App\Validator;

use Illuminate\Contracts\Validation\Validator;

interface DynamicValidator
{
    public function forAll($data): Validator;
    public function forOnly($data, $keys): Validator;
}