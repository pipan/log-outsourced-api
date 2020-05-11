<?php

namespace App\Domain\Listener;

use App\Domain\UuidEntity;

class ListenerEntity
{
    protected $id;
    protected $uuid;
    protected $projectId;
    protected $name;
    protected $rules;
    protected $handlerId;
    protected $handlerSettings;

    public function __construct($id, $uuid, $projectId, $name, $rules, $handlerId, $handlerSettings)
    {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->projectId = $projectId;
        $this->name = $name;
        $this->rules = $rules;
        $this->handlerId = $handlerId;
        $this->handlerSettings = $handlerSettings;
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

    public function getName()
    {
        return $this->name;
    }
    
    public function getRules()
    {
        return $this->rules;
    }

    public function getHandlerId()
    {
        return $this->handlerId;
    }

    public function getHandlerSettings()
    {
        return $this->handlerSettings;
    }
}