<?php

namespace App\Domain\Handler;

class HandlerEntity
{
    protected $id;
    protected $name;
    protected $serviceId;
    protected $rules;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getServiceId()
    {
        return $this->serviceId;
    }

    public function getRules()
    {
        return $this->rules;
    }
}