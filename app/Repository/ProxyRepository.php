<?php

namespace App\Repository;

use App\Domain\Config\ConfigRepository;
use App\Domain\Handler\HandlerRepository;
use App\Domain\Listener\ListenerRepository;
use App\Domain\Project\ProjectRepository;
use App\Domain\Setting\SettingRepository;

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

    public function setting(): SettingRepository
    {
        return $this->repository->setting();
    }

    public function config(): ConfigRepository
    {
        return $this->repository->config();
    }
}