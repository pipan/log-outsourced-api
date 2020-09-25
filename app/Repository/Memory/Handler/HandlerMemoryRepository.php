<?php

namespace App\Repository\Memory\Handler;

use App\Domain\Handler\HandlerEntity;
use App\Domain\Handler\HandlerRepository;

class HandlerMemoryRepository implements HandlerRepository
{
    protected $slugIndexed;

    public function __construct()
    {
        $this->slugIndexed = [];
    }

    public function getAll()
    {
        return array_values($this->slugIndexed);
    }

    public function getBySlug($slug): ?HandlerEntity
    {
        if (!isset($this->slugIndexed[$slug])) {
            return null;
        }
        return $this->slugIndexed[$slug];
    }

    public function insert(HandlerEntity $entity): HandlerEntity
    {
        $this->slugIndexed[$entity->getSlug()] = $entity;
        return $entity;
    }

    public function delete(HandlerEntity $entity)
    {
        $slug = $entity->getSlug();
        if (isset($this->slugIndexed[$slug])) {
            unset($this->slugIndexed[$slug]);
        }
        return $entity;
    }
}