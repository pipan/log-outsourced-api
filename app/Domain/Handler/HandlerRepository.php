<?php

namespace App\Domain\Handler;

use App\Domain\ExistsValidable;

interface HandlerRepository extends ExistsValidable
{
    public function getBySlug($slug);
    public function getAll();

    public function insert(HandlerEntity $entity);

    public function delete(HandlerEntity $entity);
}