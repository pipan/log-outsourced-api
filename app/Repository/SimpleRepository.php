<?php

namespace App\Repository;

use App\Domain\Administrator\AdministratorRepository;
use App\Domain\Handler\HandlerRepository;
use App\Domain\Listener\ListenerRepository;
use App\Domain\Permission\PermissionRepository;
use App\Domain\Project\ProjectRepository;
use App\Domain\Role\RoleRepository;
use App\Domain\Settings\DefaultRole\DefaultRoleRepository;
use App\Domain\User\UserRepository;

class SimpleRepository implements Repository
{
    protected $projectRepo;
    protected $listenerRepo;
    protected $handlerRepo;
    protected $administratorRepo;
    protected $roleRepo;
    protected $userRepo;
    protected $permissionRepo;
    protected $defaultRoleRepo;

    public function __construct(
        $projectRepo = null,
        $listenerRepo = null,
        $handlerRepo = null,
        $administratorRepo = null,
        $roleRepo = null,
        $userRepo = null,
        $permissionRepo = null,
        $defaultRoleRepo = null
    )
    {
        $this->projectRepo = $projectRepo;
        $this->listenerRepo = $listenerRepo;
        $this->handlerRepo = $handlerRepo;
        $this->administratorRepo = $administratorRepo;
        $this->roleRepo = $roleRepo;
        $this->userRepo = $userRepo;
        $this->permissionRepo = $permissionRepo;
        $this->defaultRoleRepo = $defaultRoleRepo;
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

    public function user(): ?UserRepository
    {
        return $this->userRepo;
    }

    public function permission(): ?PermissionRepository
    {
        return $this->permissionRepo;
    }

    public function defaultRole(): ?DefaultRoleRepository
    {
        return $this->defaultRoleRepo;
    }
}