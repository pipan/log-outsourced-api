<?php

namespace App\Domain\Handler;

interface HandlerRepository
{
    public function getBySlug($slug);
    public function getAll();

    public function insert(HandlerEntity $entity);

    public function delete(HandlerEntity $entity);
}