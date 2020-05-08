<?php

namespace App\Domain\Handler;

interface HandlerRepository
{
    public function getForProject($projectId);
    public function save(HandlerEntity $handler);
}