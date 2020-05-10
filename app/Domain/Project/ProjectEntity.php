<?php

namespace App\Domain\Project;

class ProjectEntity
{
    protected $id;
    protected $uuid;
    protected $name;

    public function __construct($id, $uuid, $name)
    {
        $this->id = $id;
        $this->uuid = $uuid;
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

    public function getUuidHex()
    {
        return bin2hex($this->getUuid());
    }

    public function getName()
    {
        return $this->name;
    }
}