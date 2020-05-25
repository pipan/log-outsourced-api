<?php

namespace App\Domain\Handler;

class HandlerEntity
{
    protected $id;
    protected $slug;
    protected $name;
    protected $meta;

    public function __construct($slug, $name, $meta)
    {
        $this->slug = $slug;
        $this->name = $name;
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

    public function getMeta()
    {
        return $this->meta;
    }
}