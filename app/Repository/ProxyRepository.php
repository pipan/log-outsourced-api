<?php

namespace App\Repository;

use App\Domain\Handler\HandlerRepository;
use App\Domain\Listener\ListenerRepository;
use App\Domain\Project\ProjectRepository;

class ProxyRepository implements Repository
{
    private $repository;

    public function setProxy(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function project(): ProjectRepository
    {
        return $this->repository->project();
    }

    public function listener(): ListenerRepository
    {
        return $this->repository->listener();
    }

    public function handler(): HandlerRepository
    {
        return $this->repository->handler();
    }
}