<?php

namespace App\Domain;

class UuidValidator
{
    public static function getRules()
    {
        return ['bail', 'filled', 'max:36'];
    }
}