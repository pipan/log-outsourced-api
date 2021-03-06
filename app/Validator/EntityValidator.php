<?php

namespace App\Validator;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Lib\Entity\Entity;

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

    public function forEntity(Entity $entity): Validator
    {
        return $this->forAll($entity->toArray());
    }

    public function forRequest(Request $request): Validator
    {
        return $this->forAll($request->all());
    }
}