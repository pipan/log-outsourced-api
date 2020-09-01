<?php

namespace App\Repository;

use App\Domain\Administrator\AdministratorRepository;
use App\Domain\Handler\HandlerRepository;
use App\Domain\Listener\ListenerRepository;
use App\Domain\Project\ProjectRepository;
use App\Domain\Role\RoleRepository;

class SimpleRepository implements Repository
{
    protected $projectRepo;
    protected $listenerRepo;
    protected $handlerRepo;
    protected $administratorRepo;
    protected $roleRepo;

    public function __construct(
        $projectRepo = null,
        $listenerRepo = null,
        $handlerRepo = null,
        $administratorRepo = null,
        $roleRepo = null
    )
    {
        $this->projectRepo = $projectRepo;
        $this->listenerRepo = $listenerRepo;
        $this->handlerRepo = $handlerRepo;
        $this->administratorRepo = $administratorRepo;
        $this->roleRepo = $roleRepo;
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

    public function administrator(): ?AdministratorRepository
    {
        return $this->administratorRepo;
    }

    public function role(): ?RoleRepository
    {
        return $this->roleRepo;
    }
}