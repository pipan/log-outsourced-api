<?php

namespace App\Repository;

use App\Domain\Administrator\AdministratorRepository;
use App\Domain\Handler\HandlerRepository;
use App\Domain\Listener\ListenerRepository;
use App\Domain\Project\ProjectRepository;
use App\Domain\Role\RoleRepository;

interface Repository
{
    public function project(): ProjectRepository;
    public function listener(): ListenerRepository;
    public function handler(): HandlerRepository;
    public function administrator(): ?AdministratorRepository;
    public function role(): ?RoleRepository;
}