<?php

namespace App\Domain\Handler;

class HandlerEntity
{
    protected $id;
    protected $slug;
    protected $name;
    protected $configSchema;
    protected $meta;

    public function __construct($slug, $name, $configSchema, $meta)
    {
        $this->slug = $slug;
        $this->name = $name;
        $this->configSchema = $configSchema;
        $this->meta = $meta;
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