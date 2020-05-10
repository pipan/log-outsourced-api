<?php

namespace App\Domain\Handler;

interface HandlerRepository
{
    public function getByHexUuid($hexUuid);
    public function getByUuid($uuid);
    public function getForProject($projectId);

    public function save(HandlerEntity $handler);
    
    public function delete(HandlerEntity $handler);
}