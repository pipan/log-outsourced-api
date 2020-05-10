<?php

namespace App\Domain\Handler;

class HandlerEntity
{
    protected $id;
    protected $uuid;
    protected $name;
    protected $projectId;
    protected $serviceId;
    protected $rules;

    public function __construct($id, $uuid, $projectId, $name)
    {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->projectId = $projectId;
        $this->name = $name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function getProjectId()
    {
        return $this->projectId;
    }

    public function getHexUuid()
    {
        return bin2hex($this->getUuid());
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