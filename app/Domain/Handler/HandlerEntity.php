<?php

namespace App\Domain\Handler;

use Lib\Entity\Entity;

class HandlerEntity extends Entity
{
    public function __construct($data)
    {
        parent::__construct([
            'slug' => $data['slug'] ?? '',
            'name' => $data['name'] ?? '',
            'meta' => $data['meta'] ?? []
        ]);
    }

    public function getSlug()
    {
        return $this->data['slug'];
    }

    public function getName()
    {
        return $this->data['name'];
    }

    public function getMeta()
    {
        return $this->data['meta'];
    }
}