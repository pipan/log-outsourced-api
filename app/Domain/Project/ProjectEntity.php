<?php

namespace App\Domain\Project;

class ProjectEntity
{
    protected $uuid;
    protected $name;

    public function __construct($uuid, $name)
    {
        $this->uuid = $uuid;
        $this->name = $name;
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