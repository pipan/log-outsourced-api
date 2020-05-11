<?php

namespace App\Repository;

use App\Domain\Handler\HandlerRepository;
use App\Domain\Listener\ListenerRepository;
use App\Domain\Project\ProjectRepository;

interface Repository
{
    public function project(): ProjectRepository;
    public function listener(): ListenerRepository;
    public function handler(): HandlerRepository;
}