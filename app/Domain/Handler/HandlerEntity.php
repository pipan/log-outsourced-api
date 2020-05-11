<?php

namespace App\Domain\Handler;

class HandlerEntity
{
    protected $id;
    protected $slug;
    protected $name;
    protected $configSchema;
    protected $meta;

    public function __construct($id, $slug, $name, $configSchema, $meta)
    {
        $this->id = $id;
        $this->slug = $slug;
        $this->name = $name;
        $this->configSchema = $configSchema;
        $this->meta = $meta;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getConfigSchema()
    {
        return $this->configSchema;
    }

    public function getMeta()
    {
        return $this->meta;
    }
}