<?php

namespace App\Validator;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Lib\Entity\Entity;

interface DynamicValidator
{
    public function forAll($data): Validator;
    public function forOnly($data, $keys): Validator;
    public function forEntity(Entity $entity): Validator;
    public function forRequest(Request $request): Validator;
}