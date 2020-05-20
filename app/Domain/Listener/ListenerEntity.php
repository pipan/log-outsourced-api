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
    protected $handlerSlug;
    protected $handlerValurs;

    public function __construct($id, $uuid, $projectId, $name, $rules, $handlerSlug, $handlerValues)
    {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->projectId = $projectId;
        $this->name = $name;
        $this->rules = $rules;
        $this->handlerSlug = $handlerSlug;
        $this->handlerValues = $handlerValues;
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

    public function getHandlerSlug()
    {
        return $this->handlerSlug;
    }

    public function getHandlerValues()
    {
        return $this->handlerValues;
    }
}