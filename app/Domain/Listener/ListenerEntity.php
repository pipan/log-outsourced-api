<?php

namespace App\Domain\Listener;

use App\Domain\UuidEntity;

class ListenerEntity
{
    protected $id;
    protected $uuid;
    protected $projectId;
    protected $name;
    protected $handlerSlug;
    protected $handlerValurs;

    public function __construct($id, $uuid, $projectId, $name, $handlerSlug, $handlerValues)
    {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->projectId = $projectId;
        $this->name = $name;
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
        return [];
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