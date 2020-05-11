<?php

namespace App\Repository;

use App\Domain\Config\ConfigRepository;
use App\Domain\Handler\HandlerRepository;
use App\Domain\Listener\ListenerRepository;
use App\Domain\Project\ProjectRepository;
use App\Domain\Setting\SettingRepository;

class SimpleRepository implements Repository
{
    protected $projectRepo;
    protected $listenerRepo;
    protected $handlerRepo;
    protected $settingRepo;
    protected $configRepo;

    public function __construct(
        $projectRepo = null,
        $listenerRepo = null,
        $handlerRepo = null,
        $settingRepo = null,
        $configRepo = null
    )
    {
        $this->projectRepo = $projectRepo;
        $this->listenerRepo = $listenerRepo;
        $this->handlerRepo = $handlerRepo;
        $this->settingRepo = $settingRepo;
        $this->configRepo = $configRepo;
    }

    public function project(): ProjectRepository
    {
        return $this->projectRepo;
    }

    public function listener(): ListenerRepository
    {
        return $this->listenerRepo;
    }

    public function handler(): HandlerRepository
    {
        return $this->handlerRepo;
    }

    public function setting(): SettingRepository
    {
        return $this->settingRepo;
    }

    public function config(): ConfigRepository
    {
        return $this->configRepo;
    }
}