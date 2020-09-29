<?php

namespace App\Domain;

use Lib\Entity\Entity;

abstract class IdEntity extends Entity
{
    public function withId($value)
    {
        return $this->with('id', $value);
    }

    public function getId()
    {
        return $this->data['id'];
    }
}