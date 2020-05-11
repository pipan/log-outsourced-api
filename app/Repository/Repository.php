<?php

namespace App\Repository;

use App\Domain\Config\ConfigRepository;
use App\Domain\Handler\HandlerRepository;
use App\Domain\Listener\ListenerRepository;
use App\Domain\Project\ProjectRepository;
use App\Domain\Setting\SettingRepository;

interface Repository
{
    public function project(): ProjectRepository;
    public function listener(): ListenerRepository;
    public function handler(): HandlerRepository;
    public function setting(): SettingRepository;
    public function config(): ConfigRepository;
}